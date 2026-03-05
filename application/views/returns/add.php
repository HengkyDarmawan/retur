<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Input Barang Retur</h6>
                </div>
                <div class="card-body">
                    <?= form_open_multipart('returns/store'); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor Pesanan / Nota <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="order_number" placeholder="Contoh: INV-12345" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Retur</label>
                                    <select name="type_id" class="form-control" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <?php foreach($types as $t): ?>
                                            <option value="<?= $t['id']; ?>"><?= $t['type_name']; ?></option>
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
                                            <option value="<?= $s['id']; ?>"><?= $s['store_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Platform</label>
                                    <select name="platform_id" class="form-control" required>
                                        <?php foreach($platforms as $p): ?>
                                            <option value="<?= $p['id']; ?>"><?= $p['platform_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tgl Pembelian (User) <span class="text-danger">*</span></label>
                                    <input type="date" name="purchase_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tgl Masuk ke Kita</label>
                                    <input type="date" name="received_date" class="form-control" value="<?= date('Y-m-d'); ?>">
                                    <small class="text-muted">Default: Hari ini</small>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group">
                            <label>Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="product_name" class="form-control" placeholder="Nama lengkap produk yang diretur" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Masa Garansi <span class="text-danger">*</span></label>
                                    <select name="warranty_duration" class="form-control" required>
                                        <option value="0.5">6 Bulan</option>
                                        <?php for($i = 1; $i <= 10; $i++): ?>
                                            <option value="<?= $i; ?>" <?= ($i == 1) ? 'selected' : ''; ?>>
                                                <?= $i; ?> Tahun
                                            </option>
                                        <?php endfor; ?>
                                        <option value="0">Tanpa Garansi / Habis</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Vendor</label>
                                    <select name="vendor_id" class="form-control" required>
                                        <?php foreach($vendors as $v): ?>
                                            <option value="<?= $v['id']; ?>"><?= $v['vendor_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Customer</label>
                                    <input type="text" name="customer_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>WhatsApp Customer</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+62</span>
                                        </div>
                                        <input type="number" name="customer_wa" class="form-control" placeholder="8123456789" required>
                                    </div>
                                    <small class="text-muted">Masukkan angka saja (tanpa 0 di depan).</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Keterangan Kerusakan / Masalah <span class="text-danger">*</span></label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Jelaskan detail keluhan customer..." required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                               <div class="form-group">
                                    <label>Foto Bukti (Bisa pilih banyak, Max 3)</label>
                                    <input type="file" name="evidence_photos[]" class="form-control-file" multiple accept="image/*">
                                    <small class="text-muted">Klik tahan Ctrl / Shift untuk pilih lebih dari 1 foto.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Video Unboxing (Opsional)</label>
                                    <input type="file" name="evidence_video" class="form-control-file">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4">Simpan Data Retur</button>
                            <a href="<?= base_url('returns'); ?>" class="btn btn-secondary">Batal</a>
                        </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>