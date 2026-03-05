<div class="container-fluid">
    <div class="card shadow mb-4 border-bottom-primary">
        <div class="card-body py-3">
            <form method="get" action="<?= base_url('dashboard'); ?>" class="row align-items-end">
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold">Periode Tanggal Terima</label>
                    <div class="input-group input-group-sm">
                        <input type="date" name="start" class="form-control" value="<?= $start; ?>">
                        <input type="date" name="end" class="form-control" value="<?= $end; ?>">
                    </div>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold">Toko</label>
                    <select name="store" class="form-control form-control-sm">
                        <option value="">Semua Toko</option>
                        <?php foreach($stores as $s): ?>
                            <option value="<?= $s['id']; ?>" <?= ($store_id == $s['id']) ? 'selected' : ''; ?>><?= $s['store_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold">Platform</label>
                    <select name="platform" class="form-control form-control-sm">
                        <option value="">Semua Platform</option>
                        <?php foreach($platforms as $p): ?>
                            <option value="<?= $p['id']; ?>" <?= ($platform_id == $p['id']) ? 'selected' : ''; ?>><?= $p['platform_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm"><i class="fas fa-filter mr-1"></i> Filter</button>
                    <a href="<?= base_url('dashboard'); ?>" class="btn btn-secondary btn-sm shadow-sm">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Avg. SLA Proses</div>
                            <div class="h5 mb-0 font-weight-bold"><?= $avg_time; ?> Hari</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-stopwatch fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Diterima</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_received; ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-box-open fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sedang Dicek</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_process; ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-microscope fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Siap Kirim</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $ready_send; ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-truck-loading fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-industry mr-2"></i>Analisis Performa Vendor (Top 5)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar" style="height: 320px; position: relative;">
                        <canvas id="vendorChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-pie mr-2"></i>Rasio Selesai vs Reject</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2" style="height: 250px;">
                        <canvas id="pieStatus"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-success"></i> Completed</span>
                        <span class="mr-2"><i class="fas fa-circle text-danger"></i> Rejected</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4 border-left-danger">
        <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center border-0">
            <h6 class="m-0 font-weight-bold text-danger">
                <i class="fas fa-history mr-2"></i>DAFTAR PRODUK MENGENDAP > 30 HARI
            </h6>
            <span class="badge badge-danger px-3 py-2"><?= count($list_overdue_30); ?> Item Kritis</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="pl-4 border-0">No. Retur</th>
                            <th class="border-0 text-center">Tgl Terima</th>
                            <th class="border-0">Toko / Platform</th>
                            <th class="border-0">Pelanggan</th>
                            <th class="border-0 text-center">Lama Inap</th>
                            <th class="border-0 text-center pr-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($list_overdue_30)): foreach($list_overdue_30 as $row): ?>
                        <tr>
                            <td class="pl-4 font-weight-bold text-primary">#<?= $row['return_number']; ?></td>
                            <td class="text-center small"><?= date('d/m/Y', strtotime($row['received_date'])); ?></td>
                            <td>
                                <div class="font-weight-bold"><?= $row['store_name']; ?></div>
                                <div class="badge badge-light border text-muted small px-2 py-0"><?= $row['platform_name']; ?></div>
                            </td>
                            <td><?= $row['customer_name']; ?></td>
                            <td class="text-center">
                                <span class="text-danger font-weight-bold"><?= $row['masa_tunggu']; ?> Hari</span>
                            </td>
                            <td class="text-center pr-4">
                                <a href="<?= base_url('returns/detail/'.$row['id']); ?>" class="btn btn-outline-primary btn-sm btn-circle shadow-sm" title="Buka Detail">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="<?= base_url('assets/img/undraw_check.svg'); ?>" style="width: 120px; opacity: 0.5;" class="mb-3">
                                <p class="text-muted font-italic mb-0">Hebat! Tidak ada barang yang mengendap lebih dari 30 hari.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/vendor/chart.js/Chart.min.js'); ?>"></script>
<script>
    // Konfigurasi Font & Warna Dasar
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    // 1. PIE CHART: COMPLETE VS REJECT
    var ctxPie = document.getElementById("pieStatus");
    if (ctxPie) { // Cek apakah elemen ada
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ["Completed", "Rejected"],
                datasets: [{
                    data: [
                        <?php 
                            $comp = 0; 
                            $rej = 0;
                            if (!empty($pie_data)) {
                                foreach($pie_data as $p) {
                                    if(strtolower($p['status']) == 'completed') $comp = $p['total'];
                                    if(strtolower($p['status']) == 'rejected') $rej = $p['total'];
                                }
                            }
                            echo (int)$comp . "," . (int)$rej; // Pastikan output adalah angka
                        ?>
                    ],
                    backgroundColor: ['#1cc88a', '#e74a3b'],
                    hoverBackgroundColor: ['#17a673', '#be2617'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                legend: { display: false },
                cutoutPercentage: 75,
            }
        });
    }

    // 2. BAR CHART: ANALISIS VENDOR
    var ctxVendor = document.getElementById("vendorChart");
    new Chart(ctxVendor, {
        type: 'bar',
        data: {
            // Perbaikan: Tambahkan pengecekan array agar tidak error jika data kosong
            labels: [<?= !empty($vendor_stats) ? "'" . implode("','", array_column($vendor_stats, 'vendor_name')) . "'" : "'Tidak ada data'"; ?>],
            datasets: [{
                label: "Total Retur (Unit)",
                backgroundColor: "#4e73df",
                data: [<?= !empty($vendor_stats) ? implode(",", array_column($vendor_stats, 'total_retur')) : "0"; ?>],
                yAxisID: 'y-axis-1',
            }, {
                label: "Rata-rata Lama (Hari)",
                backgroundColor: "#f6c23e",
                data: [<?= !empty($vendor_stats) ? implode(",", array_map('round', array_column($vendor_stats, 'avg_processing_days'))) : "0"; ?>],
                yAxisID: 'y-axis-2',
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                yAxes: [
                    { id: 'y-axis-1', type: 'linear', position: 'left', ticks: { beginAtZero: true } },
                    { id: 'y-axis-2', type: 'linear', position: 'right', gridLines: { drawOnChartArea: false }, ticks: { beginAtZero: true, callback: function(value){ return value + ' hr' } } }
                ]
            },
            legend: { position: 'top' },
            tooltips: { mode: 'index', intersect: false }
        }
    });
</script>