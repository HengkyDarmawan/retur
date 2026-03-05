/**
 * Fungsi Global WhatsApp - Versi Komplit & Fleksibel
 * @param {string} extraInfo - Berisi Resi (jika shipped) atau Keterangan (jika lainnya)
 */
function sendWaGlobal(phone, resi, status, customerName, baseUrl, extraInfo = '') {
    let msg = "";
    let trackingUrl = baseUrl + 'tracking/check/' + resi; 
    let infoLow = extraInfo.toLowerCase();

    // 1. Normalisasi nomor WA (Hapus karakter non-angka, ubah 0 ke 62)
    let formattedPhone = phone.replace(/\D/g, '');
    if (formattedPhone.startsWith('0')) {
        formattedPhone = '62' + formattedPhone.substring(1);
    }

    // 2. Mapping Status ke Pesan
    switch(status.toLowerCase()) {
        case 'received':
            msg = `Halo Kak ${customerName}, paket retur dengan nomor #${resi} telah kami TERIMA dengan baik. Saat ini sedang masuk antrean pengecekan teknisi. Kami akan segera menginfokan hasilnya ya Kak. Mohon kesediaannya menunggu.`;
            break;

        case 'checking':
            msg = `Halo Kak ${customerName}, menginfokan bahwa paket retur #${resi} saat ini sedang dalam proses PENGECEKAN mendalam oleh tim teknisi kami. Terima kasih atas kesabarannya Kak.`;
            break;

        case 'to_vendor':
            msg = `Halo Kak ${customerName}, menginfokan bahwa unit #${resi} perlu kami KIRIM KE VENDOR untuk proses klaim garansi pabrik agar mendapatkan penanganan maksimal. Estimasi waktu mengikuti kebijakan vendor ya Kak.`;
            break;

        case 'processing':
            msg = `Halo Kak ${customerName}, unit retur #${resi} saat ini sedang dalam TAHAP PERBAIKAN oleh teknisi kami. Kami pastikan unit dicek kembali sebelum dikirim balik.`;
            break;

        case 'from_vendor':
            msg = `Halo Kak ${customerName}, unit retur #${resi} telah KEMBALI DARI VENDOR. Saat ini sedang kami QC ulang.\n\nBoleh minta tolong kirimkan kembali *Alamat Pengiriman lengkapnya* Kak?\n\nJika ada *Nama Penerima atau No. HP yang berbeda*, mohon diinfokan juga ya Kak.`;
            break;

        case 'ready':
            msg = `Halo Kak ${customerName}, unit retur #${resi} sudah SELESAI dan SIAP DIKIRIM. Data pengiriman sudah kami terima dan sedang dalam proses packing. Mohon ditunggu update resinya ya Kak.`;
            break;

        case 'shipped':
            // Di sini extraInfo berisi: Nama Ekspedisi (Nomor Resi)
            msg = `Halo Kak ${customerName}, paket retur #${resi} SUDAH DIKIRIM kembali hari ini.\n\nKurir: *${extraInfo}*\n\nSemoga unitnya awet dan tidak ada kendala lagi ya Kak.`;
            break;

        case 'rejected':
            // Mengambil alasan dari Keterangan (extraInfo)
            let alasanReject = extraInfo ? `karena: *${extraInfo}*` : "sesuai dengan kebijakan garansi yang berlaku";
            msg = `Halo Kak ${customerName}, terkait pengajuan retur #${resi}, dengan berat hati kami sampaikan bahwa pengajuan BELUM DAPAT DISETUJUI ${alasanReject}.\n\nUnit akan kami bantu kirim balik ke alamat Kakak.`;
            break;

        case 'completed':
            // Cek jika alasannya Salah Kirim
            if (infoLow.includes('salah kirim')) {
                msg = `Halo Kak ${customerName}, pesanan retur #${resi} terkait kendala *SALAH KIRIM* telah selesai kami proses tukar/kirim ulang. Terima kasih atas pengertiannya dan mohon maaf atas ketidaknyamanannya. 🙏`;
            } else {
                let catatanSelesai = extraInfo ? `\n\nCatatan: *${extraInfo}*` : "";
                msg = `Halo Kak ${customerName}, transaksi retur #${resi} telah dinyatakan SELESAI.${catatanSelesai}\n\nTerima kasih banyak telah mempercayai layanan kami. Sehat selalu Kak!`;
            }
            break;

        default:
            msg = `Halo Kak ${customerName}, update terbaru untuk proses retur #${resi} saat ini adalah: *${status.toUpperCase()}*.`;
    }

    // Tambahkan Link Tracking di akhir
    msg += `\n\nDetail lengkap silakan cek di sini:\n${trackingUrl}`;

    // 3. Eksekusi SweetAlert Preview
    Swal.fire({
        title: 'Kirim Notifikasi WA?',
        html: `
            <div class="text-left small p-3 bg-light border" style="border-radius:10px; max-height:200px; overflow-y:auto;">
                <strong>Preview Pesan:</strong><br><br>
                ${msg.replace(/\n/g, '<br>')}
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#25d366',
        confirmButtonText: '<i class="fab fa-whatsapp"></i> Buka WhatsApp',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open(`https://api.whatsapp.com/send?phone=${formattedPhone}&text=${encodeURIComponent(msg)}`, '_blank');
        }
    });
}