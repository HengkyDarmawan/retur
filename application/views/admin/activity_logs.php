<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Log Aktivitas Sistem</h6>
            <span class="badge badge-primary p-2">Total Log: <?= count($logs); ?></span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="bg-light text-center small">
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Aksi</th>
                            <th>Tabel</th>
                            <th>Data ID</th>
                            <th>Detail Perubahan</th>
                            <th>IP & Security</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $l) : ?>
                            <?php 
                                $badge = 'badge-secondary';
                                if ($l['action'] == 'CREATE') $badge = 'badge-success';
                                if ($l['action'] == 'UPDATE') $badge = 'badge-warning';
                                if ($l['action'] == 'DELETE') $badge = 'badge-danger';
                            ?>
                            <tr>
                                <td class="small text-nowrap text-center"><?= date('d-m-Y H:i:s', strtotime($l['created_at'])); ?></td>
                                <td><strong><?= $l['username'] ?? 'System'; ?></strong></td>
                                <td class="text-center"><span class="badge <?= $badge; ?>"><?= $l['action']; ?></span></td>
                                <td><code><?= $l['table_name']; ?></code></td>
                                <td class="text-center"><?= $l['data_id']; ?></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailLog<?= $l['id']; ?>">
                                        <i class="fas fa-eye"></i> Lihat Perubahan
                                    </button>
                                </td>
                                <td class="small">
                                    <i class="fas fa-network-wired"></i> <?= $l['ip_address']; ?><br>
                                    <a href="https://ipinfo.io/<?= $l['ip_address']; ?>" target="_blank" class="text-primary">[Lokasi]</a>
                                    <a href="<?= base_url('admin/block_ip/' . $l['ip_address']); ?>" class="text-danger ml-1" onclick="return confirm('Ban IP ini?')">[Ban IP]</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php foreach ($logs as $l) : ?>
    <?php 
        $badge = 'badge-secondary';
        if ($l['action'] == 'CREATE') $badge = 'badge-success';
        if ($l['action'] == 'UPDATE') $badge = 'badge-warning';
        if ($l['action'] == 'DELETE') $badge = 'badge-danger';
    ?>
    <div class="modal fade" id="detailLog<?= $l['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">Audit Detail #<?= $l['id']; ?></h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body text-left">
                    <div class="row mb-3 bg-light p-2 mx-1 rounded small">
                        <div class="col-6">
                            <strong>Table:</strong> <?= $l['table_name']; ?> (ID: <?= $l['data_id']; ?>)<br>
                            <strong>User:</strong> <?= $l['username'] ?? 'System'; ?>
                        </div>
                        <div class="col-6 text-right">
                            <strong>Action:</strong> <span class="badge <?= $badge; ?>"><?= $l['action']; ?></span><br>
                            <strong>Time:</strong> <?= date('d M Y, H:i:s', strtotime($l['created_at'])); ?>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="bg-dark text-white text-center small">
                                <tr>
                                    <th>Kolom</th>
                                    <th>Data Lama (Before)</th>
                                    <th>Data Baru (After)</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <?php 
                                    $before = json_decode($l['data_before'], true) ?? [];
                                    $after = json_decode($l['data_after'], true) ?? [];
                                    $keys = array_unique(array_merge(array_keys($before), array_keys($after)));
                                    foreach ($keys as $k) : 
                                        $v_old = $before[$k] ?? 'null';
                                        $v_new = $after[$k] ?? 'null';
                                        $diff = ($l['action'] == 'UPDATE' && $v_old !== $v_new);
                                ?>
                                    <tr class="<?= $diff ? 'bg-warning-light' : ''; ?>">
                                        <td class="font-weight-bold bg-light"><?= $k; ?></td>
                                        <td class="<?= $diff ? 'text-danger' : ''; ?>"><?= $v_old; ?></td>
                                        <td class="<?= $diff ? 'text-success font-weight-bold' : ''; ?>"><?= $v_new; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<style>
    .bg-warning-light { background-color: #fff9e6 !important; }
    #dataTable td { vertical-align: middle; }
</style>