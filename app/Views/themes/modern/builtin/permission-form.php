<?php

helper('html');
// echo '<pre>'; print_r($module);die;
?>
<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$title?></h5>
	</div>
	
	<div class="card-body">
		<a href="<?=current_url()?>/add" class="btn btn-success btn-xs add" id="add-permission"><i class="fa fa-plus pe-1"></i> Tambah Permission</a>
		<hr/>
		<form id="form-permission" method="get" action="<?=current_url(true)?>" class="mb-5">
			<div>
				<div class="row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Module</label>
					<div class="col-sm-8">
						<?=options(['name' => 'id_module'], $module, @$_GET['id_module'])?>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-sm-8 offset-sm-2">
						<button type="submit" name="submit" value="submit" class="btn btn-primary mt-2">Submit</button>
					</div>
				</div>
			</div>
		</form>
		<hr/>
		<div>
		<?php
		echo '<table class="table table striped table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th colspan="4">Module - Permission</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>';
			$no = 1;
			
			foreach ($permission as $id_module => $arr) {
				
				echo '<tr class="bg-light fw-bold">
						<td>' . $no . '</td>
						<td colspan="4">' . $module[$id_module] . '</td>
						<td></td>
					</tr>';
				
				$no_permission = 1;				
				foreach ($arr as $val) {
					echo '<tr>
						<td></td>
						<td><i class="small far fa-circle"></i></td>
						<td>' . $val['nama_permission'] . '</td>
						<td>' . $val['judul_permission'] . '</td>
						<td>' . $val['keterangan'] . '</td>
						<td><div class="btn-action-group">' 
							. btn_label(['url' => 'javascript:void(0)', 'attr' => ['class' => 'edit btn btn-success btn-xs me-1', 'data-id-permission' => $val['id_permission']], 'label' => 'Edit']) 
							. btn_label(['url' => 'javascript:void(0)', 'attr' => ['class' => 'delete btn btn-danger btn-xs', 'data-url' => current_url() . '/ajaxDelete', 'data-id-permission' => $val['id_permission']], 'label' => 'Delete'])
						. '</div></td>
					</tr>';
				}
				$no++;
				
			}
			?>	
			</tbody>
			</table>
		
		</div>
	</div>
</div>