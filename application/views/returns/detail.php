<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Retur: <?= $return['return_number']; ?></h1>
        <a href="<?= base_url('returns'); ?>" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke List
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Produk & Customer</h6>
                    <span class="badge badge-<?= ($return['status'] == 'shipped') ? 'success' : 'info'; ?> p-2 shadow-sm">
                        STATUS: <?= strtoupper(str_replace('_', ' ', $return['status'])); ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 font-weight-bold text-gray-600">Nama Produk</div>
                        <div class="col-sm-8 text-primary font-weight-bold"><?= $return['product_name']; ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 font-weight-bold text-gray-600">Nomor Pesanan</div>
                        <div class="col-sm-8 font-weight-bold"><?= $return['order_number']; ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 font-weight-bold text-gray-600">Store / Platform</div>
                        <div class="col-sm-8"><?= $return['store_name']; ?> <span class="badge badge-light border"><?= $return['platform_name']; ?></span></div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4 font-weight-bold text-gray-600">Nama Customer</div>
                        <div class="col-sm-8"><?= $return['customer_name']; ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 font-weight-bold text-gray-600">WhatsApp</div>
                        <div class="col-sm-8">
                            <a href="https://wa.me/<?= $return['customer_wa']; ?>" target="_blank" class="btn btn-sm btn-success shadow-sm">
                                <i class="fab fa-whatsapp"></i> <?= $return['customer_wa']; ?>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4 font-weight-bold text-gray-600">Keterangan Kerusakan</div>
                        <div class="col-sm-8 text-danger font-italic small">"<?= $return['current_keterangan']; ?>"</div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bukti Foto & Video</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <label class="font-weight-bold small text-uppercase">Foto Kerusakan:</label>
                            <div class="row">
                                <?php if (!empty($return['evidence_photo'])) :
                                    $photos = explode(',', $return['evidence_photo']);
                                    foreach ($photos as $img) : ?>
                                        <div class="col-4 mb-2">
                                            <a href="<?= base_url('assets/uploads/returns/images/' . $img); ?>" target="_blank">
                                                <img src="<?= base_url('assets/uploads/returns/images/' . $img); ?>" class="img-fluid img-thumbnail shadow-sm" style="height: 120px; width: 100%; object-fit: cover;">
                                            </a>
                                        </div>
                                    <?php endforeach; 
                                else : ?>
                                    <div class="col-12"><p class="text-muted small italic">Tidak ada foto.</p></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-5 border-left">
                            <label class="font-weight-bold small text-uppercase">Video Unboxing:</label>
                            <?php if (!empty($return['evidence_video'])) : ?>
                                <video width="100%" height="auto" controls class="rounded shadow-sm border">
                                    <source src="<?= base_url('assets/uploads/returns/videos/' . $return['evidence_video']); ?>" type="video/mp4">
                                </video>
                            <?php else : ?>
                                <p class="text-muted small italic">Video tidak tersedia.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            
            <?php if (!empty($return['receiver_info'])): ?>
                <div class="card shadow mb-4 border-left-info">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-map-marker-alt"></i> Tujuan Pengiriman Balik</h6>
                    </div>
                    <div class="card-body">
                        <div class="p-2 bg-light border rounded small text-dark mb-2">
                            <?= nl2br(htmlspecialchars($return['receiver_info'])); ?>
                        </div>
                        <button class="btn btn-sm btn-outline-info btn-block shadow-sm" onclick="copyToClipboard('<?= str_replace(["\r", "\n"], ' ', $return['receiver_info']); ?>', 'Alamat')">
                            <i class="fas fa-copy"></i> Salin Alamat
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card shadow mb-4 border-left-primary">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Logistik & Garansi</h6>
                    <i class="fas fa-shipping-fast text-gray-300"></i>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small font-weight-bold text-uppercase text-gray-600">Dikirim Balik Via:</label>
                        <?php if(!empty($return['receipt_number'])): ?>
                            <div class="p-2 bg-gray-100 border rounded mb-1">
                                <span class="font-weight-bold text-dark d-block"><?= $return['expedition_name'] ?? 'Ekspedisi'; ?></span>
                                <span class="text-primary font-weight-bold"><?= $return['receipt_number']; ?></span>
                                <div class="mt-1">
                                    <a href="https://www.cekresi.com/?noresi=<?= $return['receipt_number']; ?>" target="_blank" class="badge badge-primary px-2 py-1">
                                        <i class="fas fa-search"></i> Lacak
                                    </a>
                                    <a href="javascript:void(0)" onclick="copyToClipboard('<?= $return['receipt_number']; ?>', 'Resi')" class="badge badge-light border ml-1">
                                        <i class="fas fa-copy"></i> Salin
                                    </a>
                                </div>
                            </div>
                            <small class="text-muted italic"><i class="far fa-calendar-alt"></i> <?= date('d M Y', strtotime($return['shipping_date'])); ?></small>
                        <?php else: ?>
                            <div class="alert alert-warning py-2 small mb-0 font-italic">
                                <i class="fas fa-exclamation-circle"></i> Belum ada data pengiriman.
                            </div>
                        <?php endif; ?>
                    </div>

                    <hr>

                    <div class="text-center p-2 bg-gray-100 rounded border shadow-sm">
                        <label class="small font-weight-bold d-block text-left mb-1 text-uppercase text-gray-600">Vendor / Brand:</label>
                        <h5 class="text-dark font-weight-bold mb-2"><?= $return['vendor_name']; ?></h5>
                        
                        <?php 
                        $expiry_date = strtotime($return['warranty_expiry']);
                        $is_expired = $expiry_date < time();
                        $days_left = round(($expiry_date - time()) / (60 * 60 * 24));
                        ?>

                        <div class="badge badge-<?= $is_expired ? 'danger' : 'success'; ?> px-3 py-2 mb-2">
                            <i class="fas <?= $is_expired ? 'fa-times-circle' : 'fa-check-circle'; ?>"></i> 
                            <?= $is_expired ? 'Garansi Habis' : 'Garansi Aktif'; ?>
                        </div>
                        <p class="font-weight-bold mb-0 small <?= $is_expired ? 'text-danger' : 'text-primary'; ?>">
                            <?= date('d M Y', $expiry_date); ?>
                            <br>
                            <span class="font-italic" style="font-size: 10px;">
                                (<?= $is_expired ? 'Lewat ' . abs($days_left) : 'Sisa ' . $days_left; ?> hari)
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-body p-2">
                    <a href="javascript:void(0)" 
                        onclick="sendWaGlobal(
                            '<?= $return['customer_wa']; ?>', 
                            '<?= $return['return_number']; ?>', 
                            '<?= $return['status']; ?>', 
                            '<?= addslashes($return['customer_name']); ?>', 
                            '<?= base_url(); ?>',
                            '<?= ($return['status'] == 'shipped') 
                                ? ($return['expedition_name'] ?? 'Kurir') . ' (' . ($return['receipt_number'] ?? '-') . ')' 
                                : addslashes($return['keterangan'] ?? ''); ?>'
                        )" 
                        class="btn btn-success btn-block shadow-sm">
                        <i class="fab fa-whatsapp"></i> Hubungi Customer
                    </a>
                    <div class="row no-gutters mt-2">
                        <div class="col-12">
                            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm btn-block">
                                <i class="fas fa-print"></i> Cetak Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-gray-100">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history"></i> Status Timeline</h6>
                </div>
                <div class="card-body px-3">
                    <ul class="timeline">
                        <?php if(!empty($logs)) : 
                            foreach($logs as $index => $log) : 
                                // Logic: Cek status DI ATASNYA (data terbaru ada di index 0)
                                // Jika status saat ini sama dengan status sebelumnya (index-1), coret status ini.
                                $prev_status = isset($logs[$index - 1]) ? $logs[$index - 1]['status'] : null;
                                $is_outdated = ($log['status'] === $prev_status);
                                ?>
                            <li class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="timeline-date small text-muted"><?= date('d M Y - H:i', strtotime($log['created_at'])); ?></div>
                                    <div class="font-weight-bold text-uppercase small">
                                        <?php if ($is_outdated): ?>
                                            <span class="text-muted"><del><?= str_replace('_', ' ', $log['status']); ?></del></span>
                                            <span class="badge badge-light border" style="font-size: 9px;">REVISI</span>
                                        <?php else: ?>
                                            <span class="text-primary"><?= str_replace('_', ' ', $log['status']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="mb-0 small text-gray-700">"<?= htmlspecialchars($log['keterangan']); ?>"</p>
                                    <small class="text-xs text-info font-italic">Admin: <?= $log['admin_name'] ?: 'System'; ?></small>
                                </div>
                            </li>
                        <?php endforeach; endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text, label = 'Data') {
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                icon: 'success',
                title: label + ' Berhasil Disalin!',
                text: text,
                timer: 1200,
                showConfirmButton: false
            });
        });
    }
</script>