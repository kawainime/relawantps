<?php

helper('html');
?>
<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$title?></h5>
	</div>
	
	<div class="card-body">
		<a href="<?=$module_url?>" class="btn btn-light btn-xs" id="add-menu"><i class="fa fa-arrow-circle-left pe-1"></i> Daftar Role Permission</a>
		<hr/>
		<?php
		if (!empty($msg)) {
			show_message($msg);
		}
// echo '<pre>'; print_r($role_permission); die;
		?>
		<form method="post" action="<?=current_url(true)?>" >
			<div>
				<div class="row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nama Role</label>
					<div class="col-sm-8">
						<?=$role['nama_role']?>
					</div>
				</div>
				<div class="row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label mb-2">Permission</label>
					<div class="form-inline mb-3">
						<a href="javascript:void(0)" class="check-all btn btn-xs btn-outline-secondary">Check All</a>
						<a href="javascript:void(0)" class="uncheck-all btn btn-xs btn-outline-success">Uncheck All</a>
					</div>
					<div class="row">
						<?php
						foreach ($permission_permodule as $id_module => $arr) 
						{
							echo '<div class="col-sm-3 permission-container">'
							 . '<label class="fs-6"><strong>' . $module[$id_module]['judul_module'] . '</strong></label>'
							 . '<div class="small mb-2"><a href="javascript:void(0)" class="checkall-module-permission">Check All</a> | <a href="javascript:void(0)" class="uncheckall-module-permission">Uncheck All</a></div>
							 
							 <div class="row module-permission">';
							
								foreach ($arr as $val) {
									$checked = '';
									if (key_exists($val['id_permission'], $role_permission)) {
										$checked = true;
									}
									echo  checkbox(['attr' => ['name' => 'permission[]', 'value' => $val['id_permission'], 'class' => 'permission', 'id' => $val['nama_permission']], 'label' => $val['nama_permission']], $checked);
								}
								echo '<div class="mb-2"></div>';
							 echo '</div>
							 </div>';
						}
						?>
					</div>
					<div class="form-inline mb-3">
						<a href="javascript:void(0)" class="check-all btn btn-xs btn-outline-secondary">Check All</a>
						<a href="javascript:void(0)" class="uncheck-all btn btn-xs btn-outline-success">Uncheck All</a>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-sm-12">
						<button type="submit" name="submit" value="submit" class="btn btn-primary mt-2">Save</button>
						<input type="hidden" name="id" value="<?=@$_GET['id']?>"/>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>