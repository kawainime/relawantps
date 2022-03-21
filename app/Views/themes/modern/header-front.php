
<!DOCTYPE HTML>
<html lang="en">
    <title>Survey Online | Jagowebdev</title>
    <meta name="descrition" content="Survey online dengan berbagai fitur menarik dan fleksibel untuk digunakan"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="shortcut icon" href="<?= $config->baseURL ?>public/images/favicon.png" />

    <link rel="stylesheet" type="text/css" href="<?= $config->baseURL ?>public/vendors/font-awesome/css/all.css?r=1646472222"/>
    <link rel="stylesheet" type="text/css" href="<?= $config->baseURL ?>public/vendors/bootstrap/css/bootstrap.min.css?r=1646472222"/>
    <link rel="stylesheet" type="text/css" href="<?= $config->baseURL ?>public/themes/modern/css/bootstrap-custom.css?r=1646472222"/>
    <link rel="stylesheet" type="text/css" href="<?= $config->baseURL ?>public/themes/modern/css/site-front.css?r=1646472222"/>
    <link rel="stylesheet" type="text/css" href="<?= $config->baseURL ?>public/vendors/overlayscrollbars/OverlayScrollbars.min.css?r=1646472222"/>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,300;0,400;0,800;1,300;1,400;1,800&display=swap" rel="stylesheet">

    <!-- Dynamic styles -->
    <?php
    if (@$styles) {
        foreach ($styles as $file) {
            echo '<link rel="stylesheet" type="text/css" href="' . $file . '?r=' . time() . '"/>' . "\n";
        }
    }
    ?>
    <script type="text/javascript">
        var base_url = "<?= $config->baseURL ?>";
        var module_url = "<?= $module_url ?>";
        var current_url = "<?= current_url() ?>";
        var theme_url = "<?= $config->baseURL . '/public/themes/modern/builtin/' ?>";
    </script>

    <script type="text/javascript" src="<?= $config->baseURL ?>public/vendors/jquery/jquery.min.js?r=1646472222"></script>
    <script type="text/javascript" src="<?= $config->baseURL ?>public/themes/modern/js/site.js?r=1646472222"></script>
    <script type="text/javascript" src="<?= $config->baseURL ?>public/themes/modern/js/survey.js?r=1646472222"></script>
    <script type="text/javascript" src="<?= $config->baseURL ?>public/vendors/bootstrap/js/bootstrap.min.js?r=1646472222"></script>
    <script type="text/javascript" src="<?= $config->baseURL ?>public/vendors/overlayscrollbars/jquery.overlayScrollbars.min.js?r=1646472222"></script>
    <script type="text/javascript">
            var base_url = "<?= $config->baseURL ?>";
    </script>

    <!-- Dynamic scripts -->
    <?php
    if (@$scripts) {
        foreach ($scripts as $file) {
            if (is_array($file)) {
                if ($file['print']) {
                    echo '<script type="text/javascript">' . $file['script'] . '</script>' . "\n";
                }
            } else {
                echo '<script type="text/javascript" src="' . $file . '?r=' . time() . '"></script>' . "\n";
            }
        }
    }
    ?>
</head>
<body>
    <div class="site-container">
        <header class="shadow-sm">
            <div class="menu-wrapper wrapper clearfix">
                <a href="#" id="mobile-menu-btn" class="show-mobile">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="nav-left">
                    <a href="" class="logo-header" title="Jagowebdev">
                        <img src="<?= $config->baseURL ?>public/images/logo-katapedia.png" alt="Katapedia"/>
                    </a>
                </div>

                <nav class="nav-right nav-header">

                    <ul class = main-menu >
                        <li class="menu">
                            <a  class="depth-0" href="<?= $config->baseURL ?>"><i class="menu-icon fas fa-home"></i>Home</a></li>
<!--                        <li class="menu has-children">
                            <a  class="depth-0 has-children" href="<?= $config->baseURL ?>#" onclick="javascript:void(0)"><i class="menu-icon fas fa-clipboard-list"></i>Survey<span class="menu-arrow-container">
                                    <i class="fa fa-angle-down arrow"></i>
                                </span></a>
                            <ul class="submenu">
                                <li class="menu">
                                    <a  class="depth-1" href="<?= $config->baseURL ?>survey-bahasa-pemrograman"><i class="menu-icon fas fa-tasks"></i>Survey Bahasa Pemrograman</a></li>
                                <li class="menu">
                                    <a  class="depth-1" href="<?= $config->baseURL ?>survey-tabungan-karyawan"><i class="menu-icon fas fa-tasks"></i>Survey Tabungan Karyawan</a></li>
                                <li class="menu">
                                    <a  class="depth-1" href="<?= $config->baseURL ?>survey-kaos-komunitas"><i class="menu-icon fas fa-tasks"></i>Survey Kaos Komunitas</a></li>
                                <li class="menu">
                                    <a  class="depth-1" href="<?= $config->baseURL ?>survey-website-kominfo"><i class="menu-icon fas fa-tasks"></i>Survey Website Kominfo</a></li>
                            </ul>
                        </li>
                        <li class="menu">
                            <a  class="depth-0" href="https://jagowebdev.com/members/produk"><i class="menu-icon fas fa-folder"></i>Produk</a></li>
                        <li class="menu">
                            <a  class="depth-0" href="<?= $config->baseURL ?>admin"><i class="menu-icon fas fa-sign-in-alt"></i>Backend</a></li>-->
                    </ul>
                    <ul class="user-menu">
                        <li class="menu">
                            <a class="login-btn" href="<?= $config->baseURL ?>login"><i class="menu-icon fas fa-lock"></i>Login</a>
                            <div class="account-menu-container shadow-sm">
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="clearfix"></div>
            </div>
        </header>
        <div class="page-container">