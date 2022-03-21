<?php
namespace App\Models\Builtin;

class PermissionModel extends \App\Models\BaseModel
{
	public function getAllModules() {
		
		$sql = 'SELECT * FROM module ORDER BY judul_module';
		$modules =  $this->db->query($sql)->getResultArray();
		foreach ($modules as $val) {
			$result[$val['id_module']] = $val['judul_module'];
		}
		
		return $result;
	}
	
	public function getPermissionByName($nama_permission) {
		
		$sql = 'SELECT * FROM permission WHERE nama_permission = ?';
		$permission = $this->db->query($sql, $nama_permission )->getRowArray();
		return $permission;
	}
	
	public function getPermissionById(int $id = null) 
	{
		$sql = 'SELECT * FROM permission LEFT JOIN module USING(id_module) WHERE id_permission = ?';
		$permission = $this->db->query($sql, $id )->getRowArray();
		
		return $permission;
	}
	
	public function getPermission(int $id = null) 
	{
		
		if ($id) {
			$sql = 'SELECT * FROM permission LEFT JOIN module USING(id_module) WHERE id_module = ?';
			$permission = $this->db->query($sql, $id )->getResultArray();
		}
		
		else {
			$sql = 'SELECT * FROM permission LEFT JOIN module USING(id_module)';
			$permission = $this->db->query($sql)->getResultArray();
		}
		
		foreach ($permission as $val) {
			$result[$val['id_module']][$val['id_permission']] = $val;
		}

		return $result;
	}
	
	public function saveData() 
	{
		$this->db->transStart();
		
		$id_new = '';
		if ($_POST['generate'] == 'otomatis') 
		{
			$sql = 'SELECT * FROM module WHERE id_module = ?';
			$module = $this->db->query($sql, (int) $_POST['id_module'])->getRowArray();
			
			$list = ['create', 'read', 'update', 'delete'];
			$keterangan = ['membuat', 'membaca', 'mengupdate', 'menghapus'];
			$nama_module = $module['nama_module'];
			$nama_module = str_replace(['/', '-'], '_', $nama_module);
			$nama_module = strtolower($nama_module);
			
			// Cek exists
			$sql = 'SELECT * FROM permission 
						WHERE id_module = ? 
						AND ( nama_permission = "create_' . $nama_module . '"
							  OR nama_permission = "read_' . $nama_module . '" 
							  OR nama_permission = "update_' . $nama_module . '" 
							  OR nama_permission = "delete_' . $nama_module . '"
						)';
			$query = $this->db->query($sql, (int) $_POST['id_module'])->getResultArray();
			$permission_exists = [];
			foreach ($query as $val) {
				$permission_exists[$val['nama_permission']] = $val['nama_permission'];
			}
			
			foreach ($list as $key => $val) 
			{
				if (in_array($val . '_' . $nama_module, $permission_exists))
					continue;
				
				$data_db = [];
				$data_db['id_module'] = (int) $_POST['id_module'];
				$data_db['nama_permission'] = $val . '_' . $nama_module;
				$data_db['grup_aksi'] = $val;
				$data_db['judul_permission'] = ucfirst($val) . ' ' . ucfirst(str_replace('_', ' ', $nama_module));
				$data_db['keterangan'] = 'Hak akses untuk ' . $keterangan[$key] . ' data module';
				$query = $this->db->table('permission')->insert($data_db);
			}
		} else {
			$data_db['id_module'] = (int) $_POST['id_module'];
			$data_db['nama_permission'] = $_POST['nama_permission'];
			$data_db['grup_aksi'] = $_POST['grup_aksi'];
			$data_db['judul_permission'] = $_POST['judul_permission'];
			$data_db['keterangan'] =  $_POST['keterangan'];
			if (empty($_POST['id'])) {
				$query = $this->db->table('permission')->insert($data_db);
				$id_new = $this->db->insertID();
			} else {
				$query = $this->db->table('permission')->update($data_db, ['id_permission' => (int) $_POST['id']] );
			}
		}
		
		$this->db->transComplete();
		if ($this->db->transStatus() == false) {
			$result['status'] = 'error';
			$result['message'] = 'Data gagal disimpan';
		} else {
			$result['status'] = 'ok';
			$result['message'] = 'Data berhasil disimpan';
		}
		$result['id'] = $id_new;
		return $result;
	}
	
	/* public function saveData() {
		
		$data_db['nama_permission'] = $_POST['nama_permission'];
		$data_db['judul_permission'] = $_POST['judul_permission'];
		$data_db['keterangan'] = $_POST['keterangan'];
		
		if (!empty($_POST['id'])) {
			$query = $this->db->table('permission')->update( $data_db, [ 'id_permission' => (int) $_POST['id'] ] );
		} else {
			$query = $this->db->table('permission')->insert( $data_db );	
		}			
		
		
		return $query;
	} */
	
	public function deleteData($id) {
		
		$delete = $this->db->table('permission')->delete(['id_permission' => (int) trim($_POST['id']) ]);
		return $delete;
	}
}
?>