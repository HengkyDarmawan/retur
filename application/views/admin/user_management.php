<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Pengguna Sistem</h6>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newUserModal">
                        <i class="fas fa-plus fa-sm"></i> Tambah User
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="bg-light text-center">
                                    <th>#</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach ($all_user as $u) : ?>
                                    <tr>
                                        <td class="text-center"><?= $i++; ?></td>
                                        <td class="text-center">
                                            <img src="<?= base_url('assets/img/profile/') . $u['image']; ?>" class="img-thumbnail" width="50">
                                        </td>
                                        <td><?= $u['name']; ?></td>
                                        <td><?= $u['username']; ?></td>
                                        <td><?= $u['email']; ?></td>
                                        <td class="text-center"><span class="badge badge-info"><?= $u['role']; ?></span></td>
                                        <td class="text-center">
                                            <?= $u['is_active'] == 1 ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-secondary">Non-Aktif</span>'; ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editUserModal<?= $u['id']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="<?= base_url('admin/deleteuser/') . $u['id']; ?>" class="btn btn-danger btn-sm btn-delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <?= form_open('admin/usermanagement'); ?>
                <div class="modal-body">
                    <div class="form-group"><input type="text" class="form-control" name="name" placeholder="Nama Lengkap" required></div>
                    <div class="form-group"><input type="text" class="form-control" name="username" placeholder="Username" required></div>
                    <div class="form-group"><input type="email" class="form-control" name="email" placeholder="Email" required></div>
                    <div class="form-group"><input type="password" class="form-control" name="password" placeholder="Password" required></div>
                    <div class="form-group">
                        <select name="role_id" class="form-control">
                            <option value="">Pilih Role</option>
                            <?php foreach ($role as $r) : ?>
                                <option value="<?= $r['id']; ?>"><?= $r['role']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan User</button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<div class="modal fade" id="editUserModal<?= $u['id']; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-left">
            <div class="modal-header">
                <h5 class="modal-title">Edit User: <?= $u['name']; ?></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <?= form_open('admin/edituser'); ?>
                <input type="hidden" name="id" value="<?= $u['id']; ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" value="<?= $u['name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" value="<?= $u['username']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="<?= $u['email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Password <small class="text-danger">(Kosongkan jika tidak ingin diubah)</small></label>
                        <input type="password" class="form-control" name="password" placeholder="Password Baru">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role_id" class="form-control">
                            <?php foreach ($role as $r) : ?>
                                <option value="<?= $r['id']; ?>" <?= $r['id'] == $u['role_id'] ? 'selected' : ''; ?>><?= $r['role']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="is_active" class="form-control">
                            <option value="1" <?= $u['is_active'] == 1 ? 'selected' : ''; ?>>Aktif</option>
                            <option value="0" <?= $u['is_active'] == 0 ? 'selected' : ''; ?>>Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>