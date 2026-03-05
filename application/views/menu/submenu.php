<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg-12">
            <?php if (validation_errors()) : ?>
                <div class="alert alert-danger" role="alert"><?= validation_errors(); ?></div>
            <?php endif; ?>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Manajemen Submenu</h6>
                    <?php if (user_can('add')) : ?>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newSubMenuModal">
                            <i class="fas fa-plus fa-sm"></i> Tambah Submenu
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="bg-light">
                                    <th width="5%">#</th>
                                    <th>Title</th>
                                    <th>Menu Induk</th>
                                    <th>URL</th>
                                    <th>Icon</th>
                                    <th width="5%">Urutan</th>
                                    <th width="5%">Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach ($subMenu as $sm) : ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><strong><?= $sm['title']; ?></strong></td>
                                    <td><span class="badge badge-secondary"><?= $sm['menu']; ?></span></td>
                                    <td><code><?= $sm['url']; ?></code></td>
                                    <td class="text-center"><i class="<?= $sm['icon']; ?>"></i></td>
                                    <td class="text-center"><?= $sm['sort_order']; ?></td>
                                    <td class="text-center">
                                        <?= $sm['is_active'] == 1 
                                            ? '<span class="badge badge-success">Aktif</span>' 
                                            : '<span class="badge badge-danger">Non-Aktif</span>'; ?>
                                    </td>
                                    <?php if (user_can('edit') || user_can('delete')) : ?>
                                        <td>
                                            <?php if (user_can('edit')) : ?>
                                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editSubMenuModal<?= $sm['id']; ?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            <?php endif; ?>

                                            <?php if (user_can('delete')) : ?>
                                                <a href="<?= base_url('menu/delete_submenu/' . $sm['id']); ?>" class="btn btn-sm btn-danger btn-delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>

                                <div class="modal fade" id="editSubMenuModal<?= $sm['id']; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Submenu</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <?= form_open('menu/edit_submenu'); ?>
                                                <input type="hidden" name="id" value="<?= $sm['id']; ?>">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Judul Submenu</label>
                                                        <input type="text" class="form-control" name="title" value="<?= $sm['title']; ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Induk Menu Utama</label>
                                                        <select name="menu_id" class="form-control">
                                                            <?php foreach ($menu as $m) : ?>
                                                                <option value="<?= $m['id']; ?>" <?= ($m['id'] == $sm['menu_id']) ? 'selected' : ''; ?>><?= $m['menu']; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>URL</label>
                                                        <input type="text" class="form-control" name="url" value="<?= $sm['url']; ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Icon Class</label>
                                                        <input type="text" class="form-control" name="icon" value="<?= $sm['icon']; ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Nomor Urutan</label>
                                                        <input type="number" class="form-control" name="sort_order" value="<?= $sm['sort_order']; ?>" required>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="is_active" value="1" id="is_active_edit<?= $sm['id']; ?>" <?= $sm['is_active'] == 1 ? 'checked' : ''; ?>>
                                                        <label class="custom-control-label" for="is_active_edit<?= $sm['id']; ?>">Status Aktif</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
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
<div class="modal fade" id="newSubMenuModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Submenu Baru</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <?= form_open('menu/submenu'); ?> 
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" name="title" placeholder="Judul Submenu">
                    </div>
                    <div class="form-group">
                        <select name="menu_id" class="form-control">
                            <option value="">Pilih Menu Induk</option>
                            <?php foreach ($menu as $m) : ?>
                                <option value="<?= $m['id']; ?>"><?= $m['menu']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="url" placeholder="URL (Contoh: menu/submenu)">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="icon" placeholder="Icon FontAwesome (Contoh: fas fa-fw fa-folder)">
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control" name="sort_order" placeholder="Nomor Urut">
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="is_active" value="1" id="is_active_add" checked>
                        <label class="custom-control-label" for="is_active_add">Status Aktif?</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>