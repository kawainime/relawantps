<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020-2021
*/

namespace App\Controllers\Builtin;
use App\Models\Builtin\RolePermissionModel;

class Role_permission extends \App\Controllers\BaseController
{
	protected $model;
	private $formValidation;
	
	public function __construct() {
		
		parent::__construct();
		
		$this->model = new RolePermissionModel;	
		$this->data['site_title'] = 'Halaman Role';
		
		$this->addJs(base_url() . '/public/themes/modern/builtin/js/role-permission.js');
		
		helper(['cookie', 'form']);
	}
	
	public function index()
	{
		$this->cekHakAkses('read_data');
		if ($this->request->getPost('delete')) 
		{
			$this->cekHakAkses('delete_data');
			$result = $this->model->deleteAllPermission();
			if ($result) {
				$this->data['msg'] = ['status' => 'ok', 'message' => 'Data berhasil dihapus'];
			} else {
				$this->data['msg'] = ['status' => 'warning', 'message' => 'Tidak ada data yang dihapus'];
			}
		}
		
		$this->setData();
		$data = $this->data;
		$data['role'] = $this->model->getAllRole();
		$this->view('builtin/role-permission-result.php', $data);
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
			$form_errors = $this->validateForm();
	
			if ($form_errors) {
				$data['msg']['status'] = 'error';
				$data['form_errors'] = $form_errors;
				$data['msg']['message'] = $form_errors;
			} else {
				$save = $this->model->saveData();
				if ($save) {
					$data['msg']['status'] = 'ok';
					$data['msg']['message'] = 'Data berhasil disimpan';
					// $data = array_merge($data, $save);
				} else {
					$data['msg']['status'] = 'error';
					$data['msg']['message'] = $save['message'];
				}
			}
		}
		
		$data['role'] = $this->model->getRoleById($_GET['id']);
		$data['role_permission'] = $this->model->getRolePermissionByIdRole($_GET['id']);
		$this->view('builtin/role-permission-form.php', $data);
	}
	
	public function setData() {
		$this->data['module'] = $this->model->getAllModules();
		$this->data['permission_permodule'] = $this->model->getAllPermissionByModule();
		$this->data['role_permission'] = $this->model->getAllRolePermission();
		// $this->data['all_role_permission'] = $this->model->getAllRolePermission();
	}
	
	private function validateForm() {

		$validation =  \Config\Services::validation();
		$validation->setRule('id', 'ID Role', 'trim|required');
		$validation->withRequest($this->request)->run();
		$form_errors = $validation->getErrors();
			
		return $form_errors;
	}
}
