<?php

/**
 * 	App Name	: Admin Template Dashboard Codeigniter 4	
 * 	Developed by: Agus Prawoto Hadi
 * 	Website		: https://jagowebdev.com
 * 	Year		: 2021
 */

namespace App\Controllers;

use App\Models\RelawanModel;
use App\Models\ProvinsiModel;
use App\Models\KabupatenModel;
use App\Models\KecamatanModel;
use App\Models\KelurahanModel;
use App\Models\RdppDptModel;
use App\Models\CalegModel;
use App\Models\PemilihModel;
use App\Models\UserModel;

class Create_relawan extends \App\Controllers\BaseController {

    public function __construct() {

        parent::__construct();

        $this->model = new RelawanModel;
        $this->modProv = new ProvinsiModel;
        $this->modKab = new KabupatenModel;
        $this->modKec = new KecamatanModel;
        $this->modKel = new KelurahanModel;
        $this->modRD = new RdppDptModel;
        $this->modCaleg = new CalegModel;
        $this->modPemilih = new PemilihModel;
        $this->modUser = new UserModel;
        $this->data['site_title'] = 'Image Upload';

        $this->addJs($this->config->baseURL . 'public/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js');
        $this->addJs($this->config->baseURL . 'public/themes/modern/js/date-picker.js');
        $this->addJs($this->config->baseURL . 'public/themes/modern/js/image-upload.js');
        $this->addJs($this->config->baseURL . 'public/vendors/datatables/datatables.min.js');

        $this->addJs($this->config->baseURL . 'public/vendors/jquery.select2/js/select2.full.min.js');
        $this->addJs($this->config->baseURL . 'public/vendors/jquery.select2/js/select2.bootstrap.js');

        $this->addStyle($this->config->baseURL . 'public/vendors/datatables/datatables.min.css');
        $this->addJs($this->config->baseURL . 'public/themes/modern/js/create-relawan.js');
        $this->addStyle($this->config->baseURL . 'public/vendors/bootstrap-datepicker/css/bootstrap-datepicker3.css');

        $this->addStyle($this->config->baseURL . 'public/vendors/jquery.select2/css/select2.min.css');
        $this->addStyle($this->config->baseURL . 'public/vendors/jquery.select2/css/select2-bootstrap4.min.css');
    }

    public function index() {
        $this->cekHakAkses('read_data');

        $data = $this->data;
        if (!empty($_POST['delete'])) {
            $this->cekHakAkses('delete_data', 'user_relawan', 'id_caleg');

            $result = $this->model->deleteData();
            // $result = true;
            if ($result) {
                $data['msg'] = ['status' => 'ok', 'message' => 'Data Relawan berhasil dihapus'];
            } else {
                $data['msg'] = ['status' => 'error', 'message' => 'Data Relawan gagal dihapus'];
            }
        }
        $this->view('create-relawan-result.php', $data);
    }

    public function add() {
        $data = $this->data;
        $data['title'] = 'Tambah Data Relawan';
        $data['breadcrumb']['Add'] = '';

        $data['msg'] = [];
        if (isset($_POST['submit'])) {
            $form_errors = false;

            if ($form_errors) {
                $data['msg']['status'] = 'error';
                $data['msg']['content'] = $form_errors;
            } else {

                $message = $this->model->saveData();

                $data = array_merge($data, $message);
                $data['breadcrumb']['Edit'] = '';
                $data_relawan = $this->model->getRelawanById($message['id']);
                $data = array_merge($data, $data_relawan);
            }
        }

        $data['caleg'] = $this->modCaleg->getCalegByIdUser($this->session->get('user')['id_user']);

        $query = $this->modProv->getProvinsiById($data['caleg']['id_prov']);
        $data['caleg']['provinsi'] = $query['nama'];

        $query = $this->modKab->getKabupatenById($data['caleg']['id_kab']);
        $data['caleg']['kabupaten'] = $query['nama'];

        $dapil = $data['caleg']['id_dapil'];
        $query = $this->modKec->getKecamatan(" where dapil like '%,$dapil]' or dapil like '[$dapil,%' or dapil like '%,$dapil,%'");

//        $query = $this->modProv->getProvinsi(" where id = 41863");
        $data['kec'][''] = '';
        foreach ($query as $key => $val) {
            $data['kec'][$val['id']] = $val['nama'];
        }

        $this->view('create-relawan-form.php', $data);
    }

    public function edit() {
        $this->cekHakAkses('update_data', 'user_relawan|id_caleg');

        $this->data['title'] = 'Edit ' . $this->currentModule['judul_module'];
        ;
        $data = $this->data;

        if (empty($_GET['id'])) {
            $this->errorDataNotFound();
        }

        // Submit
        $data['msg'] = [];
        if (isset($_POST['submit'])) {
            $form_errors = false;

            if ($form_errors) {
                $data['msg']['status'] = 'error';
                $data['msg']['content'] = $form_errors;
            } else {

                // $query = false;
                $message = $this->model->saveData();

                $data = array_merge($data, $message);
            }
        }

        $data['breadcrumb']['Edit'] = '';

        $query = $this->modProv->getProvinsi(" where id = 41863");
        $data['prov'][''] = '';
        foreach ($query as $key => $val) {
            $data['prov'][$val['id']] = $val['nama'];
        }

        $data_relawan = $this->model->getRelawanById($_GET['id']);
        if (empty($data_relawan)) {
            $this->errorDataNotFound();
        }
        $data = array_merge($data, $data_relawan);

        $this->view('create-relawan-form', $data);
    }

    public function pemilih() {
        $this->cekHakAkses('read_data');

        $data = $this->data;
        if (!empty($_POST['delete'])) {
            $this->cekHakAkses('delete_data', 'pemilih', 'id_relawan');

            $result = $this->model->deleteData();
            // $result = true;
            if ($result) {
                $data['msg'] = ['status' => 'ok', 'message' => 'Data Pemilih berhasil dihapus'];
            } else {
                $data['msg'] = ['status' => 'error', 'message' => 'Data Pemilih gagal dihapus'];
            }
        }

        $data_relawan = $this->model->getRelawanByIdUser($_GET['id']);

        if (empty($data_relawan)) {
            $this->errorDataNotFound();
        }

        $qKec = $this->modKec->getKecamatanById($data_relawan['id_kec']);
        $qKel = $this->modKel->getKelurahanById($data_relawan['id_kel']);
        $data_relawan['kecamatan'] = $qKec['nama'];
        $data_relawan['kelurahan'] = $qKel['nama'];

        $data = array_merge($data, $data_relawan);

        $data['id_relawan'] = $_GET['id'];
        $this->view('create-relawan-pemilih-result.php', $data);
    }

    public function pilih() {
        $this->cekHakAkses('update_data', 'user_relawan|id_caleg');

        $this->data['title'] = 'Register Relawan';
//        $this->data['title'] = 'Edit ' . $this->currentModule['judul_module'];
        ;
        $data = $this->data;

        if (empty($_GET['id'])) {
            $this->errorDataNotFound();
        }

        // Submit
        $data['msg'] = [];
        if (isset($_POST['submit'])) {
            $form_errors = false;

            if ($form_errors) {
                $data['msg']['status'] = 'error';
                $data['msg']['content'] = $form_errors;
            } else {

                // $query = false;
                $message = $this->model->saveData();

                $data = array_merge($data, $message);
            }
        }

        $data['breadcrumb']['Edit'] = '';

//        $query = $this->modProv->getProvinsi(" where id = 41863");
//        $data['prov'][''] = '';
//        foreach ($query as $key => $val) {
//            $data['prov'][$val['id']] = $val['nama'];
//        }
//        $data_relawan = $this->model->getViewRelawanById($_GET['id']);
//        if (empty($data_relawan)) {
//            $this->errorDataNotFound();
//        }

        $data_relawan = $this->model->getRelawanById($_GET['id']);

        if (empty($data_relawan)) {
            $this->errorDataNotFound();
        }

        $qProv = $this->modProv->getProvinsiById($data_relawan['id_prov']);
        $qKab = $this->modKab->getKabupatenById($data_relawan['id_kab']);
        $qKec = $this->modKec->getKecamatanById($data_relawan['id_kec']);
        $qKel = $this->modKel->getKelurahanById($data_relawan['id_kel']);
        if ($data_relawan['id_user']) {
            $qUser = $this->modUser->getPenggunaById($data_relawan['id_user']);
            $data_relawan['email'] = $qUser['email'];
        } else {
            $data_relawan['email'] = '';
        }

        $data_relawan['provinsi'] = $qProv['nama'];
        $data_relawan['kabupaten'] = $qKab['nama'];
        $data_relawan['kecamatan'] = $qKec['nama'];
        $data_relawan['kelurahan'] = $qKel['nama'];

        $data = array_merge($data, $data_relawan);

        $this->view('create-relawan-reg', $data);
    }

    public function getDataDTPemilih() {
        $id_relawan = $_GET['id'];
//        print_r($id_relawan); exit;

        $this->cekHakAkses('read_data', 'pemilih|id_relawan', $id_relawan);

        $num_data = $this->modPemilih->countAllData($this->whereOwn('id_relawan', $id_relawan));
        $result['draw'] = $start = $this->request->getPost('draw') ?: 1;
        $result['recordsTotal'] = $num_data;

        $query = $this->modPemilih->getListData($this->whereOwn('id_relawan', $id_relawan));
        $result['recordsFiltered'] = $query['total_filtered'];

        $qProv = $this->modProv->getProvinsi(" where id > 0");
        $qKab = $this->modKab->getKabupaten(" where parent_id = $filterid");

        helper('html');
        $id_user = $this->session->get('user')['id_user'];

        $no = $this->request->getPost('start') + 1 ?: 1;
        foreach ($query['data'] as $key => &$val) {
            $image = 'noimage.png';

//            if (array_key_exists($val['id_user'], $foto)) {
//                
//            }

            if ($val['avatar']) {
                if (file_exists('public/images/pemilih/' . $val['avatar'])) {
                    $image = $val['avatar'];
                }
            }

            $val['ignore_search_foto'] = '<div class="list-foto"><img src="' . $this->config->baseURL . 'public/images/pemilih/' . $image . '"/></div>';
//            $val['tgl_lahir'] = $val['tempat_lahir'] . ', ' . format_tanggal($val['tgl_lahir']);

            $val['ignore_search_urut'] = $no;
            $val['ignore_search_action'] = btn_action([
                'pilih' => ['url' => $this->config->baseURL . $this->currentModule['nama_module'] . '/pilih?id=' . $val['id']]
//                , 'delete' => ['url' => ''
//                    , 'id' => $val['id']
//                    , 'delete-title' => 'Hapus data Pemilih: <strong>' . $val['nama'] . '</strong> ?'
//                ]
            ]);
            $no++;
        }

        $result['data'] = $query['data'];
        echo json_encode($result);
        exit();
    }

    public function getDataDT() {
//        print_r('masuk'); exit;

        $this->cekHakAkses('read_data', 'user_relawan|id_caleg');

        $num_data = $this->model->countAllData($this->whereOwn('id_caleg'));
        $result['draw'] = $start = $this->request->getPost('draw') ?: 1;
        $result['recordsTotal'] = $num_data;

        $data['caleg'] = $this->modCaleg->getCalegByIdUser($this->session->get('user')['id_user']);

        $query = $this->modProv->getProvinsiById($data['caleg']['id_prov']);
        $provinsi = $query['nama'];

        $query = $this->modKab->getKabupatenById($data['caleg']['id_kab']);
        $kabupaten = $query['nama'];

        $dapil = $data['caleg']['id_dapil'];
        $query = $this->modKec->getKecamatan(" where dapil like '%,$dapil]' or dapil like '[$dapil,%' or dapil like '%,$dapil,%'");

//        $query = $this->modProv->getProvinsi(" where id = 41863");
        $kec = array();
        foreach ($query as $key => $val) {
            $kec[$val['id']] = $val['nama'];
        }

        $query = $this->modKel->getKelurahan(" where dapil like '%,$dapil]' or dapil like '[$dapil,%' or dapil like '%,$dapil,%'");
        $kel = array();
        foreach ($query as $key => $val) {
            $kel[$val['id']] = $val['nama'];
        }

//        $query = $this->model->getListViewData($this->whereOwn('id_caleg'));
        $query = $this->model->getListViewDataNew($this->whereOwn('id_caleg'));
        $result['recordsFiltered'] = $query['total_filtered'];

        helper('html');
        $id_user = $this->session->get('user')['id_user'];

//        $users = $this->model->getUserRelawan(" where id_user in (select ur.id_user from user_relawan ur where ur.id_caleg = $id_user and ur.id_user is not null)");
//        $foto = array();
//        foreach ($users as $key => $user) {
//            if ($user['avatar']) {
//                $foto[$user['id_user']] = $user['avatar'];
//            }
//        }

        $no = $this->request->getPost('start') + 1 ?: 1;
        foreach ($query['data'] as $key => &$val) {
            $image = 'noimage.png';

//            if (array_key_exists($val['id_user'], $foto)) {
//                
//            }

            if ($val['avatar']) {
                if (file_exists('public/images/user/' . $val['avatar'])) {
                    $image = $val['avatar'];
                }
            }

            $val['ignore_search_foto'] = '<div class="list-foto"><img src="' . $this->config->baseURL . 'public/images/user/' . $image . '"/></div>';
//            $val['tgl_lahir'] = $val['tempat_lahir'] . ', ' . format_tanggal($val['tgl_lahir']);

            $val['ignore_search_urut'] = $no;
            $val['provinsi'] = $provinsi;
            $val['kabupaten'] = $kabupaten;

            if (isset($kec[$val['id_kec']]))
                $val['kecamatan'] = $kec[$val['id_kec']];
            else {
                $val['kecamatan'] = '';
            }

            if (isset($kel[$val['id_kel']]))
                $val['kelurahan'] = $kel[$val['id_kel']];
            else {
                $val['kelurahan'] = '';
            }

            $val['ignore_search_action'] = btn_action([
                'pilih' => ['url' => $this->config->baseURL . $this->currentModule['nama_module'] . '/pilih?id=' . $val['id']],
                'pemilih' => ['url' => $this->config->baseURL . $this->currentModule['nama_module'] . '/pemilih?id=' . $val['id_user'], 'btn_class' => 'btn btn-primary', 'icon' => 'fa-user-friends', 'text' => 'Pemilih', 'hide' => empty($val['id_user'])],
//                , 'delete' => ['url' => ''
//                    , 'id' => $val['id']
//                    , 'delete-title' => 'Hapus data Relawan: <strong>' . $val['nama'] . '</strong> ?'
//                ]
            ]);
            $no++;
        }

        $result['data'] = $query['data'];
        echo json_encode($result);
        exit();
    }

    public function getDataKab() {
        $filterid = $_GET['filterid'];

        if (!$filterid) {
            $filterid = '0';
        }

        $items = $this->modKab->getKabupaten(" where parent_id = $filterid");

        $html = '';

        $select = '<option value=\"\"></option>';
        $html .= '$("select#id_kec").html("' . $select . '");';
        $html .= '$("select#id_kel").html("' . $select . '");';
        $html .= '$("select#noTps").html("' . $select . '");';
        $html .= '$("select#idDpt").html("' . $select . '");';
//        $html .= '$("#jmltps").val(0);';
//        $html .= '$("#butuhsuara").val(0);';

        foreach ($items as $list) :
            $select .= "<option value='" . $list['id'] . "'>" . $list['nama'] . "</option>";
        endforeach;

        $html .= '$("select#id_kab").html("' . $select . '");';
//        $html .= '$("input[name=\'' . $csrf_token['name'] . '\']").val("' . $_COOKIE[$csrf_token['name']] . '");';
        echo $html;
    }

    public function getDataKec() {
        $filterid = $_GET['filterid'];

        if (!$filterid) {
            $filterid = '0';
        }

        $items = $this->modKec->getKecamatan(" where parent_id = $filterid");

        $html = '';

        $select = '<option value=\"\"></option>';
        $html .= '$("select#id_kel").html("' . $select . '");';
        $html .= '$("select#noTps").html("' . $select . '");';
        $html .= '$("select#idDpt").html("' . $select . '");';
//        $html .= '$("#jmltps").val(0);';
//        $html .= '$("#butuhsuara").val(0);';

        foreach ($items as $list) :
            $select .= "<option value='" . $list['id'] . "'>" . $list['nama'] . "</option>";
        endforeach;

        $html .= '$("select#id_kec").html("' . $select . '");';
//        $html .= '$("input[name=\'' . $csrf_token['name'] . '\']").val("' . $_COOKIE[$csrf_token['name']] . '");';
        echo $html;
    }

    public function getDataKel() {
        $filterid = $_GET['filterid'];

        if (!$filterid) {
            $filterid = '0';
        }

        $items = $this->modKel->getKelurahan(" where parent_id = $filterid");

        $html = '';

        $select = '<option value=\"\"></option>';
        $html .= '$("select#noTps").html("' . $select . '");';
        $html .= '$("select#idDpt").html("' . $select . '");';
//        $html .= '$("#jmltps").val(0);';
//        $html .= '$("#butuhsuara").val(0);';

        foreach ($items as $list) :
            $select .= "<option value='" . $list['id'] . "'>" . $list['nama'] . "</option>";
        endforeach;

        $html .= '$("select#id_kel").html("' . $select . '");';
//        $html .= '$("input[name=\'' . $csrf_token['name'] . '\']").val("' . $_COOKIE[$csrf_token['name']] . '");';
        echo $html;
    }

    public function getDataTps() {
        $filterid = $_GET['filterid'];
        $idprov = $_GET['idprov'];
        $idkab = $_GET['idkab'];
        $idkec = $_GET['idkec'];

        if (!$filterid) {
            $filterid = '0';
        }

        $items = $this->modRD->getTpsByKelurahan($idprov, $idkab, $idkec, $filterid);

        $html = '';

        $select = '<option value=\"\"></option>';
        $html .= '$("select#idDpt").html("' . $select . '");';
//        $html .= '$("select#id_kel").html("' . $select . '");';
//        $html .= '$("#jmltps").val(0);';
//        $html .= '$("#butuhsuara").val(0);';

        foreach ($items as $list) :
            $select .= "<option value='" . $list['noTps'] . "'>" . $list['namaTps'] . "</option>";
        endforeach;

        $html .= '$("select#noTps").html("' . $select . '");';
//        $html .= '$("input[name=\'' . $csrf_token['name'] . '\']").val("' . $_COOKIE[$csrf_token['name']] . '");';
        echo $html;
    }

    public function getDataDpt() {
        $filterid = $_GET['filterid'];
        $idprov = $_GET['idprov'];
        $idkab = $_GET['idkab'];
        $idkec = $_GET['idkec'];
//        $idkel = $_GET['idkel'];

        if (!$filterid) {
            $filterid = '0';
        }

//        $items = $this->modRD->getRdppDpt($idprov, $idkab, " where idKec = $idkec and idKel = $idkel and noTps = $filterid");
        $items = $this->modRD->getRdppDpt($idprov, $idkab, " where idKec = $idkec and idKel = $filterid");

        $html = '';

        $select = '<option value=\"\"></option>';
//        $html .= '$("select#id_kec").html("' . $select . '");';
//        $html .= '$("select#id_kel").html("' . $select . '");';
//        $html .= '$("#jmltps").val(0);';
//        $html .= '$("#butuhsuara").val(0);';

        foreach ($items as $list) :
            $select .= "<option value='" . $list['idDpt'] . "'>TPS " . $list['noTps'] . ' - ' . $list['nama'] . ' - ' . $list['nik'] . "</option>";
        endforeach;

        $html .= '$("select#idDpt").html("' . $select . '");';
//        $html .= '$("input[name=\'' . $csrf_token['name'] . '\']").val("' . $_COOKIE[$csrf_token['name']] . '");';
        echo $html;
    }

}
