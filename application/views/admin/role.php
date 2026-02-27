<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg-12">

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Role User</h6>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newRoleModal">
                        <i class="fas fa-plus fa-sm"></i> Tambah Role
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="bg-light text-center">
                                    <th width="5%">#</th>
                                    <th>Nama Role</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach ($role as $r) : ?>
                                    <tr>
                                        <td class="text-center"><?= $i++; ?></td>
                                        <td><strong><?= strtoupper($r['role']); ?></strong></td>
                                        <td class="text-center">
                                            <?php if ($r['id'] == 1) : ?>
                                                <span class="badge badge-secondary">Full Access & System Protected</span>
                                            <?php else : ?>
                                                <a href="<?= base_url('admin/roleaccess/') . $r['id']; ?>" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-user-lock"></i> Akses
                                                </a>

                                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editRoleModal<?= $r['id']; ?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>

                                                <a href="<?= base_url('admin/deleterole/' . $r['id']); ?>" class="btn btn-danger btn-sm btn-delete">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="editRoleModal<?= $r['id']; ?>" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content text-left">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Ubah Nama Role</h5>
                                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <?= form_open('admin/editrole'); ?>
                                                    <input type="hidden" name="id" value="<?= $r['id']; ?>">
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Nama Role</label>
                                                            <input type="text" class="form-control" name="role" value="<?= $r['role']; ?>" required>
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

<div class="modal fade" id="newRoleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Role Baru</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <?= form_open('admin/role'); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Role</label>
                        <input type="text" class="form-control" name="role" placeholder="Contoh: Admin Web" required>
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