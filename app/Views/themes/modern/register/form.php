<div class="register-container shadow-sm">
    <div class="register-header">
        <h1>Register Caleg</h1/>
    </div>
    <div class="register-body">
        <?php
        // echo '<pre>'; print_r($form_error); die;
        if (!empty($message)) {
            show_message($message);
        }
        // $this->load->library('auth_library');
        // $form_token = $this->auth_library->generateTokenForm();
        helper('form');
        helper('html');
        ?>
        <form action="<?= current_url() ?>" method="post" accept-charset="utf-8">

            <!--<p class="mb-4" style="text-align:center">Komitmen kami: kami akan berusaha semaksimal mungkin menyimpan data Anda dengan aman dan <strong>tidak akan membagi data Anda</strong> ke siapapun</p>-->
            <div class="form-group mb-3">
                <label>Bagaimana kami memanggil Anda?</label>
                <div class="form-inline">
                    <select name="gender" class="form-control register-input">
                        <option value="L" <?= set_select('gender', 'L') ?>>Bapak/Mas</option>
                        <option value="P" <?= set_select('gender', 'P') ?>>Ibu/Mbak</option>
                    </select>
                    <input type="text" name="nama" value="<?= set_value('nama') ?>" class="form-control register-input" placeholder="Nama" aria-label="Nama" required>
                </div>
            </div>
            <div class="form-group mb-3">
                <label>Email</label>
                <input type="email"  name="email" value="<?= set_value('email', '') ?>" class="form-control register-input" placeholder="Email" aria-label="Email" required>
            </div>
            <div class="form-group mb-3">
                <label>Username</label>
                <input type="text"  name="username" value="<?= set_value('username', '') ?>" class="form-control register-input" placeholder="Username" aria-label="Username" required>
                <p class="small">Username untuk login</p>
            </div>
            <div class="form-group mb-3">
                <label>Provinsi</label>
                <!--<input type="text"  name="provinsi" value="<?= set_value('provinsi', '') ?>" class="form-control register-input" placeholder="Provinsi" aria-label="Provinsi" required>-->
                <?php
                echo options(['id' => 'provinsi', 'name' => 'provinsi', 'class' => 'select2 form-control', 'required' => ''], $prov, set_value('provinsi', ''));
                ?>
                <!--<p class="small">Username untuk login</p>-->
            </div>
            <div class="form-group mb-3">
                <label>Kota/Kabupaten</label>
                <!--<input type="text"  name="kabupaten" value="<?= set_value('kabupaten', '') ?>" class="form-control register-input" placeholder="Kabupaten" aria-label="Kabupaten" required>-->
                <?php
                $optionsKab[''] = '';
                echo options(['class' => 'form-control select2 kabSelect', 'name' => 'kabupaten', 'id' => 'kabupaten', 'required' => ''], $optionsKab);
                ?>
                <!--<p class="small">Username untuk login</p>-->
            </div>
            <div class="form-group mb-3">
                <label>DAPIL</label>
                <!--<input type="text"  name="dapilkab" value="<?= set_value('dapilkab', '') ?>" class="form-control register-input" placeholder="DAPIL" aria-label="DAPIL" required>-->
                <?php
                $optionsDapilkab[''] = '';
                echo options(['class' => 'form-control select2', 'name' => 'dapilkab', 'id' => 'dapilkab', 'required' => ''], $optionsDapilkab);
                ?>
                <!--<p class="small">Username untuk login</p>-->
            </div>
            <div class="form-group mb-3">
                <label>Target Suara</label>
                <input type="number"  id="target" name="target" value="<?= set_value('target', '') ?>" class="form-control register-input" placeholder="Target Suara" aria-label="Target Suara" required min="0">
                <!--<p class="small">Username untuk login</p>-->
            </div>
            <div class="form-group mb-3">
                <label>Nomor WA</label>
                <input type="text" id="nowa" name="nowa" value="<?= set_value('nowa', '') ?>" class="form-control register-input" placeholder="Nomor WA" aria-label="Nomor WA" required>
                <!--<p class="small">Username untuk login</p>-->
            </div>
            <div class="form-group mb-3">
                <label>Password</label>
                <input type="password"  name="password" class="form-control register-input" placeholder="Password" aria-label="Password" required>
                <div class="pwstrength_viewport_progress"></div>
                <p class="small">Bantu kami untuk melindungi data Anda dengan membuat password yang kuat, indikator: medium-strong, min 8 karakter, paling sedikit mengandung huruf kecil, huruf besar, dan angka.</p>
            </div>
            <div class="form-group mb-3">
                <label>Confirm Password</label>
                <input type="password"  name="password_confirm" class="form-control register-input" placeholder="Confirm Password" aria-label="Confirm Password" required>
            </div>
            <div class="form-group mb-3" style="margin-bottom:0">
                <input type="hidden" name="id_role" value="12"/>
                <input type="hidden" name="reg_caleg" value="1"/>
                <button type="submit" name="submit" value="submit" class="btn btn-success" style="display:block;width:100%">Register</button>
                <?= csrf_formfield() ?>
            </div>
    </div>
<!--    <div class="login-footer">
        <p>Lupa Password? <a href="<?= $config->baseURL ?>recovery">Request reset password</a></p>
        <p>Sudah punya akun? <a href="<?= $config->baseURL ?>login">Login disini</a></p>
        <p>Tidak menerima link aktivasi? <a href="<?= $config->baseURL ?>resendlink">Kirim ulang</a></p>
    </div>-->
</div>