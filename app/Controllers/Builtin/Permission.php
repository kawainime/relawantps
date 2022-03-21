<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Controllers\Builtin;
use App\Models\Builtin\PermissionModel;

class Permission extends \App\Controllers\BaseController
{
	protected $model;
	private $formValidation;
	
	public function __construct() {
		
		parent::__construct();
		// $this->mustLoggedIn();
		
		$this->model = new PermissionModel;	
		$this->data['site_title'] = 'Halaman Permission';
		
		$this->addJs( base_url() . '/public/themes/modern/js/permission.js' );
		$this->addStyle( base_url() . '/public/vendors/wdi/wdi-loader.css' );
		
		helper(['cookie', 'form']);
	}
	
	public function index()
	{
		$this->cekHakAkses('read_data');
	
		$data = $this->data;
		if ($this->request->getPost('delete')) 
		{
			$this->cekHakAkses('delete_data');
			$result = $this->model->deleteData();
			if ($result) {
				$data['msg'] = ['status' => 'ok', 'message' => 'Data berhasil dihapus'];
			} else {
				$data['msg'] = ['status' => 'warning', 'message' => 'Tidak ada data yang dihapus'];
			}
		}
		
		$id = !empty($_GET['id_module']) ? $_GET['id_module'] : null;
		$data['permission'] = $this->model->getPermission($id);
		$data['module'] = ['' => 'All Modules'] + $this->model->getAllModules();
		
		$data['title'] = 'Edit Permission';
		
		$this->view('builtin/permission-form.php', $data);
	}
	
	public function ajaxFormEdit() 
	{
		$data['message'] = [];
		if (empty($_GET['id'])) {
			$data['message'] = ['status' => 'error', 'message' => 'Invalid input'];
		} else {
		
			$id = (int) $_GET['id'];
			$query = $this->model->getPermissionById($id);
			$data['result'] = $query;
			$data['modules'] = $this->model->getAllMOdules();
		}
		
		echo view('themes/modern/builtin/permission-form-edit-ajax.php', $data);
		exit;
	}
	
	public function add() 
	{
		$data = $this->data;
		$data['message'] = [];
		$data['title'] = 'Tambah Permission';
		
		$message = [];
		if (isset($_POST['submit'])) {
			$exists = $this->model->getPermissionByName($_POST['nama_permission']);
			if ($exists) {
				$message['status'] = 'error';
				$message['message'] = 'Nama permission <strong>' . $_POST['nama_permission'] . '</strong> sudah ada di database';
			} else {
				$form_errors = $this->validateForm();
	
				if ($form_errors) {
					$message['status'] = 'error';
					$data['form_errors'] = $form_errors;
					$message['message'] = $form_errors;
				} else {
					$query = $this->model->saveData();
					if ($query['status'] == 'ok') {
						$message['status'] = 'ok';
						$message['message'] = 'Data berhasil disimpan';
						$data['id'] = $query['id'];
						$data['title'] = 'Edit Permission';
					} else {
						$message['status'] = 'error';
						$message['message'] = 'Data gagal disimpan';
					}
				}
			}
		}
		
		$data['message'] = $message;
		$data['modules'] = $this->model->getAllModules();
		
		$this->view('builtin/permission-form-add.php', $data);
	}
	
	public function ajaxEdit() {
		
		$_POST = array_map('trim', $_POST);
		if (empty($_POST['nama_permission']) || empty($_POST['judul_permission']) || empty($_POST['keterangan']) ) {
			$result['status'] = 'error';
			$result['message'] = 'Semua data harus diisi';
			
		} else {
		
			$query = $this->model->saveData();
			if ($query) {
				$result['status'] = 'ok';
				$result['message'] = 'Data berhasil disimpan';
			} else {
				$result['status'] = 'error';
				$result['message'] = 'Data gagal dihapus';
			}
		}
		echo json_encode($result);
			exit;
	}
	
	public function ajaxDelete() {
		
		if (empty(trim($_POST['id']))) {
			
			$result['status'] = 'error';
			$result['message'] = 'Semua data harus diisi';
			
		} else {
			$id = (int) $_POST['id'];
			$query = $this->model->deleteData($id);
			if ($query) {
				$result['status'] = 'ok';
				$result['message'] = 'Data berhasil dihapus';
			} else {
				$result['status'] = 'error';
				$result['message'] = 'Data gagal dihapus';
			}
		}
		echo json_encode($result);
		exit;
	}
	
	private function validateForm() {

		$validation =  \Config\Services::validation();
		
		$validation->setRule('id_module', 'Nama Module', 'trim|required');
		if ($this->request->getPost('generate') == 'manual') {
			$validation->setRule('nama_permission', 'Nama Permission', 'trim|required');
			$validation->setRule('judul_permission', 'Judul Permission', 'trim|required');
			$validation->setRule('keterangan', 'Keterangan', 'trim|required');
			$validation->setRule('grup_aksi', 'Nama Role', 'trim|required');
		}
		
		$validation->withRequest($this->request)->run();
		$form_errors = $validation->getErrors();
		
		return $form_errors;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function add__() 
	{
		$this->cekHakAkses('create_data');
		
		$this->setData();
		$data = $this->data;
		
		$breadcrumb['Add'] = '';
		$data['title'] = 'Tambah ' . $this->currentModule['judul_module'];
		$data['msg'] = [];
		
		$error = false;
		if ($this->request->getPost('submit'))
		{
			$save_msg = $this->saveData();
			$data = array_merge( $data, $save_msg);
		}
		
		$this->view('builtin/role-form.php', $data);
	}
	
	public function edit()
	{
		$this->cekHakAkses('update_data');
		
		if (!$this->request->getGet('id')) {
			$this->printError(['status' => 'error', 'message' => 'Parameter tidak lengkap']);
		}
		
		$this->setData();
		$data = $this->data;
		$data['title'] = 'Edit ' . $this->currentModule['judul_module'];
		$breadcrumb['Edit'] = '';
	
		// Submit
		$data['msg'] = [];
		if ($this->request->getPost('submit')) 
		{
			$save = $this->saveData();
			$data = array_merge($data, $save);
		}

		$this->view('builtin/role-form.php', $data);
	}
	
	public function setData() {
		$this->data['module_role'] = $this->model->listModuleRole();
		$this->data['module_status'] = $this->model->getModuleStatus();
		$this->data['role'] = $this->model->getRole();
	}
	
	private function saveData() 
	{
		$form_errors = $this->validateForm();
	
		if ($form_errors) {
			$data['msg']['status'] = 'error';
			$data['form_errors'] = $form_errors;
			$data['msg']['message'] = $form_errors;
		} else {
			$save = $this->model->saveData();
			if ($save['status'] == 'ok') {
				$data['msg']['status'] = 'ok';
				$data['msg']['message'] = 'Data berhasil disimpan';
			} else {
				$data['msg']['status'] = 'error';
				$data['msg']['message'] = $save['message'];
			}
		}
		
		return $data;
	}
	
	
	
}
