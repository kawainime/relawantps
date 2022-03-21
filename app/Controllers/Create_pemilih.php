<?php

/**
 * 	App Name	: Admin Template Dashboard Codeigniter 4	
 * 	Developed by: Agus Prawoto Hadi
 * 	Website		: https://jagowebdev.com
 * 	Year		: 2021
 */

namespace App\Controllers;

use App\Models\PemilihModel;
use App\Models\ProvinsiModel;
use App\Models\KabupatenModel;
use App\Models\KecamatanModel;
use App\Models\KelurahanModel;
use App\Models\RdppDptModel;
use App\Models\CalegModel;
use App\Models\RelawanModel;

class Create_pemilih extends \App\Controllers\BaseController {

    public function __construct() {

        parent::__construct();

        $this->model = new PemilihModel;
        $this->modProv = new ProvinsiModel;
        $this->modKab = new KabupatenModel;
        $this->modKec = new KecamatanModel;
        $this->modKel = new KelurahanModel;
        $this->modRD = new RdppDptModel;
        $this->modCaleg = new CalegModel;
        $this->modRelawan = new RelawanModel;
        $this->data['site_title'] = 'Image Upload';

        $this->addJs($this->config->baseURL . 'public/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js');
        $this->addJs($this->config->baseURL . 'public/themes/modern/js/date-picker.js');
        $this->addJs($this->config->baseURL . 'public/themes/modern/js/image-upload.js');
        $this->addJs($this->config->baseURL . 'public/vendors/datatables/datatables.min.js');

        $this->addJs($this->config->baseURL . 'public/vendors/jquery.select2/js/select2.full.min.js');
        $this->addJs($this->config->baseURL . 'public/vendors/jquery.select2/js/select2.bootstrap.js');

        $this->addStyle($this->config->baseURL . 'public/vendors/datatables/datatables.min.css');
        $this->addJs($this->config->baseURL . 'public/themes/modern/js/create-pemilih.js');
        $this->addStyle($this->config->baseURL . 'public/vendors/bootstrap-datepicker/css/bootstrap-datepicker3.css');

        $this->addStyle($this->config->baseURL . 'public/vendors/jquery.select2/css/select2.min.css');
        $this->addStyle($this->config->baseURL . 'public/vendors/jquery.select2/css/select2-bootstrap4.min.css');
    }

    public function index() {
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
        $this->view('create-pemilih-result.php', $data);
    }

    public function add() {
        $data = $this->data;
        $data['title'] = 'Tambah Data Pemilih';
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
                $data_relawan = $this->model->getPemilihById($message['id']);
                $data = array_merge($data, $data_relawan);
            }
        }
        
        $data['relawan'] = $this->modRelawan->getViewRelawanByIdUser($this->session->get('user')['id_user']);
//        $dapil = $data['caleg']['id_dapil'];

        $query = $this->modRD->getRdppDpt($data['relawan']['id_prov'], $data['relawan']['id_kab'], " where idKec = ".$data['relawan']['id_kec']." and idKel = ".$data['relawan']['id_kel']." and noTps = ".$data['relawan']['noTps']." and idDpt <> '".$data['relawan']['idDpt']."'");
        
//        $query = $this->modKec->getKecamatan(" where dapil like '%,$dapil]' or dapil like '[$dapil,%' or dapil like '%,$dapil,%'");

//        $query = $this->modProv->getProvinsi(" where id = 41863");
        $data['dpt'][''] = '';
        foreach ($query as $key => $val) {
            $data['dpt'][$val['idDpt']] = $val['nama'].' - '.$val['nik'];
        }

        $this->view('create-pemilih-form.php', $data);
    }

    public function edit() {
        $this->cekHakAkses('update_data', 'pemilih|id_relawan');

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

        $data_pemilih = $this->model->getViewPemilihById($_GET['id']);
        if (empty($data_pemilih)) {
            $this->errorDataNotFound();
        }
        $data = array_merge($data, $data_pemilih);

        $this->view('create-pemilih-form', $data);
    }

    public function pilih() {
        $this->cekHakAkses('update_data', 'pemilih|id_relawan');

        $this->data['title'] = 'Edit Pemilih';
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

        $data_pemilih = $this->model->getViewPemilihById($_GET['id']);
        if (empty($data_pemilih)) {
            $this->errorDataNotFound();
        }
        $data = array_merge($data, $data_pemilih);

        $this->view('create-pemilih-reg', $data);
    }

    public function getDataDT() {

        $this->cekHakAkses('read_data', 'pemilih|id_relawan');

        $num_data = $this->model->countAllData($this->whereOwn('id_relawan'));
        $result['draw'] = $start = $this->request->getPost('draw') ?: 1;
        $result['recordsTotal'] = $num_data;

        $query = $this->model->getListViewData($this->whereOwn('id_relawan'));
        $result['recordsFiltered'] = $query['total_filtered'];

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
        $idkel = $_GET['idkel'];

        if (!$filterid) {
            $filterid = '0';
        }

        $items = $this->modRD->getRdppDpt($idprov, $idkab, " where idKec = $idkec and idKel = $idkel and noTps = $filterid");

        $html = '';

        $select = '<option value=\"\"></option>';
//        $html .= '$("select#id_kec").html("' . $select . '");';
//        $html .= '$("select#id_kel").html("' . $select . '");';
//        $html .= '$("#jmltps").val(0);';
//        $html .= '$("#butuhsuara").val(0);';

        foreach ($items as $list) :
            $select .= "<option value='" . $list['idDpt'] . "'>" . $list['nama'] . ' - ' . $list['nik'] . "</option>";
        endforeach;

        $html .= '$("select#idDpt").html("' . $select . '");';
//        $html .= '$("input[name=\'' . $csrf_token['name'] . '\']").val("' . $_COOKIE[$csrf_token['name']] . '");';
        echo $html;
    }

}
