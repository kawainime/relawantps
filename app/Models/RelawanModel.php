<?php

/**
 * 	App Name	: Admin Template Dashboard Codeigniter 4	
 * 	Developed by: Agus Prawoto Hadi
 * 	Website		: https://jagowebdev.com
 * 	Year		: 2021
 */

namespace App\Models;

use App\Models\ProvinsiModel;
use App\Models\KabupatenModel;
use App\Models\KecamatanModel;
use App\Models\KelurahanModel;
use App\Models\UserModel;

class RelawanModel extends \App\Models\BaseModel {

    private $fotoPath;

    public function __construct() {
        parent::__construct();
        $this->fotoPath = 'public/images/foto/';

        $this->modProv = new ProvinsiModel;
        $this->modKab = new KabupatenModel;
        $this->modKec = new KecamatanModel;
        $this->modKel = new KelurahanModel;
        $this->modUser = new UserModel;
    }

    public function getRelawan($where) {
        $sql = 'SELECT * FROM user_relawan' . $where;
        $result = $this->db->query($sql)->getResultArray();
        return $result;
    }

    public function deleteData() {
        $sql = 'SELECT foto FROM user_relawan WHERE id = ?';
        $img = $this->db->query($sql, $_POST['id'])->getRowArray();
        if ($img['foto']) {
            if (file_exists($this->fotoPath . $img['foto'])) {
                $unlink = unlink($this->fotoPath . $img['foto']);
                if (!$unlink) {
                    return false;
                }
            }
        }
        $result = $this->db->table('user_relawan')->delete(['id' => $_POST['id']]);
        return $result;
    }

    public function getRelawanById($id) {
        $sql = 'SELECT * FROM user_relawan WHERE id = ?';
        $result = $this->db->query($sql, trim($id))->getRowArray();
        return $result;
    }

    public function getViewRelawanById($id) {
        $sql = 'SELECT * FROM v_user_relawan WHERE id = ?';
        $result = $this->db->query($sql, trim($id))->getRowArray();
        return $result;
    }

    public function getRelawanByIdUser($id) {
        $sql = 'SELECT * FROM user_relawan WHERE id_user = ?';
        $result = $this->db->query($sql, trim($id))->getRowArray();

        $prov = $this->modProv->getProvinsiById($result['id_prov']);
        $kab = $this->modKab->getKabupatenById($result['id_kab']);
        $kec = $this->modKec->getKecamatanById($result['id_kec']);
        $kel = $this->modKel->getKelurahanById($result['id_kel']);
        $user = $this->modUser->getPenggunaById($result['id_user']);

        $result['provinsi'] = $prov['nama'];
        $result['kabupaten'] = $kab['nama'];
        $result['kecamatan'] = $kec['nama'];
        $result['kelurahan'] = $kel['nama'];
        $result['avatar'] = $user['nama'];
        $result['username'] = $user['username'];
        $result['email'] = $user['email'];

        return $result;
    }

    public function getViewRelawanByIdUser($id) {
        $sql = 'SELECT * FROM v_user_relawan WHERE id_user = ?';
        $result = $this->db->query($sql, trim($id))->getRowArray();

        return $result;
    }

    public function saveData() {
        $this->db->transBegin();

        helper('upload_file');

        $error = false;

        if (isset($_POST['idDpt'])) {
            $sql = 'SELECT * FROM rdpp_dpt_' . $_POST['id_prov'] . '_' . $_POST['id_kab'] . ' WHERE idDpt = ?';
            $result = $this->db->query($sql, $_POST['idDpt'])->getRowArray();

            $data_db['nik'] = $result['nik'];
            $data_db['nama'] = $result['nama'];
            $data_db['tempatLahir'] = $result['tempatLahir'];
            $data_db['jenisKelamin'] = $result['jenisKelamin'];

//            if (isset($_POST['noTps']))
            $data_db['noTps'] = $result['noTps'];

            $sql = 'SELECT * FROM user_relawan WHERE idDpt = ?';
            $result = $this->db->query($sql, trim($_POST['idDpt']))->getRowArray();

            if ($result) {
                $error = true;
                $result['msg']['status'] = 'error';
                $result['msg']['content'] = $result['nama'] . ' - ' . $result['nik'] . ' sudah terdaftar sebagai Relawan TPS';
            }

            $data_db['idDpt'] = $_POST['idDpt'];
        }

        if (!$error) {
            if (isset($_POST['id_prov']))
                $data_db['id_prov'] = $_POST['id_prov'];
            if (isset($_POST['id_kab']))
                $data_db['id_kab'] = $_POST['id_kab'];
            if (isset($_POST['id_kec']))
                $data_db['id_kec'] = $_POST['id_kec'];
            if (isset($_POST['id_kel']))
                $data_db['id_kel'] = $_POST['id_kel'];
            if (isset($_POST['no_wa']))
                $data_db['no_wa'] = $_POST['no_wa'];
            if (isset($_POST['rt']))
                $data_db['rt'] = $_POST['rt'];
            if (isset($_POST['rw']))
                $data_db['rw'] = $_POST['rw'];
            if (isset($_POST['nik']))
                $data_db['nik'] = $_POST['nik'];

            if ($_POST['id']) {
                $id_user = null;
                if (isset($_POST['id_user'])) {
//                    if (empty($_POST['id_user'])) {
                    $form_errors = $this->validateUser();

                    if ($form_errors) {
                        $result['msg']['status'] = 'error';
                        $result['form_errors'] = $form_errors;
                        $result['msg']['message'] = $form_errors;
                        $result['msg']['content'] = $form_errors;
                        $error = true;
                    } else {
                        $save = $this->saveUser();
                        if ($save['status'] == 'ok') {
                            $result['msg']['status'] = 'ok';
                            $result['msg']['message'] = 'Data berhasil disimpan';
                            $result['msg']['content'] = 'Data berhasil disimpan';
                            $id_user = $save['id_user'];
                        } else {
                            $result['msg']['status'] = 'error';
                            $result['msg']['message'] = $save['message'];
                            $result['msg']['content'] = $save['message'];
                        }
                    }
//                    }
                }

                if (!$error) {
                    $data_db['updated'] = date('Y-m-d H:i:s');
                    $data_db['id_user_edit'] = $_SESSION['user']['id_user'];

                    if ($id_user)
                        $data_db['id_user'] = $id_user;

                    $query = $this->db->table('user_relawan')->update($data_db, ['id' => $_POST['id']]);
                    if ($query) {
                        $result['msg']['status'] = 'ok';
                        $result['msg']['content'] = 'Data berhasil disimpan';
                    } else {
                        $error = true;
                        $result['msg']['status'] = 'error';
                        $result['msg']['content'] = 'Data gagal disimpan';
                    }
                }
            } else {

                $data_db['created'] = date('Y-m-d H:i:s');
                $data_db['id_user_input'] = $_SESSION['user']['id_user'];
                $data_db['id_caleg'] = $_SESSION['user']['id_user'];
                $query = $this->db->table('user_relawan')->insert($data_db);
                $result['id'] = '';
                if ($query) {
                    $result['msg']['status'] = 'ok';
                    $result['msg']['content'] = 'Data berhasil disimpan';
                    $result['id'] = $this->db->insertID();
                } else {
                    $error = true;
                    $result['msg']['status'] = 'error';
                    $result['msg']['content'] = 'Data gagal disimpan';
                }
            }
        }

//        $data_db['id_user'] = $_POST['nama'];
        if ($error) {
            $this->db->transRollback();
        } else {
            $this->db->transCommit();
        }

        return $result;
    }

    private function saveUser($action_user = []) {
        $fields = ['username', 'id_role', 'nama', 'email'];
//        if ($action_user['update_data'] == 'all') {
//            $fields[] = 'username';
//        }

        foreach ($fields as $field) {
            $data_db[$field] = $this->request->getPost($field);
        }

        $data_db['verified'] = 1;
        $data_db['status'] = 1;

        if (!$this->request->getPost('id_user')) {
            $data_db['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            $exp = explode('-', $this->request->getPost('tgl_lahir'));
        }

        // Save database
        if ($this->request->getPost('id_user')) {
            $id_user = $this->request->getPost('id_user');
            $save = $this->db->table('user')->update($data_db, ['id_user' => $id_user]);
        } else {
            $data_db['status'] = 1;
            $save = $this->db->table('user')->insert($data_db);
            $id_user = $this->db->insertID();
        }
        // $save = true;
        if ($save) {

            $file = $this->request->getFile('avatar');
            $path = ROOTPATH . 'public/images/user/';

            $sql = 'SELECT avatar FROM user WHERE id_user = ?';
            $img_db = $this->db->query($sql, $id_user)->getRowArray();
            $new_name = $img_db['avatar'];

            if (!empty($_POST['avatar_delete_img'])) {
                $del = delete_file($path . $img_db['avatar']);
                $new_name = '';
                if (!$del) {
                    $result['message'] = 'Gagal menghapus gambar lama';
                    $error = true;
                }
            }


            if ($file && $file->getName()) {
                //old file
                if ($img_db['avatar']) {
                    if (file_exists($path . $img_db['avatar'])) {
                        $unlink = delete_file($path . $img_db['avatar']);
                        if (!$unlink) {
                            $result['msg']['status'] = 'error';
                            $result['msg']['content'] = 'Gagal menghapus gambar lama';
                        }
                    }
                }

                helper('upload_file');
                $new_name = get_filename($file->getName(), $path);
                $file->move($path, $new_name);

                if (!$file->hasMoved()) {
                    $result['message'] = 'Error saat memperoses gambar';
                    return $result;
                }
            }

            // Update avatar
            $data_db = [];
            $data_db['avatar'] = $new_name;
            $save = $this->db->table('user')->update($data_db, ['id_user' => $id_user]);
        }

        if ($save) {
            $result['status'] = 'ok';
            $result['message'] = 'Data berhasil disimpan';
            $result['id_user'] = $id_user;

            // Reload data user
//            $this->session->set('user', $this->getUserById($this->session->get('user')['id_user']));
        } else {
            $result['status'] = 'error';
        }

        return $result;
    }

    private function validateUser() {

        $validation = \Config\Services::validation();
        $validation->setRule('nama', 'Nama', 'trim|required');
        $validation->setRule('email', 'Nama', 'trim|required');
        $validation->setRule('username', 'Username', 'trim|required');
        $validation->setRule('id_role', 'Role', 'trim|required');

        if ($this->request->getPost('id_user')) {
            if ($this->request->getPost('email') != $this->request->getPost('email_lama')) {
                // echo 'sss'; die;
//                if ($this->actionUser['update_data'] == 'all') {
                $validation->setRules(
                        ['email' => [
                                'label' => 'Email',
                                'rules' => 'required|valid_email|is_unique[user.email]',
                                'errors' => [
                                    'is_unique' => 'Email sudah digunakan'
                                    , 'valid_email' => 'Alamat email tidak valid'
                                ]
                            ]
                        ]
                );
//                }
            }
        } else {
            $validation->setRule('password', 'Password', 'trim|required|min_length[3]');
            $validation->setRules(
                    ['username' => [
                            'label' => 'Username',
                            'rules' => 'required|is_unique[user.username]',
                            'errors' => [
                                'required' => 'Username tidak boleh kosong',
                                'is_unique' => 'Username sudah digunakan'
                            ]
                        ],
                        'email' => [
                            'label' => 'Email',
                            'rules' => 'required|valid_email|is_unique[user.email]',
                            'errors' => [
                                'is_unique' => 'Email sudah digunakan'
                                , 'valid_email' => 'Alamat email tidak valid'
                            ]
                        ],
                        'ulangi_password' => [
                            'label' => 'Ulangi Password',
                            'rules' => 'required|matches[password]',
                            'errors' => [
                                'required' => 'Ulangi password tidak boleh kosong'
                                , 'matches' => 'Ulangi password tidak cocok dengan password'
                            ]
                        ]
                    ]
            );
        }

        $valid = $validation->withRequest($this->request)->run();
        $form_errors = $validation->getErrors();

        $file = $this->request->getFile('avatar');
        if ($file && $file->getName()) {
            if ($file->isValid()) {
                $type = $file->getMimeType();
                $allowed = ['image/png', 'image/jpeg', 'image/jpg'];

                if (!in_array($type, $allowed)) {
                    $form_errors['avatar'] = 'Tipe file harus ' . join($allowed, ', ');
                }

                if ($file->getSize() > 300 * 1024) {
                    $form_errors['avatar'] = 'Ukuran file maksimal 300Kb';
                }

                $info = \Config\Services::image()
                        ->withFile($file->getTempName())
                        ->getFile()
                        ->getProperties(true);

                if ($info['height'] < 100 || $info['width'] < 100) {
                    $form_errors['avatar'] = 'Dimensi file minimal: 100px x 100px';
                }
            } else {
                $form_errors['avatar'] = $file->getErrorString() . '(' . $file->getError() . ')';
            }
        }

        return $form_errors;
    }

    public function countAllData($where) {
        $sql = 'SELECT COUNT(*) AS jml FROM user_relawan' . $where;
        $result = $this->db->query($sql)->getRow();
        return $result->jml;
    }

    public function getUserRelawan($where) {
        $sql = 'SELECT * FROM user' . $where;
        $result = $this->db->query($sql)->getResultArray();
        return $result;
    }

    public function getListData($where) {

        $columns = $this->request->getPost('columns');

        // Search
        $search_all = @$this->request->getPost('search')['value'];
        if ($search_all) {
            // Additional Search
//            $columns[]['data'] = 'tempat_lahir';
            foreach ($columns as $val) {

                if (strpos($val['data'], 'ignore_search') !== false)
                    continue;

                if (strpos($val['data'], 'ignore') !== false)
                    continue;

                $where_col[] = $val['data'] . ' LIKE "%' . $search_all . '%"';
            }
            $where .= ' AND (' . join(' OR ', $where_col) . ') ';
        }

        // Order
        $start = $this->request->getPost('start') ?: 0;
        $length = $this->request->getPost('length') ?: 10;

        $order_data = $this->request->getPost('order');
        $order = '';
        if (strpos($_POST['columns'][$order_data[0]['column']]['data'], 'ignore_search') === false) {
            $order_by = $columns[$order_data[0]['column']]['data'] . ' ' . strtoupper($order_data[0]['dir']);
            $order = ' ORDER BY ' . $order_by . ' LIMIT ' . $start . ', ' . $length;
        }

        // Query Total Filtered
        // $sql = 'SELECT COUNT(*) AS jml_data FROM mahasiswa ' . $where;
        $total_filtered = $this->db
                ->table('user_relawan')
                ->where(str_replace('WHERE', '', $where))
                ->countAllResults();

        // Query Data
        $sql = 'SELECT * FROM user_relawan 
				' . $where . $order;
        $data = $this->db->query($sql)->getResultArray();

        return ['data' => $data, 'total_filtered' => $total_filtered];
    }

    public function getListViewData($where) {

        $columns = $this->request->getPost('columns');

        // Search
        $search_all = @$this->request->getPost('search')['value'];
        if ($search_all) {
            // Additional Search
//            $columns[]['data'] = 'tempat_lahir';
            foreach ($columns as $val) {

                if (strpos($val['data'], 'ignore_search') !== false)
                    continue;

                if (strpos($val['data'], 'ignore') !== false)
                    continue;

                $where_col[] = $val['data'] . ' LIKE "%' . $search_all . '%"';
            }
            $where .= ' AND (' . join(' OR ', $where_col) . ') ';
        }

        // Order
        $start = $this->request->getPost('start') ?: 0;
        $length = $this->request->getPost('length') ?: 10;

        $order_data = $this->request->getPost('order');
        $order = '';
        if (strpos($_POST['columns'][$order_data[0]['column']]['data'], 'ignore_search') === false) {
            $order_by = $columns[$order_data[0]['column']]['data'] . ' ' . strtoupper($order_data[0]['dir']);
            $order = ' ORDER BY ' . $order_by . ' LIMIT ' . $start . ', ' . $length;
        }

        // Query Total Filtered
        // $sql = 'SELECT COUNT(*) AS jml_data FROM mahasiswa ' . $where;
        $total_filtered = $this->db
                ->table('v_user_relawan')
                ->where(str_replace('WHERE', '', $where))
                ->countAllResults();

        // Query Data
        $sql = 'SELECT * FROM v_user_relawan 
				' . $where . $order;
        $data = $this->db->query($sql)->getResultArray();

        return ['data' => $data, 'total_filtered' => $total_filtered];
    }

    public function getListViewDataNew($where) {

        $columns = $this->request->getPost('columns');

        // Search
        $search_all = @$this->request->getPost('search')['value'];
        if ($search_all) {
            // Additional Search
//            $columns[]['data'] = 'tempat_lahir';
            foreach ($columns as $val) {

                if (strpos($val['data'], 'ignore_search') !== false)
                    continue;

                if (strpos($val['data'], 'ignore') !== false)
                    continue;

                $where_col[] = $val['data'] . ' LIKE "%' . $search_all . '%"';
            }
            $where .= ' AND (' . join(' OR ', $where_col) . ') ';
        }

        // Order
        $start = $this->request->getPost('start') ?: 0;
        $length = $this->request->getPost('length') ?: 10;

        $order_data = $this->request->getPost('order');
        $order = '';
        if (strpos($_POST['columns'][$order_data[0]['column']]['data'], 'ignore_search') === false) {
            $order_by = $columns[$order_data[0]['column']]['data'] . ' ' . strtoupper($order_data[0]['dir']);
            $order = ' ORDER BY ' . $order_by . ' LIMIT ' . $start . ', ' . $length;
        }

        // Query Total Filtered
        // $sql = 'SELECT COUNT(*) AS jml_data FROM mahasiswa ' . $where;
        $total_filtered = $this->db
                ->table('v_user_relawan_new')
                ->where(str_replace('WHERE', '', $where))
                ->countAllResults();

        // Query Data
        $sql = 'SELECT * FROM v_user_relawan_new
				' . $where . $order;
        $data = $this->db->query($sql)->getResultArray();

        return ['data' => $data, 'total_filtered' => $total_filtered];
    }

}

?>