<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <?php 
    // ── BANNER DRAFT AKTIF ──
    if (!empty($active_drafts)): 
    ?>
    <div class="card border-left-warning shadow mb-4" id="draftBanner">
        <div class="card-body py-3">
            <div class="d-flex align-items-start">
                <div class="mr-3">
                    <i class="fas fa-drafting-compass fa-2x text-warning"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="font-weight-bold text-warning mb-1">
                        <i class="fas fa-exclamation-triangle"></i>
                        Anda memiliki <?= count($active_drafts); ?> draft import yang belum dipublish
                    </h6>
                    <p class="text-muted small mb-2">
                        Data di bawah sudah tersimpan di database sebagai draft. Lanjutkan review dan publish sebelum data hilang atau tertimpa import baru.
                    </p>

                    <!-- Daftar draft -->
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0" style="max-width: 700px;">
                            <thead>
                                <tr class="text-muted" style="font-size:0.75rem;">
                                    <th>Draft Key</th>
                                    <th class="text-center">Jumlah Baris</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($active_drafts as $d): ?>
                                <tr style="font-size:0.8rem;">
                                    <td>
                                        <code class="text-warning"><?= $d['draft_key']; ?></code>
                                        <?php if ($active_draft_key === $d['draft_key']): ?>
                                            <span class="badge badge-warning ml-1">Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-secondary"><?= $d['total_rows']; ?> baris</span>
                                    </td>
                                    <td class="text-muted">
                                        <?= date('d/m/Y H:i', strtotime($d['created_at'])); ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('returns/resume_draft/' . $d['draft_key']); ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-pencil-alt"></i> Lanjutkan
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm btn-discard-draft"
                                                data-key="<?= $d['draft_key']; ?>">
                                            <i class="fas fa-trash"></i> Buang
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <button type="button" class="close ml-3" id="closeDraftBanner" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php // ── END BANNER ── ?>

    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter"></i> Filter Data</h6>
        </div>
        <div class="card-body">
            <form action="<?= base_url('returns'); ?>" method="GET">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label>Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control" value="<?= $filter['start_date'] ?? '' ?>">
                    </div>
                    <div class="col-md-2 mb-3">
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
                            
                            <option value="user complain" <?= $filter['status'] == 'user complain' ? 'selected' : '' ?>>User Complain</option>
                            <option value="aju banding" <?= $filter['status'] == 'aju banding' ? 'selected' : '' ?>>Aju Banding</option>
                            <option value="menang banding" <?= $filter['status'] == 'menang banding' ? 'selected' : '' ?>>Menang Banding</option>
                            <option value="kalah banding" <?= $filter['status'] == 'kalah banding' ? 'selected' : '' ?>>Kalah Banding</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Tipe Retur</label>
                        <select name="type_id" class="form-control">
                            <option value="">-- Semua --</option>
                            <?php foreach($return_types as $t): ?>
                                <option value="<?= $t['id']; ?>" <?= $filter['type_id'] == $t['id'] ? 'selected' : '' ?>>
                                    <?= $t['type_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Lama di Sistem</label>
                        <select name="duration" class="form-control">
                            <option value="">-- Semua --</option>
                            <option value="14" <?= $filter['duration'] == '14' ? 'selected' : '' ?>>>= 14 Hari Kerja</option>
                            <option value="21" <?= $filter['duration'] == '21' ? 'selected' : '' ?>>>= 21 Hari Kerja (Warning)</option>
                            <option value="30" <?= $filter['duration'] == '30' ? 'selected' : '' ?>>>= 30 Hari Kerja (Overdue)</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>&nbsp;</label>
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i></button>
                            <button type="submit" name="export" value="excel" class="btn btn-success ml-1" title="Export Excel">
                                <i class="fas fa-file-excel"></i>
                            </button>
                            <button type="button" class="btn btn-info ml-1" data-toggle="modal" data-target="#importExcelModal" title="Import Excel">
                                <i class="fas fa-file-import"></i>
                            </button>
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
                            <th>Tipe Retur</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($returns as $r) : 
                            
                            // BARIS CONTINUE SUDAH DIHAPUS AGAR DATA MUNCUL
                            
                            // --- PERBAIKAN LOGIKA BADGE SESUAI CSS ---
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
                                
                                // --- STATUS KOMPLAIN BARU ---
                                case 'user complain':
                                    $badge = ['class' => 'badge-danger', 'label' => 'User Complain']; 
                                    break;
                                case 'aju banding':
                                    $badge = ['class' => 'badge-warning', 'label' => 'Aju Banding']; 
                                    break;
                                case 'menang banding':
                                    $badge = ['class' => 'badge-success', 'label' => 'Menang Banding']; 
                                    break;
                                case 'kalah banding':
                                    $badge = ['class' => 'badge-dark', 'label' => 'Kalah Banding']; 
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

                            // Hitung Aging (Mengambil data Hari Kerja dari Controller)
                            $selisih = $r['working_day_age'] ?? 0;
                            $aging_class = ($selisih >= 21) ? 'text-danger font-weight-bold' : (($selisih >= 14) ? 'text-warning font-weight-bold' : 'text-muted');
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <span class="badge badge-primary mb-1">#<?= $r['return_number']; ?></span><br>
                                <small class="text-muted"><i class="fas fa-receipt fa-fw"></i> <?= $r['order_number']; ?></small>
                            </td>
                            <td data-order="<?= $r['received_date']; ?>">
                                <?= format_indo($r['received_date']); ?><br>
                                <small class="<?= $aging_class; ?>" title="Dihitung berdasarkan hari kerja (Sabtu, Minggu & Libur Nasional dikecualikan)">
                                    <i class="far fa-clock"></i> <?= $selisih; ?> Hari Kerja
                                </small>
                            </td>
                            <td>
                                <strong><?= $r['customer_name']; ?></strong><br>
                                <small class="text-primary"><i class="fab fa-whatsapp"></i> <?= $r['customer_wa']; ?></small>
                            </td>
                            <td>
                                <span class="small font-weight-bold"><?= $r['store_name']; ?></span><br>
                                <span class="badge badge-light border"><?= $r['platform_name']; ?></span>
                            </td>
                            <td>
                                <span class="badge badge-primary mb-1">#<?= $r['type_name']; ?></span><br>
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
                                            <a class="dropdown-item" href="<?= base_url('returns/edit/'.$r['id']); ?>">
                                                <i class="fas fa-edit fa-sm fa-fw mr-2 text-warning"></i> Edit Data
                                            </a>
                                            <a class="dropdown-item update-status-btn" href="javascript:void(0)" 
                                                data-toggle="modal" 
                                                data-target="#updateStatusModal" 
                                                data-id="<?= $r['id']; ?>" 
                                                data-number="<?= $r['return_number']; ?>" 
                                                data-status="<?= $r['status']; ?>"
                                                data-type="<?= strtolower($r['type_name'] ?? ''); ?>">
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

<!-- update modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-left-info shadow">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-info">Update Status: <span id="display_return_number"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart('returns/update_status'); ?>
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
                    <div id="field_evidence" style="display:none;" class="p-3 bg-light rounded mb-3 border border-warning">
                        <label class="font-weight-bold text-warning"><i class="fas fa-camera"></i> Upload Foto/Video Bukti</label>
                        <input type="file" name="evidence_files[]" class="form-control-file" multiple accept="image/*,video/*">
                        <small class="text-muted">Bisa pilih banyak file sekaligus (Format: JPG, PNG, MP4).</small>
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
<!-- import data -->
<div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-left-info shadow">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-info"><i class="fas fa-file-import"></i> Import Data Retur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart('returns/import_preview'); ?>
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <strong>Format Excel (Mulai dari Baris ke-2):</strong><br>
                        Kolom A: No Order | Kolom B: Nama | Kolom C: Tanggal Masuk (YYYY-MM-DD) | Kolom D: Tanggal Pembelian (YYYY-MM-DD) | Kolom E: Nama Barang | Kolom F: Vendor
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">File Excel (.xlsx)</label>
                            <input type="file" name="file_excel" class="form-control-file border p-1" accept=".xlsx, .xls" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Status Default</label>
                            <select name="status" class="form-control" required>
                                <option value="received">Received (Diterima)</option>
                                <option value="checking">Checking (Sedang Dicek)</option>
                                <option value="user complain">User Complain</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Jenis Retur</label>
                            <select name="type_id" class="form-control" required>
                                <?php foreach($return_types as $t): ?>
                                    <option value="<?= $t['id']; ?>"><?= $t['type_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Masa Garansi</label>
                            <select name="warranty_duration" class="form-control" required>
                                <option value="0.5">6 Bulan</option>
                                <?php for($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?= $i; ?>" <?= ($i == 1) ? 'selected' : ''; ?>><?= $i; ?> Tahun</option>
                                <?php endfor; ?>
                                <option value="0">Habis / Tanpa Garansi</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Store / Toko</label>
                            <select name="store_id" class="form-control" required>
                                <?php foreach($stores as $s): ?>
                                    <option value="<?= $s['id']; ?>"><?= $s['store_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Platform</label>
                            <select name="platform_id" class="form-control" required>
                                <?php foreach($platforms as $p): ?>
                                    <option value="<?= $p['id']; ?>"><?= $p['platform_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info"><i class="fas fa-upload"></i> Proses Import</button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script>
    // ── DRAFT BANNER ACTIONS ──

    // Tombol X untuk sembunyikan banner sementara (tidak hapus draft)
    const closeBanner = document.getElementById('closeDraftBanner');
        if (closeBanner) {
            closeBanner.addEventListener('click', function() {
                document.getElementById('draftBanner').style.display = 'none';
            });
        }

    // Tombol Buang Draft
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-discard-draft');
        if (!btn) return;

        const draftKey = btn.dataset.key;

        Swal.fire({
            title: 'Buang draft ini?',
            html: `Draft <code>${draftKey}</code> akan dihapus permanen.<br>Data yang belum dipublish akan hilang.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Buang!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (!result.isConfirmed) return;

            const csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
            const csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

            fetch('<?= base_url('returns/import_draft_cancel_ajax'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `draft_key=${encodeURIComponent(draftKey)}&${csrfName}=${csrfHash}`
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    // Hapus baris dari tabel banner
                    btn.closest('tr').remove();

                    // Jika tidak ada draft tersisa, sembunyikan seluruh banner
                    const remaining = document.querySelectorAll('.btn-discard-draft').length;
                    if (remaining === 0) {
                        const banner = document.getElementById('draftBanner');
                        if (banner) banner.remove();
                    }

                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success',
                        title: 'Draft berhasil dibuang', showConfirmButton: false, timer: 2000
                    });
                }
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Simpan template status original saat halaman pertama kali dimuat
        const originalStatusHtml = $('#modal_status').html();

        let checkJquery = setInterval(function() {
            if (window.jQuery) {
                clearInterval(checkJquery);
                
                // FIX: Menggunakan Event Delegation (document on click) 
                // Agar tombol di halaman 2, 3, dst tetap jalan.
                $(document).on('click', '.update-status-btn', function() {
                    const id = $(this).data('id');
                    const number = $(this).data('number');
                    const status = $(this).data('status');
                    const type = $(this).data('type'); 

                    // Set data ke input modal
                    $('#modal_id').val(id);
                    $('#display_return_number').text(number);
                    
                    const statusDropdown = $('#modal_status');
                    
                    // Reset UI: Sembunyikan semua field opsional dulu
                    $('#field_evidence, #field_ready, #field_shipped, #receiver_detail').hide();
                    $('#is_different_receiver').prop('checked', false);

                    if (type === 'complain' || type === 'masuk komplain marketplace') {
                        // Ganti Pilihan Status khusus Complain
                        statusDropdown.html(`
                            <option value="user complain">User Complain</option>
                            <option value="aju banding">Aju Banding</option>
                            <option value="menang banding">Menang Banding</option>
                            <option value="kalah banding">Kalah Banding</option>
                        `);
                        statusDropdown.val(status);
                    } else {
                        // Balikkan ke status normal (Received, Checking, dll)
                        statusDropdown.html(originalStatusHtml);
                        statusDropdown.val(status);
                        
                        // Jalankan toggle untuk memunculkan field (Resi/Alamat) jika statusnya Ready/Shipped/Checking
                        toggleStatusFields(status);
                    }
                });
            }
        }, 100);
    });

    // Fungsi ini dipanggil saat dropdown status diubah manual atau saat modal dibuka
    function toggleStatusFields(val) {
        $('#field_ready').hide();
        $('#field_shipped').hide();
        $('#field_evidence').hide();
        
        const isComplainStatus = ['user complain', 'aju banding', 'menang banding', 'kalah banding'].includes(val);
        if (isComplainStatus) return;

        if (val === 'ready') {
            $('#field_ready').show();
        } else if (val === 'shipped') {
            $('#field_shipped').show();
        } else if (val === 'checking' || val === 'from_vendor') {
            $('#field_evidence').show();
        }
    }

    function toggleReceiverDetail(isChecked) {
        if(isChecked) $('#receiver_detail').show();
        else $('#receiver_detail').hide();
    }
</script>