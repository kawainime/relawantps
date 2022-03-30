<?php

/**
 * 	App Name	: Admin Template Dashboard Codeigniter 4	
 * 	Developed by: Agus Prawoto Hadi
 * 	Website		: https://jagowebdev.com
 * 	Year		: 2020
 */

namespace App\Controllers;

use App\Models\Builtin\LoginModel;
use \Config\App;
use App\Libraries\Auth;
use App\Models\ProvinsiModel;
use App\Models\KabupatenModel;
use App\Models\DapilkabModel;
use App\Models\TpsModel;

class Simulasi extends \App\Controllers\BaseController {

    protected $model = '';

    public function __construct() {
        parent::__construct();
        $this->model = new LoginModel;
        $this->modProv = new ProvinsiModel;
        $this->modKab = new KabupatenModel;
        $this->modDapilkab = new DapilkabModel;
        $this->modTps = new TpsModel;

        $this->data['site_title'] = 'Simulasi Daerah Pemilihan & Kebutuhan Suara Tiap TPS';

        $this->addJs($this->config->baseURL . 'public/vendors/jquery.select2/js/select2.full.min.js');
        $this->addJs($this->config->baseURL . 'public/vendors/jquery.select2/js/select2.bootstrap.js');
        $this->addJs($this->config->baseURL . 'public/themes/modern/builtin/js/simulasi.js');
        $this->addStyle($this->config->baseURL . 'public/vendors/jquery.select2/css/select2.min.css');
        $this->addStyle($this->config->baseURL . 'public/vendors/jquery.select2/css/select2-bootstrap4.min.css');

        helper(['cookie', 'form']);
    }

    public function index() {
//        print_r('masuk sini??'); exit;
        $this->mustNotLoggedIn();
        $this->data['status'] = '';
        if ($this->request->getPost('password')) {

            $this->login();
            if ($this->session->get('logged_in')) {
                return redirect()->to($this->config->baseURL);
            }
        }

        $query = $this->model->getSettingRegistrasi();
        foreach ($query as $val) {
            $this->data['setting_registrasi'][$val['param']] = $val['value'];
        }

        $query = $this->modProv->getProvinsi(" where id > 0");
        $this->data['prov'][''] = '';
        foreach ($query as $key => $val) {
            $this->data['prov'][$val['id']] = $val['nama'];
        }

        csrf_settoken();
//        echo view('themes/modern/builtin/login-page', $this->data);
        $this->viewFront('simulasi-page.php', $this->data);
    }

    public function getDataKab() {
        $filterid = $_GET['filterid'];

        if (!$filterid) {
            $filterid = '0';
        }
        
        $items = $this->modKab->getKabupaten(" where parent_id = $filterid");

        $html = '';

        $select = '<option value=\"\"></option>';
        $html .= '$("select#dapilkab").html("' . $select . '");';
        $html .= '$("#jmltps").val(0);';
        $html .= '$("#butuhsuara").val(0);';

        foreach ($items as $list) :
            $select .= "<option value='" . $list['id'] . "'>" . $list['nama'] . "</option>";
        endforeach;

        $html .= '$("select#kabupaten").html("' . $select . '");';
//        $html .= '$("input[name=\'' . $csrf_token['name'] . '\']").val("' . $_COOKIE[$csrf_token['name']] . '");';
        echo $html;
    }

    public function getDataDapilkab() {
        $filterid = $_GET['filterid'];

        if (!$filterid) {
            $filterid = '0';
        }
        
        $items = $this->modDapilkab->getDapilkab(" where kab_id = $filterid");

        $html = '';

        $select = '<option value=\"\"></option>';
        $html .= '$("#jmltps").val(0);';
        $html .= '$("#butuhsuara").val(0);';
//        $html .= '$("select#poktan_kec").html("' . $select . '");';
//        $html .= '$("select#poktan_desa").html("' . $select . '");';

        foreach ($items as $list) :
            $select .= "<option value='" . $list['id'] . "'>" . $list['nama'] . "</option>";
        endforeach;

        $html .= '$("select#dapilkab").html("' . $select . '");';
//        $html .= '$("input[name=\'' . $csrf_token['name'] . '\']").val("' . $_COOKIE[$csrf_token['name']] . '");';
        echo $html;
    }
    
    public function getJmlTpsBySuara() {
        $jml = $_GET['jml'];
        $targetsuara = $_GET['target'];
        
        $butuh = 0;
        if ($jml > 0) {
            if ($targetsuara > 0) {
                $butuh = ceil($targetsuara/$jml);
            }
        }
        $html = '$("#butuhsuara").val('.$butuh.');';
        echo $html;
    }

    public function getJmlTps() {
        $filterid = $_GET['filterid'];
        $targetsuara = $_GET['target'];

        if (!$filterid) {
            $filterid = '0';
        }
        
        $jml = $this->modTps->countTps(" where dapil like '%,$filterid,%' or dapil like '%[$filterid,%' or dapil like '%,$filterid]%'");

        $html = '';

//        $select = '<option value=\"\"></option>';
        $html .= '$("#jmltps").val('.$jml.');';
        
        $butuh = 0;
        if (!empty($targetsuara)) {
            if (!empty($jml)) {
                $butuh = ceil($targetsuara/$jml);
            }
        }
        $html .= '$("#butuhsuara").val('.$butuh.');';
        
//        $html .= '$("select#poktan_kec").html("' . $select . '");';
//        $html .= '$("select#poktan_desa").html("' . $select . '");';

//        foreach ($items as $list) :
//            $select .= "<option value='" . $list['id'] . "'>" . $list['nama'] . "</option>";
//        endforeach;
//
//        $html .= '$("select#dapilkab").html("' . $select . '");';
//        $html .= '$("input[name=\'' . $csrf_token['name'] . '\']").val("' . $_COOKIE[$csrf_token['name']] . '");';
        echo $html;
    }

    private function login() {
        // Check Token
        $validation_message = csrf_validation();

        // Cek CSRF token
        if ($validation_message) {
            $this->data['status'] = 'error';
            $this->data['message'] = $validation_message['message'];
            return;
        }

        $error = false;
        $user = $this->model->checkUser($this->request->getPost('username'));
        if ($user) {
            if ($user['verified'] == 0) {
                $message = 'User belum aktif';
                $error = true;
            }

            if (!password_verify($this->request->getPost('password'), $user['password'])) {
                $message = 'Username dan/atau Password tidak cocok';
                $error = true;
            }
        } else {
            $message = 'User tidak ditemukan';
            $error = true;
        }

        if ($error) {
            $this->data['status'] = 'error';
            $this->data['message'] = $message;
            return;
        }

        if ($this->request->getPost('remember')) {
            $this->model->setUserToken($user);
        }

        $this->session->set('user', $user);
        $this->session->set('logged_in', true);
        $this->model->recordLogin();
    }

    public function refreshLoginData() {
        $email = $this->session->get('user')['email'];
        $result = $this->model->checkUser($email);
        $this->session->set('user', $result);
    }

    public function logout() {
        $user = $this->session->get('user');
        if ($user) {
            $this->model->deleteAuthCookie($this->session->get('user')['id_user']);
        }
        $this->session->destroy();
        // $this->session->stop();
        header('location: ' . $this->config->baseURL . 'login');
        exit;
        // return redirect()->to($this->config->baseURL . 'login');
        // exit;
        // return redirect()->to($this->config->baseURL . 'login');
    }

}
