# Test Notes

## Environment

- OS lokal: Windows + Laragon
- Backend: Laravel
- Admin panel: Filament
- Database: MySQL/MariaDB lokal
- URL admin: `http://127.0.0.1:8000/admin/login`
- URL API: `http://127.0.0.1:8000/api/v1`

## Command Validasi

```bash
php artisan test
npm run build
php artisan route:list --path=customers
php artisan demo:reset
```

Catatan: `php artisan demo:reset` membutuhkan MySQL Laragon aktif di port 3306.

## Happy Path

| Skenario | Langkah | Expected Result |
| --- | --- | --- |
| Login admin | Login `admin@smartcrm.com` / `password` | Masuk dashboard admin. |
| Tambah customer | Isi kode, nama, email, status, simpan | Data muncul di list customer. |
| Isi 20+ field standar | Lengkapi identitas, kontak, alamat, klasifikasi, follow-up | Semua field tersimpan. |
| Edit customer | Ubah status/tag/PIC | Perubahan tersimpan dan activity log dibuat. |
| Import CSV/Excel | Upload file customer | Data masuk ke database. |
| Export CSV | Klik Unduh CSV | File CSV terunduh. |
| Export Excel | Klik Unduh Excel | File XLSX terunduh. |
| Filter status | Pilih status Lead/Customer | Tabel hanya menampilkan status terkait. |
| Filter tag | Pilih tag VIP/B2B/Retail | Tabel hanya menampilkan customer dengan tag terkait. |
| Cek duplikat | Buka halaman duplicate | Kandidat duplikat tampil beserta score dan alasan. |
| Merge duplikat | Klik merge pasangan customer | Data digabung dan duplikat dihapus. |
| Activity log | Buka detail customer | Riwayat perubahan dapat dilihat. |
| Favorite list | Klik action favorite lalu filter Favorite List | Customer favorit tampil sesuai filter. |
| Attachment UI | Tambah lampiran di form customer | File tersimpan dan dapat dibuka/diunduh dari UI. |

## API Test Scenario

### List customer

```bash
curl http://127.0.0.1:8000/api/v1/customers
```

### Filter customer

```bash
curl "http://127.0.0.1:8000/api/v1/customers?status=Lead&tag=VIP"
```

### Duplicate detection

```bash
curl "http://127.0.0.1:8000/api/v1/customers/duplicates?threshold=50"
```

### Merge duplicate

```bash
curl -X POST http://127.0.0.1:8000/api/v1/customers/1/merge \
  -H "Content-Type: application/json" \
  -d "{\"duplicate_id\":2,\"strategy\":\"prefer_complete\"}"
```

### Export JSON

```bash
curl http://127.0.0.1:8000/api/v1/customers/export/json
```

## Edge Cases

| Kasus | Expected Result |
| --- | --- |
| Email sudah dipakai saat create | Validasi gagal `422`. |
| Customer code sudah dipakai | Validasi gagal `422`. |
| File import bukan CSV/XLSX/XLS | Validasi gagal `422`. |
| Merge dengan `duplicate_id` sama dengan primary id | Validasi gagal. |
| Customer tidak ditemukan | Response `404`. |
| Attachment lebih dari 10 MB | Validasi gagal. |
| Role Support mencoba edit customer | Action edit tidak tampil atau akses ditolak. |

## Known Issue

- Jika database lokal belum aktif, command demo reset dan akses admin akan gagal karena koneksi MySQL ditolak.
- UI favorite dan attachment sudah tersedia. Endpoint API tetap dipakai untuk skenario integrasi antar modul.

## Rekomendasi Testing Lanjutan

1. Tambahkan feature test untuk duplicate detection dan merge.
2. Tambahkan feature test untuk role access CustomerResource.
3. Tambahkan test import dengan file contoh 50 data.
4. Tambahkan screenshot halaman list, form, duplicate, dan dashboard untuk lampiran laporan akhir.
