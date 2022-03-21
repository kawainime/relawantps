<?php
if ($message) {
	show_message($message);
	exit;
}
helper('html');
?>
<form method="post" action="<?=base_url()?>/builtin/permission/ajaxEdit">
<div class="row mb-3">
	<div class="col-sm-12">
		<label>Nama Module</label>
		<?=options(['name' => 'id_module'], $modules, $result['id_module'])?>
	</div>
</div>
<div class="row mb-3">
	<label class="col-sm-12">Grup Aksi</label>
	<div class="col-sm-8">
		<?=options(['name' => 'grup_aksi'], ['create' => 'Create', 'read' => 'Read', 'update' => 'Update', 'delete' => 'Delete'], @$result['grup_aksi'])?>
	</div>
</div>
<div class="row mb-3">
	<div class="col-sm-12">
		<label>Nama Permission</label>
		<input type="text" class="form-control" name="nama_permission" value="<?=$result['nama_permission']?>"/>
		<small>Buat nama permission sesuai grup aksi dan nama module, misal untuk module <strong>produk</strong> grup aksi <strong>read</strong>, nama permission nya misal: read_produk_own</small> 
	</div>
</div>
<div class="row mb-3">
	<div class="col-sm-12">
		<label>Judul Permission</label>
		<input type="text" class="form-control" name="judul_permission" value="<?=$result['judul_permission']?>"/>
	</div>
</div>
<div class="row mb-3">
	<div class="col-sm-12">
		<label>Keterangan</label>
		<textarea class="form-control" name="keterangan"><?=$result['keterangan']?></textarea>
	</div>
</div>
<input type="hidden" name="id" value="<?=$result['id_permission']?>"/>
<input type="hidden" name="id_module_old" value="<?=$result['id_module']?>"/>
<input type="hidden" name="generate" value="manual"/>
</form>