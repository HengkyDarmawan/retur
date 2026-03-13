 </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yakin ingin keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Logout" di bawah jika kamu siap untuk mengakhiri sesi ini.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    
                    <?php echo form_open('auth/logout'); ?>
                        <button type="submit" class="btn btn-primary">Logout</button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/js/wa-handler.js'); ?>"></script>
    <script src="<?= base_url('assets/')?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/')?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/')?>vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= base_url('assets/')?>js/sb-admin-2.min.js"></script>
    
    <script src="<?= base_url('assets/')?>vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url('assets/')?>vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Hancurkan inisialisasi lama jika ada untuk mencegah konflik
            if ($.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable().destroy();
            }

            $('#dataTable').DataTable({
                // Urutkan berdasarkan kolom indeks ke-1 (Tgl Masuk) secara Descending
                "order": [[1, "desc"]], 
                "stateSave": false, // Jangan simpan posisi sortir terakhir di browser
                "columnDefs": [
                    // Matikan fitur klik sortir di kolom selain tanggal agar tidak sengaja teracak
                    { "orderable": false, "targets": [0, 2, 3, 4, 5] },
                    // Beritahu DataTables bahwa kolom indeks 1 adalah tipe tanggal
                    { "type": "date", "targets": 1 }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
                }
            });
        });
        $(document).ready(function() {
            // 1. Inisialisasi DataTables
            if ($('#dataTable').length > 0) {
                $('#dataTable').DataTable();
            }

            // 2. Logika SweetAlert untuk FlashData (Redirect)
            const flashData = "<?= $this->session->flashdata('message'); ?>";
            const type = "<?= $this->session->flashdata('type'); ?>";
            
            if (flashData) {
                let title = 'Informasi';
                if (type === 'success') title = 'Berhasil!';
                if (type === 'error') title = 'Gagal!';
                if (type === 'warning') title = 'Peringatan!';

                Swal.fire({
                    title: title,
                    text: flashData,
                    icon: type,
                    confirmButtonColor: '#4e73df'
                });
            }

            // 3. Logika Tombol Hapus (Confirm Dialog)
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const href = $(this).attr('href');
                Swal.fire({
                    title: 'Yakin hapus data?',
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });

            // 4. Logika Ubah Akses Role (AJAX + CSRF)
            $('.check-access').on('click', function() {
                const roleId = $(this).data('role');
                const submenuId = $(this).data('submenu');
                const typeAccess = $(this).data('type');

                // Ambil token CSRF terbaru dari PHP
                const csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
                const csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

                $.ajax({
                    url: "<?= base_url('admin/changeaccess'); ?>",
                    type: 'post',
                    data: {
                        [csrfName]: csrfHash, 
                        roleId: roleId,
                        submenuId: submenuId,
                        type: typeAccess
                    },
                    success: function() {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: true
                        });

                        Toast.fire({
                            icon: 'success',
                            title: 'Akses berhasil diperbarui'
                        }).then(() => {
                            // Reload wajib agar token CSRF & sidebar terupdate
                            location.reload(); 
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal mengubah akses. Token mungkin kadaluwarsa, silakan refresh.', 'error');
                    }
                });
            });
        });
    </script>
    
    

</body>

</html>