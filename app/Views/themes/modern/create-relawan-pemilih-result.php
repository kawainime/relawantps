<div class="card">
    <div class="card-header">
        <h5 class="card-title">Create Pemilih</h5>
    </div>

    <div class="card-body">
        <?php
        helper('html');

        echo btn_label(['attr' => ['class' => 'btn btn-light btn-xs'],
            'url' => $config->baseURL . $current_module['nama_module'],
            'icon' => 'fa fa-arrow-circle-left',
//            'label' => $current_module['judul_module']
            'label' => 'Daftar Relawan'
        ]);
        ?>
        <hr/>
        <?php
        if (!empty($msg)) {
            show_alert($msg);
        }

        $column = [
            'ignore_search_urut' => 'No'
            , 'ignore_search_foto' => 'Foto'
            , 'nama' => 'Nama DPT'
            , 'no_wa' => 'No. WA'
            , 'nik' => 'NIK'
            , 'tempatLahir' => 'Tempat Lahir'
            , 'jenisKelamin' => 'P/L'
            , 'provinsi' => 'Provinsi'
            , 'kabupaten' => 'Kota/Kab'
            , 'kecamatan' => 'Kecamatan'
            , 'kelurahan' => 'Kelurahan/Desa'
            , 'noTps' => 'No. TPS'
            , 'ignore_search_action' => 'Action'
        ];

        $settings['order'] = [2, 'desc'];
        $index = 0;
        $th = '';
        foreach ($column as $key => $val) {
            $th .= '<th>' . $val . '</th>';
            if (strpos($key, 'ignore_search') !== false) {
                $settings['columnDefs'][] = ["targets" => $index, "orderable" => false];
            }
            $index++;
        }
        ?>
        <div class="tab-content" id="myTabContent">
            <div class="bg-lightgrey p-3 ps-4">
                <h5>Relawan</h5>
            </div>
            <hr/>
            <div class="row mb-3">
                <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nama</label>
                <div class="col-sm-3">
                    <label class="col-sm-12 col-md-12 col-form-label"><?= $nama ?></label>
                </div><label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">NIK</label>
                <div class="col-sm-3">
                    <label class="col-sm-12 col-md-12 col-form-label"><?= $nik ?></label>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Kecamatan</label>
                <div class="col-sm-3">
                    <label class="col-sm-12 col-md-12 col-form-label"><?= $kecamatan ?></label>
                </div>
                <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Kelurahan</label>
                <div class="col-sm-3">
                    <label class="col-sm-12 col-md-12 col-form-label"><?= $kelurahan ?></label>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">TPS</label>
                <div class="col-sm-3">
                    <label class="col-sm-12 col-md-12 col-form-label"><?= $noTps ?></label>
                </div>
            </div>
        </div>
        <hr/>
        <div class="bg-lightgrey p-3 ps-4">
            <h5>Daftar Pemilih</h5>
        </div>
        <hr/>
        <a href="<?= current_url() ?>/add" class="btn btn-success btn-xs"><i class="fa fa-plus pe-1"></i> Tambah Pemilih</a>
        <hr/>

        <table id="table-result" class="table display table-striped table-bordered table-hover" style="width:100%">
            <thead>
                <tr>
                    <?= $th ?>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <?= $th ?>
                </tr>
            </tfoot>
        </table>
        <?php
        foreach ($column as $key => $val) {
            $column_dt[] = ['data' => $key];
        }
        ?>
        <span id="dataTables-column" style="display:none"><?= json_encode($column_dt) ?></span>
        <span id="dataTables-setting" style="display:none"><?= json_encode($settings) ?></span>
        <span id="dataTables-url" style="display:none"><?= $module_url . '/getDataDTPemilih?id=' . $id_relawan ?></span>
    </div>
</div>