# Panduan Demo Presentasi SmartCRM79

## Modul Customer Data Management

Dokumen ini dibuat untuk membantu anggota kelompok memahami alur project dan melakukan demo
modul **Customer Data Management** secara runtut.

---

# 1. Tujuan Demo

Modul **Customer Data Management** adalah bagian SmartCRM yang berfungsi sebagai pusat data
pelanggan atau **single source of truth**.

Artinya, semua data pelanggan disimpan dan dikelola di satu tempat agar dapat digunakan oleh
modul lain seperti:

- Sales
- Marketing
- Support
- Dashboard
- Integration/API

Fitur utama yang didemokan:

- CRUD customer
- 20+ field standar customer
- Dynamic custom fields
- Tagging dan segmentasi
- Assignment PIC Sales/Admin
- Favorite list
- Attachment customer
- Import/export CSV, Excel, JSON
- Search dan filter
- Duplicate detection
- Smart merge
- Activity log
- Perbedaan akses berdasarkan role

---

# 2. Persiapan Sebelum Demo

1. Buka Laragon.
2. Start Apache/Nginx dan MySQL.
3. Buka terminal di folder project:

```bash
cd D:\laragon\www\smartCRM
```

4. Jalankan migrasi dan seeder jika belum:

```bash
php artisan migrate
php artisan db:seed
```

5. Jalankan server Laravel:

```bash
php artisan serve
```

6. Buka halaman admin:

```text
http://127.0.0.1:8000/admin/login
```

---

# 3. Akun Demo

Semua akun menggunakan password:

```text
password
```

| Role | Email |
| --- | --- |
| Admin | `admin@smartcrm.com` |
| Sales | `sales@smartcrm.com` |
| Marketing | `marketing@smartcrm.com` |
| Support | `support@smartcrm.com` |
| Manager/Analyst | `manager@smartcrm.com` |

---

# 4. Pembuka Presentasi

Gunakan kalimat berikut saat membuka presentasi:

> Pada project SmartCRM79, kelompok kami bertanggung jawab pada modul Customer Data
> Management. Modul ini berfungsi sebagai pusat data pelanggan atau single source of truth.
> Semua data customer dikelola di satu tempat agar bisa digunakan oleh modul lain seperti
> Sales, Marketing, Support, Dashboard, dan Integration.

---

# 5. Alur Demo Admin

## 5.1 Login Admin

Login menggunakan:

```text
admin@smartcrm.com
password
```

Jelaskan:

> Admin memiliki akses paling lengkap untuk mengelola data customer.

## 5.2 Buka Menu Manajemen Pelanggan

Buka menu:

```text
CRM Pelanggan > Manajemen Pelanggan
```

Jelaskan:

> Halaman ini adalah pusat pengelolaan customer. Semua data pelanggan tersimpan di sini.

## 5.3 Jelaskan Widget Statistik

Tunjukkan widget di bagian atas:

- Total Pelanggan
- Lead Aktif
- Baru Bulan Ini
- Belum Ditugaskan

Jelaskan:

> Widget statistik membantu admin atau manager melihat kondisi data customer secara cepat.

## 5.4 Tambah Customer Baru

Klik tombol **Create** atau **Tambah Customer**.

Contoh data:

| Field | Contoh |
| --- | --- |
| Kode Pelanggan | `CUST-DEMO-001` |
| Nama Lengkap | `Demo Customer` |
| Email | `demo.customer@mail.com` |
| Telepon | `081234567890` |
| WhatsApp | `081234567890` |
| Perusahaan | `PT Demo Indonesia` |
| Industri | `Retail` |
| Status | `Lead` |
| Tipe Customer | `B2B` |
| Source | `Campaign` |
| Lead Score | `80` |
| PIC | Pilih salah satu Sales/Admin |

Jelaskan:

> Data customer tidak hanya berisi nama dan email. Sistem ini sudah mendukung 20+ field
> standar agar informasi pelanggan lebih lengkap.

## 5.5 Demo Validasi Data

Jelaskan aturan validasi:

- Kode pelanggan wajib.
- Nama pelanggan wajib.
- Email wajib dan harus valid.
- Email harus unik.
- Kode customer harus unik.
- Status customer wajib.

Kalimat demo:

> Jika ada data yang tidak valid atau email sudah digunakan, sistem akan menolak input dan
> menampilkan pesan validasi.

## 5.6 Demo Dynamic Custom Fields

Di bagian **Atribut Data Tambahan**, tambahkan contoh:

| Nama Field | Tipe | Nilai |
| --- | --- | --- |
| Instagram | Text | `@demo.customer` |
| Kategori Loyalitas | Dropdown | `Gold` |
| Tanggal Kontrak | Date | Pilih tanggal |
| Prioritas Tinggi | Checkbox | Aktif |

Jelaskan:

> Custom fields digunakan ketika admin membutuhkan atribut tambahan tanpa harus mengubah
> struktur database utama.

## 5.7 Demo Tagging dan Segmentasi

Pilih tag:

- VIP
- B2B
- Prioritas Tinggi

Jelaskan:

> Tag digunakan untuk mengelompokkan customer agar Sales dan Marketing lebih mudah
> membuat segmentasi.

## 5.8 Demo Assignment PIC

Tunjukkan field:

```text
Ditugaskan ke Sales/Admin
```

Jelaskan:

> Setiap customer bisa ditugaskan ke PIC tertentu agar jelas siapa yang bertanggung jawab
> menangani customer tersebut.

## 5.9 Demo Attachment

Tambahkan file contoh, misalnya:

- PDF kontrak
- Foto KTP
- Dokumen legal

Jelaskan:

> Attachment digunakan untuk menyimpan dokumen pendukung pelanggan.

## 5.10 Simpan Customer

Klik **Save**.

Jelaskan:

> Setelah data disimpan, sistem otomatis mencatat aktivitas perubahan ke activity log.

---

# 6. Demo Search, Filter, dan Favorite

Kembali ke halaman list customer.

Demo fitur berikut:

1. Search nama customer.
2. Filter status, misalnya `Lead`.
3. Filter tag, misalnya `VIP`.
4. Filter PIC.
5. Klik action favorite atau bintang.
6. Gunakan filter Favorite List.

Jelaskan:

> Search dan filter membantu user menemukan customer dengan cepat walaupun jumlah data
> sudah banyak. Favorite list digunakan untuk menandai customer penting.

---

# 7. Demo Import dan Export

## 7.1 Import Data

Klik:

```text
Import CSV/Excel
```

Gunakan template:

```text
docs/customer-data-management/customer-import-template.csv
```

Jelaskan:

> Import digunakan untuk memasukkan banyak data customer sekaligus tanpa input manual satu
> per satu.

## 7.2 Export Data

Klik:

- Export Data
- Unduh Excel
- Unduh CSV

Untuk export JSON, gunakan endpoint:

```text
http://127.0.0.1:8000/api/v1/customers/export/json
```

Jelaskan:

> Export digunakan untuk laporan, backup, atau integrasi data dengan sistem lain.

---

# 8. Demo Duplicate Detection dan Smart Merge

## 8.1 Cek Duplikat

Klik:

```text
Cek Duplikat
```

Jelaskan:

> Sistem mengecek kemungkinan customer duplikat berdasarkan email, nomor telepon, nama, dan
> perusahaan.

## 8.2 Smart Merge

Jika ada kandidat duplikat, klik merge.

Jelaskan:

> Smart merge menggabungkan dua data customer menjadi satu data yang lebih lengkap. Tag,
> custom field, dan attachment dari customer duplikat ikut dipindahkan ke customer utama.

---

# 9. Demo Activity Log

Buka detail atau edit salah satu customer.

Tunjukkan bagian:

```text
Riwayat Aktivitas & Linimasa Kronologis
```

Jelaskan:

> Activity log digunakan sebagai audit trail. Sistem mencatat aktivitas seperti create,
> update, delete, dan merge sehingga perubahan data bisa ditelusuri.

---

# 10. Demo Role Lain

Setelah demo admin, logout lalu login menggunakan role lain.

## 10.1 Sales

Login:

```text
sales@smartcrm.com
password
```

Jelaskan:

> Sales fokus pada follow-up customer, mengelola customer, assignment, dan pengecekan
> duplikat.

## 10.2 Marketing

Login:

```text
marketing@smartcrm.com
password
```

Jelaskan:

> Marketing fokus pada segmentasi, tagging, import, export, dan data campaign.

## 10.3 Support

Login:

```text
support@smartcrm.com
password
```

Jelaskan:

> Support fokus melihat data customer, attachment, dan activity log untuk membantu layanan
> pelanggan.

## 10.4 Manager

Login:

```text
manager@smartcrm.com
password
```

Jelaskan:

> Manager fokus pada statistik, filter data, export laporan, dan kualitas data customer.

---

# 11. Pembagian Presentasi Berdua

## Orang Pertama: Penjelasan Konsep

Bagian yang dijelaskan:

- Apa itu SmartCRM.
- Apa itu Customer Data Management.
- Kenapa modul ini penting.
- Role pengguna.
- Fitur utama.
- Diagram sederhana.

## Orang Kedua: Demo Aplikasi

Bagian yang didemokan:

- Login admin.
- CRUD customer.
- Custom fields.
- Tagging.
- Assignment PIC.
- Attachment.
- Search dan filter.
- Import/export.
- Duplicate detection.
- Smart merge.
- Activity log.
- Perbedaan role.

---

# 12. Kalimat Penutup Presentasi

Gunakan kalimat berikut:

> Dengan modul Customer Data Management ini, SmartCRM dapat menyimpan data pelanggan
> secara lebih rapi, lengkap, mudah dicari, dapat diimport/export, dapat dicek duplikatnya,
> dan seluruh aktivitas perubahan datanya tercatat melalui activity log. Modul ini menjadi
> fondasi utama untuk pengelolaan pelanggan di SmartCRM.

---

# 13. Checklist Sebelum Presentasi

Pastikan hal berikut sudah siap:

- Laragon aktif.
- MySQL aktif.
- `php artisan serve` berjalan.
- Akun demo bisa login.
- Data customer tersedia.
- Ada contoh data duplikat.
- Ada file template import.
- Browser sudah membuka halaman login.
- Alur demo sudah dibagi antara dua orang.
