<div class="card">
	<div class="card-header">
		<h5 class="card-title">Daftar Role</h5>
	</div>
	
	<div class="card-body">
		<a href="<?=current_url()?>/add" class="btn btn-success btn-xs"><i class="fa fa-plus pe-1"></i> Tambah Role</a>
		<hr/>
		<?php 
		if (!$role) {
			show_message('Data tidak ditemukan', '', false);
		} else {
			if (!empty($msg)) {
				show_alert($msg);
			}
			
			?>
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
			<thead>
			<tr>
				<th>No</th>
				<th>Nama Role</th>
				<th>Judul Role</th>
				<th>Jml. Module</th>
				<th>Jml. Permission</th>
				<th>Aksi</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ($role_permission as $val) {
				$mm[$val['id_role']][$val['id_module']] = $val;
				$ss[$val['id_role']][] = $val;
			}
			// echo '<pre>'; print_r($role_permission); die;
			foreach ($role as $key => $val) 
			{
				$jml_module = key_exists($val['id_role'], $mm) ? count($mm[$val['id_role']]) : 0;
				$jml_permission = key_exists($val['id_role'], $ss) ? count($ss[$val['id_role']]) : 0;
				echo '<tr>
						<td>' . ($key + 1) . '</td>
						<td>' . $val['nama_role'] . '</td>
						<td>' . $val['judul_role'] . '</td>
						<td>' . $jml_module . '</td>
						<td>' . $jml_permission . '</td>
						<td>
							<div class="btn-action-group">
								<a target="_blank" href="'.current_url().'/edit?id='. $val['id_role'] .'" class="btn btn-success btn-xs me-1"><i class="fa fa-edit"></i>&nbsp;Edit</a>';
								if ($jml_permission > 0) { 
									echo '<form method="post" action="'.current_url().'"><button data-action="delete-data" data-delete-title="Hapus <strong>semua</strong> permission dari role <strong>'.$val['judul_role'].'</strong>?" type="submit" class="btn btn-danger btn-xs" name="delete"><i class="fas fa-times"></i>&nbsp;Delete</button><input type="hidden" name="id" value="'.$val['id_role'].'"/><input type="hidden" name="delete" value="delete"/></form>';
								}
							echo 
							'</div>
						</td>
					</tr>';
			}
			?>
			</tbody>
			</table>
			</div>
			<?php 
		} ?>
		
	</div>
</div>