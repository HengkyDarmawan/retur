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
                                    <?php if($type == 'holidays'): ?>
                                        <th width="20%">Tanggal Libur</th>
                                    <?php endif; ?>
                                    <th>Nama / Deskripsi</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 1; 
                                    foreach ($list as $l) : 
                                        // Mapping field berdasarkan type
                                        $field_map = [
                                            'platforms'    => 'platform_name',
                                            'stores'       => 'store_name',
                                            'vendors'      => 'vendor_name',
                                            'expeditions'  => 'expedition_name',
                                            'return_types' => 'type_name',
                                            'holidays'     => 'description'
                                        ];
                                        $field_name = $field_map[$type] ?? 'name';
                                        $val = $l[$field_name];
                                    ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <?php if($type == 'holidays'): ?>
                                            <td><span class="badge badge-info"><?= date('d M Y', strtotime($l['holiday_date'])); ?></span></td>
                                        <?php endif; ?>
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
                                                        <?php if($type == 'holidays'): ?>
                                                            <div class="form-group">
                                                                <label>Tanggal Libur</label>
                                                                <input type="date" class="form-control" name="date" value="<?= $l['holiday_date']; ?>" required>
                                                            </div>
                                                        <?php endif; ?>

                                                        <div class="form-group">
                                                            <label>Nama / Deskripsi</label>
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
                    <?php if($type == 'holidays'): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Selesai (Opsional)</label>
                                <input type="date" class="form-control" name="end_date">
                                <small class="text-muted">*Isi jika libur lebih dari 1 hari</small>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label><?= ($type == 'holidays') ? 'Deskripsi Libur' : 'Nama'; ?></label>
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