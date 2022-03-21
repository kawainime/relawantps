<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$title?></h5>
	</div>
	
	<div class="card-body">
		<a href="<?=base_url()?>/builtin/permission/ajaxAdd" class="btn btn-success me-3"><i class="fa fa-plus pe-1"></i> Tambah Permission</a>
		<a href="<?=base_url()?>/builtin/permission" class="btn btn-light"><i class="fa fa-arrow-left"></i> Data Permission</a>
		<hr/>
		<?php
		if ($message) {
			show_message($message);
		}
		helper('html');
		?>
		<form method="post" action="" class="mb-5">
			<div>
				<div class="row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Module</label>
					<div class="col-sm-8">
						<?=options(['name' => 'id_module'], $modules, set_value('id_module', ''))?>
					</div>
				</div>
				<div class="row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Permission</label>
					<div class="col-sm-8">
						<?=options(['name' => 'generate'], ['otomatis' => 'Otomatis (CRUD)', 'manual' => 'Manual'], set_value('generate', @$generate) )?>
						<small>Otomatis: otomatis akan membuat permission untuk CRUD, misal untuk module produk: create_produk, read_produk, update_produk, delete_produk</small>
					</div>
				</div>
				<?php
				$display = @$_POST['generate'] == 'otomatis' || empty($_POST) ? 'style="display:none"' : '';
				?>
				<div class="input-container" <?=$display?>>
					<div class="row mb-3">
						<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Grup Aksi</label>
						<div class="col-sm-8">
							<?=options(['name' => 'grup_aksi'], ['create' => 'Create', 'read' => 'Read', 'update' => 'Update', 'delete' => 'Delete'], set_value('grup_aksi', @$grup_aksi))?>
						</div>
					</div>
					<div class="row mb-3">
						<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nama Permission</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="nama_permission" value="<?=set_value('nama_permission', '')?>"/>
							<small>Buat nama permission sesuai grup aksi dan nama module, misal untuk module <strong>produk</strong> grup aksi <strong>read</strong>, nama permission nya misal: read_produk_own</small> 
						</div>
					</div>
					<div class="row mb-3">
						<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Judul Permission</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="judul_permission" value="<?=set_value('judul_permission', '')?>"/>
						</div>
					</div>
					<div class="row mb-3">
						<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Keterangan</label>
						<div class="col-sm-8">
							<textarea class="form-control" name="keterangan"><?=set_value('keterangan', '')?></textarea>
						</div>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-sm-8 offset-sm-2">
						<button type="submit" name="submit" value="submit" class="btn btn-primary mt-2">Submit</button>
						<input type="hidden" name="id" value="<?=set_value('id', @$id)?>"/>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>