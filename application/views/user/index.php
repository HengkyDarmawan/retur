<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center border-right">
                            <div class="p-3">
                                <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" 
                                class="img-fluid rounded-circle shadow-sm border" 
                                style="width: 200px; height: 200px; object-fit: cover;"
                                loading="lazy" 
                                decoding="async"
                                alt="Profile Picture">
                                <h4 class="mt-3 font-weight-bold text-gray-800"><?= $user['name']; ?></h4>
                                <span class="badge badge-pill <?= ($user['role_id'] == 1) ? 'badge-primary' : 'badge-info'; ?> px-3">
                                    <?= ($user['role_id'] == 1) ? 'Administrator' : 'Staff/User'; ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="pl-md-4">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <h6 class="text-xs font-weight-bold text-primary text-uppercase mb-1">Username</h6>
                                        <p class="h6 mb-3 text-gray-700"><?= $user['username']; ?></p>
                                    </div>
                                    <div class="col-sm-4">
                                        <h6 class="text-xs font-weight-bold text-primary text-uppercase mb-1">Email Address</h6>
                                        <p class="h6 mb-3 text-gray-700"><?= $user['email']; ?></p>
                                    </div>
                                    <div class="col-sm-4">
                                        <h6 class="text-xs font-weight-bold text-primary text-uppercase mb-1">Member Since</h6>
                                        <p class="h6 mb-3 text-gray-700"><?= date('d F Y', $user['date_created']); ?></p>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="d-flex flex-wrap align-items-center">
                                    <a href="<?= base_url('user/edit'); ?>" class="btn btn-primary btn-icon-split mr-2 mb-2">
                                        <span class="icon text-white-50"><i class="fas fa-user-edit"></i></span>
                                        <span class="text">Edit My Profile</span>
                                    </a>
                                    <a href="<?= base_url('user/changepassword'); ?>" class="btn btn-light btn-icon-split border mb-2">
                                        <span class="icon text-gray-600"><i class="fas fa-lock"></i></span>
                                        <span class="text">Security Settings</span>
                                    </a>
                                </div>
                                
                                <div class="mt-3 p-3 bg-light rounded small">
                                    <i class="fas fa-info-circle text-info"></i> 
                                    Tips: Pastikan email Anda selalu aktif untuk keperluan verifikasi dan notifikasi sistem.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($this->session->flashdata('swal_icon')) : ?>
    <script>
        Swal.fire({
            icon: '<?= $this->session->flashdata('swal_icon'); ?>',
            title: '<?= $this->session->flashdata('swal_title'); ?>',
            text: '<?= $this->session->flashdata('swal_text'); ?>',
            timer: 2500,
            showConfirmButton: false,
            timerProgressBar: true
        });
    </script>
<?php endif; ?>