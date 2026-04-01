<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
        <div>
            <a href="<?= base_url('returns'); ?>" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Data Utama
            </a>
            <button class="btn btn-success btn-sm shadow-sm" data-toggle="modal" data-target="#confirmAllModal">
                <i class="fas fa-check-double fa-sm"></i> Konfirmasi Semua Draft
            </button>
        </div>
    </div>

    <div class="alert alert-warning border-left-warning shadow" role="alert">
        <i class="fas fa-info-circle"></i> <strong>Informasi:</strong> Data di bawah ini adalah hasil import Excel yang <strong>belum divalidasi</strong>. Anda bisa mengedit data yang salah sebelum menekan tombol konfirmasi.
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-warning">
            <h6 class="m-0 font-weight-bold text-white">Daftar Tunggu Konfirmasi (Draft)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover small" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>No. Order</th>
                            <th>Customer</th>
                            <th>Tgl Masuk</th>
                            <th>Tgl Beli</th>
                            <th>Nama Barang</th>
                            <th>Vendor</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($drafts as $d) : ?>
                            <tr>
                                <td class="font-weight-bold text-primary"><?= $d['order_number']; ?></td>
                                <td><?= $d['customer_name']; ?></td>
                                <td><?= format_indo($d['received_date']); ?></td>
                                <td><?= format_indo($d['purchase_date']); ?></td>
                                <td><?= $d['product_name']; ?></td>
                                <td>
                                    <?php if($d['vendor_name']): ?>
                                        <span class="badge badge-info"><?= $d['vendor_name']; ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Tidak Ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?= base_url('returns/confirm_draft/' . $d['id']); ?>" class="btn btn-success btn-sm" title="Konfirmasi">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <a href="<?= base_url('returns/edit/' . $d['id']); ?>" class="btn btn-primary btn-sm" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('returns/delete_draft/' . $d['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus draft ini?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmAllModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-gray-800" id="exampleModalLabel">Konfirmasi Massal?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin mengonfirmasi <strong>Semua data draft</strong>? 
                Data yang sudah dikonfirmasi akan muncul di laporan utama dan dashboard.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <a class="btn btn-success" href="<?= base_url('returns/confirm_all_drafts'); ?>">Ya, Konfirmasi Semua</a>
            </div>
        </div>
    </div>
</div>