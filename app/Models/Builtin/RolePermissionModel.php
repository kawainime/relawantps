<?php
namespace App\Models\Builtin;

class RolePermissionModel extends \App\Models\BaseModel
{
	
	public function deleteAllPermission() {
		$delete = $this->db->table('role_permission')->delete(['id_role' => $_POST['id']]);
		return $delete;
	}
	public function getRolePermissionByIdRole($id) 
	{
		$sql = 'SELECT * FROM role_permission WHERE id_role = ?';
		$query = $this->db->query($sql, $id)->getResultArray();
		
		$result = [];
		foreach ($query as $val) {
			$result[$val['id_permission']] = $val;
		}

		return $result;
	}
	
	public function getAllPermissionByModule() 
	{
		$sql = 'SELECT * FROM permission LEFT JOIN module USING(id_module)';
		$permission = $this->db->query($sql)->getResultArray();
				
		foreach ($permission as $val) {
			$result[$val['id_module']][$val['id_permission']] = $val;
		}

		return $result;
	}
	
	public function getAllModules() {
		$sql = 'SELECT * FROM module';
		$query = $this->db->query($sql)->getResultArray();
		foreach ($query as $val) {
			$result[$val['id_module']] = $val;
		}
		return $result;
	}
	
	public function getRoleById($id) {
		$sql = 'SELECT * FROM role WHERE id_role = ?';
		$result = $this->db->query($sql, $id)->getRowArray();
		return $result;
	}
	
	public function getAllRole() {
		$sql = 'SELECT * FROM role';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function getAllRolePermission() {
		$sql = 'SELECT * FROM role_permission 
					LEFT JOIN permission USING(id_permission) 
					LEFT JOIN module USING(id_module)';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function saveData() 
	{
		$this->db->transStart();
		
		$table = $this->db->table('role_permission');
		$table->delete(['id_role' => $_POST['id']]);
		
		if (key_exists('permission', $_POST)) {
			foreach ($_POST['permission'] as $val) {
				$data_db[] = ['id_role' => $_POST['id'], 'id_permission' => $val];
			}
		}
		$table->insertBatch($data_db);
		
		$this->db->transComplete();
		if ($this->db->transStatus() == false) {
			return false;
		}
		
		return true;
	}
}
?>