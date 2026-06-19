# UI/UX Specification

## Prinsip Tampilan

Tampilan mengikuti konsep dashboard operasional SmartCRM: padat, jelas, dan berbasis role. Modul
Customer Data Management tidak dibuat seperti landing page, tetapi seperti tool kerja harian untuk
admin, sales, marketing, support, dan manager.

## Navigasi

- Menu utama: `CRM Pelanggan > Manajemen Pelanggan`
- Halaman list: `/admin/customers`
- Halaman tambah: `/admin/customers/create`
- Halaman edit: `/admin/customers/{id}/edit`
- Halaman detail: `/admin/customers/{id}`
- Halaman cek duplikat: `/admin/customers/duplicates`

## Halaman Manajemen Pelanggan

### Header Actions

| Aksi | Role | Fungsi |
| --- | --- | --- |
| Import CSV/Excel | Admin, Marketing | Upload data pelanggan massal. |
| Export Data | Admin, Marketing, Manager/Analyst | Export dari Filament exporter. |
| Unduh Excel | Admin, Marketing, Manager/Analyst | Download langsung file `.xlsx`. |
| Unduh CSV | Admin, Marketing, Manager/Analyst | Download langsung file `.csv`. |
| Cek Duplikat | Admin, Sales, Manager/Analyst | Membuka halaman kandidat duplicate. |
| Create | Admin, Sales, Marketing | Menambah pelanggan baru. |

### Kolom Tabel

- Kode pelanggan
- Nama lengkap
- Email
- Perusahaan
- PIC Sales/Admin
- Tag
- Status
- Tanggal dibuat

### Filter

- Status pelanggan
- Perusahaan
- PIC Sales/Admin
- Tag pelanggan
- Search bawaan tabel untuk kode, nama, email, perusahaan, dan PIC

## Form Customer

### Section: Informasi Utama Pelanggan

Berisi field identitas utama seperti kode, nama, email, telepon, perusahaan, status, dan PIC.

State validasi:

- Kode pelanggan wajib dan unik.
- Nama lengkap wajib.
- Email wajib, valid, dan unik.
- Status wajib.
- PIC boleh kosong jika belum ditugaskan.

### Section: Segmentasi Pelanggan

User dapat memilih lebih dari satu tag seperti `VIP`, `Prioritas Tinggi`, `B2B`, atau `Retail`.
Admin/Marketing juga bisa membuat tag baru dari form.

### Section: Atribut Data Tambahan

Berisi repeater custom field:

- Nama atribut, contoh `Instagram`, `NPWP`, `Kategori VIP`.
- Nilai atribut, contoh `@customer.id`, `01.234.567.8-901.000`, `Gold`.

### Section: Riwayat Aktivitas

Menampilkan log perubahan data pelanggan. Field dibuat read-only karena log adalah bukti audit,
bukan data input manual.

## Halaman Cek Duplikat

Tujuan halaman ini adalah memudahkan demo smart merge tanpa harus memakai Postman.

Komponen:

- Daftar kandidat duplicate maksimal 20 pasangan.
- Duplicate score.
- Alasan match, misalnya email sama, nomor telepon sama, nama sama, perusahaan sama.
- Informasi customer utama dan customer duplikat.
- Tombol merge untuk menggabungkan pasangan data.

State:

- Jika tidak ada kandidat: tampilkan kondisi kosong.
- Jika merge berhasil: tampilkan notifikasi sukses dan data duplikat hilang dari kandidat.

## Perbedaan Tampilan Per Role

| Role | Tampilan yang Menonjol |
| --- | --- |
| Admin | Full management, import, export, duplicate, create, edit, merge. |
| Sales | Fokus follow-up: create/edit customer, assignment, duplicate check, merge. |
| Marketing | Fokus segmentasi: tag, import, export, edit data campaign. |
| Support | Fokus read-only: melihat detail dan riwayat customer. |
| Manager/Analyst | Fokus monitoring: statistik, export, filter, duplicate review. |

## Demo Flow

1. Login sebagai Admin.
2. Buka Manajemen Pelanggan.
3. Tunjukkan stats widget: Total Pelanggan, Lead Aktif, Baru Bulan Ini, Belum Ditugaskan.
4. Tunjukkan filter status/tag/PIC.
5. Tambah customer baru dengan custom field dan tag.
6. Import file CSV/Excel.
7. Export CSV/Excel.
8. Buka Cek Duplikat dan merge satu kandidat.
9. Buka detail customer untuk melihat activity log.
10. Login role lain untuk menunjukkan perbedaan akses.
