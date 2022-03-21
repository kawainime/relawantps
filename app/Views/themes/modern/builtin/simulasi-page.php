<div class="section">
    <section>
        <div class="survey-container shadow-sm ">
            <h2 class="section-title no-margin"><?= $site_title ?></h1>
                <p class="section-desc">Simulasi ini bertujuan untuk mengetahui suara yang dibutuhkan per TPS</p>
                <p class="text-danger">*) Required</p>
                <div class="content">
                    <form method="post" action="">
                        <?php
                        if (!empty($message)) {
                            show_message($message);
                        }

                        helper('html');
                        ?>
                        <div class="row">
                            <div class="col-12"><p>Provinsi <span class="text-danger">*</span></p>
                                <!--<div class="description mt-n2 mb-3">Contoh validasi dimana respon hanya diperbolehkan huruf (a-z) dan spasi. Selain itu, panjang karakter minimal 5 karakter </div>-->
                                <!--<input class="form-control"  type="text" name="id_pertanyaan[1]" value="" />-->
                                <?php
                                echo options(['id' => 'provinsi', 'name' => 'provinsi', 'class' => 'select2 form-control'], $prov, set_value('provinsi', ''));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12"><p>Kota/Kabupaten <span class="text-danger">*</span></p>
                                <!--<div class="description mt-n2 mb-3">Contoh validasi dimana respon harus berupa alamat email yang valid dan email harus mengandung string email.com atau gmail.com</div>-->
                                <!--<input class="form-control"  type="email" name="id_pertanyaan[2]" value=""  required/>-->
                                <?php
                                $optionsKab[''] = '';
                                echo options(['class' => 'form-control select2 kabSelect', 'name' => 'kabupaten', 'id' => 'kabupaten'], $optionsKab);
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12"><p>Dapil <span class="text-danger">*</span></p>
                                <?php
                                $optionsDapilkab[''] = '';
                                echo options(['class' => 'form-control select2', 'name' => 'dapilkab', 'id' => 'dapilkab'], $optionsDapilkab);
                                ?>
<!--                                <select name="id_pertanyaan[3]" class="select-options-lainnya form-control">
                                    <option value="1">Laki Laki</option>
                                    <option value="2">Perempuan</option>
                                </select>-->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12"><p>Target Suara <span class="text-danger">*</span></p>
                                <!--<div class="description mt-n2 mb-3">Contoh validasi dimana respon harus berupa alamat url yang valid</div>-->
                                <input class="form-control"  type="number" name="targetsuara" id="targetsuara" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12"><p>Dapil Anda Tersedia </p>
                                <!--<div class="description mt-n2 mb-3">Contoh validasi dimana respon harus berupa alamat url yang valid</div>-->
                                <div class="col-9">
                                    <input class="form-control"  type="number" name="jmltps" id="jmltps" readonly/>
                                </div>
                                <p>TPS </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12"><p>Suara yang Dibutuhkan </p>
                                <!--<div class="description mt-n2 mb-3">Contoh validasi dengan nilai minimal 7 dan nilai maksimal 60 (usia antara 7 s.d 60 tahun)</div>-->
                                <div class="col-9">
                                    <input class="form-control"  type="text" name="butuhsuara" id="butuhsuara" readonly/>
                                </div>
                                <p>/TPS </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12"><p>Insya Allah, Anda Lolos ke Parlemen! </p>
                            </div>
                        </div>					
                        <!--<button class="btn btn-success mt-3" type="submit" name="submit" value="submit">Submit Survey</button>-->
                        <input type="hidden" name="id" value="1"/>
                        <input type="hidden" name="csrf_app_token" value="59386c1f70bc77cbe776bcbe2e08237bf6dcdb91b9170c31688fcb0f63e5650b"/>				</form>
                </div>
        </div>
    </section>
</div>