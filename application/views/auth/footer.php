
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/')?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/')?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/')?>vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/')?>js/sb-admin-2.min.js"></script>
    <script>
        const flashData = "<?= $this->session->flashdata('message'); ?>";
        const type = "<?= $this->session->flashdata('type'); ?>"; // kita tambah session 'type'

        if (flashData) {
            Swal.fire({
                title: type === 'success' ? 'Berhasil!' : 'Ops!',
                text: flashData,
                icon: type, // success, error, warning, info
                confirmButtonText: 'OK',
                confirmButtonColor: '#4e73df' // Warna primary SB Admin 2
            });
        }
    </script>

</body>

</html>