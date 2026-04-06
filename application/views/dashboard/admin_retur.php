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
    <!-- disini -->
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
                            <th class="border-0 text-center">Jenis Retur</th>   <!-- BARU -->
                            <th class="border-0 text-center">Status</th>         <!-- BARU -->
                            <th class="border-0 text-center">Lama Inap</th>
                            <th class="border-0 text-center pr-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($list_overdue_30)): foreach ($list_overdue_30 as $row):

                            // Badge Jenis Retur
                            $type_upper = strtoupper(trim($row['type_name'] ?? ''));
                            switch ($type_upper) {
                                case 'KLAIM GARANSI':
                                    $type_badge = 'badge-success';  break;
                                case 'RUSAK':
                                    $type_badge = 'badge-warning';  break;
                                case 'SALAH KIRIM':
                                    $type_badge = 'badge-primary';  break;
                                case 'MASUK KOMPLAIN MARKETPLACE':
                                    $type_badge = 'badge-danger';   break;
                                default:
                                    $type_badge = 'badge-secondary';
                            }

                            // Badge Status
                            switch ($row['status']) {
                                case 'received':       $s_badge = 'badge-primary';   $s_label = 'Received';       break;
                                case 'checking':       $s_badge = 'badge-warning';   $s_label = 'Checking';       break;
                                case 'to_vendor':      $s_badge = 'badge-info';      $s_label = 'To Vendor';      break;
                                case 'processing':     $s_badge = 'badge-info';      $s_label = 'Processing';     break;
                                case 'from_vendor':    $s_badge = 'badge-info';      $s_label = 'From Vendor';    break;
                                case 'ready':          $s_badge = 'badge-success';   $s_label = 'Ready';          break;
                                case 'shipped':        $s_badge = 'badge-success';   $s_label = 'Shipped';        break;
                                case 'completed':      $s_badge = 'badge-success';   $s_label = 'Completed';      break;
                                case 'rejected':       $s_badge = 'badge-dark';      $s_label = 'Rejected';       break;
                                case 'user complain':  $s_badge = 'badge-danger';    $s_label = 'User Complain';  break;
                                case 'aju banding':    $s_badge = 'badge-warning';   $s_label = 'Aju Banding';    break;
                                case 'menang banding': $s_badge = 'badge-success';   $s_label = 'Menang Banding'; break;
                                case 'kalah banding':  $s_badge = 'badge-dark';      $s_label = 'Kalah Banding';  break;
                                default:               $s_badge = 'badge-secondary'; $s_label = $row['status'];
                            }
                        ?>
                        <tr>
                            <td class="pl-4 font-weight-bold text-primary">#<?= $row['return_number']; ?></td>

                            <td class="text-center small">
                                <?= date('d/m/Y', strtotime($row['received_date'])); ?>
                            </td>

                            <td>
                                <div class="font-weight-bold small"><?= $row['store_name']; ?></div>
                                <span class="badge badge-light border text-muted px-2 py-0" style="font-size:0.7rem">
                                    <?= $row['platform_name']; ?>
                                </span>
                            </td>

                            <td class="small"><?= $row['customer_name']; ?></td>

                            <!-- Kolom Jenis Retur -->
                            <td class="text-center">
                                <span class="badge <?= $type_badge; ?> px-2 py-1" style="font-size:0.7rem; border-radius:50px;">
                                    <?= $row['type_name'] ?? '-'; ?>
                                </span>
                            </td>

                            <!-- Kolom Status -->
                            <td class="text-center">
                                <span class="badge <?= $s_badge; ?> px-2 py-1" style="font-size:0.7rem; border-radius:50px; text-transform:uppercase;">
                                    <?= $s_label; ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="text-danger font-weight-bold small">
                                    <i class="far fa-clock"></i> <?= $row['masa_tunggu']; ?> HK
                                </span>
                            </td>

                            <td class="text-center pr-4">
                                <div class="dropdown no-arrow">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                        <a class="dropdown-item" href="<?= base_url('returns/detail/'.$row['id']); ?>">
                                            <i class="fas fa-eye fa-sm fa-fw mr-2 text-primary"></i> Detail
                                        </a>
                                        <a class="dropdown-item" href="<?= base_url('returns/edit/'.$row['id']); ?>">
                                            <i class="fas fa-edit fa-sm fa-fw mr-2 text-warning"></i> Edit Data
                                        </a>
                                        <a class="dropdown-item update-status-btn" href="javascript:void(0)"
                                            data-toggle="modal"
                                            data-target="#updateStatusModal"
                                            data-id="<?= $row['id']; ?>"
                                            data-number="<?= $row['return_number']; ?>"
                                            data-status="<?= $row['status']; ?>"
                                            data-type="<?= strtolower(trim($row['type_name'] ?? '')); ?>">
                                            <i class="fas fa-sync-alt fa-sm fa-fw mr-2 text-info"></i> Update Status
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-success mb-3 d-block"></i>
                                <p class="text-muted font-italic mb-0">Tidak ada barang yang mengendap lebih dari 30 hari.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-left-info shadow">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-info">
                    Update Status: <span id="display_return_number"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?= form_open_multipart('returns/update_status'); ?>
                <div class="modal-body">
                    <input type="hidden" name="id" id="modal_id">

                    <div class="form-group">
                        <label class="font-weight-bold">Status Retur</label>
                        <select name="status" id="modal_status" class="form-control" required
                                onchange="toggleStatusFields(this.value)">
                            <option value="received">Received (Diterima)</option>
                            <option value="checking">Checking (Sedang Dicek)</option>
                            <option value="to_vendor">To Vendor (Kirim ke Vendor)</option>
                            <option value="processing">Processing (Proses Perbaikan)</option>
                            <option value="from_vendor">From Vendor (Kembali dari Vendor)</option>
                            <option value="ready">Ready (Siap Kirim Balik)</option>
                            <option value="shipped">Shipped (Sudah Dikirim)</option>
                            <option value="completed">Completed (Selesai)</option>
                            <option value="rejected">Rejected (Ditolak)</option>
                        </select>
                    </div>

                    <!-- Field Bukti (Checking / From Vendor) -->
                    <div id="field_evidence" style="display:none;" class="p-3 bg-light rounded mb-3 border border-warning">
                        <label class="font-weight-bold text-warning">
                            <i class="fas fa-camera"></i> Upload Foto/Video Bukti
                        </label>
                        <input type="file" name="evidence_files[]" class="form-control-file"
                        multiple accept="image/*,video/*">
                        <small class="text-muted">Bisa pilih banyak file (JPG, PNG, MP4).</small>
                    </div>

                    <!-- Field Alamat (Ready) -->
                    <div id="field_ready" style="display:none;" class="p-3 bg-light rounded mb-3 border">
                        <div class="form-group">
                            <label class="font-weight-bold text-primary">
                                <i class="fas fa-map-marker-alt"></i> Alamat Pengiriman Balik
                            </label>
                            <textarea name="shipping_address" class="form-control" rows="2"
                            placeholder="Masukkan alamat lengkap tujuan..."></textarea>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_different_receiver"
                            name="is_different_receiver"
                            onchange="toggleReceiverDetail(this.checked)">
                            <label class="custom-control-label" for="is_different_receiver">
                                Nama/No. HP Penerima Berbeda?
                            </label>
                        </div>
                        <div id="receiver_detail" style="display:none;" class="mt-3">
                            <div class="row">
                                <div class="col-6">
                                    <label class="small font-weight-bold">Nama Penerima</label>
                                    <input type="text" name="receiver_name" class="form-control form-control-sm">
                                </div>
                                <div class="col-6">
                                    <label class="small font-weight-bold">No. WA Penerima</label>
                                    <input type="text" name="receiver_phone" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Field Ekspedisi & Resi (Shipped) -->
                    <div id="field_shipped" style="display:none;" class="p-3 bg-light rounded mb-3 border border-info">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-info">Ekspedisi</label>
                                    <select name="courier_id" class="form-control">
                                        <option value="">-- Pilih --</option>
                                        <?php foreach ($couriers as $c): ?>
                                            <option value="<?= $c['id']; ?>">
                                                <?= $c['expedition_name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-info">Nomor Resi</label>
                                    <input type="text" name="receipt_number" class="form-control"
                                    placeholder="No. Resi">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold text-info">Tanggal Kirim</label>
                            <input type="date" name="shipping_date" class="form-control"
                            value="<?= date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Keterangan / Catatan</label>
                        <textarea name="keterangan" id="modal_keterangan" class="form-control" rows="2"
                        placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Update Sekarang</button>
                </div>
            <?= form_close(); ?>
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

    // SESUDAH — pakai uppercase, konsisten dengan data-type di HTML
    // Sama persis dengan returns/index.php
    document.addEventListener('DOMContentLoaded', function() {

        const originalStatusHtml = $('#modal_status').html();

        let checkJquery = setInterval(function() {
            if (window.jQuery) {
                clearInterval(checkJquery);

                $(document).on('click', '.update-status-btn', function() {
                    const id     = $(this).data('id');
                    const number = $(this).data('number');
                    const status = $(this).data('status');
                    const type   = String($(this).data('type') || '').toLowerCase().trim(); // ← lowercase

                    $('#modal_id').val(id);
                    $('#display_return_number').text(number);

                    const statusDropdown = $('#modal_status');

                    $('#field_evidence, #field_ready, #field_shipped, #receiver_detail').hide();
                    $('#is_different_receiver').prop('checked', false);

                    // Sama persis dengan index: cek lowercase
                    if (type === 'complain' || type === 'masuk komplain marketplace') {
                        statusDropdown.html(`
                            <option value="user complain">User Complain</option>
                            <option value="aju banding">Aju Banding</option>
                            <option value="menang banding">Menang Banding</option>
                            <option value="kalah banding">Kalah Banding</option>
                        `);
                        statusDropdown.val(status);
                    } else {
                        statusDropdown.html(originalStatusHtml);
                        statusDropdown.val(status);
                        toggleStatusFields(status);
                    }
                });
            }
        }, 100);
    });

    function toggleStatusFields(val) {
        $('#field_ready').hide();
        $('#field_shipped').hide();
        $('#field_evidence').hide();

        const isComplainStatus = ['user complain','aju banding','menang banding','kalah banding'].includes(val);
        if (isComplainStatus) return;

        if      (val === 'ready')                              $('#field_ready').show();
        else if (val === 'shipped')                            $('#field_shipped').show();
        else if (val === 'checking' || val === 'from_vendor')  $('#field_evidence').show();
    }

    function toggleReceiverDetail(isChecked) {
        if (isChecked) $('#receiver_detail').show();
        else           $('#receiver_detail').hide();
    }
</script>