<div class="login-container">
    <div class="login-header">
        <h1>Login ke akun kamu</h1/>
    </div>
    <div class="login-body">
        <?php if (!empty($message)) { ?>
            <div class="alert alert-danger">
                <?= $message ?>
            </div>
        <?php }
        ?>
        <form method="post" action="" class="form-horizontal form-login">
            <div class="form-group input-group mb-3">
                <span class="input-group-text">
                    <i class="fa fa-user"></i>
                </span>
                <input type="text" name="username" value="<?= @$_POST['username'] ?>" class="form-control login-input" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required>
            </div>
            <div class="form-group input-group mb-3">
                <span class="input-group-text">
                    <i class="fa fa-lock"></i>
                </span>
                <input type="password"  name="password" class="form-control login-input" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required>
            </div>
            <div style="margin-bottom:10px">
                Administrator: admin:admin, user biasa: user:user. Versi demo tidak dapat mengubah data kecuali menu Layout Setting
            </div>
            <div class="form-group input-group">
                <div class="checkbox">
                    <label style="font-weight:normal"><input name="remember" value="1" type="checkbox">&nbsp;&nbsp;Remember me</label>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:7px">
                <button type="submit" class="form-control btn btn-primary" name="submit">Submit</button>
                <?php csrf_field(); ?>
            </div><?php
//	helper('registrasi');
//	$setting_registrasi = get_setting_registrasi();
                ?>
    </div>
    <div class="login-footer">
        <p>Lupa Password? <a href="<?= $config->baseURL ?>recovery">Request reset password</a></p>
        <?php if ($setting_registrasi['enable'] == 'Y') { ?>
            <p>Belum punya akun? <a href="<?= $config->baseURL ?>register">Daftar akun</a></p>
        <?php } ?>
        <p>Tidak menerima link aktivasi? <a href="<?= $config->baseURL ?>resendlink">Kirim ulang</a></p>
    </div>
</div>