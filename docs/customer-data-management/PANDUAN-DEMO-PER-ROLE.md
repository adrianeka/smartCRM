# Panduan Lengkap Demo Per Role SmartCRM79

## Modul Customer Data Management

Dokumen ini dipakai untuk latihan demo dan presentasi. Fokusnya adalah membantu presenter
menjelaskan **fungsi setiap role**, **halaman yang dibuka**, **tombol yang digunakan**, dan
**kalimat penjelasan ke dosen**.

---

# 1. Konsep Utama yang Harus Dipahami

Project SmartCRM79 adalah aplikasi CRM. Bagian yang kita kerjakan adalah:

```text
Customer Data Management
```

Modul ini berfungsi sebagai pusat data pelanggan atau **single source of truth**.

Artinya:

- Data pelanggan disimpan di satu tempat.
- Data bisa digunakan oleh Sales, Marketing, Support, Manager, dan modul lain.
- Sistem membantu menjaga data tetap rapi, lengkap, tidak duplikat, dan mudah dicari.

Kalimat sederhana untuk dosen:

> Modul Customer Data Management adalah fondasi data pelanggan di SmartCRM. Semua data
> customer dibuat, dicari, difilter, diimport, diexport, dicek duplikatnya, digabungkan,
> diberi tag, diberi PIC, dilengkapi attachment, dan dicatat riwayat aktivitasnya.

---

# 2. Persiapan Sebelum Demo

## 2.1 Jalankan Project

1. Buka Laragon.
2. Start Apache/Nginx dan MySQL.
3. Buka terminal di folder project:

```bash
cd D:\laragon\www\smartCRM
```

4. Jika database belum siap, jalankan:

```bash
php artisan migrate
php artisan db:seed
```

5. Jalankan server:

```bash
php artisan serve
```

6. Buka admin panel:

```text
http://127.0.0.1:8000/admin/login
```

## 2.2 Akun Demo

Semua akun memakai password:

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

## 2.3 Urutan Demo yang Disarankan

Urutan paling aman untuk presentasi:

1. Admin
2. Sales
3. Marketing
4. Support
5. Manager/Analyst

Alasannya:

- Admin menunjukkan fitur paling lengkap.
- Role lain tinggal dibandingkan dengan Admin.
- Dosen lebih mudah paham perbedaan akses setiap role.

---

# 3. Peta Halaman dan Fungsi Umum

## 3.1 Dashboard

Setelah login, user masuk ke dashboard.

Yang perlu dijelaskan:

- Dashboard berubah sesuai role.
- Ada welcome card yang menjelaskan fungsi role.
- Ada quick links untuk membuka halaman penting.
- Ada widget ringkasan sesuai role.

Kalimat demo:

> Setelah login, sistem menampilkan dashboard sesuai role. Jadi setiap role tidak perlu
> melihat semua fitur, tetapi langsung diarahkan ke fitur yang paling relevan.

## 3.2 Quick Links

Quick Links adalah tombol cepat di dashboard.

Contoh fungsi:

| Quick Link | Fungsi |
| --- | --- |
| Pelanggan | Membuka halaman Manajemen Pelanggan. |
| Leads & Pipeline | Mengarah ke data customer dengan status lead. |
| Campaigns | Mengarah ke data customer yang relevan untuk campaign. |
| Tickets | Mengarah ke data customer aktif untuk kebutuhan support. |
| Import / Export | Mengarah ke halaman customer untuk proses import/export. |
| Audit Log | Membuka log aktivitas sistem. |
| Analytics | Membuka dashboard monitoring. |
| Pengguna | Membuka manajemen user, khusus Admin. |

Kalimat demo:

> Quick Links membantu user masuk ke fitur yang paling sering digunakan sesuai perannya.

## 3.3 Manajemen Pelanggan

Ini halaman utama modul kita.

Fungsi halaman:

- Melihat daftar customer.
- Menambah customer.
- Mengedit customer.
- Melihat detail customer.
- Search customer.
- Filter status, perusahaan, PIC, tag, favorite.
- Import/export data.
- Cek duplikat.
- Smart merge.
- Menandai favorite.

Kalimat demo:

> Halaman Manajemen Pelanggan adalah pusat pengelolaan data customer. Di sini user bisa
> melihat, mencari, mengelompokkan, mengimpor, mengekspor, dan menjaga kualitas data
> pelanggan.

---

# 4. Demo Role Admin

## 4.1 Tujuan Role Admin

Admin adalah role dengan akses paling lengkap.

Admin bertanggung jawab untuk:

- Mengelola data customer.
- Mengelola user.
- Melakukan import/export.
- Mengecek data duplikat.
- Melakukan smart merge.
- Melihat audit log.
- Memastikan data customer rapi dan valid.

Kalimat pembuka role:

> Pertama, saya login sebagai Admin. Role Admin memiliki akses paling lengkap untuk
> mengelola data customer dan mengawasi kualitas data di sistem.

## 4.2 Langkah Demo Admin

### Langkah 1 - Login Admin

Login:

```text
admin@smartcrm.com
password
```

Yang ditunjukkan:

- Dashboard Admin.
- Welcome card.
- Quick Links.

Jelaskan:

> Di dashboard Admin, sistem menampilkan akses utama seperti Pelanggan, Pengguna, Audit Log,
> Webhook Logs, dan Analytics.

### Langkah 2 - Buka Manajemen Pelanggan

Klik:

```text
Quick Links > Pelanggan
```

atau menu:

```text
CRM Pelanggan > Manajemen Pelanggan
```

Yang ditunjukkan:

- Tabel customer.
- Widget statistik customer.
- Tombol import/export.
- Tombol cek duplikat.
- Tombol create.
- Filter dan search.

Jelaskan:

> Ini adalah halaman utama Customer Data Management. Semua data customer menjadi pusat data
> untuk modul lain seperti Sales, Marketing, Support, dan Dashboard.

### Langkah 3 - Jelaskan Widget Statistik Customer

Tunjukkan statistik:

- Total Pelanggan
- Lead Aktif
- Baru Bulan Ini
- Belum Ditugaskan

Kalimat demo:

> Statistik ini membantu Admin melihat kualitas dan kondisi data customer secara cepat.
> Misalnya, jika ada customer yang belum ditugaskan, Admin bisa segera assign ke Sales.

### Langkah 4 - Demo Tambah Customer

Klik:

```text
Create / Tambah Customer
```

Isi contoh:

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
| PIC | Pilih Sales/Admin |

Jelaskan:

> Form customer sudah mendukung 20+ field standar. Data ini penting karena customer bukan
> hanya nama dan email, tetapi juga punya sumber data, status, tipe customer, score, PIC,
> alamat, catatan, dan informasi follow-up.

### Langkah 5 - Demo Validasi

Jelaskan validasi:

- Kode customer wajib.
- Kode customer harus unik.
- Nama wajib.
- Email wajib dan harus valid.
- Email harus unik.
- Status wajib.

Kalimat demo:

> Jika data tidak valid, sistem langsung memberi pesan validasi. Ini mencegah data customer
> yang salah masuk ke database.

### Langkah 6 - Demo Custom Fields

Buka bagian:

```text
Atribut Data Tambahan
```

Tambahkan:

| Nama Field | Tipe | Nilai |
| --- | --- | --- |
| Instagram | Text | `@demo.customer` |
| Kategori Loyalitas | Dropdown | `Gold` |
| Tanggal Kontrak | Date | Pilih tanggal |
| Prioritas Tinggi | Checkbox | Aktif |

Jelaskan:

> Custom fields digunakan untuk data tambahan yang belum ada di field standar. Admin bisa
> menambahkan atribut baru tanpa programmer harus mengubah struktur database utama.

### Langkah 7 - Demo Tagging dan Segmentasi

Pilih tag:

- VIP
- B2B
- Prioritas Tinggi

Jelaskan:

> Tag digunakan untuk segmentasi customer. Contohnya customer VIP bisa diprioritaskan oleh
> Sales atau dipakai Marketing untuk campaign tertentu.

### Langkah 8 - Demo Assignment PIC

Tunjukkan field:

```text
Ditugaskan ke Sales/Admin
```

Jelaskan:

> Assignment PIC digunakan agar setiap customer jelas siapa penanggung jawabnya. Ini
> membantu kerja Sales dan Admin menjadi lebih teratur.

### Langkah 9 - Demo Attachment

Tambahkan file contoh:

- PDF kontrak
- Foto KTP
- Dokumen legal

Jelaskan:

> Attachment digunakan untuk menyimpan dokumen pendukung customer, seperti kontrak atau
> dokumen identitas.

### Langkah 10 - Simpan Customer

Klik:

```text
Save
```

Jelaskan:

> Setelah data disimpan, sistem mencatat aktivitas ke activity log. Ini berguna sebagai
> audit trail.

### Langkah 11 - Demo Search dan Filter

Di halaman list customer, demo:

1. Search nama customer.
2. Filter status `Lead`.
3. Filter tag `VIP`.
4. Filter PIC.
5. Filter Favorite List.

Jelaskan:

> Search dan filter membantu user menemukan customer dengan cepat walaupun data customer
> sudah banyak.

### Langkah 12 - Demo Favorite

Klik action:

```text
Jadikan Favorit
```

Lalu gunakan filter:

```text
Favorite List
```

Jelaskan:

> Favorite list dipakai untuk menandai customer penting agar mudah ditemukan kembali.

### Langkah 13 - Demo Import

Klik:

```text
Import CSV/Excel
```

Gunakan template:

```text
docs/customer-data-management/customer-import-template.csv
```

Jelaskan:

> Import digunakan untuk memasukkan banyak data customer sekaligus. Ini menghemat waktu
> dibanding input manual satu per satu.

### Langkah 14 - Demo Export

Klik:

- Export Data
- Unduh Excel
- Unduh CSV

Jelaskan:

> Export digunakan untuk laporan, backup, atau kebutuhan integrasi dengan sistem lain.

### Langkah 15 - Demo Duplicate Detection

Klik:

```text
Cek Duplikat
```

Jelaskan:

> Sistem mencari kemungkinan data customer yang sama berdasarkan email, nomor telepon,
> nama, dan perusahaan. Setiap kandidat diberi score.

### Langkah 16 - Demo Smart Merge

Jika ada kandidat duplikat, klik merge.

Jelaskan:

> Smart merge menggabungkan customer duplikat menjadi satu data utama yang lebih lengkap.
> Tag, custom field, dan attachment dipindahkan ke customer utama, lalu data duplikat
> dihapus.

### Langkah 17 - Demo Activity Log

Buka detail/edit customer, lalu tunjukkan:

```text
Riwayat Aktivitas & Linimasa Kronologis
```

Jelaskan:

> Activity log mencatat perubahan data customer. Ini penting agar sistem punya riwayat
> siapa melakukan perubahan dan apa aktivitasnya.

## 4.3 Kesimpulan Role Admin

Kalimat penutup:

> Jadi Admin berperan sebagai pengelola utama data customer. Admin memastikan data lengkap,
> valid, tidak duplikat, dan siap digunakan oleh role lain.

---

# 5. Demo Role Sales

## 5.1 Tujuan Role Sales

Sales fokus pada customer yang perlu ditindaklanjuti.

Sales bertanggung jawab untuk:

- Melihat daftar customer.
- Menambah atau mengedit customer.
- Melihat status lead/customer.
- Menggunakan search dan filter.
- Mengecek customer duplikat.
- Melakukan smart merge.
- Melihat PIC dan follow-up customer.

Kalimat pembuka role:

> Sekarang saya login sebagai Sales. Role Sales fokus pada pengelolaan customer yang perlu
> di-follow-up dan menjaga agar data prospek tetap rapi.

## 5.2 Langkah Demo Sales

### Langkah 1 - Login Sales

Login:

```text
sales@smartcrm.com
password
```

Tunjukkan:

- Dashboard Sales.
- Welcome card Sales.
- Quick Links Sales.

Jelaskan:

> Dashboard Sales berisi akses cepat ke customer, leads, dan calendar/follow-up.

### Langkah 2 - Buka Pelanggan

Klik:

```text
Pelanggan
```

Jelaskan:

> Sales membuka data customer untuk melihat prospek atau customer yang perlu dihubungi.

### Langkah 3 - Search Customer

Cari customer berdasarkan:

- Nama
- Email
- Perusahaan
- Status

Jelaskan:

> Search membantu Sales menemukan customer yang ingin di-follow-up tanpa harus mencari
> manual.

### Langkah 4 - Filter Lead

Gunakan filter:

```text
Status = Lead
```

Jelaskan:

> Status Lead menunjukkan calon customer yang masih perlu ditindaklanjuti oleh Sales.

### Langkah 5 - Edit Customer

Klik edit pada salah satu customer.

Ubah contoh:

- Status dari `Lead` ke `Active` atau `Customer`.
- Lead Score.
- PIC.
- Catatan follow-up.
- Next follow-up date.

Jelaskan:

> Sales dapat memperbarui status customer sesuai hasil follow-up. Misalnya dari Lead menjadi
> Customer jika sudah berhasil dikonversi.

### Langkah 6 - Demo Duplicate Detection

Klik:

```text
Cek Duplikat
```

Jelaskan:

> Sales juga bisa mengecek data duplikat karena Sales sering menambahkan data prospek baru.
> Jika ternyata customer sudah ada, data bisa digabung agar tidak dobel.

### Langkah 7 - Demo Smart Merge

Jika ada kandidat, lakukan merge.

Jelaskan:

> Smart merge membantu Sales menjaga data tetap bersih, sehingga satu customer tidak
> tercatat berkali-kali.

## 5.3 Hal yang Tidak Perlu Ditekankan pada Sales

Sales tidak perlu terlalu banyak membahas:

- Manajemen user.
- Webhook logs.
- Audit teknis.
- Pengaturan sistem.

## 5.4 Kesimpulan Role Sales

Kalimat penutup:

> Role Sales digunakan untuk mengelola prospek dan customer yang perlu follow-up. Sales
> dapat mencari, mengedit, memberi catatan, mengecek duplikat, dan mengupdate status
> customer.

---

# 6. Demo Role Marketing

## 6.1 Tujuan Role Marketing

Marketing fokus pada segmentasi dan kebutuhan campaign.

Marketing bertanggung jawab untuk:

- Melihat data customer.
- Mengelompokkan customer dengan tag.
- Menggunakan custom fields.
- Import data campaign.
- Export data untuk campaign.
- Search dan filter segmentasi.

Kalimat pembuka role:

> Sekarang saya login sebagai Marketing. Role Marketing fokus pada segmentasi customer dan
> pengolahan data untuk kebutuhan campaign.

## 6.2 Langkah Demo Marketing

### Langkah 1 - Login Marketing

Login:

```text
marketing@smartcrm.com
password
```

Tunjukkan:

- Dashboard Marketing.
- Quick Links Customers, Campaigns, Import/Export.

Jelaskan:

> Dashboard Marketing diarahkan ke data customer, campaign, dan import/export karena data
> customer digunakan untuk segmentasi pemasaran.

### Langkah 2 - Buka Pelanggan

Klik:

```text
Pelanggan
```

Jelaskan:

> Marketing menggunakan data customer untuk menentukan target campaign.

### Langkah 3 - Filter Tag

Gunakan filter:

- VIP
- B2B
- Retail
- Prioritas Tinggi

Jelaskan:

> Filter tag membantu Marketing memilih kelompok customer tertentu. Misalnya hanya customer
> VIP yang akan menerima campaign khusus.

### Langkah 4 - Filter Source atau Campaign

Jika tersedia data source, cari/filter:

```text
Campaign
```

Jelaskan:

> Source membantu Marketing mengetahui asal customer, misalnya dari campaign, referral,
> website, atau social media.

### Langkah 5 - Demo Custom Fields

Buka/edit customer dan tunjukkan custom fields.

Jelaskan:

> Marketing bisa memakai custom fields untuk menyimpan data tambahan seperti kategori
> campaign, minat customer, channel favorit, atau loyalitas.

### Langkah 6 - Demo Import

Klik:

```text
Import CSV/Excel
```

Jelaskan:

> Marketing bisa mengimpor data customer dari hasil campaign atau event tanpa memasukkan
> satu per satu.

### Langkah 7 - Demo Export

Klik:

- Export Data
- Unduh Excel
- Unduh CSV

Jelaskan:

> Export membantu Marketing mengambil data segmentasi untuk dianalisis atau digunakan
> dalam campaign.

## 6.3 Hal yang Tidak Perlu Ditekankan pada Marketing

Marketing tidak perlu terlalu fokus pada:

- Smart merge teknis.
- Manajemen user.
- Webhook logs.

## 6.4 Kesimpulan Role Marketing

Kalimat penutup:

> Role Marketing digunakan untuk segmentasi customer, import data campaign, export data,
> dan mengelola atribut tambahan customer melalui custom fields.

---

# 7. Demo Role Support

## 7.1 Tujuan Role Support

Support fokus pada melihat data customer untuk membantu layanan pelanggan.

Support bertanggung jawab untuk:

- Mencari customer.
- Melihat detail customer.
- Melihat attachment.
- Melihat activity log.
- Memahami riwayat pelanggan sebelum menangani kasus.

Kalimat pembuka role:

> Sekarang saya login sebagai Support. Role Support tidak fokus mengubah data, tetapi
> melihat informasi pelanggan untuk membantu proses layanan.

## 7.2 Langkah Demo Support

### Langkah 1 - Login Support

Login:

```text
support@smartcrm.com
password
```

Tunjukkan:

- Dashboard Support.
- Quick Links Customers dan Tickets.

Jelaskan:

> Dashboard Support diarahkan ke customer dan ticket karena Support membutuhkan data
> pelanggan sebelum menangani masalah.

### Langkah 2 - Buka Pelanggan

Klik:

```text
Pelanggan
```

Jelaskan:

> Support membuka data customer untuk memastikan identitas pelanggan dan melihat riwayatnya.

### Langkah 3 - Search Customer

Cari customer berdasarkan:

- Nama
- Email
- Perusahaan
- Nomor telepon

Jelaskan:

> Search membantu Support menemukan customer dengan cepat saat pelanggan menghubungi tim
> support.

### Langkah 4 - View Detail Customer

Klik:

```text
View
```

Jelaskan:

> Support bisa melihat detail customer, tetapi tidak harus mengubah data. Ini menjaga agar
> data penting tidak sembarangan berubah.

### Langkah 5 - Lihat Attachment

Tunjukkan bagian lampiran jika ada.

Jelaskan:

> Attachment membantu Support melihat dokumen pendukung, misalnya kontrak, identitas, atau
> dokumen layanan.

### Langkah 6 - Lihat Activity Log

Tunjukkan bagian:

```text
Riwayat Aktivitas & Linimasa Kronologis
```

Jelaskan:

> Activity log membantu Support memahami histori perubahan customer, sehingga penanganan
> kasus lebih kontekstual.

## 7.3 Hal yang Tidak Perlu Ditekankan pada Support

Support tidak perlu menonjolkan:

- Create customer.
- Import/export.
- Merge duplikat.
- Manajemen user.

## 7.4 Kesimpulan Role Support

Kalimat penutup:

> Role Support digunakan untuk melihat informasi customer dan riwayat aktivitasnya agar
> layanan pelanggan lebih cepat dan tepat.

---

# 8. Demo Role Manager / Analyst

## 8.1 Tujuan Role Manager

Manager/Analyst fokus pada monitoring, laporan, dan kualitas data.

Manager bertanggung jawab untuk:

- Melihat statistik customer.
- Melakukan search/filter.
- Export data untuk laporan.
- Mengecek data duplikat.
- Melihat audit log.
- Memantau kondisi data customer.

Kalimat pembuka role:

> Sekarang saya login sebagai Manager/Analyst. Role ini digunakan untuk monitoring data,
> melihat statistik, meninjau kualitas data, dan mengambil laporan.

## 8.2 Langkah Demo Manager

### Langkah 1 - Login Manager

Login:

```text
manager@smartcrm.com
password
```

Tunjukkan:

- Dashboard Manager.
- Quick Links Customers, Leads, Audit Log, Analytics.

Jelaskan:

> Dashboard Manager menampilkan akses ke data customer, leads, laporan, dan analytics.

### Langkah 2 - Buka Manajemen Pelanggan

Klik:

```text
Pelanggan
```

Jelaskan:

> Manager menggunakan halaman customer untuk melihat kondisi data secara keseluruhan.

### Langkah 3 - Jelaskan Statistik

Tunjukkan:

- Total Pelanggan
- Lead Aktif
- Baru Bulan Ini
- Belum Ditugaskan

Jelaskan:

> Statistik ini membantu Manager melihat pertumbuhan customer dan data mana yang perlu
> ditindaklanjuti.

### Langkah 4 - Demo Filter

Gunakan filter:

- Status
- Perusahaan
- PIC
- Tag
- Favorite

Jelaskan:

> Filter membantu Manager menganalisis data dari berbagai sudut, misalnya customer VIP,
> customer lead, atau customer yang belum ditugaskan.

### Langkah 5 - Demo Export

Klik:

- Export Data
- Unduh Excel
- Unduh CSV

Jelaskan:

> Export digunakan Manager untuk membuat laporan atau melakukan analisis data di luar
> sistem.

### Langkah 6 - Demo Cek Duplikat

Klik:

```text
Cek Duplikat
```

Jelaskan:

> Manager bisa meninjau kualitas data. Jika ada banyak duplikat, berarti perlu pembersihan
> data agar laporan lebih akurat.

### Langkah 7 - Buka Audit Log

Klik:

```text
Audit Log
```

Jelaskan:

> Audit log membantu Manager memantau aktivitas perubahan data di sistem.

## 8.3 Hal yang Tidak Perlu Ditekankan pada Manager

Manager tidak perlu banyak melakukan:

- Input detail customer.
- Edit field teknis.
- Upload attachment.

## 8.4 Kesimpulan Role Manager

Kalimat penutup:

> Role Manager/Analyst digunakan untuk memantau kualitas data customer, melihat statistik,
> mengecek duplikasi, dan mengekspor data untuk laporan.

---

# 9. Perbandingan Akses Setiap Role

| Fitur | Admin | Sales | Marketing | Support | Manager |
| --- | --- | --- | --- | --- | --- |
| Lihat customer | Ya | Ya | Ya | Ya | Ya |
| Tambah customer | Ya | Ya | Ya | Tidak | Tidak |
| Edit customer | Ya | Ya | Ya | Tidak | Tidak |
| View detail | Ya | Terbatas | Terbatas | Ya | Ya |
| Import data | Ya | Tidak | Ya | Tidak | Tidak |
| Export data | Ya | Tidak | Ya | Tidak | Ya |
| Cek duplikat | Ya | Ya | Tidak/terbatas | Tidak | Ya |
| Smart merge | Ya | Ya | Tidak | Tidak | Review |
| Tagging | Ya | Ya | Ya | Lihat | Lihat |
| Assignment PIC | Ya | Ya | Tidak/terbatas | Lihat | Lihat |
| Attachment | Ya | Ya | Terbatas | Lihat | Lihat |
| Activity log | Ya | Ya | Terbatas | Ya | Ya |
| Manajemen user | Ya | Tidak | Tidak | Tidak | Tidak |

Catatan:

> Jika ada tombol yang tidak tampil pada role tertentu, itu bukan error. Itu adalah bagian
> dari role-based access control agar setiap role hanya melihat fitur yang relevan.

---

# 10. Script Presentasi Singkat

## 10.1 Pembuka

> Pada project SmartCRM79, kelompok kami mengerjakan modul Customer Data Management.
> Modul ini menjadi pusat data pelanggan atau single source of truth. Tujuannya agar data
> customer tersimpan rapi, mudah dicari, dapat dikelompokkan, bisa diimport/export, bisa
> dicek duplikatnya, dan semua aktivitas perubahannya tercatat.

## 10.2 Saat Menjelaskan Role

> Di sistem ini, setiap user memiliki role. Role menentukan fitur apa saja yang bisa
> diakses. Admin memiliki akses paling lengkap, Sales fokus follow-up, Marketing fokus
> segmentasi dan campaign, Support fokus melihat data pelanggan, sedangkan Manager fokus
> monitoring dan laporan.

## 10.3 Saat Menjelaskan Customer Form

> Form customer ini berisi field standar seperti nama, email, telepon, perusahaan, status,
> sumber data, lead score, PIC, alamat, dan catatan. Selain itu ada custom fields agar user
> bisa menambahkan atribut tambahan tanpa mengubah struktur database utama.

## 10.4 Saat Menjelaskan Duplicate Detection

> Duplicate detection digunakan untuk menjaga kualitas data. Sistem mencari kemungkinan
> customer yang sama berdasarkan email, nomor telepon, nama, dan perusahaan. Jika ditemukan,
> user dapat menjalankan smart merge.

## 10.5 Saat Menjelaskan Activity Log

> Activity log digunakan sebagai audit trail. Jadi setiap perubahan data customer dapat
> ditelusuri, termasuk aktivitas create, update, delete, dan merge.

## 10.6 Penutup

> Dengan modul ini, SmartCRM memiliki fondasi data pelanggan yang lengkap dan siap digunakan
> oleh modul lain seperti Sales, Marketing, Support, Dashboard, dan Integration.

---

# 11. Pertanyaan Dosen yang Mungkin Muncul

## Pertanyaan: Kenapa setiap role tampilannya beda?

Jawaban:

> Karena sistem menggunakan role-based access control. Setiap role hanya diberi akses ke
> fitur yang sesuai tugasnya agar sistem lebih aman dan tidak membingungkan user.

## Pertanyaan: Apa bedanya tag dan custom field?

Jawaban:

> Tag digunakan untuk mengelompokkan customer, misalnya VIP atau B2B. Custom field digunakan
> untuk menyimpan atribut tambahan, misalnya Instagram, tanggal kontrak, atau kategori
> loyalitas.

## Pertanyaan: Kenapa perlu duplicate detection?

Jawaban:

> Karena data customer bisa saja masuk dari banyak sumber. Duplicate detection membantu
> menemukan data ganda agar database tetap bersih dan laporan tidak salah.

## Pertanyaan: Apa fungsi smart merge?

Jawaban:

> Smart merge menggabungkan dua data customer yang sama menjadi satu data utama yang lebih
> lengkap. Data seperti tag, custom field, dan attachment ikut dipindahkan.

## Pertanyaan: Apa fungsi activity log?

Jawaban:

> Activity log mencatat riwayat perubahan data. Ini penting untuk audit dan memastikan
> perubahan data bisa ditelusuri.

## Pertanyaan: Kenapa customer disebut single source of truth?

Jawaban:

> Karena semua modul lain memakai data customer dari satu sumber yang sama. Jadi Sales,
> Marketing, Support, dan Manager tidak menggunakan data yang berbeda-beda.

---

# 12. Checklist Demo

Sebelum presentasi, pastikan:

- Laragon aktif.
- MySQL aktif.
- Server Laravel berjalan.
- Semua akun role bisa login.
- Data customer tersedia.
- Minimal ada satu customer dengan tag.
- Minimal ada satu customer favorite.
- Minimal ada contoh data duplikat.
- Template import tersedia.
- Browser sudah siap di halaman login.
- Pembagian presentasi sudah jelas.

---

# 13. Pembagian Demo Berdua

## Presenter 1

Bagian:

- Pembukaan.
- Penjelasan konsep SmartCRM.
- Penjelasan Customer Data Management.
- Penjelasan role.
- Penjelasan fitur utama.
- Penjelasan diagram/laporan.

## Presenter 2

Bagian:

- Login Admin.
- Demo CRUD customer.
- Demo custom fields.
- Demo tagging.
- Demo assignment.
- Demo attachment.
- Demo search/filter/favorite.
- Demo import/export.
- Demo duplicate detection.
- Demo smart merge.
- Demo role Sales, Marketing, Support, Manager.

Saran:

> Presenter 1 menjelaskan konteks, Presenter 2 mengoperasikan aplikasi. Saat Presenter 2
> klik fitur, Presenter 1 bisa membantu menjelaskan fungsi bisnisnya.
