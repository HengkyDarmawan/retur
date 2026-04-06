<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cek Status Retur</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body { background-color: #f0f2f8; font-family: 'Nunito', sans-serif; }

        /* ── Header ── */
        .hero {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            padding: 70px 0 110px;
            color: #fff;
            text-align: center;
        }
        .hero h1 { font-size: 1.8rem; font-weight: 800; margin-bottom: 6px; }
        .hero p  { opacity: .85; font-size: .95rem; }

        /* ── Search card ── */
        .search-card {
            margin-top: -60px;
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(0,0,0,.1);
            border: none;
        }
        .search-card .input-group-lg .form-control {
            border-radius: 10px 0 0 10px !important;
            border: 1.5px solid #d1d3e2;
            font-size: 1rem;
        }
        .search-card .btn-primary {
            border-radius: 0 10px 10px 0 !important;
            font-weight: 700;
            padding: 0 28px;
        }

        /* ── Status banner ── */
        .status-banner {
            padding: 28px 20px 22px;
            text-align: center;
            border-bottom: 2px dashed rgba(0,0,0,.08);
        }
        .status-banner .status-icon {
            width: 64px; height: 64px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center; justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 10px;
        }
        .status-banner h3 { font-weight: 800; font-size: 1.3rem; margin: 0; }
        .status-banner small { font-size: .78rem; opacity: .7; }

        /* ── Info rows ── */
        .info-block { padding: 14px 0; border-bottom: 1px solid #f0f0f0; }
        .info-block:last-child { border-bottom: none; }
        .info-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: #f0f3ff;
            color: #4e73df;
            display: inline-flex;
            align-items: center; justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            margin-right: 14px;
        }
        .info-label { font-size: .75rem; color: #9095a0; font-weight: 700; text-transform: uppercase; margin-bottom: 2px; }
        .info-value { font-size: .95rem; color: #2d3748; font-weight: 700; }

        /* ── Komentar admin ── */
        .admin-note {
            background: #fff8e1;
            border-left: 4px solid #f6c23e;
            border-radius: 0 10px 10px 0;
            padding: 14px 18px;
            font-size: .88rem;
        }
        .admin-note .note-header {
            font-size: .72rem;
            font-weight: 700;
            color: #b8860b;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .admin-note .note-text { color: #5a4a00; line-height: 1.6; }

        /* ── Garansi badge ── */
        .warranty-ok   { background: #d4edda; color: #155724; border-radius: 8px; padding: 6px 12px; display:inline-block; }
        .warranty-warn { background: #f8d7da; color: #721c24; border-radius: 8px; padding: 6px 12px; display:inline-block; }

        /* ── Resi/ekspedisi ── */
        .resi-box {
            background: #e8f4fd;
            border: 1.5px dashed #90caf9;
            border-radius: 10px;
            padding: 14px 18px;
            font-size: .88rem;
        }

        /* ── Steps tracker ── */
        .step-track { display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 6px; padding: 20px 0 10px; }
        .step-item  { display: flex; flex-direction: column; align-items: center; font-size: .65rem; font-weight: 700; color: #b0b7c3; text-transform: uppercase; width: 70px; text-align: center; }
        .step-dot   { width: 28px; height: 28px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: .7rem; margin-bottom: 4px; }
        .step-item.done   .step-dot { background: #1cc88a; color: #fff; }
        .step-item.active .step-dot { background: #4e73df; color: #fff; box-shadow: 0 0 0 4px rgba(78,115,223,.2); }
        .step-line  { width: 30px; height: 2px; background: #e2e8f0; margin-bottom: 16px; flex-shrink: 0; }
        .step-line.done { background: #1cc88a; }

        /* ── Sensor badge ── */
        .sensor-badge { background: #eee; color: #888; border-radius: 4px; padding: 0 6px; font-size: .75rem; letter-spacing: 2px; }

        /* ── Result card ── */
        .result-card { border-radius: 16px; box-shadow: 0 8px 30px rgba(0,0,0,.07); border: none; overflow: hidden; }

        /* Color helpers */
        .bg-status-primary { background: #e8eafe; } .text-status-primary { color: #4e73df; }
        .bg-status-warning { background: #fff3cd; } .text-status-warning { color: #856404; }
        .bg-status-info    { background: #d1ecf1; } .text-status-info    { color: #0c5460; }
        .bg-status-success { background: #d4edda; } .text-status-success { color: #155724; }
        .bg-status-danger  { background: #f8d7da; } .text-status-danger  { color: #721c24; }
        .bg-status-secondary{background: #e2e3e5; } .text-status-secondary{color: #383d41;}
    </style>
</head>
<body>

<!-- ── HERO ── -->
<div class="hero">
    <div class="container">
        <h1><i class="fas fa-box-open mr-2"></i>Cek Status Retur</h1>
        <p>Masukkan nomor tanda terima untuk memantau status barang Anda</p>
    </div>
</div>

<div class="container pb-5">
<div class="row justify-content-center">
<div class="col-lg-7">

    <!-- ── SEARCH CARD ── -->
    <div class="card search-card mb-4">
        <div class="card-body p-4">
            <form action="<?= base_url('cek') ?>" method="GET">
                <label class="font-weight-bold text-gray-700 mb-2">
                    <i class="fas fa-receipt mr-1 text-primary"></i> Nomor Tanda Terima
                </label>
                <div class="input-group input-group-lg">
                    <input type="text" name="nomor" class="form-control"
                        placeholder="Cth: RET-20260310-ABCD-0001"
                        value="<?= htmlspecialchars($nomor ?? '') ?>" required>
                    <div class="input-group-append">
                        <button class="btn btn-primary px-4" type="submit">
                            <i class="fas fa-search mr-1"></i> Lacak
                        </button>
                    </div>
                </div>
                <small class="text-muted mt-1 d-block">
                    <i class="fas fa-info-circle"></i>
                    Nomor tanda terima ada di bukti penerimaan barang Anda.
                </small>
            </form>
        </div>
    </div>

    <?php if (!empty($nomor) && !empty($result)):
        $st = $result['status_display'];
    ?>

    <!-- ── RESULT CARD ── -->
    <div class="card result-card animate__animated animate__fadeInUp mb-3">

        <!-- Status Banner -->
        <div class="status-banner bg-status-<?= $st['color']; ?>">
            <div class="status-icon bg-<?= $st['color'] === 'info' ? 'info' : $st['color']; ?> text-white
                    bg-status-<?= $st['color']; ?> text-status-<?= $st['color']; ?>">
                <i class="fas <?= $st['icon']; ?>"></i>
            </div>
            <div class="text-status-<?= $st['color']; ?>">
                <small>Status Barang Anda Saat Ini</small>
                <h3 class="mt-1"><?= $st['label']; ?></h3>
            </div>
        </div>

        <!-- Step Tracker -->
        <?php
        // Urutan step yang ditampilkan ke customer (3 tahap saja — simpel)
        $steps = [
            'received' => 'Diterima',
            'process'  => 'Diproses', // gabungan checking/to_vendor/processing/from_vendor
            'ready'    => 'Siap Kirim',
            'shipped'  => 'Dikirim',
            'done'     => 'Selesai',
        ];
        // Tentukan posisi step saat ini
        $internal = $result['status'];
        if (in_array($internal, ['checking','to_vendor','processing','from_vendor'])) {
            $current_step = 'process';
        } elseif ($internal === 'received')                                  { $current_step = 'received'; }
        elseif ($internal === 'ready')                                       { $current_step = 'ready'; }
        elseif ($internal === 'shipped')                                     { $current_step = 'shipped'; }
        elseif (in_array($internal, ['completed','menang banding']))         { $current_step = 'done'; }
        elseif (in_array($internal, ['rejected','kalah banding']))           { $current_step = 'done'; }
        else                                                                 { $current_step = 'process'; }

        $step_keys   = array_keys($steps);
        $current_idx = array_search($current_step, $step_keys);
        ?>
        <div class="px-3 pb-2">
            <div class="step-track">
                <?php foreach ($steps as $key => $label):
                    $idx   = array_search($key, $step_keys);
                    $state = ($idx < $current_idx) ? 'done' : (($idx === $current_idx) ? 'active' : '');
                ?>
                <?php if ($idx > 0): ?>
                    <div class="step-line <?= ($idx <= $current_idx) ? 'done' : ''; ?>"></div>
                <?php endif; ?>
                <div class="step-item <?= $state; ?>">
                    <div class="step-dot">
                        <?php if ($state === 'done'): ?>
                            <i class="fas fa-check" style="font-size:.6rem"></i>
                        <?php elseif ($state === 'active'): ?>
                            <i class="fas fa-circle" style="font-size:.5rem"></i>
                        <?php else: ?>
                            <?= $idx + 1; ?>
                        <?php endif; ?>
                    </div>
                    <?= $label; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card-body px-4 pt-2 pb-3">

            <!-- Keterangan admin (jika ada) -->
            <?php if (!empty($last_note)): ?>
            <div class="admin-note mb-4">
                <div class="note-header">
                    <i class="fas fa-comment-dots mr-1"></i>
                    Pesan dari Tim Kami
                    <?php if (!empty($last_note_date)): ?>
                        <span class="font-weight-normal text-muted ml-2" style="text-transform:none">
                            — <?= date('d M Y, H:i', strtotime($last_note_date)); ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="note-text"><?= htmlspecialchars($last_note); ?></div>
            </div>
            <?php endif; ?>

            <!-- Info Barang -->
            <div class="row">
                <div class="col-md-6">

                    <div class="d-flex align-items-start info-block">
                        <div class="info-icon"><i class="fas fa-receipt"></i></div>
                        <div>
                            <div class="info-label">No. Tanda Terima</div>
                            <div class="info-value" style="font-size:.85rem"><?= $result['return_number']; ?></div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start info-block">
                        <div class="info-icon"><i class="fas fa-box"></i></div>
                        <div>
                            <div class="info-label">Nama Produk</div>
                            <div class="info-value"><?= htmlspecialchars($result['customer_product'] ?? '-'); ?></div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start info-block">
                        <div class="info-icon"><i class="fas fa-user"></i></div>
                        <div>
                            <div class="info-label">Nama Pelanggan</div>
                            <!-- SENSOR: tampilkan inisial saja -->
                            <div class="info-value">
                                <?= htmlspecialchars($result['customer_display']); ?>
                                <span class="sensor-badge ml-1" title="Data disamarkan demi privasi">🔒</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start info-block">
                        <div class="info-icon"><i class="fas fa-store"></i></div>
                        <div>
                            <div class="info-label">Toko</div>
                            <div class="info-value"><?= htmlspecialchars($result['customer_store'] ?? '-'); ?></div>
                            <small class="text-muted"><?= htmlspecialchars($result['customer_platform'] ?? '-'); ?></small>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">

                    <div class="d-flex align-items-start info-block">
                        <div class="info-icon"><i class="fas fa-shopping-bag"></i></div>
                        <div>
                            <div class="info-label">Tanggal Pembelian</div>
                            <div class="info-value">
                                <?= !empty($result['purchase_date'])
                                    ? date('M Y', strtotime($result['purchase_date']))
                                    : '-'; ?>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start info-block">
                        <div class="info-icon"><i class="fas fa-calendar-check"></i></div>
                        <div>
                            <div class="info-label">Barang Diterima Kami</div>
                            <div class="info-value"><?= date('M Y', strtotime($result['received_date'])); ?></div>
                        </div>
                    </div>

                    <!-- Garansi -->
                    <?php if (!empty($result['warranty_expiry'])):
                        $exp_ts    = strtotime($result['warranty_expiry']);
                        $expired   = $exp_ts < time();
                        $days_diff = round((abs($exp_ts - time())) / 86400);
                    ?>
                    <div class="d-flex align-items-start info-block">
                        <div class="info-icon <?= $expired ? 'text-danger' : 'text-success'; ?>" style="background:<?= $expired ? '#fde8e8' : '#e8f9f0'; ?>">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <div class="info-label">Status Garansi</div>
                            <div class="<?= $expired ? 'warranty-warn' : 'warranty-ok'; ?> mt-1">
                                <i class="fas <?= $expired ? 'fa-times-circle' : 'fa-check-circle'; ?> mr-1"></i>
                                <?= $expired ? 'Garansi Habis' : 'Garansi Aktif'; ?>
                                <span class="font-weight-normal ml-1" style="font-size:.8rem">
                                    (<?= $expired ? 'lewat ' : 'sisa '; ?><?= $days_diff; ?> hari)
                                </span>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <!-- s/d <?= date('d M Y', $exp_ts); ?> -->
                            </small>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Lama Pengerjaan -->
                    <div class="d-flex align-items-start info-block">
                        <div class="info-icon text-primary" style="background:#eef0ff">
                            <i class="fas fa-stopwatch"></i>
                        </div>
                        <div>
                            <div class="info-label">Lama Pengerjaan</div>
                            <div class="info-value text-primary"><?= $result['aging']; ?> Hari Kerja</div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Info Pengiriman Balik (jika sudah shipped) -->
            <?php if ($result['status'] === 'shipped' && !empty($result['receipt_number'])): ?>
            <div class="resi-box mt-3">
                <div class="font-weight-bold text-info mb-2">
                    <i class="fas fa-truck mr-1"></i> Info Pengiriman Balik
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="info-label">Ekspedisi</div>
                        <!-- SENSOR: nama ekspedisi boleh tampil, tidak ada data internal -->
                        <div class="font-weight-bold"><?= htmlspecialchars($result['expedition_name'] ?? '-'); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Nomor Resi</div>
                        <div class="font-weight-bold text-info"><?= htmlspecialchars($result['customer_recipt']); ?></div>
                    </div>
                </div>
                <?php if (!empty($result['shipping_date'])): ?>
                <div class="mt-2">
                    <div class="info-label">Tanggal Kirim</div>
                    <div class="font-weight-bold"><?= date('d M Y', strtotime($result['shipping_date'])); ?></div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Disclaimer sensor -->
    <div class="text-center text-muted small mt-2 mb-4">
        <i class="fas fa-lock mr-1"></i>
        Beberapa informasi disamarkan demi menjaga privasi pelanggan.
    </div>

    <?php elseif (!empty($nomor) && empty($result)): ?>

    <!-- ── NOT FOUND ── -->
    <div class="card result-card animate__animated animate__fadeIn text-center p-5">
        <i class="fas fa-search fa-3x text-gray-400 mb-3"></i>
        <h5 class="font-weight-bold text-gray-700">Data Tidak Ditemukan</h5>
        <p class="text-muted mb-0">
            Nomor <strong><?= htmlspecialchars($nomor); ?></strong> tidak ada di sistem kami.<br>
            Pastikan nomor tanda terima yang Anda masukkan sudah benar.
        </p>
    </div>

    <?php endif; ?>

</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</body>
</html>