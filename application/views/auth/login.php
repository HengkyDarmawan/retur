<div class="container" style="margin-top: 200px;">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-6 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                </div>
                                <?php if($this->session->flashdata('message')) : ?>
                                    <?= $this->session->flashdata('message'); ?>
                                <?php endif; ?>
                                <?php echo form_open('auth', ['class' => 'user']); ?>
                                    <div class="form-group">
                                        <input type="text" name="username" class="form-control form-control-user" 
                                            placeholder="Enter Username..." value="<?= set_value('username'); ?>">
                                        <?= form_error('username', '<small class="text-danger ml-3">', '</small>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control form-control-user" 
                                            placeholder="Password">
                                        <?= form_error('password', '<small class="text-danger ml-3">', '</small>'); ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
