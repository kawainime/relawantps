<div class="card">
    <div class="card-header">
        <h5 class="card-title"><?= $title ?></h5>
    </div>

    <div class="card-body">
        <?php
        helper('html');
//        echo btn_label(['attr' => ['class' => 'btn btn-success btn-xs'],
//            'url' => $config->baseURL . $current_module['nama_module'] . '/add',
//            'icon' => 'fa fa-plus',
//            'label' => 'Tambah Data'
//        ]);

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
                <div class="bg-lightgrey p-3 ps-4">
                    <h5>Profil</h5>
                </div>
                <hr/>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Provinsi</label>
                    <div class="col-sm-5">
                        <label class="col-sm-12 col-md-12 col-form-label"><?= $provinsi ?></label>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Kota/Kabupaten</label>
                    <div class="col-sm-5">
                        <label class="col-sm-12 col-md-12 col-form-label"><?= $kabupaten ?></label>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Kecamatan</label>
                    <div class="col-sm-5">
                        <label class="col-sm-12 col-md-12 col-form-label"><?= $kecamatan ?></label>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Kelurahan</label>
                    <div class="col-sm-5">
                        <label class="col-sm-12 col-md-12 col-form-label"><?= $kelurahan ?></label>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">TPS</label>
                    <div class="col-sm-5">
                        <label class="col-sm-12 col-md-12 col-lg-3 col-xl-2 col-form-label"><?= $noTps ?></label>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nama</label>
                    <div class="col-sm-5">
                        <label class="col-sm-12 col-md-12 col-form-label"><?= $nama ?></label>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">NIK</label>
                    <div class="col-sm-5">
                        <input class="form-control" type="text" name="nik" value="<?= set_value('nik', @$nik) ?>" required="required"/>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">RT/RW</label>
                    <div class="col-sm-1">
                        <input class="form-control" type="text" name="rt" value="<?= set_value('rt', @$rt) ?>" required="required"/>
                    </div>/
                    <div class="col-sm-1">
                        <input class="form-control" type="text" name="rw" value="<?= set_value('rw', @$rw) ?>" required="required"/>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nomor WA</label>
                    <div class="col-sm-5">
                        <input class="form-control" type="text" name="no_wa" value="<?= set_value('no_wa', @$no_wa) ?>" required="required"/>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Foto</label>
                    <div class="col-sm-5">
                        <?php
                        $avatar = @$_FILES['file']['name'] ?: @$avatar;
                        if (!empty($avatar)) {
                            echo '<div class="img-choose" style="margin:inherit;margin-bottom:10px">
									<div class="img-choose-container">
										<img src="' . $config->baseURL . '/public/images/user/' . $avatar . '?r=' . time() . '"/>
										<a href="javascript:void(0)" class="remove-img"><i class="fas fa-times"></i></a>
									</div>
								</div>
								';
                        }
                        ?>
                        <input type="hidden" class="avatar-delete-img" name="avatar_delete_img" value="0">
                        <input type="file" class="file" name="avatar">
                        <?php if (!empty($form_errors['avatar'])) echo '<small style="display:block" class="alert alert-danger mb-0">' . $form_errors['avatar'] . '</small>' ?>
                        <small class="small" style="display:block">Maksimal 300Kb, Minimal 100px x 100px, Tipe file: .JPG, .JPEG, .PNG</small>
                        <div class="upload-img-thumb mb-2"><span class="img-prop"></span></div>
                    </div>
                </div>
                <div class="bg-lightgrey p-3 ps-4">
                    <h5>Login</h5>
                </div>
                <hr/>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Username</label>
                    <div class="col-sm-8 col-md-6 col-lg-4">
                        <input class="form-control" type="text" name="username" value="<?= set_value('username', @$username) ?>" placeholder="Username" required="required" <?= !empty($id_user)?'readonly':'' ?>/>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Email</label>
                    <div class="col-sm-8 form-inline">
                        <input class="form-control" type="text" name="email" value="<?= set_value('email', @$email) ?>" placeholder="Email" required="required" <?= !empty($id_user)?'readonly':'' ?>/>
                        <input type="hidden" name="email_lama" value="<?= set_value('email_lama', $email) ?>" />
                    </div>
                </div>
                <?php
                if (empty($id_user)) { ?>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Password Baru</label>
                    <div class="col-sm-8 form-inline">
                        <input class="form-control" type="password" name="password" required="required"/>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Ulangi Password Baru</label>
                    <div class="col-sm-8 form-inline">
                        <input class="form-control" type="password" name="ulangi_password" required="required"/>
                    </div>
                </div>
                <?php } ?>
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
                    <input type="hidden" name="id_user" value="<?= $id_user ?>"/>
                    <input type="hidden" name="nama" value="<?= $nama ?>"/>
                    <input type="hidden" name="id_role" value="13"/>
                </div>
            </div>
    </div>
</form>
</div>
</div>