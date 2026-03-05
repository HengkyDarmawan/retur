<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter"></i> Filter Data</h6>
        </div>
        <div class="card-body">
            <form action="<?= base_url('returns'); ?>" method="GET">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control" value="<?= $filter['start_date'] ?? '' ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control" value="<?= $filter['end_date'] ?? '' ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">-- Semua --</option>
                            <option value="received" <?= $filter['status'] == 'received' ? 'selected' : '' ?>>Received</option>
                            <option value="checking" <?= $filter['status'] == 'checking' ? 'selected' : '' ?>>Checking</option>
                            <option value="to_vendor" <?= $filter['status'] == 'to_vendor' ? 'selected' : '' ?>>To Vendor</option>
                            <option value="processing" <?= $filter['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                            <option value="from_vendor" <?= $filter['status'] == 'from_vendor' ? 'selected' : '' ?>>From Vendor</option>
                            <option value="ready" <?= $filter['status'] == 'ready' ? 'selected' : '' ?>>Ready</option>
                            <option value="shipped" <?= $filter['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                            <option value="completed" <?= $filter['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="rejected" <?= $filter['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Lama di Sistem</label>
                        <select name="duration" class="form-control">
                            <option value="">-- Semua --</option>
                            <option value="3" <?= $filter['duration'] == '3' ? 'selected' : '' ?>>>= 3 Hari</option>
                            <option value="7" <?= $filter['duration'] == '7' ? 'selected' : '' ?>>>= 7 Hari (Peringatan)</option>
                            <option value="14" <?= $filter['duration'] == '14' ? 'selected' : '' ?>>>= 14 Hari (Overdue)</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>&nbsp;</label>
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i></button>
                            <a href="<?= base_url('returns'); ?>" class="btn btn-secondary ml-1"><i class="fas fa-undo"></i></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Barang Retur</h6>
            <a href="<?= base_url('returns/add'); ?>" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm"></i> Tambah Retur
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Retur</th>
                            <th>Tgl Masuk</th>
                            <th>Customer</th>
                            <th>Store/Platform</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($returns as $r) : 
                            // --- PERBAIKAN LOGIKA BADGE SESUAI CSS KAMU ---
                            switch ($r['status']) {
                                case 'received':
                                    $badge = ['class' => 'badge-received', 'label' => 'Received']; 
                                    break;
                                case 'checking':
                                    $badge = ['class' => 'badge-checking', 'label' => 'Checking']; 
                                    break;
                                case 'ready':
                                    $badge = ['class' => 'badge-ready', 'label' => 'Ready']; 
                                    break;
                                case 'shipped':
                                    $badge = ['class' => 'badge-shipped', 'label' => 'Shipped']; 
                                    break;
                                case 'completed':
                                    $badge = ['class' => 'badge-completed', 'label' => 'Completed']; 
                                    break;
                                case 'rejected':
                                    $badge = ['class' => 'badge-rejected', 'label' => 'Rejected']; 
                                    break;
                                // Status yang tidak ada di CSS spesifik dialirkan ke processing
                                case 'to_vendor':
                                case 'processing':
                                case 'from_vendor':
                                    $badge = ['class' => 'badge-processing', 'label' => str_replace('_', ' ', $r['status'])]; 
                                    break;
                                default:
                                    $badge = ['class' => 'badge-light', 'label' => $r['status']]; 
                                    break;
                            }

                            // Hitung Aging (Lama Barang)
                            $tgl_masuk = new DateTime($r['received_date']);
                            $hari_ini  = new DateTime();
                            $selisih   = $hari_ini->diff($tgl_masuk)->days;
                            $aging_class = ($selisih >= 14) ? 'text-danger font-weight-bold' : (($selisih >= 7) ? 'text-warning font-weight-bold' : 'text-muted');
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <span class="badge badge-primary mb-1">#<?= $r['return_number']; ?></span><br>
                                <small class="text-muted"><i class="fas fa-receipt fa-fw"></i> <?= $r['order_number']; ?></small>
                            </td>
                            <td data-order="<?= $r['received_date']; ?>">
                                <?= date('d/m/Y', strtotime($r['received_date'])); ?><br>
                                <small class="<?= $aging_class; ?>"><i class="far fa-clock"></i> <?= $selisih; ?> Hari</small>
                            </td>
                            <td>
                                <strong><?= $r['customer_name']; ?></strong><br>
                                <small class="text-primary"><i class="fab fa-whatsapp"></i> <?= $r['customer_wa']; ?></small>
                            </td>
                            <td>
                                <span class="small font-weight-bold"><?= $r['store_name']; ?></span><br>
                                <span class="badge badge-light border"><?= $r['platform_name']; ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge <?= $badge['class']; ?> px-3 py-2 text-uppercase" style="font-size: 0.7rem; border-radius: 50px;">
                                    <?= $badge['label']; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($r['status'] == 'completed' || $r['status'] == 'rejected') : ?>
                                    <div class="dropdown no-arrow">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                            <a class="dropdown-item" href="<?= base_url('returns/detail/'.$r['id']); ?>">
                                                <i class="fas fa-eye fa-sm fa-fw mr-2 text-primary"></i> Detail
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item font-weight-bold text-success" href="javascript:void(0)" 
                                                onclick="sendWaGlobal(
                                                    '<?= $r['customer_wa']; ?>', 
                                                    '<?= $r['return_number']; ?>', 
                                                    '<?= $r['status']; ?>', 
                                                    '<?= addslashes($r['customer_name']); ?>', 
                                                    '<?= base_url(); ?>',
                                                    '<?= ($r['status'] == 'shipped') 
                                                        ? ($r['expedition_name'] ?? 'Kurir') . ' (' . ($r['receipt_number'] ?? '-') . ')' 
                                                        : addslashes($r['keterangan'] ?? ''); ?>'
                                                )">
                                                <i class="fab fa-whatsapp fa-sm fa-fw mr-2"></i> Notif via WA
                                            </a>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="dropdown no-arrow">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                            <a class="dropdown-item" href="<?= base_url('returns/detail/'.$r['id']); ?>">
                                                <i class="fas fa-eye fa-sm fa-fw mr-2 text-primary"></i> Detail
                                            </a>
                                            <a class="dropdown-item update-status-btn" href="javascript:void(0)" 
                                                data-toggle="modal" data-target="#updateStatusModal" 
                                                data-id="<?= $r['id']; ?>" 
                                                data-number="<?= $r['return_number']; ?>" 
                                                data-status="<?= $r['status']; ?>">
                                                <i class="fas fa-sync-alt fa-sm fa-fw mr-2 text-info"></i> Update Status
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item font-weight-bold text-success" href="javascript:void(0)" 
                                                onclick="sendWaGlobal(
                                                    '<?= $r['customer_wa']; ?>', 
                                                    '<?= $r['return_number']; ?>', 
                                                    '<?= $r['status']; ?>', 
                                                    '<?= addslashes($r['customer_name']); ?>', 
                                                    '<?= base_url(); ?>',
                                                    '<?= ($r['status'] == 'shipped') 
                                                        ? ($r['expedition_name'] ?? 'Kurir') . ' (' . ($r['receipt_number'] ?? '-') . ')' 
                                                        : addslashes($r['keterangan'] ?? ''); ?>'
                                                )">
                                                <i class="fab fa-whatsapp fa-sm fa-fw mr-2"></i> Notif via WA
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
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
                <h5 class="modal-title font-weight-bold text-info">Update Status: <span id="display_return_number"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('returns/update_status'); ?>
                <div class="modal-body">
                    <input type="hidden" name="id" id="modal_id">
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Status Retur</label>
                        <select name="status" id="modal_status" class="form-control" required onchange="toggleStatusFields(this.value)">
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

                    <div id="field_ready" style="display:none;" class="p-3 bg-light rounded mb-3 border">
                        <div class="form-group">
                            <label class="font-weight-bold text-primary"><i class="fas fa-map-marker-alt"></i> Alamat Pengiriman Balik</label>
                            <textarea name="shipping_address" class="form-control" rows="2" placeholder="Masukkan alamat lengkap tujuan pengiriman balik..."></textarea>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_different_receiver" name="is_different_receiver" onchange="toggleReceiverDetail(this.checked)">
                            <label class="custom-control-label" for="is_different_receiver">Nama/No. HP Penerima Berbeda?</label>
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

                    <div id="field_shipped" style="display:none;" class="p-3 bg-light rounded mb-3 border border-info">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-info">Ekspedisi</label>
                                    <select name="courier_id" class="form-control">
                                        <option value="">-- Pilih --</option>
                                        <?php foreach($couriers as $c): ?>
                                            <option value="<?= $c['id']; ?>"><?= $c['expedition_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-info">Nomor Resi</label>
                                    <input type="text" name="receipt_number" class="form-control" placeholder="No. Resi">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold text-info">Tanggal Kirim</label>
                            <input type="date" name="shipping_date" class="form-control" value="<?= date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Keterangan / Catatan</label>
                        <textarea name="keterangan" id="modal_keterangan" class="form-control" rows="2" placeholder="Tuliskan info seperti 'Salah Kirim' di sini jika perlu..."></textarea>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    let checkJquery = setInterval(function() {
        if (window.jQuery) {
            clearInterval(checkJquery);
            $('.update-status-btn').on('click', function() {
                const id = $(this).data('id');
                const number = $(this).data('number');
                const status = $(this).data('status');

                $('#modal_id').val(id);
                $('#display_return_number').text(number);
                $('#modal_status').val(status);
                
                toggleStatusFields(status);
            });
        }
    }, 100);
});

function toggleStatusFields(val) {
    const fieldReady = document.getElementById('field_ready');
    const fieldShipped = document.getElementById('field_shipped');
    
    // Default sembunyikan semua panel tambahan
    fieldReady.style.display = 'none';
    fieldShipped.style.display = 'none';

    // Panel Alamat HANYA muncul jika status 'ready'
    // (from_vendor tidak perlu input alamat lagi)
    if (val === 'ready') {
        fieldReady.style.display = 'block';
    } 
    // Panel Kurir muncul jika status 'shipped'
    else if (val === 'shipped') {
        fieldShipped.style.display = 'block';
    }
}

function toggleReceiverDetail(isChecked) {
    const detail = document.getElementById('receiver_detail');
    detail.style.display = isChecked ? 'block' : 'none';
}
</script>