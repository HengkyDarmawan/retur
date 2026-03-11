<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tracking Status Retur</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800" rel="stylesheet">

    <style>
        body { 
            background-color: #f4f7fc; 
            font-family: 'Nunito', sans-serif;
        }
        .header-bg {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            padding: 80px 0 100px;
            color: white;
            text-align: center;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
        }
        .search-card {
            margin-top: -50px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: none;
        }
        .result-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: none;
            overflow: hidden;
        }
        .status-banner {
            background-color: #e8eafe;
            color: #4e73df;
            padding: 20px;
            text-align: center;
            border-bottom: 2px dashed #cdd4f6;
        }
        .status-banner h2 { font-weight: 800; letter-spacing: 2px; margin: 0; }
        .info-row {
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child { border-bottom: none; }
        .icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f8f9fc;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #4e73df;
            font-size: 1.2rem;
            margin-right: 15px;
        }
        .info-label { font-size: 0.85rem; color: #858796; font-weight: 600; text-transform: uppercase; margin-bottom: 2px; }
        .info-value { font-size: 1rem; color: #3a3b45; font-weight: 700; }
    </style>
</head>

<body>

    <div class="header-bg">
        <h1 class="font-weight-bold mb-2">Pusat Bantuan Klaim & Retur</h1>
        <p class="text-light opacity-75">Pantau status barang retur Anda secara real-time</p>
    </div>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="card search-card mb-4">
                    <div class="card-body p-4">
                        <form action="<?= base_url('cek') ?>" method="GET">
                            <label class="font-weight-bold text-gray-800">Nomor Tanda Terima</label>
                            <div class="input-group input-group-lg">
                                <input type="text" name="nomor" class="form-control" 
                                       placeholder="Cth: RET-20260310-..." value="<?= htmlspecialchars($nomor) ?>" required>
                                <div class="input-group-append">
                                    <button class="btn btn-primary px-4" type="submit">
                                        <i class="fas fa-search mr-2"></i> Lacak
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if ($nomor && $result): ?>
                <div class="card result-card animate__animated animate__fadeInUp">
                    
                    <div class="status-banner">
                        <span class="text-uppercase font-weight-bold small text-muted d-block mb-1">Status Barang Saat Ini:</span>
                        <h2><i class="fas fa-check-circle mr-2"></i><?= strtoupper($result['status']) ?></h2>
                    </div>

                    <div class="card-body p-5">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center info-row">
                                    <div class="icon-box"><i class="fas fa-receipt"></i></div>
                                    <div>
                                        <div class="info-label">No. Tanda Terima</div>
                                        <div class="info-value"><?= $result['return_number'] ?></div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center info-row">
                                    <div class="icon-box"><i class="fas fa-box-open"></i></div>
                                    <div>
                                        <div class="info-label">Nama Produk</div>
                                        <div class="info-value"><?= $result['product_name'] ?? '-' ?></div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center info-row">
                                    <div class="icon-box"><i class="fas fa-user"></i></div>
                                    <div>
                                        <div class="info-label">Nama Customer</div>
                                        <div class="info-value"><?= $result['customer_name'] ?></div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center info-row">
                                    <div class="icon-box"><i class="fas fa-store"></i></div>
                                    <div>
                                        <div class="info-label">Toko / Platform</div>
                                        <div class="info-value"><?= ($result['store_name'] ?? 'Toko Pusat') . ' - ' . ($result['platform_name'] ?? 'Offline') ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center info-row">
                                    <div class="icon-box"><i class="fas fa-shopping-cart"></i></div>
                                    <div>
                                        <div class="info-label">Tgl. Pembelian</div>
                                        <div class="info-value"><?= !empty($result['purchase_date']) ? date('d M Y', strtotime($result['purchase_date'])) : '-' ?></div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center info-row">
                                    <div class="icon-box"><i class="fas fa-calendar-check"></i></div>
                                    <div>
                                        <div class="info-label">Tgl. Barang Diterima Kami</div>
                                        <div class="info-value"><?= date('d M Y', strtotime($result['received_date'])) ?></div>
                                    </div>
                                </div>

                                <?php 
                                if (!empty($result['warranty_expiry'])) {
                                    $expiry_date = strtotime($result['warranty_expiry']);
                                    $is_expired  = $expiry_date < time();
                                    $days_left   = round(($expiry_date - time()) / (60 * 60 * 24));
                                    
                                    $badge_color = $is_expired ? 'danger' : 'success';
                                    $icon_class  = $is_expired ? 'fa-times-circle' : 'fa-check-circle';
                                    $status_text = $is_expired ? 'Garansi Habis' : 'Garansi Aktif';
                                    $sisa_hari   = $is_expired ? '(Lewat ' . abs($days_left) . ' hari)' : '(Sisa ' . $days_left . ' hari)';
                                } else {
                                    $is_expired  = null;
                                }
                                ?>
                                <div class="d-flex align-items-center info-row">
                                    <div class="icon-box bg-<?= isset($badge_color) ? $badge_color : 'warning' ?> text-white">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div>
                                        <div class="info-label">Status Garansi</div>
                                        <?php if (!empty($result['warranty_expiry'])): ?>
                                            <div class="info-value text-<?= $badge_color ?> font-weight-bold">
                                                <i class="fas <?= $icon_class ?> mr-1"></i> <?= $status_text ?>
                                            </div>
                                            <div class="small text-muted mt-1 font-weight-bold">
                                                <?= date('d M Y', $expiry_date) ?> 
                                                <span class="font-italic font-weight-normal" style="font-size: 0.85rem;">
                                                    <?= $sisa_hari ?>
                                                </span>
                                            </div>
                                        <?php else: ?>
                                            <div class="info-value text-muted">Tanggal tidak tersedia</div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center info-row">
                                    <div class="icon-box bg-primary text-white"><i class="fas fa-stopwatch"></i></div>
                                    <div>
                                        <div class="info-label">Lama Pengerjaan</div>
                                        <div class="info-value text-primary font-weight-bold"><?= $result['aging'] ?> Hari Kerja</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <script>
        $(document).ready(function() {
            <?php if ($nomor && !$result): ?>
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ditemukan',
                    text: 'Data retur dengan nomor <?= $nomor ?> tidak ada di sistem kami. Pastikan nomor yang dimasukkan benar.',
                    confirmButtonColor: '#4e73df'
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>