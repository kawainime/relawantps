<div class="card">
    <div class="card-header">
        <h5 class="card-title"><?= $title ?></h5>
    </div>

    <div class="card-body">
        <?php
        helper('html');
        echo btn_label(['attr' => ['class' => 'btn btn-success btn-xs'],
            'url' => $config->baseURL . $current_module['nama_module'] . '/add',
            'icon' => 'fa fa-plus',
            'label' => 'Tambah Data'
        ]);

        echo btn_label(['attr' => ['class' => 'btn btn-light btn-xs'],
            'url' => $config->baseURL . $current_module['nama_module'],
            'icon' => 'fa fa-arrow-circle-left',
//            'label' => $current_module['judul_module']
            'label' => 'Daftar Relawan'
        ]);
        ?>
        <hr/>
        <?php
        if (@$tgl_lahir) {
            $exp = explode('-', $tgl_lahir);
            $tgl_lahir = $exp[2] . '-' . $exp[1] . '-' . $exp[0];
        }
        if (!empty($msg)) {
            show_message($msg['content'], $msg['status']);
        }
        ?>
        <form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
            <div class="tab-content" id="myTabContent">
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Provinsi</label>
                    <div class="col-sm-5">
                        <label class="col-sm-12 col-md-12 col-form-label"><?= $caleg['provinsi'] ?></label>
                        <input type="hidden" id="id_prov" name="id_prov" value="<?= $caleg['id_prov'] ?>"/>
                        <?php
//                        echo options(['id' => 'id_prov', 'name' => 'id_prov', 'class' => 'select2 form-control', 'required' => ''], $prov, set_value('id_prov', ''));
                        ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Kota/Kabupaten</label>
                    <div class="col-sm-5">
                        <label class="col-sm-12 col-md-12 col-form-label"><?= $caleg['kabupaten'] ?></label>
                        <input type="hidden" id="id_kab" name="id_kab" value="<?= $caleg['id_kab'] ?>"/>
                        <?php
//                        $optionsKab[''] = '';
//                        echo options(['class' => 'form-control select2 kabSelect', 'name' => 'id_kab', 'id' => 'id_kab'], $optionsKab);
                        ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Kecamatan</label>
                    <div class="col-sm-5">
                        <?php
                        echo options(['id' => 'id_kec', 'name' => 'id_kec', 'class' => 'select2 form-control', 'required' => ''], $kec, set_value('id_kec', ''));
//                        $optionsKec[''] = '';
//                        echo options(['class' => 'form-control select2 kecSelect', 'name' => 'id_kec', 'id' => 'id_kec'], $optionsKec);
                        ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Kelurahan</label>
                    <div class="col-sm-5">
                        <?php
                        $optionsKel[''] = '';
                        echo options(['class' => 'form-control select2 kecSelect', 'name' => 'id_kel', 'id' => 'id_kel'], $optionsKel);
                        ?>
                    </div>
                </div>
<!--                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">TPS</label>
                    <div class="col-sm-5">
                        <?php
//                        $optionsTps[''] = '';
//                        echo options(['class' => 'form-control select2 kecSelect', 'name' => 'noTps', 'id' => 'noTps'], $optionsTps);
                        ?>
                    </div>
                </div>-->
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">TPS - Nama - NIK</label>
                    <div class="col-sm-6">
                        <?php
                        $optionsTps[''] = '';
                        echo options(['class' => 'form-control select2 kecSelect', 'name' => 'idDpt', 'id' => 'idDpt'], $optionsTps);
                        ?>
                    </div>
                </div>
                <!--                <div class="row mb-3">
                                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nama Relawan</label>
                                    <div class="col-sm-5">
                                        <input class="form-control" type="text" name="nama" value="<?= set_value('nama', @$nama) ?>" required="required"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Tempat Lahir</label>
                                    <div class="col-sm-5">
                                        <input class="form-control" type="text" name="tempat_lahir" value="<?= set_value('tempat_lahir', @$tempat_lahir) ?>" required="required"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Tgl. Lahir</label>
                                    <div class="col-sm-5">
                                        <input class="form-control date-picker" type="text" name="tgl_lahir" value="<?= set_value('tgl_lahir', @$tgl_lahir) ?>"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">NPM</label>
                                    <div class="col-sm-5">
                                        <input class="form-control" type="text" name="npm" value="<?= set_value('npm', @$npm) ?>"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Prodi</label>
                                    <div class="col-sm-5">
                                        <input class="form-control" type="text" name="prodi" value="<?= set_value('prodi', @$prodi) ?>" required="required"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Fakultas</label>
                                    <div class="col-sm-5">
                                        <input class="form-control" type="text" name="fakultas" value="<?= set_value('fakultas', @$fakultas) ?>" required="required"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Alamat</label>
                                    <div class="col-sm-5">
                                        <input class="form-control" type="text" name="alamat" value="<?= set_value('alamat', @$alamat) ?>" required="required"/>
                                    </div>
                                </div>-->
            </div>
            <!--            <div class="row mb-3">
                            <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Foto (Image Upload)</label>
                            <div class="col-sm-5">
            <?php
            if (!empty($foto)) {
                $note = '';
                if (file_exists(ROOTPATH . 'public/images/foto/' . $foto)) {
                    $image = $config->baseURL . 'public/images/foto/' . $foto;
                } else {
                    $image = $config->baseURL . 'public/images/foto/noimage.png';
                    $note = '<small><b>Note</strong>: File <strong>public/images/foto/' . $foto . '</strong> tidak ditemukan</small>';
                }
                echo '<div class="img-choose" style="margin:inherit;margin-bottom:10px">
									<div class="img-choose-container">
										<img src="' . $image . '?r=' . time() . '"/>
										<a href="javascript:void(0)" class="remove-img"><i class="fas fa-times"></i></a>
									</div>
								</div>
								' . $note . '
								';
            }
            ?>
                                <input type="hidden" class="foto-delete-img" name="foto_delete_img" value="0">
                                <input type="hidden" class="foto-max-size" name="foto_max_size" value="300000"/>
                                <input type="file" class="file form-control" name="foto">
            <?php if (!empty($form_errors['foto'])) echo '<small class="alert alert-danger">' . $form_errors['foto'] . '</small>' ?>
                                <small class="small" style="display:block">Maksimal 300Kb, Minimal 100px x 100px, Tipe file: .JPG, .JPEG, .PNG</small>
                                <div class="upload-img-thumb"><span class="img-prop"></span></div>
                            </div>
                        </div>-->
            <div class="row">
                <div class="col-sm-5">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                    <input type="hidden" name="id" value="<?= @$_GET['id'] ?>"/>
                </div>
            </div>
    </div>
</form>
</div>
</div>