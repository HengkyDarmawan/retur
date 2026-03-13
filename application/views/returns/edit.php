<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data Retur: <?= $return['return_number']; ?></h6>
        </div>
        <div class="card-body">
            <?= form_open_multipart('returns/update_data'); ?>
            <input type="hidden" name="id" value="<?= $return['id']; ?>">
            <input type="hidden" name="old_photos" value='<?= $return['evidence_photo']; ?>'>
            <input type="hidden" name="old_video" value="<?= $return['evidence_video']; ?>">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nomor Pesanan / Nota</label>
                        <input type="text" class="form-control" name="order_number" value="<?= $return['order_number']; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jenis Retur</label>
                        <select name="type_id" class="form-control" required>
                            <?php foreach($types as $t): ?>
                                <option value="<?= $t['id']; ?>" <?= ($t['id'] == $return['type_id']) ? 'selected' : ''; ?>><?= $t['type_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Store / Toko</label>
                        <select name="store_id" class="form-control" required>
                            <?php foreach($stores as $s): ?>
                                <option value="<?= $s['id']; ?>" <?= ($s['id'] == $return['store_id']) ? 'selected' : ''; ?>><?= $s['store_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Platform</label>
                        <select name="platform_id" class="form-control" required>
                            <?php foreach($platforms as $p): ?>
                                <option value="<?= $p['id']; ?>" <?= ($p['id'] == $return['platform_id']) ? 'selected' : ''; ?>><?= $p['platform_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tgl Pembelian (User)</label>
                        <input type="date" name="purchase_date" class="form-control" value="<?= $return['purchase_date']; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tgl Masuk ke Kita</label>
                        <input type="date" name="received_date" class="form-control" value="<?= $return['received_date']; ?>">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="product_name" class="form-control" value="<?= $return['product_name']; ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Masa Garansi</label>
                        <?php 
                            // Hitung selisih tahun antara tgl beli dan tgl expired untuk menentukan 'selected'
                            $date1 = new DateTime($return['purchase_date']);
                            $date2 = new DateTime($return['warranty_expiry']);
                            $interval = $date1->diff($date2);
                            
                            $current_duration = 0;
                            if ($interval->y > 0) {
                                $current_duration = $interval->y;
                            } elseif ($interval->m == 6) {
                                $current_duration = 0.5;
                            }
                        ?>
                        <select name="warranty_expiry" class="form-control" required>
                            <option value="0.5" <?= ($current_duration == 0.5) ? 'selected' : ''; ?>>6 Bulan</option>
                            <?php for($i = 1; $i <= 10; $i++): ?>
                                <option value="<?= $i; ?>" <?= ($i == $current_duration) ? 'selected' : ''; ?>><?= $i; ?> Tahun</option>
                            <?php endfor; ?>
                            <option value="0" <?= ($current_duration == 0) ? 'selected' : ''; ?>>Tanpa Garansi</option>
                        </select>
                        <small class="text-muted">Exp: <?= date('d M Y', strtotime($return['warranty_expiry'])); ?></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Vendor</label>
                        <select name="vendor_id" class="form-control" required>
                            <?php foreach($vendors as $v): ?>
                                <option value="<?= $v['id']; ?>" <?= ($v['id'] == $return['vendor_id']) ? 'selected' : ''; ?>><?= $v['vendor_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Customer</label>
                        <input type="text" name="customer_name" class="form-control" value="<?= $return['customer_name']; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>WhatsApp Customer</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">+62</span>
                            </div>
                            <input type="number" name="customer_wa" class="form-control" 
                                value="<?= ltrim($return['customer_wa'], '62'); ?>" 
                                placeholder="812345678">
                        </div>
                        <small class="text-muted">Opsional. Masukkan angka saja tanpa nol/62 di depan.</small>
                    </div>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Ganti Foto Bukti (Max 3, JPG/PNG -> WebP)</label>
                        <input type="file" name="evidence_photos[]" class="form-control-file" multiple>
                        <div class="mt-2">
                            <?php 
                            $imgs = json_decode($return['evidence_photo'], true);
                            if($imgs): foreach($imgs as $img): ?>
                                <img src="<?= base_url('assets/img/returns/').$img; ?>" width="80" class="img-thumbnail">
                            <?php endforeach; endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Ganti Video Unboxing</label>
                        <input type="file" name="evidence_video" class="form-control-file">
                        <?php if($return['evidence_video']): ?>
                            <small class="text-success">Video tersedia: <?= $return['evidence_video']; ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4">Update Data Retur</button>
                <a href="<?= base_url('returns'); ?>" class="btn btn-secondary">Batal</a>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>