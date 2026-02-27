<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg-12">
            <?= form_error('menu', '<div class="alert alert-danger">', '</div>'); ?>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Menu Utama</h6>
                    <?php if (user_can('add')) : ?>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newMenuModal">
                            <i class="fas fa-plus fa-sm"></i> Tambah Menu
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="bg-light">
                                    <th width="5%">#</th>
                                    <th>Menu</th>
                                    <th width="15%">Urutan</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach ($menu as $m) : ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><strong><?= strtoupper($m['menu']); ?></strong></td>
                                        <td><span class="badge badge-info px-3"><?= $m['sort_order']; ?></span></td>
                                        
                                        <?php if (user_can('edit') || user_can('delete')) : ?>
                                        <td>
                                            <?php if (user_can('edit')) : ?>
                                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editMenuModal<?= $m['id']; ?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            <?php endif; ?>

                                            <?php if (user_can('delete')) : ?>
                                                <a href="<?= base_url('menu/delete_menu/' . $m['id']); ?>" class="btn btn-danger btn-sm btn-delete">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <?php endif; ?>
                                    </tr>

                                    <div class="modal fade" id="editMenuModal<?= $m['id']; ?>" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Menu Utama</h5>
                                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <?= form_open('menu/edit_menu'); ?>
                                                    <input type="hidden" name="id" value="<?= $m['id']; ?>">
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Nama Menu</label>
                                                            <input type="text" class="form-control" name="menu" value="<?= $m['menu']; ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Nomor Urut</label>
                                                            <input type="number" class="form-control" name="sort_order" value="<?= $m['sort_order']; ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Update Data</button>
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

<div class="modal fade" id="newMenuModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Menu Utama</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <?= form_open('menu'); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Menu</label>
                        <input type="text" class="form-control" name="menu" placeholder="Contoh: Management" required>
                    </div>
                    <div class="form-group">
                        <label>Nomor Urut (Sort Order)</label>
                        <input type="number" class="form-control" name="sort_order" placeholder="Angka urutan tampil" required>
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
