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
use App\Models\RelawanModel;

class PemilihModel extends \App\Models\BaseModel {

    private $fotoPath;

    public function __construct() {
        parent::__construct();
        $this->fotoPath = 'public/images/foto/';

        $this->modProv = new ProvinsiModel;
        $this->modKab = new KabupatenModel;
        $this->modKec = new KecamatanModel;
        $this->modKel = new KelurahanModel;
        $this->modRel = new RelawanModel;
    }

    public function deleteData() {
        $sql = 'SELECT foto FROM pemilih WHERE id = ?';
        $img = $this->db->query($sql, $_POST['id'])->getRowArray();
        if ($img['foto']) {
            if (file_exists($this->fotoPath . $img['foto'])) {
                $unlink = unlink($this->fotoPath . $img['foto']);
                if (!$unlink) {
                    return false;
                }
            }
        }
        $result = $this->db->table('pemilih')->delete(['id' => $_POST['id']]);
        return $result;
    }

    public function getPemilih($where) {
        $sql = 'SELECT * FROM pemilih' . $where;
        $result = $this->db->query($sql)->getResultArray();
        return $result;
    }

    public function getPemilihById($id) {
        $sql = 'SELECT * FROM pemilih WHERE id = ?';
        $result = $this->db->query($sql, trim($id))->getRowArray();

        $prov = $this->modProv->getProvinsiById($result['id_prov']);
        $kab = $this->modKab->getKabupatenById($result['id_kab']);
        $kec = $this->modKec->getKecamatanById($result['id_kec']);
        $kel = $this->modKel->getKelurahanById($result['id_kel']);

        $result['provinsi'] = $prov['nama'];
        $result['kabupaten'] = $kab['nama'];
        $result['kecamatan'] = $kec['nama'];
        $result['kelurahan'] = $kel['nama'];

        return $result;
    }

    public function getViewPemilihById($id) {
        $sql = 'SELECT * FROM v_pemilih WHERE id = ?';
        $result = $this->db->query($sql, trim($id))->getRowArray();
        return $result;
    }

    public function saveData() {
        $this->db->transBegin();

        helper('upload_file');

        $error = false;

        if (isset($_POST['idDpt'])) {
            $sql = 'SELECT * FROM rdpp_dpt_' . $_POST['id_prov'] . '_' . $_POST['id_kab'] . ' WHERE idDpt = ?';
            $result = $this->dbdpt->query($sql, $_POST['idDpt'])->getRowArray();

            $data_db['nik'] = $result['nik'];
            $data_db['nama'] = $result['nama'];
            $data_db['tempatLahir'] = $result['tempatLahir'];
            $data_db['jenisKelamin'] = $result['jenisKelamin'];

            $sql = 'SELECT * FROM pemilih WHERE idDpt = ?';
            $result = $this->db->query($sql, trim($_POST['idDpt']))->getRowArray();

            if ($result) {
                $error = true;
                $result['msg']['status'] = 'error';
                $result['msg']['content'] = $result['nama'] . ' - ' . $result['nik'] . ' sudah terdaftar sebagai Pemilih';
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
            if (isset($_POST['nik']))
                $data_db['nik'] = $_POST['nik'];
            if (isset($_POST['status']))
                $data_db['status'] = $_POST['status'];
            if (isset($_POST['tipe']))
                $data_db['tipe'] = $_POST['tipe'];

            if (isset($_POST['noTps']))
                $data_db['noTps'] = $_POST['noTps'];

            if ($_POST['id']) {
                $data_db = [];
                if (isset($_POST['no_wa']))
                    $data_db['no_wa'] = $_POST['no_wa'];
                if (isset($_POST['nik']))
                    $data_db['nik'] = $_POST['nik'];
                if (isset($_POST['status']))
                    $data_db['status'] = $_POST['status'];
                if (isset($_POST['tipe']))
                    $data_db['tipe'] = $_POST['tipe'];
                $data_db['updated'] = date('Y-m-d H:i:s');
                $data_db['id_user_edit'] = $_SESSION['user']['id_user'];

                $file = $this->request->getFile('avatar');
                $path = ROOTPATH . 'public/images/pemilih/';

                $sql = 'SELECT avatar FROM pemilih WHERE id = ?';
                $img_db = $this->db->query($sql, $_POST['id'])->getRowArray();
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
                $data_db['avatar'] = $new_name;
//                print_r($data_db); exit;

                $query = $this->db->table('pemilih')->update($data_db, ['id' => $_POST['id']]);
                if ($query) {
                    $result['msg']['status'] = 'ok';
                    $result['msg']['content'] = 'Data berhasil disimpan';
                } else {
                    $error = true;
                    $result['msg']['status'] = 'error';
                    $result['msg']['content'] = 'Data gagal disimpan';
                }
            } else {

                $data_db['created'] = date('Y-m-d H:i:s');
                $data_db['id_user_input'] = $_SESSION['user']['id_user'];

                if ($_SESSION['user']['id_role'] == 13) {
                    $data_db['id_relawan'] = $_SESSION['user']['id_user'];
                } elseif ($_SESSION['user']['id_role'] == 12) {
                    $data_db['id_relawan'] = $_POST['id_relawan'];
                }

                $query = $this->db->table('pemilih')->insert($data_db);
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
//        $sql = 'SELECT COUNT(*) AS jml FROM pemilih' . $where;
//        $sql = 'SELECT COUNT(*) AS jml FROM pemilih ur join user_relawan ur2 on ur2.id_user = ur.id_relawan' . $where;
        $sql = 'SELECT COUNT(*) AS jml FROM v_pemilih_new' . $where;
        $result = $this->db->query($sql)->getRow();
        return $result->jml;
    }

    public function getUserRelawan($where) {
        $sql = 'SELECT * FROM user' . $where;
        $result = $this->db->query($sql)->getResultArray();
        return $result;
    }

//    public function getListData($where) {
//
//        $columns = $this->request->getPost('columns');
//
//        // Search
//        $search_all = @$this->request->getPost('search')['value'];
//        if ($search_all) {
//            // Additional Search
////            $columns[]['data'] = 'tempat_lahir';
//            foreach ($columns as $val) {
//
//                if (strpos($val['data'], 'ignore_search') !== false)
//                    continue;
//
//                if (strpos($val['data'], 'ignore') !== false)
//                    continue;
//
//                $where_col[] = $val['data'] . ' LIKE "%' . $search_all . '%"';
//            }
//            $where .= ' AND (' . join(' OR ', $where_col) . ') ';
//        }
//
//        // Order
//        $start = $this->request->getPost('start') ?: 0;
//        $length = $this->request->getPost('length') ?: 10;
//
//        $order_data = $this->request->getPost('order');
//        $order = '';
//        if (strpos($_POST['columns'][$order_data[0]['column']]['data'], 'ignore_search') === false) {
//            $order_by = $columns[$order_data[0]['column']]['data'] . ' ' . strtoupper($order_data[0]['dir']);
//            $order = ' ORDER BY ' . $order_by . ' LIMIT ' . $start . ', ' . $length;
//        }
//
//        // Query Total Filtered
//        // $sql = 'SELECT COUNT(*) AS jml_data FROM mahasiswa ' . $where;
//        $total_filtered = $this->db
//                ->table('user_relawan')
//                ->where(str_replace('WHERE', '', $where))
//                ->countAllResults();
//
//        // Query Data
//        $sql = 'SELECT * FROM user_relawan 
//				' . $where . $order;
//        $data = $this->db->query($sql)->getResultArray();
//
//        return ['data' => $data, 'total_filtered' => $total_filtered];
//    }

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
                ->table('v_pemilih')
                ->where(str_replace('WHERE', '', $where))
                ->countAllResults();

        // Query Data
//        print_r($where); exit;
        $sql = 'SELECT * FROM v_pemilih 
				' . $where . $order;
        $data = $this->db->query($sql)->getResultArray();

        return ['data' => $data, 'total_filtered' => $total_filtered];
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
                ->table('v_pemilih_new')
                ->where(str_replace('WHERE', '', $where))
                ->countAllResults();

//        $relawan = $this->modRel->getRelawanByIdUser($_SESSION['user']['id_user']);

        $provs = $this->modProv->getProvinsiPemilih();
        foreach ($provs as $key => $value) {
            $prov[$value['id']] = $value['nama'];
        }

        $kabs = $this->modKab->getKabupatenPemilih();
        foreach ($kabs as $key => $value) {
            $kab[$value['id']] = $value['nama'];
        }

        $kecs = $this->modKec->getKecamatanPemilih();
        foreach ($kecs as $key => $value) {
            $kec[$value['id']] = $value['nama'];
        }

        $kels = $this->modKel->getKelurahanPemilih();
        foreach ($kels as $key => $value) {
            $kel[$value['id']] = $value['nama'];
        }
        // Query Data
//        print_r($kel); exit;
        $sql = 'SELECT * FROM v_pemilih_new 
				' . $where . $order;
        $data = $this->db->query($sql)->getResultArray();

        foreach ($data as $key => $value) {
            $data[$key]['provinsi'] = $prov[$value['id_prov']];
            $data[$key]['kabupaten'] = $kab[$value['id_kab']];
            $data[$key]['kecamatan'] = $kec[$value['id_kec']];
            $data[$key]['kelurahan'] = $kel[$value['id_kel']];
        }

        return ['data' => $data, 'total_filtered' => $total_filtered];
    }

}

?>