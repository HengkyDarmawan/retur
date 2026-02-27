<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Security Settings - Update Password</h6>
                </div>
                <div class="card-body">
                    <div class="col-lg-7">
                        <?= form_open('user/changepassword'); ?>
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                                <?= form_error('current_password', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="new_password1">New Password</label>
                                <input type="password" class="form-control" id="new_password1" name="new_password1">
                                <?= form_error('new_password1', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="form-group">
                                <label for="new_password2">Repeat New Password</label>
                                <input type="password" class="form-control" id="new_password2" name="new_password2">
                                <?= form_error('new_password2', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary btn-icon-split">
                                    <span class="icon text-white-50"><i class="fas fa-key"></i></span>
                                    <span class="text">Change Password</span>
                                </button>
                                <a href="<?= base_url('user'); ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
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
            showConfirmButton: true
        });
    </script>
<?php endif; ?>
<script>
    // Jika form disubmit dan terjadi error CSRF (halaman reload)
    // Kita bisa arahkan balik dan beri notifikasi
    <?php if ($this->session->flashdata('swal_icon') == 'error') : ?>
        Swal.fire({
            icon: 'error',
            title: 'Sesi Kadaluwarsa',
            text: 'Silakan coba lagi, sesi keamanan Anda telah diperbarui.',
        });
    <?php endif; ?>
</script>