<?php

/**
 * 	App Name	: Admin Template Dashboard Codeigniter 4	
 * 	Developed by: Agus Prawoto Hadi
 * 	Website		: https://jagowebdev.com
 * 	Year		: 2020-2021
 */

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\Auth;
use Config\App;
use App\Models\BaseModel;

class BaseController extends Controller {

    protected $data;
    protected $config;
    protected $session;
    protected $router;
    protected $request;
    protected $isLoggedIn;
    protected $auth;
    protected $user;
    protected $model;
    public $currentModule;
    private $controllerName;
    private $methodName;
    protected $actionUser;
    protected $moduleURL;
    protected $moduleRole;

    public function __construct() {
        date_default_timezone_set('Asia/Jakarta');
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->config = new App;
        $this->auth = new Auth;
        $this->model = new BaseModel;
        helper('util');
        $web = $this->session->get('web');

        $nama_module = $web['nama_module'];
        $module = $this->model->getModule($nama_module);
        if (!$module) {
            $this->data['status'] = 'error';
            $this->data['title'] = 'ERROR';
            $this->data['content'] = 'Module ' . $nama_module . ' tidak ditemukan di database';
            $this->viewError($this->data);
            exit();
        }
        $this->currentModule = $module;
        $this->moduleURL = $web['module_url'];

        $this->model->checkRememberme();
        $this->isLoggedIn = $this->session->get('logged_in');
        $this->data['current_module'] = $this->currentModule;
        $this->data['scripts'] = array($this->config->baseURL . '/public/assets/vendors/jquery/jquery.min.js'
            , $this->config->baseURL . '/public/assets/vendors/flatpickr/flatpickr.js'
            , $this->config->baseURL . '/public/themes/modern/assets/js/site.js?r=' . time()
            , $this->config->baseURL . '/public/assets/vendors/zenscroll/zenscroll-min.js'
            , $this->config->baseURL . '/public/assets/vendors/bootstrap/js/bootstrap.js'
        );
        $this->data['styles'] = array(
            $this->config->baseURL . '/public/assets/vendors/bootstrap/css/bootstrap.css'
            , $this->config->baseURL . '/public/themes/modern/assets/css/site.css?r=' . time()
        );
        $this->data['config'] = $this->config;
        $this->data['request'] = $this->request;
        $this->data['isloggedin'] = $this->isLoggedIn;
        $this->data['session'] = $this->session;
        $this->data['site_title'] = 'Admin Template Codeigniter 4';
        $this->data['site_desc'] = 'Admin Template Codeigniter 4 lengkap dengan berbagai fitur untuk memudahkan pengembangan aplikasi';
        $this->data['settingWeb'] = $this->model->getSettingWeb();
        $this->data['user'] = [];
        $this->data['auth'] = $this->auth;
        $this->data['scripts'] = [];
        $this->data['styles'] = [];
        $this->data['module_url'] = $this->moduleURL;

        if ($this->isLoggedIn) {
            $user_setting = $this->model->getUserSetting();
            if ($user_setting) {
                $this->data['app_layout'] = json_decode($user_setting->param, true);
            }
        } else {
            $query = $this->model->getAppLayoutSetting();
            foreach ($query as $val) {
                $app_layout[$val['param']] = $val['value'];
            }
            $this->data['app_layout'] = $app_layout;
        }

        // Login? Yes, No, Restrict
        if ($this->currentModule['login'] == 'Y' && $nama_module != 'login' && $nama_module != 'simulasi') {
            $this->loginRequired();
        } else if ($this->currentModule['login'] == 'R') {
            $this->loginRestricted();
        }

        if ($this->isLoggedIn) {
            $this->user = $this->session->get('user');
            $this->data['user'] = $this->user;

            // List action assigned to role
            $this->data['action_user'] = $this->actionUser;

            $this->data['menu'] = $this->model->getMenu(1, false, $this->currentModule['nama_module']);

            $this->data['breadcrumb'] = ['Home' => $this->config->baseURL, $this->currentModule['judul_module'] => $this->moduleURL];
            $this->data['module_role'] = $this->model->getDefaultUserModule();

            $this->getModuleRole();
            $this->getListAction();

            // Check Global Role Action
            $this->checkRoleAction();

            if ($nama_module == 'login' || $nama_module == 'simulasi') {
                $this->redirectOnLoggedIn();
            }
        } else {
            $this->data['menu_front'] = $this->model->get_menu_front('Header');
            $this->data['menu_front_footer'] = $this->model->get_menu_front('Footer');
        }
    }

    private function getModuleRole() {
        $query = $this->model->getModuleRole($this->currentModule['id_module']);
        $this->moduleRole = [];
        foreach ($query as $val) {
            $this->moduleRole[$val['id_role']] = $val;
        }
    }

    private function cekHakBukaModul($moduleRole) {
        if ($moduleRole) {
            $id_role = $this->session->get('user')['id_role'];
            return (key_exists($id_role, $moduleRole));
        }
        
        return false;
    }

    private function getListAction() {
        $id_role = $this->session->get('user')['id_role'];

        if ($this->isLoggedIn && $this->currentModule['nama_module'] != 'login' && $this->currentModule['nama_module'] != 'simulasi') {

            if ($this->moduleRole) {
                if (key_exists($id_role, $this->moduleRole)) {

                    $this->actionUser = $this->moduleRole[$id_role];
                }
                if ($this->currentModule['nama_module'] != 'login') {

//                    print_r($this->moduleRole); exit;
                    if (!key_exists($id_role, $this->moduleRole)) {
                        $this->setCurrentModule('error');
                        $this->data['msg']['status'] = 'error';
                        $this->data['msg']['message'] = 'Anda tidak berhak mengakses halaman ini';
                        $this->view('error.php', $this->data);

                        exit();
                    }
                }
            } else {
                $this->setCurrentModule('error');
                $this->data['msg']['status'] = 'error';
                $this->data['msg']['message'] = 'Role untuk module ini belum diatur';
                $this->view('error.php', $this->data);
                exit();
            }
        }
    }

    private function setCurrentModule($module) {
        $this->currentModule['nama_module'] = $module;
    }

    protected function getControllerName() {
        return $this->controllerName;
    }

    protected function getMethodName() {
        return $this->methodName;
    }

    protected function addStyle($file) {
        $this->data['styles'][] = $file;
    }

    protected function addJs($file, $print = false) {
        if ($print) {
            $this->data['scripts'][] = ['print' => true, 'script' => $file];
        } else {
            $this->data['scripts'][] = $file;
        }
    }

    protected function viewError($data) {

        echo view('app_error.php', $data);
    }

    protected function view($file, $data = false, $file_only = false) {
        if (is_array($file)) {
            foreach ($file as $file_item) {
                echo view($file_item, $data);
            }
        } else {
            $query = $this->model->getModuleRole(15);
            $data['boleh_setting'] = $this->cekHakBukaModul($query);
            echo view('themes/modern/header.php', $data);
            echo view('themes/modern/' . $file, $data);
            echo view('themes/modern/footer.php');
        }
    }

    protected function viewFront($file, $data = false, $file_only = false) {
        if (is_array($file)) {
            foreach ($file as $file_item) {
                echo view($file_item, $data);
            }
        } else {
            echo view('themes/modern/header-front.php', $data);
//            echo view('themes/modern/' . $file, $data);
            echo view('themes/modern/builtin/' . $file, $data);
            echo view('themes/modern/footer-front.php');
        }
    }

    protected function loginRequired() {
        if (!$this->isLoggedIn) {
            header('Location: ' . $this->config->baseURL . 'login');
            // redirect()->to($this->config->baseURL . 'login');
            exit();
        }
    }

    protected function loginRestricted() {
        if ($this->isLoggedIn) {
            if ($this->methodName !== 'logout') {
                header('Location: ' . $this->config->baseURL);
            }
        }
    }

    protected function redirectOnLoggedIn() {

        if ($this->isLoggedIn) {
            header('Location: ' . $this->config->baseURL . $this->data['module_role']->nama_module);
            // redirect($this->router->default_controller);
        }
    }

    protected function mustNotLoggedIn() {
        if ($this->isLoggedIn) {
            if ($this->currentModule['nama_module'] == 'login' || $this->currentModule['nama_module'] == 'simulasi') {
                header('Location: ' . $this->config->baseURL . $this->data['module_role']->nama_module);
                exit();
            }
        }
    }

    protected function mustLoggedIn() {
        if (!$this->isLoggedIn) {
            header('Location: ' . $this->config->baseURL . 'login');
            exit();
        }
    }

    private function checkRoleAction() {
        if ($this->config->checkRoleAction['enable_global']) {
            $method = $this->session->get('web')['method_name'];
            $error = false;
            if ($method == 'add') {
                if ($this->actionUser['create_data'] == 'no') {
                    $error = 'Role Anda tidak diperkenankan untuk menambah data';
                }
            } else if ($method == 'edit') {
                if ($this->actionUser['update_data'] == 'no') {
                    $error = 'Role Anda tidak diperkenankan untuk mengubah data';
                }
            } else {
                if (!empty($_POST['delete'])) {
                    if ($this->actionUser['delete_data'] == 'no') {
                        $error = 'Role Anda tidak diperkenankan untuk menghapus data';
                    }
                }
            }

            if ($error) {
                $this->data['msg'] = ['status' => 'error', 'message' => $error];
                $this->view('error.php', $this->data);
                exit;
            }
        }
    }

    protected function cekHakAksesAja($action) {
        $allowed = $this->actionUser[$action];

        return $allowed;
    }

    protected function cekHakAkses($action, $table_column = null, $column_check = null) {

        $action_title = ['read_data' => 'membuka data', 'create_data' => 'menambah data', 'update_data' => 'mengubah data', 'delete_data' => 'menghapus data'];
        $allowed = $this->actionUser[$action];
//        print_r($allowed); exit;

        if ($allowed == 'no') {
            $this->currentModule['nama_module'] = 'error';
            $this->data['msg'] = ['status' => 'error', 'message' => 'Role Anda tidak diperkenankan untuk ' . $action_title[$action]];
            $this->view('error.php', $this->data);
        } else if ($allowed == 'own') {
            // Read -> go to where_own()
            if ($action == 'read_data')
                return true;

            // Update and delete
            $column = '';
            if ($table_column) {
                $exp = explode('|', $table_column);
                $table = $exp[0];
                $column = @$exp[1];
            } else {
                $table = $this->currentModule['nama_module'];
            }

            if (!$column) {
                $column = 'id_' . $table;
            }

            if (!$column_check) {
                $column_check = $this->config->checkRoleAction['field'];
            }

//            print_r($table.' '.$column.' '.trim($_REQUEST['id'])); exit;
            $result = $this->model->getDataById($table, $column, trim($_REQUEST['id']));

            if ($result) {
                $data = $result[0];

                if ($data[$column_check] != $_SESSION['user']['id_user']) {
                    $this->data['msg'] = ['status' => 'error', 'message' => 'Role Anda tidak diperkenankan untuk ' . $action_title[$action] . ' ini'];
                    $this->view('/error.php', $this->data);
                }
            }
        }
    }

    public function whereOwn($column = null, $column_check = null) {
        if (!$column)
            $column = $this->config->checkRoleAction['field'];

        if (!$column_check)
            $column_check = $_SESSION['user']['id_user'];

        if ($this->actionUser['read_data'] == 'own') {
            return ' WHERE ' . $column . ' = ' . $column_check;
        }

        return ' WHERE 1 = 1 ';
    }

    protected function printError($message) {
        $this->data['title'] = 'Error...';
        $this->data['msg'] = $message;
        $this->view('error.php', $this->data);
        exit();
    }

    /* Used for modules when edited data not found */

    protected function errorDataNotFound($addData = null) {
        $data = $this->data;
        $data['title'] = 'Error';
        $data['msg']['status'] = 'error';
        $data['msg']['content'] = 'Data tidak ditemukan';

        if ($addData) {
            $data = array_merge($data, $addData);
        }
        $this->view('error-data-notfound.php', $data);
        exit;
    }

}
