<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg-12">
            <?= form_error('name', '<div class="alert alert-danger">', '</div>'); ?>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar <?= $title; ?></h6>
                    <?php if (user_can('add')) : ?>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newDataModal">
                            <i class="fas fa-plus fa-sm"></i> Tambah Data
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="bg-light">
                                    <th width="5%">#</th>
                                    <th>Nama</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1; 
                                foreach ($list as $l) : 
                                    // Dinamis mengambil field name
                                    $val = $l[$type == 'platforms' ? 'platform_name' : ($type == 'stores' ? 'store_name' : ($type == 'vendors' ? 'vendor_name' : 'expedition_name'))];
                                ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><strong><?= strtoupper($val); ?></strong></td>
                                        <td>
                                            <?php if (user_can('edit')) : ?>
                                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal<?= $l['id']; ?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            <?php endif; ?>

                                            <?php if (user_can('delete')) : ?>
                                                <a href="<?= base_url('master/delete/' . $type . '/' . $l['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="editModal<?= $l['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Data</h5>
                                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <?= form_open('master/edit/' . $type); ?>
                                                    <input type="hidden" name="id" value="<?= $l['id']; ?>">
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Nama</label>
                                                            <input type="text" class="form-control" name="name" value="<?= $val; ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                <?= form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newDataModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah <?= $title; ?></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <?= form_open('master/' . $type); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="name" placeholder="Input nama data baru..." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>