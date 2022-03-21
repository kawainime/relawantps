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
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-md-2 col-lg-2 col-xl-2 col-form-label">Kelurahan</label>
                <div class="col-sm-5">
                    <?php
                    $optionsKel[''] = '';
                    echo options(['class' => 'form-control select2 kecSelect', 'name' => 'id_kel', 'id' => 'id_kel'], $optionsKel);
                    ?>
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
                <div class="col-12 col-md-12 col-lg-12 col-xl-6" style="overflow-x:auto">
                    <canvas id="pie-container" style="min-width:400px;margin:auto;width:500px"></canvas>
                </div>
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
        $wil[] = "'".$val['wilayah']."'";
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
        <?php }
        ?>
    </div>
</div>