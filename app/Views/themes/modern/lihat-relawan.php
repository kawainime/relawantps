<div class="card">
    <div class="card-header">
        <h5 class="card-title"><?= $current_module['judul_module'] ?></h5>
    </div>
    <form method="get">
        <?php helper('html') ?>
    </form>
    <div class="card-body">
        <?php
        if ($message['status'] == 'error') {
            show_message($message);
        }
        ?>
        <form method="get" action="" class="form-horizontal">
            <div class="row mb-3">
                <label class="col-sm-2 col-md-2 col-lg-2 col-xl-2 col-form-label">Provinsi</label>
                <div class="col-sm-5 form-inline">
                    <label class="col-sm-12 col-md-12 col-form-label"><?= $caleg['provinsi'] ?></label>
                    <input type="hidden" id="id_prov" name="id_prov" value="<?= $caleg['id_prov'] ?>"/>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-md-2 col-lg-2 col-xl-2 col-form-label">Kota/Kabupaten</label>
                <div class="col-sm-5 form-inline">
                    <label class="col-sm-12 col-md-12 col-form-label"><?= $caleg['kabupaten'] ?></label>
                    <input type="hidden" id="id_kab" name="id_kab" value="<?= $caleg['id_kab'] ?>"/>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-md-2 col-lg-2 col-xl-2 col-form-label">Kecamatan</label>
                <div class="col-sm-5">
                    <?php
                    echo options(['id' => 'id_kec', 'name' => 'id_kec', 'class' => 'select2 form-control'], $kec, set_value('id_kec', ''));
                    ?>
                    <input type="hidden" id="kec_id" name="kec_id" value="<?= @$kec_id ?>"/>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-md-2 col-lg-2 col-xl-2 col-form-label">Kelurahan</label>
                <div class="col-sm-5">
                    <?php
                    $optionsKel[''] = '';
                    echo options(['class' => 'form-control select2 kecSelect', 'name' => 'id_kel', 'id' => 'id_kel'], $optionsKel);
                    ?>
                    <input type="hidden" id="kel_id" name="kel_id" value="<?= @$kel_id ?>"/>
                </div>
            </div>
            <div class="row mb-3">
                <!--<label class="col-sm-2 col-md-2 col-lg-2 col-xl-2 col-form-label">Tahun</label>-->
                <div class="col-sm-5 form-inline">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-md-2 col-lg-2 col-xl-2 col-form-label">Target TPS</label>
                <div class="col-sm-5 form-inline">
                    <label class="col-sm-12 col-md-12 col-form-label"><?= $total_tps ?></label>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-md-2 col-lg-2 col-xl-2 col-form-label">Capaian</label>
                <div class="col-sm-5 form-inline">
                    <label class="col-sm-12 col-md-12 col-form-label"><?= $capaian ?>%</label>
                </div>
            </div>
        </form>
        <?php
        if ($message['status'] == 'ok') {
            ?>
            <div class="row mb-3">
                <div class="col-12 col-md-12 col-lg-12 col-xl-6" style="overflow-x:auto">
                    <canvas id="bar-container" height="400px" style="min-width:500px;margin:auto;width:100%"></canvas>
                </div>
                <!--                <div class="col-12 col-md-12 col-lg-12 col-xl-6" style="overflow-x:auto">
                                    <canvas id="pie-container" style="min-width:400px;margin:auto;width:500px"></canvas>
                                </div>-->
            </div>

            <script>
                function dynamicColors() {
                    var r = Math.floor(Math.random() * 255);
                    var g = Math.floor(Math.random() * 255);
                    var b = Math.floor(Math.random() * 255);
                    return "rgba(" + r + "," + g + "," + b + ", 0.8)";
                }
                var randomBackground = [];

                for (i = 0; i < 12; i++) {
                    randomBackground.push(dynamicColors());
                }

                var barChartData = {
    //                    labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    labels: [
    <?php
    foreach ($relawan as $val) {
        $wil[] = "'" . $val['wilayah'] . "'";
    }

    echo implode(',', $wil);
    ?>
                    ],
                    datasets: [{
                            label: 'Grafik Relawan ' + <?= $tahun ?>,
                            backgroundColor: randomBackground,
                            borderWidth: 1,
                            data: [
    <?php
    foreach ($relawan as $val) {
        $total[] = $val['total'];
    }

    echo join(',', $total);
    ?>
                            ]
                        }]
                };

                configBar = {
                    type: 'bar',
                    data: barChartData,
                    options: {
                        responsive: false,
                        legend: {
                            display: false,
                            labels: {

                            }
                        },
                        maintainAspectRatio: false,
                        title: {
                            display: true,
                            text: 'Grafik Relawan <?= $label ?>',
                            fontSize: 14,
                            lineHeight: 3
                        },
                        tooltips: {
                            callbacks: {
                                label: function (tooltipItems, data) {
                                    // return data.labels[tooltipItems.index] + ": " + data.datasets[0].data[tooltipItems.index].toLocaleString();
                                    // return "Total : " + data.datasets[0].data[tooltipItems.index].toLocaleString();
                                    return "Total : " + data.datasets[0].data[tooltipItems.index].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                }
                            }
                        },
                        scales: {
                            yAxes: [{
                                    ticks: {
                                        beginAtZero: false,
                                        callback: function (value, index, values) {
                                            // return value.toLocaleString();
                                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                        }
                                    }
                                }]
                        }
                    }
                }




                /*
                 PIE Chart
                 */

    //                var configPie = {
    //                    type: 'pie',
    //                    data: {
    //                        datasets: [{
    //                                data: [
    //    <?php
    foreach ($item_terjual as $val) {
        $jumlah[] = $val['jml'];
    }

    echo join(',', $jumlah);
    ?>//
    //                                ],
    //                                backgroundColor: [
    //    <?php
    foreach ($item_terjual as $val) {
        $func[] = 'dynamicColors()';
    }

    echo join(',', $func);
    ?>//
    //                                ],
    //                                label: 'Dataset 1'
    //                            }],
    //                        labels: [
    //    <?php
    foreach ($item_terjual as $val) {
        $nama[] = $val['nama'];
    }

    echo '"' . join('","', $nama) . '"';
    ?>//
    //                        ]
    //                    },
    //                    options: {
    //                        responsive: false,
    //                        // maintainAspectRatio: false,
    //                        title: {
    //                            display: true,
    //                            text: 'Barang Terjual ' + <?= $tahun ?>,
    //                            fontSize: 14,
    //                            lineHeight: 3
    //                        },
    //                        legend: {
    //                            display: true,
    //                            position: 'right',
    //                            fullWidth: false,
    //                            labels: {
    //                                padding: 10,
    //                                boxWidth: 30
    //                            }
    //                        }
    //                    }
    //                };

                window.onload = function () {
                    /* PIE */
    //                    var ctx = document.getElementById('pie-container').getContext('2d');
    //                    window.myPie = new Chart(ctx, configPie);

                    /* BAR */
                    var ctx = document.getElementById('bar-container').getContext('2d');
                    window.myBar = new Chart(ctx, configBar);
                };
            </script>
            <?php
        }

        $column = [
            'ignore_search_urut' => 'No'
            , 'ignore_search_foto' => 'Foto'
            , 'nama' => 'Nama DPT'
            , 'no_wa' => 'No. WA'
            , 'nik' => 'NIK'
            , 'tempatLahir' => 'Tempat Lahir'
            , 'jenisKelamin' => 'P/L'
            , 'ignore_search_provinsi' => 'Provinsi'
            , 'ignore_search_kabupaten' => 'Kota/Kab'
            , 'ignore_search_kecamatan' => 'Kecamatan'
            , 'ignore_search_kelurahan' => 'Kelurahan/Desa'
            , 'rt_rw' => 'RT/RW'
            , 'noTps' => 'No. TPS'
//            , 'ignore_search_action' => 'Action'
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
        <hr>

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
        $data_param['kec_id'] = @$kec_id;
        $data_param['kel_id'] = @$kel_id;
        ?>
        <span id="dataTables-column" style="display:none"><?= json_encode($column_dt) ?></span>
        <span id="dataTables-setting" style="display:none"><?= json_encode($settings) ?></span>
        <span id="dataTables-data" style="display:none"><?= json_encode($data_param) ?></span>
        <span id="dataTables-url" style="display:none"><?= current_url() . '/getDataDT' ?></span>
    </div>
</div>