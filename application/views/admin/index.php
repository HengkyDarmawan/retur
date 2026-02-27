<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-network-wired"></i> Top 5 IP Aktif</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>IP Address</th>
                            <th>Total Aksi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($top_ips as $ip) : ?>
                        <tr>
                            <td><?= $ip['ip_address']; ?></td>
                            <td><span class="badge badge-info"><?= $ip['total']; ?> hits</span></td>
                            <td>
                                <a href="https://ipinfo.io/<?= $ip['ip_address']; ?>" target="_blank" class="btn btn-xs btn-outline-primary">Cek Lokasi</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4 border-left-danger">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-exclamation-triangle"></i> Indikasi Akun Dishare</h6>
            </div>
            <div class="card-body">
                <?php if($multi_login) : ?>
                    <div class="alert alert-warning small">User di bawah ini login dari banyak IP dalam 24 jam terakhir.</div>
                    <?php foreach($multi_login as $ml) : ?>
                        <div class="mb-2">
                            <strong><?= $ml['username']; ?></strong>: <span class="text-danger"><?= $ml['ip_count']; ?> IP Berbeda</span><br>
                            <small class="text-muted"><?= $ml['ips']; ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="text-center text-success small">Semua akun aman (1 Akun = 1 IP).</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>