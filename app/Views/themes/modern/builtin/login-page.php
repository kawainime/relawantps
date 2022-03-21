<div class="login-container">
    <div class="login-header">
        <h1>Login ke akun kamu</h1/>

        <?php
        if (!empty($desc)) {
            echo '<p>' . $desc . '</p>';
        }
        ?>
    </div>
    <div class="login-body">
        <?php if (!empty($message)) { ?>
            <div class="alert alert-danger">
                <?= $message ?>
            </div>
            <?php
        }
        //echo password_hash('admin', PASSWORD_DEFAULT);
        ?>
        <form method="post" action="" class="form-horizontal form-login">
            <div class="form-group input-group mb-3">
                <span class="input-group-text">
                    <i class="fa fa-user"></i>
                </span>
                <!--<input type="text" name="username" value="" class="form-control login-input" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required>-->
                <input type="text" name="username" value="<?= @$_POST['username'] ?>" class="form-control login-input" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required>
            </div>
            <div class="form-group input-group mb-3">
                <span class="input-group-text">
                    <i class="fa fa-lock"></i>
                </span>
                <!--<input type="password"  name="password" class="form-control login-input" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required>-->
                <input type="password"  name="password" class="form-control login-input" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required>
            </div>
            <!--            <div style="margin-bottom:10px">
                            Administrator: admin:admin, user biasa: user:user. Versi demo tidak dapat mengubah data kecuali menu Layout Setting
                        </div>-->
            <!--            <div class="form-group input-group">
                            <div class="checkbox">
                                <label style="font-weight:normal"><input name="remember" value="1" type="checkbox">&nbsp;&nbsp;Remember me</label>
                            </div>
                        </div>-->
            <div class="checkbox mb-3">
                <label style="font-weight:normal"><input name="remember" value="1" type="checkbox">&nbsp;&nbsp;Remember me</label>
            </div>
            <div class="form-group" style="margin-bottom:7px">
                <!--<button type="submit" class="form-control btn btn-primary" name="submit">Submit</button>-->
                <button type="submit" class="form-control btn <?= $settingWeb->btn_login ?>" name="submit">Submit</button>
                <!--<input type="hidden" name="csrf_app_token" value="6b388899cef5e840986fcfd0b9b20fa65778211d173295ba93e7ed982f06d88e"/>-->
                <?php
                $form_token = $auth->generateFormToken('login_form_token');
                ?>	
                <?= csrf_formfield() ?>
            </div>
        </form>
        <div class="login-footer">
            <p>Lupa Password? <a href="<?= $config->baseURL ?>recovery">Request reset password</a></p>
            <?php if ($setting_registrasi['enable'] == 'Y') { ?>
                <p>Belum punya akun? <a href="<?= $config->baseURL ?>register">Daftar akun</a></p>
            <?php } ?>
            <p>Tidak menerima link aktivasi? <a href="<?= $config->baseURL ?>register/resendlink">Kirim ulang</a></p>
        </div>
    </div>
<!--    <div class="login-footer">
        <p>Lupa Password? <a href="http://localhost/survey/recovery">Request reset password</a></p>
        <p>Belum punya akun? <a href="http://localhost/survey/register">Daftar akun</a></p>
        <p>Tidak menerima link aktivasi? <a href="http://localhost/survey/resendlink">Kirim ulang</a></p>
    </div>-->
</div>