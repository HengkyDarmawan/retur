<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$csrf_name  = $this->security->get_csrf_token_name();
$csrf_hash  = $this->security->get_csrf_hash();

$status_normal = [
    'received'    => 'Received',
    'checking'    => 'Checking',
    'to_vendor'   => 'To Vendor',
    'processing'  => 'Processing',
    'from_vendor' => 'From Vendor',
    'ready'       => 'Ready',
    'shipped'     => 'Shipped',
    'completed'   => 'Completed',
    'rejected'    => 'Rejected',
];
$status_complain = [
    'user complain'  => 'User Complain',
    'aju banding'    => 'Aju Banding',
    'menang banding' => 'Menang Banding',
    'kalah banding'  => 'Kalah Banding',
];

// Kumpulkan semua type_id yang namanya "MASUK KOMPLAIN MARKETPLACE"
// Pakai strtoupper agar case-insensitive
$complain_type_ids = [];
foreach ($return_types as $t) {
    if (strtoupper(trim($t['type_name'])) === 'MASUK KOMPLAIN MARKETPLACE') {
        $complain_type_ids[] = (int)$t['id'];
    }
}
?>

<style>
.draft-field  { min-width: 90px; font-size: 0.78rem; }
.table th     { font-size: 0.78rem; vertical-align: middle; white-space: nowrap; }
.table td     { vertical-align: middle; padding: 4px 6px; }
#save-indicator { font-size: 0.8rem; transition: all 0.3s; }
.saving  { color: #f6c23e; }
.saved   { color: #1cc88a; }
.unsaved { color: #e74a3b; }
</style>

<div class="container-fluid">

    <!-- ── PAGE HEADER ── -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-clipboard-check text-info"></i> Preview Import Retur
            </h1>
            <small class="text-muted">Draft Key: <code><?= $draft_key; ?></code></small>
        </div>
        <div class="d-flex align-items-center flex-wrap">
            <span id="save-indicator" class="mr-3 saved">
                <i class="fas fa-check-circle"></i> Tersimpan di DB
            </span>
            <button type="button" id="btnSaveDraft" class="btn btn-warning btn-sm shadow-sm mr-1">
                <i class="fas fa-save"></i> Simpan Draft
            </button>
            <button type="button" id="btnCancel" class="btn btn-secondary btn-sm shadow-sm mr-1">
                <i class="fas fa-times"></i> Batalkan
            </button>
            <button type="button" id="btnPublish" class="btn btn-success btn-sm shadow-sm">
                <i class="fas fa-cloud-upload-alt"></i> Publish
                <span class="badge badge-light ml-1" id="rowCountBadge"><?= count($draft); ?></span>
            </button>
        </div>
    </div>

    <!-- ── INFO ALERT ── -->
    <div class="alert alert-info border-left-info py-2 shadow-sm small mb-3">
        <i class="fas fa-info-circle"></i>
        <strong>Draft tersimpan di database</strong> — aman jika browser ditutup atau Anda harus pergi sebentar.
        Klik <strong>Simpan Draft</strong> setelah edit, lalu <strong>Publish</strong> saat sudah yakin.
        <br>
        <i class="fas fa-lightbulb text-warning"></i>
        <em>Dropdown <strong>Status</strong> otomatis menyesuaikan saat <strong>Tipe Retur</strong> diubah.</em>
    </div>

    <!-- ── TABEL DRAFT ── -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table"></i> Draft —
                <span id="rowCountText"><?= count($draft); ?></span> baris
            </h6>
            <span class="text-muted small">
                <i class="fas fa-edit"></i> Edit langsung di tabel, lalu klik Simpan Draft
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0" id="draftTable">
                    <thead class="thead-dark">
                        <tr>
                            <th width="35"  class="text-center">#</th>
                            <th>No. Order</th>
                            <th>Customer</th>
                            <th>Nama Barang</th>
                            <th width="125">Tgl Masuk</th>
                            <th width="125">Tgl Beli</th>
                            <th>Vendor</th>
                            <th width="135">Store</th>
                            <th width="135">Platform</th>
                            <th width="175">Tipe Retur</th>
                            <th width="165">Status</th>
                            <th width="50"  class="text-center">
                                <i class="fas fa-trash text-danger"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="draftBody">

                        <?php foreach ($draft as $i => $row):
                            // Cek apakah tipe baris ini adalah MASUK KOMPLAIN MARKETPLACE
                            $is_complain = in_array((int)$row['type_id'], $complain_type_ids);
                            $status_list = $is_complain ? $status_complain : $status_normal;
                        ?>
                        <tr id="draft-row-<?= $i; ?>" data-index="<?= $i; ?>">

                            <!-- Nomor urut -->
                            <td class="text-center text-muted small"><?= $i + 1; ?></td>

                            <!-- No. Order -->
                            <td>
                                <input type="text"
                                       class="form-control form-control-sm draft-field change-tracker"
                                       name="order_number"
                                       value="<?= htmlspecialchars($row['order_number']); ?>">
                            </td>

                            <!-- Customer -->
                            <td>
                                <input type="text"
                                       class="form-control form-control-sm draft-field change-tracker"
                                       name="customer_name"
                                       value="<?= htmlspecialchars($row['customer_name']); ?>">
                            </td>

                            <!-- Nama Barang -->
                            <td>
                                <input type="text"
                                       class="form-control form-control-sm draft-field change-tracker"
                                       name="product_name"
                                       value="<?= htmlspecialchars($row['product_name']); ?>">
                            </td>

                            <!-- Tgl Masuk -->
                            <td>
                                <input type="date"
                                       class="form-control form-control-sm draft-field change-tracker"
                                       name="received_date"
                                       value="<?= $row['received_date']; ?>">
                            </td>

                            <!-- Tgl Beli -->
                            <td>
                                <input type="date"
                                       class="form-control form-control-sm draft-field change-tracker"
                                       name="purchase_date"
                                       value="<?= $row['purchase_date']; ?>">
                            </td>

                            <!-- Vendor -->
                            <td>
                                <input type="text"
                                       class="form-control form-control-sm draft-field change-tracker"
                                       name="vendor_name"
                                       value="<?= htmlspecialchars($row['vendor_name']); ?>">
                            </td>

                            <!-- Store -->
                            <td>
                                <select class="form-control form-control-sm draft-field change-tracker"
                                        name="store_id">
                                    <?php foreach ($stores as $s): ?>
                                    <option value="<?= $s['id']; ?>"
                                            <?= (int)$row['store_id'] === (int)$s['id'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($s['store_name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <!-- Platform -->
                            <td>
                                <select class="form-control form-control-sm draft-field change-tracker"
                                        name="platform_id">
                                    <?php foreach ($platforms as $p): ?>
                                    <option value="<?= $p['id']; ?>"
                                            <?= (int)$row['platform_id'] === (int)$p['id'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($p['platform_name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <!-- Tipe Retur -->
                            <!-- data-complain-id dipakai JS untuk tahu mana ID yang trigger mode complain -->
                            <td>
                                <select class="form-control form-control-sm draft-field change-tracker select-type-id"
                                        name="type_id">
                                    <?php foreach ($return_types as $t): ?>
                                    <option value="<?= $t['id']; ?>"
                                            <?= (int)$row['type_id'] === (int)$t['id'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($t['type_name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <!-- Status — dirender PHP sesuai tipe, diupdate JS saat tipe diubah -->
                            <td>
                                <select class="form-control form-control-sm draft-field change-tracker select-status"
                                        name="status">
                                    <?php foreach ($status_list as $val => $label): ?>
                                    <option value="<?= $val; ?>"
                                            <?= $row['status'] === $val ? 'selected' : ''; ?>>
                                        <?= $label; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <!-- Hapus baris -->
                            <td class="text-center">
                                <button type="button"
                                        class="btn btn-danger btn-sm btn-delete-row"
                                        data-index="<?= $i; ?>"
                                        title="Hapus baris ini">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>

                        </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">
                <i class="fas fa-database text-success"></i>
                Draft disimpan di database — aman meski browser ditutup.
            </small>
            <button type="button" id="btnPublishBottom" class="btn btn-success">
                <i class="fas fa-cloud-upload-alt"></i>
                Publish <span id="rowCountBottom"><?= count($draft); ?></span> Data ke Database
            </button>
        </div>
    </div>

</div><!-- /container-fluid -->

<script>
    const BASE_URL  = '<?= base_url(); ?>';
    const DRAFT_KEY = '<?= $draft_key; ?>';
    const CSRF_NAME = '<?= $csrf_name; ?>';
    let   csrfHash  = '<?= $csrf_hash; ?>';

    // ── Daftar type_id yang masuk mode KOMPLAIN (dari PHP) ──
    // Hanya "MASUK KOMPLAIN MARKETPLACE" yang masuk sini
    const COMPLAIN_TYPE_IDS = <?= json_encode(array_values($complain_type_ids)); ?>;

    // ── Definisi status per grup ──
    const STATUS_NORMAL = {
        'received'    : 'Received',
        'checking'    : 'Checking',
        'to_vendor'   : 'To Vendor',
        'processing'  : 'Processing',
        'from_vendor' : 'From Vendor',
        'ready'       : 'Ready',
        'shipped'     : 'Shipped',
        'completed'   : 'Completed',
        'rejected'    : 'Rejected',
    };
    const STATUS_COMPLAIN = {
        'user complain'  : 'User Complain',
        'aju banding'    : 'Aju Banding',
        'menang banding' : 'Menang Banding',
        'kalah banding'  : 'Kalah Banding',
    };

    // ============================================================
    // Helper: AJAX POST dengan CSRF otomatis (fix 403 CI3)
    // ============================================================
    function ajaxPost(url, data) {
        data[CSRF_NAME] = csrfHash;
        const body = Object.entries(data)
            .map(([k, v]) => encodeURIComponent(k) + '=' + encodeURIComponent(v))
            .join('&');

        return fetch(BASE_URL + url, {
            method : 'POST',
            headers: {
                'Content-Type'     : 'application/x-www-form-urlencoded',
                'X-Requested-With' : 'XMLHttpRequest'
            },
            body
        })
        .then(r => r.json())
        .then(res => {
            // Rotasi CSRF hash CI3 setiap request
            if (res.csrf_hash) csrfHash = res.csrf_hash;
            return res;
        });
    }

    // ============================================================
    // Kumpulkan semua baris dari DOM untuk dikirim ke server
    // ============================================================
    function collectRows() {
        const rows = [];
        document.querySelectorAll('#draftBody tr').forEach(tr => {
            const get = name => tr.querySelector(`[name="${name}"]`)?.value ?? '';
            rows.push({
                order_number    : get('order_number'),
                customer_name   : get('customer_name'),
                product_name    : get('product_name'),
                received_date   : get('received_date'),
                purchase_date   : get('purchase_date'),
                vendor_name     : get('vendor_name'),
                warranty_expiry : get('purchase_date'), // controller hitung ulang dari purchase_date
                store_id        : get('store_id'),
                platform_id     : get('platform_id'),
                type_id         : get('type_id'),
                status          : get('status'),
            });
        });
        return rows;
    }

    // ============================================================
    // Update semua counter badge jumlah baris
    // ============================================================
    function updateCount() {
        const n = document.querySelectorAll('#draftBody tr').length;
        ['rowCountBadge', 'rowCountText', 'rowCountBottom'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = n;
        });
    }

    // ============================================================
    // Indikator save state (Tersimpan / Menyimpan / Belum disimpan)
    // ============================================================
    const indicator = document.getElementById('save-indicator');

    function markUnsaved() {
        indicator.className = 'mr-3 unsaved';
        indicator.innerHTML = '<i class="fas fa-exclamation-circle"></i> Ada perubahan belum disimpan';
    }
    function markSaving() {
        indicator.className = 'mr-3 saving';
        indicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    }
    function markSaved(time) {
        indicator.className = 'mr-3 saved';
        indicator.innerHTML = `<i class="fas fa-check-circle"></i> Tersimpan${time ? ' pukul ' + time : ''}`;
    }

    // Tandai unsaved saat ada input/change pada field biasa
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('change-tracker')) markUnsaved();
    });
    document.addEventListener('change', function(e) {
        // change-tracker biasa (bukan select-type-id — itu punya handler sendiri di bawah)
        if (e.target.classList.contains('change-tracker') &&
            !e.target.classList.contains('select-type-id')) {
            markUnsaved();
        }
    });

    // ============================================================
    // Rebuild dropdown Status saat Tipe Retur diubah
    // Aturan: hanya MASUK KOMPLAIN MARKETPLACE → status complain
    //         semua tipe lain                  → status normal
    // ============================================================
    function buildStatusOptions(statusMap, selectedValue) {
        return Object.entries(statusMap)
            .map(([val, label]) =>
                `<option value="${val}" ${val === selectedValue ? 'selected' : ''}>${label}</option>`
            ).join('');
    }

    function rebuildStatusDropdown(typeSelect) {
        const typeId       = parseInt(typeSelect.value, 10);
        const isComplain   = COMPLAIN_TYPE_IDS.includes(typeId);
        const tr           = typeSelect.closest('tr');
        const statusSelect = tr.querySelector('select[name="status"]');
        if (!statusSelect) return;

        const currentStatus = statusSelect.value;
        const statusMap     = isComplain ? STATUS_COMPLAIN : STATUS_NORMAL;

        // Pertahankan status lama kalau masih valid di grup baru
        const keepCurrent   = Object.keys(statusMap).includes(currentStatus);
        const defaultStatus = isComplain ? 'user complain' : 'received';

        statusSelect.innerHTML = buildStatusOptions(
            statusMap,
            keepCurrent ? currentStatus : defaultStatus
        );
    }

    // Event: type_id berubah → rebuild status dropdown + tandai unsaved
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('select-type-id')) {
            rebuildStatusDropdown(e.target);
            markUnsaved();
        }
    });

    // ============================================================
    // Simpan Draft ke DB (tanpa publish)
    // ============================================================
    function saveDraft(silent = false) {
        const rows = collectRows();
        markSaving();

        return ajaxPost('returns/import_draft_save', {
            draft_key : DRAFT_KEY,
            rows      : JSON.stringify(rows)
        }).then(res => {
            if (res.success) {
                markSaved(res.saved_at);
                if (!silent) {
                    Swal.fire({
                        toast             : true,
                        position          : 'top-end',
                        icon              : 'success',
                        title             : 'Draft berhasil disimpan!',
                        showConfirmButton  : false,
                        timer             : 2000
                    });
                }
            } else {
                markUnsaved();
                if (!silent) Swal.fire('Gagal', res.message || 'Gagal menyimpan draft', 'error');
            }
            return res;
        }).catch(() => { markUnsaved(); });
    }

    document.getElementById('btnSaveDraft').addEventListener('click', () => saveDraft(false));

    // Auto-save diam-diam setiap 60 detik jika ada perubahan
    setInterval(() => {
        if (indicator.classList.contains('unsaved')) saveDraft(true);
    }, 60000);

    // Peringatan sebelum tutup tab kalau ada unsaved changes
    // Pakai window.onbeforeunload agar bisa di-null-kan saat redirect programatis
    window.onbeforeunload = function(e) {
        if (indicator.classList.contains('unsaved')) {
            e.preventDefault();
            e.returnValue = '';
        }
    };

    // ============================================================
    // Hapus satu baris dari draft
    // ============================================================
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete-row');
        if (!btn) return;

        Swal.fire({
            title             : 'Hapus baris ini?',
            text              : 'Baris akan langsung dihapus dari draft di database.',
            icon              : 'warning',
            showCancelButton  : true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor : '#6c757d',
            confirmButtonText : 'Ya, Hapus!',
            cancelButtonText  : 'Batal'
        }).then(result => {
            if (!result.isConfirmed) return;

            const index = btn.dataset.index;

            ajaxPost('returns/import_draft_delete_row', {
                draft_key : DRAFT_KEY,
                row_index : index
            }).then(res => {
                if (res.success) {
                    document.getElementById('draft-row-' + index)?.remove();
                    updateCount();

                    if (res.remaining === 0) {
                        Swal.fire('Draft Kosong', 'Semua baris telah dihapus.', 'info')
                            .then(() => {
                                window.onbeforeunload = null;
                                window.location.href = BASE_URL + 'returns';
                            });
                    } else {
                        Swal.fire({
                            toast             : true,
                            position          : 'top-end',
                            icon              : 'success',
                            title             : 'Baris dihapus',
                            showConfirmButton  : false,
                            timer             : 1500
                        });
                    }
                }
            });
        });
    });

    // ============================================================
    // Batalkan seluruh draft (AJAX → hapus dari DB → redirect)
    // ============================================================
    document.getElementById('btnCancel').addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        Swal.fire({
            title             : 'Batalkan Import?',
            text              : 'Semua data draft akan dihapus permanen dari database.',
            icon              : 'warning',
            showCancelButton  : true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor : '#6c757d',
            confirmButtonText : 'Ya, Batalkan!',
            cancelButtonText  : 'Kembali'
        }).then(result => {
            if (!result.isConfirmed) return;

            ajaxPost('returns/import_draft_cancel_ajax', {
                draft_key: DRAFT_KEY
            }).then(() => {
                window.onbeforeunload = null;
                window.location.href = BASE_URL + 'returns';
            }).catch(() => {
                // Fallback: navigasi biasa kalau AJAX gagal
                window.onbeforeunload = null;
                window.location.href = BASE_URL + 'returns/import_draft_cancel';
            });
        });
    });

    // ============================================================
    // Publish ke database
    // ============================================================
    function doPublish() {
        const count = document.querySelectorAll('#draftBody tr').length;
        if (count === 0) {
            Swal.fire('Kosong!', 'Tidak ada data untuk dipublish.', 'warning');
            return;
        }

        const confirmPublish = () => {
            Swal.fire({
                title             : `Publish ${count} data?`,
                html              : `Data akan <strong>langsung disimpan ke database</strong> dan draft dihapus otomatis.`,
                icon              : 'question',
                showCancelButton  : true,
                confirmButtonColor: '#1cc88a',
                cancelButtonColor : '#6c757d',
                confirmButtonText : '<i class="fas fa-cloud-upload-alt"></i> Ya, Publish!',
                cancelButtonText  : 'Cek Lagi',
            }).then(result => {
                if (!result.isConfirmed) return;

                Swal.fire({
                    title             : 'Memproses...',
                    html              : `Menyimpan <b>${count}</b> data retur ke database...`,
                    allowOutsideClick : false,
                    didOpen           : () => Swal.showLoading()
                });

                ajaxPost('returns/import_publish', {
                    draft_key: DRAFT_KEY
                }).then(res => {
                    if (res.success) {
                        Swal.fire({
                            icon              : 'success',
                            title             : 'Berhasil!',
                            html              : `<b>${res.count}</b> data retur berhasil disimpan ke database.`,
                            confirmButtonColor: '#1cc88a'
                        }).then(() => {
                            window.onbeforeunload = null;
                            window.location.href = BASE_URL + 'returns';
                        });
                    } else {
                        Swal.fire('Gagal!', res.message || 'Terjadi kesalahan saat menyimpan.', 'error');
                    }
                }).catch(() => {
                    Swal.fire('Error!', 'Koneksi ke server gagal. Coba lagi.', 'error');
                });
            });
        };

        // Jika ada perubahan belum disimpan, tanya dulu
        if (indicator.classList.contains('unsaved')) {
            Swal.fire({
                title             : 'Ada perubahan belum disimpan',
                text              : 'Simpan draft terlebih dahulu sebelum publish?',
                icon              : 'warning',
                showCancelButton  : true,
                confirmButtonText : 'Simpan & Publish',
                cancelButtonText  : 'Publish Apa Adanya',
                confirmButtonColor: '#f6c23e',
            }).then(result => {
                if (result.isConfirmed) {
                    saveDraft(true).then(() => confirmPublish());
                } else {
                    confirmPublish();
                }
            });
        } else {
            confirmPublish();
        }
    }

    document.getElementById('btnPublish').addEventListener('click', doPublish);
    document.getElementById('btnPublishBottom').addEventListener('click', doPublish);
</script>