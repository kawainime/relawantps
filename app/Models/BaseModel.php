<?php

/**
 * 	App Name	: Admin Template Dashboard Codeigniter 4	
 * 	Developed by: Agus Prawoto Hadi
 * 	Website		: https://jagowebdev.com
 * 	Year		: 2020
 */

namespace App\Models;

use App\Libraries\Auth;

class BaseModel extends \CodeIgniter\Model {

    protected $request;
    protected $session;
    private $auth;
    protected $user;

    public function __construct() {
        parent::__construct();

        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        $user = $this->session->get('user');
        if ($user)
            $this->user = $this->getUserById($user['id_user']);

        $this->auth = new \App\Libraries\Auth;
    }

    public function checkRememberme() {
        if ($this->session->get('logged_in')) {
            return true;
        }

        helper('cookie');
        $cookie_login = get_cookie('remember');

        if ($cookie_login) {
            list($selector, $cookie_token) = explode(':', $cookie_login);

            $sql = 'SELECT * FROM user_token WHERE selector = ?';
            $data = $this->db->query($sql, $selector)->getRowArray();

            if ($this->auth->validateToken($cookie_token, @$data['token'])) {

                if ($data['expires'] > date('Y-m-d H:i:s')) {
                    $user_detail = $this->getUserById($data['id_user']);
                    $this->session->set('user', $user_detail);
                    $this->session->set('logged_in', true);
                }
            }
        }

        return false;
    }

    public function getUserById($id_user = null, $array = false) {

        if (!$id_user) {
            if (!$this->user) {
                return false;
            }
            $id_user = $this->user['id_user'];
        }

        $query = $this->db->query('SELECT * FROM user WHERE id_user = ?', [$id_user]);
        if ($array)
            return $query->getRowArray();

        return $query->getRowArray();
    }

    public function getUserSetting() {

        $result = $this->db->query('SELECT * FROM setting_app_user WHERE id_user = ?', [$this->session->get('user')['id_user']])
                ->getRow();

        if (!$result) {
            $query = $this->db->query('SELECT * FROM setting_app_tampilan')
                    ->getResultArray();

            foreach ($query as $val) {
                $data[$val['param']] = $val['value'];
            }
            $result = new \StdClass;
            $result->param = json_encode($data);
        }
        return $result;
    }

    public function getAppLayoutSetting() {
        $result = $this->db->query('SELECT * FROM setting_app_tampilan')->getResultArray();
        return $result;
    }

    public function getDefaultUserModule() {

        $query = $this->db->query('SELECT * 
							FROM role 
							LEFT JOIN module USING(id_module)
							WHERE id_role = ? '
                        , $this->session->get('user')['id_role']
                )->getRow();
        return $query;
    }

    public function getModule($nama_module) {
        $result = $this->db->query('SELECT * FROM module WHERE nama_module = ?', [$nama_module])
                ->getRowArray();
        return $result;
    }

// MENU
    public function get_menu_front($group = '') {
        if (!$group)
            return;

        global $db;

        $result = [];
        $sql = 'SELECT * FROM menu_frontend WHERE nama_group = "' . $group . '" ORDER BY urut';

        $menu_array = $this->db->query($sql)->getResultArray();

        foreach ($menu_array as $row) {
            $result[$row['id_menu']] = $row;
            $result[$row['id_menu']]['highlight'] = 0;
            $result[$row['id_menu']]['depth'] = 0;
            $result[$row['id_menu']]['highlight'] = 0;
        }

        return $result;
    }

    public function getMenu($aktif = 'all', $showAll = false, $current_module = '') {

        $result = [];
        $where = ' ';
        $where_aktif = '';
        if ($aktif != 'all') {
            $where_aktif = ' AND aktif = ' . $aktif;
        }

        $role = '';
        if (!$showAll) {
            $role = ' AND id_role = ' . $_SESSION['user']['id_role'];
        }

        $sql = 'SELECT * FROM menu 
					LEFT JOIN menu_role USING (id_menu)
					LEFT JOIN module USING (id_module)
				WHERE 1 = 1 ' . $role
                . $where_aktif . ' 
				ORDER BY urut';

        $menu_array = $this->db->query($sql)->getResultArray();

        $current_id = '';
        foreach ($menu_array as $val) {

            $result[$val['id_menu']] = $val;
            $result[$val['id_menu']]['highlight'] = 0;
            $result[$val['id_menu']]['depth'] = 0;

            if ($current_module == $val['nama_module']) {

                $current_id = $val['id_menu'];
                $result[$val['id_menu']]['highlight'] = 1;
            }
        }

        if ($current_id) {
            $this->menuCurrent($result, $current_id);
        }

        return $result;
    }

    private function menuCurrent(&$result, $current_id) {
        $parent = $result[$current_id]['id_parent'];

        $result[$parent]['highlight'] = 1; // Highlight menu parent
        if (@$result[$parent]['id_parent']) {
            $this->menuCurrent($result, $parent);
        }
    }

    public function getModuleRole($id_module) {
        $result = $this->db->query('SELECT * FROM module_role WHERE id_module = ? ', $id_module)->getResultArray();
        return $result;
    }

    public function validateFormToken($session_name = null, $post_name = 'form_token') {

        $form_token = explode(':', $this->request->getPost($post_name));

        $form_selector = $form_token[0];
        $sess_token = $this->session->get('token');
        if ($session_name)
            $sess_token = $sess_token[$session_name];

        if (!key_exists($form_selector, $sess_token))
            return false;

        try {
            $equal = $this->auth->validateToken($sess_token[$form_selector], $form_token[1]);

            return $equal;
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

    // For role check BaseController->cekHakAkses
    public function getDataById($table, $column, $id) {
        $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $column . ' = ?';
        return $this->db->query($sql, $id)->getResultArray();
    }

    public function checkUser($username) {
        $sql = 'SELECT * FROM user 
				WHERE username = ?';

        $query = $this->db->query($sql, [$username]);
        $result = $query->getRowArray();

        return $result;
    }

    public function getSettingWeb() {
        $sql = 'SELECT * FROM setting_web';
        $query = $this->db->query($sql)->getResultArray();

        $settingWeb = new \stdClass();
        foreach ($query as $val) {
            $settingWeb->{$val['param']} = $val['value'];
        }
        return $settingWeb;
    }

    public function getSettingRegistrasi() {
        $sql = 'SELECT * FROM setting_register';
        $query = $this->db->query($sql)->getResultArray();
        foreach ($query as $val) {
            $setting_register[$val['param']] = $val['value'];
        }
        return $setting_register;
    }

}
