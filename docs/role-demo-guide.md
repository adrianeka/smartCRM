# SmartCRM79 Role Demo Guide

Gunakan password `password` untuk semua akun demo.

## Super Admin

Email: `admin@smartcrm.com`

Tujuan demo:
- Kelola akun pengguna di menu Users.
- Lihat semua customer di Customer Management.
- Tunjukkan widget statistik customer: Total Customers, Active Leads, New This Month, dan Unassigned.
- Assign customer ke Sales/Admin tertentu.
- Pantau Audit Log dan Webhook Logs.

Narasi:
Super Admin bertanggung jawab mengatur sistem, user, data pelanggan, dan pemantauan aktivitas teknis.

## Sales

Email: `sales@smartcrm.com`

Tujuan demo:
- Buka Customer Management.
- Tambah customer baru.
- Edit data customer.
- Isi PIC/assignment dan tag segmentasi pelanggan.
- Klik Cek Duplikat.
- Gunakan Merge Duplikat dari baris customer utama.
    
Narasi:
Sales berfokus pada pengelolaan relasi pelanggan, follow-up, dan menjaga data customer tetap bersih dari duplikasi.

## Marketing

Email: `marketing@smartcrm.com`

Tujuan demo:
- Buka Customer Management.
- Tambah atau edit customer dengan custom fields seperti `Segment`, `Kategori`, atau `Campaign Source`.
- Tambahkan tag seperti `VIP`, `B2B`, atau `Retail`.
- Gunakan filter status, perusahaan, tag, dan PIC.
- Import CSV/Excel.
- Download Excel atau CSV.

Narasi:
Marketing menggunakan data customer untuk segmentasi, campaign, dan kebutuhan export data.

## Support

Email: `support@smartcrm.com`

Tujuan demo:
- Buka Customer Management.
- Gunakan pencarian customer.
- Gunakan filter status/tag untuk mencari kelompok pelanggan.
- Klik View pada customer.
- Tunjukkan bahwa role ini fokus melihat data, bukan mengubah data.

Narasi:
Support membutuhkan akses cepat ke identitas dan histori customer untuk membantu pelayanan.

## Manager/Analyst

Email: `manager@smartcrm.com`

Tujuan demo:
- Buka dashboard performa.
- Buka Customer Management untuk review data.
- Lihat widget statistik di bagian atas halaman Customer.
- Gunakan filter perusahaan, tag, dan PIC untuk analisis.
- Download Excel/CSV.
- Buka Audit Log untuk memantau aktivitas.

Narasi:
Manager memantau kualitas data, performa, dan aktivitas sistem tanpa melakukan perubahan operasional harian.

## Skenario Duplikasi Customer

Buat dua customer mirip:

Customer utama:
- Kode: `CUST-DEMO-001`
- Nama: `Budi Santoso`
- Email: `budi.primary@mail.com`
- Phone: `08123456789`

Customer duplikat:
- Kode: `CUST-DEMO-002`
- Nama: `Budi Santoso`
- Email: `budi.secondary@mail.com`
- Phone: `08123456789`

Lalu:
- Klik Cek Duplikat.
- Kembali ke Customer Management.
- Pada customer utama, klik Merge Duplikat.
- Pilih customer duplikat.
- Pilih strategi `Pakai data yang paling lengkap`.
- Konfirmasi merge.
