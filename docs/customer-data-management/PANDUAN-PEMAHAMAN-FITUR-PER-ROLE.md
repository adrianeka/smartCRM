# Panduan Memahami Website SmartCRM79

## Fokus: Customer Data Management dan Demo Per Role

Dokumen ini dibuat untuk membantu kamu memahami project dari nol: tujuan project, alur website,
arti setiap dashboard, fungsi tombol, cara mengisi data, dan cara menjelaskan demo ke dosen.

---

# 1. Project Ini Sebenarnya Tentang Apa?

SmartCRM79 adalah aplikasi **Customer Relationship Management** atau CRM.

CRM adalah sistem untuk membantu perusahaan mengelola hubungan dengan pelanggan. Di dalam CRM,
data pelanggan dipakai oleh beberapa tim:

- **Sales** untuk follow-up calon pelanggan.
- **Marketing** untuk segmentasi dan campaign.
- **Support** untuk membantu pelanggan yang punya masalah.
- **Manager** untuk melihat laporan dan performa.
- **Admin** untuk mengelola data, user, role, dan sistem.

Bagian yang kita kerjakan adalah:

```text
Customer Data Management
```

Artinya, modul kita bertugas menjadi pusat data pelanggan.

Kalimat mudahnya:

> Project ini dibuat agar semua data pelanggan tersimpan rapi di satu tempat, mudah dicari,
> bisa dikelompokkan, bisa diimport/export, bisa dicek duplikatnya, dan setiap perubahan
> datanya tercatat.

Kenapa project ini harus dibuat?

Karena tanpa CRM, data pelanggan biasanya tersebar di Excel, chat, email, atau catatan manual.
Akibatnya data bisa dobel, susah dicari, tidak jelas siapa PIC-nya, dan tim lain bisa memakai
data yang berbeda-beda.

Tujuan utama project:

- Membuat data customer lebih rapi.
- Membantu setiap role bekerja sesuai tugasnya.
- Mengurangi data duplikat.
- Membantu proses follow-up customer.
- Menyediakan data untuk laporan dan analisis.
- Menjadi single source of truth untuk seluruh data pelanggan.

---

# 2. Cara Membaca Website Ini

Website ini punya beberapa role. Setiap role melihat dashboard dan tombol yang berbeda.

| Role | Fokus Utama |
| --- | --- |
| Admin | Mengelola sistem, user, role, customer, audit, webhook. |
| Sales | Follow-up customer, lead, deal, pipeline. |
| Marketing | Segmentasi customer, campaign, import/export data. |
| Support | Melihat data customer untuk layanan dan ticket. |
| Manager | Monitoring, laporan, statistik, kualitas data. |

Kalau ada tombol yang tidak muncul di role tertentu, itu bukan error. Itu karena sistem memakai
**role-based access control**, yaitu akses fitur dibedakan berdasarkan tugas user.

---

# 3. Istilah Dasar yang Harus Kamu Pahami

| Istilah | Artinya |
| --- | --- |
| Customer | Orang/perusahaan yang menjadi pelanggan atau calon pelanggan. |
| Lead | Calon pelanggan yang masih perlu di-follow-up. |
| Qualified | Lead yang sudah cukup potensial. |
| Proposal | Tahap ketika penawaran/proposal sudah diberikan. |
| Negotiation | Tahap negosiasi harga, kebutuhan, atau kontrak. |
| Customer / Active | Pelanggan yang sudah aktif. |
| Inactive | Pelanggan yang sudah tidak aktif. |
| Pipeline | Alur proses penjualan dari lead sampai deal. |
| Deal | Peluang penjualan. |
| Ticket | Permintaan bantuan/masalah dari pelanggan. |
| SLA | Batas waktu penanganan ticket/support. |
| Webhook | Kiriman data otomatis dari/ke sistem lain. |
| Audit Log | Catatan aktivitas sistem: siapa mengubah apa dan kapan. |
| PIC | Person in Charge, orang yang bertanggung jawab menangani customer. |
| Tag | Label untuk mengelompokkan customer, misalnya VIP, B2B, Retail. |
| Custom Field | Kolom tambahan dinamis sesuai kebutuhan. |

---

# 4. Role Admin

## 4.1 Fungsi Admin

Admin adalah role dengan akses paling lengkap. Admin bertugas memastikan sistem berjalan,
data pelanggan rapi, user bisa login, role benar, dan aktivitas sistem bisa diaudit.

Kalimat demo:

> Saya mulai dari role Admin karena Admin memiliki akses paling lengkap. Admin bisa mengelola
> user, role, customer, webhook, dan audit log.

## 4.2 Dashboard Admin

Dashboard Admin berisi ringkasan kondisi sistem dan bisnis.

| Tampilan | Maksudnya |
| --- | --- |
| Total Webhook | Jumlah semua webhook/log integrasi yang pernah masuk atau diproses. |
| Berhasil | Jumlah webhook/proses integrasi yang sukses. |
| Gagal | Jumlah webhook/proses integrasi yang error. |
| Menunggu | Webhook/proses yang belum selesai atau masih pending. |
| Total Pendapatan | Estimasi nilai pendapatan dari data sales/deal. Ini bagian CRM umum, bukan hanya customer. |
| Pertumbuhan Penjualan | Gambaran kenaikan/penurunan performa sales. |
| Tingkat Kehilangan | Persentase peluang/customer yang hilang atau tidak berhasil dikonversi. |
| Pelanggan Aktif | Jumlah customer dengan status aktif. |
| Sales Pipeline | Tahapan proses penjualan: Lead, Qualified, Proposal, Negotiation. |
| Tiket berdasarkan Prioritas | Ringkasan ticket/support berdasarkan prioritas tinggi, sedang, rendah. |
| Quick Links | Tombol cepat untuk membuka halaman penting. |
| Deal Teratas | Customer/deal dengan nilai atau peluang paling tinggi. |
| Tugas Hari Ini | Follow-up atau tugas yang dijadwalkan hari ini. |
| Deadline Terdekat | Jadwal follow-up/tugas yang paling dekat waktunya. |
| Tiket Mendesak | Ticket/support yang mendekati batas SLA. |
| Ringkasan Tugas | Jumlah tugas terlambat, menunggu, dan selesai hari ini. |

Cara demo Dashboard Admin:

1. Login sebagai Admin.
2. Tunjukkan dashboard.
3. Jelaskan bahwa dashboard adalah ringkasan cepat.
4. Klik Quick Link **Pelanggan** untuk masuk ke modul utama kita.

Kalimat demo:

> Dashboard ini memberi gambaran cepat untuk Admin. Ada data integrasi, data sales, data
> support, dan shortcut ke halaman penting. Walaupun fokus kelompok kami Customer Data
> Management, dashboard ini menunjukkan bahwa data customer dipakai oleh banyak bagian CRM.

## 4.3 Webhook Logs

Webhook Logs adalah halaman untuk melihat komunikasi otomatis dengan sistem lain.

Contoh sederhana:

Jika ada sistem lain mengirim data customer ke SmartCRM, data itu bisa masuk lewat webhook.
Halaman Webhook Logs menyimpan jejaknya.

| Field | Artinya |
| --- | --- |
| Event Type | Jenis event, misalnya `customer.created`, `payment.updated`, `ticket.created`. |
| Target URL | Alamat tujuan webhook dikirim. |
| Payload | Isi data yang dikirim, biasanya format JSON. |
| Response | Balasan dari sistem tujuan. |
| Status | Status proses, misalnya success, failed, pending. |
| Status Module | Modul asal/tujuan, misalnya customer, sales, support. |
| Status Code | Kode HTTP, misalnya 200 sukses, 400 salah request, 500 server error. |

Cara demo:

1. Buka **Webhook Logs**.
2. Tunjukkan tabel log.
3. Jelaskan bahwa halaman ini untuk debugging integrasi.
4. Tidak perlu membuat webhook baru saat demo jika belum diminta.

Kalimat demo:

> Webhook Logs digunakan untuk melihat riwayat komunikasi antar sistem. Jika ada data dari
> sistem lain yang gagal masuk, Admin bisa mengecek status, payload, dan response-nya di sini.

## 4.4 Roles

Halaman Roles digunakan untuk mengatur hak akses setiap role.

Contoh role:

- super_admin
- Sales
- Marketing
- Support
- Manager/Analyst

Field penting:

| Field | Maksudnya |
| --- | --- |
| Guard Name | Penanda sistem autentikasi Laravel. Biasanya `web`. |
| Select All | Mencentang semua permission sekaligus. |
| Resources | Hak akses ke resource/menu data, seperti Customer, User, Webhook Log. |
| Pages | Hak akses ke halaman khusus, seperti Dashboard. |
| Widgets | Hak akses ke widget dashboard. |

Arti permission:

| Permission | Maksudnya |
| --- | --- |
| view any | Boleh melihat daftar data. |
| view | Boleh melihat detail satu data. |
| create | Boleh membuat data baru. |
| update | Boleh mengedit data. |
| delete | Boleh menghapus satu data. |
| delete any | Boleh menghapus banyak data sekaligus. |
| force delete | Menghapus permanen satu data. |
| force delete any | Menghapus permanen banyak data. |
| restore | Mengembalikan data yang soft-deleted. |
| restore any | Mengembalikan banyak data. |
| replicate | Menyalin/duplikasi data. |
| reorder | Mengubah urutan data/menu jika fitur mendukung. |

Cara demo Roles:

1. Buka **Roles**.
2. Klik edit role, misalnya `Sales`.
3. Tunjukkan checkbox permission.
4. Jelaskan bahwa checkbox menentukan fitur apa yang boleh dipakai role itu.
5. Jangan mengubah permission saat demo jika tidak perlu.

Kalimat demo:

> Halaman Roles digunakan untuk mengatur hak akses. Misalnya Support hanya boleh melihat
> customer, sedangkan Admin boleh mengelola semua data.

## 4.5 User Management / Pengguna

Halaman Pengguna digunakan untuk mengelola akun user.

Fungsi:

- Melihat daftar user.
- Membuat user baru.
- Mengedit nama/email/password.
- Mengatur role user.
- Melihat avatar/profil user.

Cara demo:

1. Buka **Pengguna**.
2. Tunjukkan daftar user demo.
3. Klik edit salah satu user.
4. Tunjukkan bagian role.
5. Jelaskan bahwa role menentukan dashboard dan akses fitur.

Kalimat demo:

> Halaman Pengguna digunakan Admin untuk membuat akun dan memberi role. Setelah user diberi
> role Sales, Marketing, Support, atau Manager, tampilan dan aksesnya akan berbeda.

## 4.6 Audit Log

Audit Log adalah catatan aktivitas sistem.

Fungsi:

- Melihat siapa melakukan perubahan.
- Melihat kapan perubahan dilakukan.
- Melihat aksi seperti create, update, delete, merge.
- Membantu debugging dan audit.

Cara demo:

1. Buka **Audit Log**.
2. Tunjukkan daftar aktivitas.
3. Jelaskan salah satu log.

Kalimat demo:

> Audit Log penting karena setiap perubahan data bisa ditelusuri. Ini membuat sistem lebih
> transparan dan aman.

---

# 5. Manajemen Pelanggan

Halaman ini adalah halaman paling penting untuk modul kita.

## 5.1 Widget di Halaman Pelanggan

| Widget | Maksudnya |
| --- | --- |
| Total Pelanggan | Semua customer yang ada di database. |
| Lead Aktif | Customer dengan status Lead, yaitu calon pelanggan yang perlu follow-up. |
| Baru Bulan Ini | Customer yang baru dibuat pada bulan berjalan. |
| Belum Ditugaskan | Customer yang belum punya PIC Sales/Admin. |

Cara menjalankan tampilan ini:

- Tambah customer baru, maka Total Pelanggan dan Baru Bulan Ini naik.
- Ubah status customer menjadi Lead, maka Lead Aktif berubah.
- Kosongkan PIC, maka Belum Ditugaskan bertambah.
- Isi PIC, maka Belum Ditugaskan berkurang.

## 5.2 Tombol di Manajemen Pelanggan

| Tombol | Fungsi |
| --- | --- |
| Create / Tambah Customer | Membuat data customer baru. |
| Import CSV/Excel | Memasukkan banyak customer dari file. |
| Export Data | Export data melalui fitur Filament. |
| Unduh Excel | Download data customer dalam format Excel. |
| Unduh CSV | Download data customer dalam format CSV. |
| Cek Duplikat | Membuka halaman kandidat customer duplikat. |
| View | Melihat detail customer. |
| Edit | Mengubah data customer. |
| Jadikan Favorit | Menandai customer penting. |
| Merge Duplikat | Menggabungkan customer yang sama/duplikat. |

## 5.3 Field Edit Customer

| Field | Diisi Apa | Gunanya |
| --- | --- | --- |
| Kode Pelanggan | Kode unik, contoh `CUST-001` | Identitas unik customer. |
| Nama Lengkap | Nama customer | Identitas utama. |
| Jabatan | Contoh `Manager`, `Owner` | Mengetahui posisi customer. |
| Email | Email valid | Kontak utama dan validasi unik. |
| Website | Website perusahaan/customer | Informasi tambahan. |
| No. Telepon | Nomor telepon | Kontak customer. |
| WhatsApp | Nomor WhatsApp | Kontak cepat. |
| Nama Perusahaan | Contoh `PT Maju Jaya` | Untuk customer B2B. |
| Industri | Contoh Retail, Finance, Education | Segmentasi bisnis. |
| No Identitas/KTP | Nomor identitas jika dibutuhkan | Data legal/customer verification. |
| NPWP | Nomor pajak | Data legal/perusahaan. |
| Jenis Kelamin | Laki-laki/Perempuan/Lainnya | Profil customer individual. |
| Tanggal Lahir | Tanggal lahir | Profil tambahan. |
| Status Pelanggan | Lead, Active, Customer, Inactive | Tahap hubungan customer. |
| Tipe Customer | Individual, B2B, Retail, Enterprise | Kategori customer. |
| Sumber Data | Campaign, Website, Referral, Event | Dari mana customer berasal. |
| Lead Score | Angka 0-100 | Seberapa potensial customer. |
| Metode Kontak Favorit | Email, Phone, WhatsApp, Meeting | Cara terbaik menghubungi customer. |
| Terakhir Dihubungi | Tanggal terakhir kontak | Riwayat follow-up. |
| Jadwal Follow-up Berikutnya | Tanggal follow-up selanjutnya | Membantu Sales membuat jadwal. |
| Ditugaskan ke Sales/Admin | Pilih PIC | Menentukan penanggung jawab. |
| Alamat/Kota/Provinsi | Lokasi customer | Informasi alamat. |
| Catatan Internal | Catatan bebas | Info tambahan untuk tim internal. |
| Tag/Label | VIP, B2B, Retail | Segmentasi customer. |
| Atribut Data Tambahan | Custom field | Data fleksibel sesuai kebutuhan. |
| Lampiran Pelanggan | File PDF/gambar | Dokumen customer. |
| Riwayat Aktivitas | Otomatis | Audit trail perubahan data. |

Kalimat demo:

> Form customer dibuat lengkap agar data pelanggan tidak hanya berisi nama dan email, tetapi
> juga informasi kontak, bisnis, follow-up, PIC, segmentasi, dan dokumen pendukung.

---

# 6. Role Sales

## 6.1 Dashboard Sales

| Tampilan | Maksudnya |
| --- | --- |
| Kontak Aktif | Customer/prospek yang masih aktif dihubungi. |
| Nilai Pipeline | Estimasi nilai peluang penjualan. |
| Deal Aktif | Peluang penjualan yang masih berjalan. |
| Tingkat Kemenangan | Persentase deal yang berhasil. |
| Sales Pipeline | Tahapan lead sampai negotiation. |
| Deal Teratas | Deal/customer paling potensial. |
| Deadline Terdekat | Follow-up paling dekat. |
| Tugas Hari Ini | Tugas Sales yang harus dilakukan hari ini. |

Cara demo Sales:

1. Login sebagai Sales.
2. Jelaskan dashboard Sales.
3. Buka Manajemen Pelanggan.
4. Filter status Lead.
5. Edit customer.
6. Isi catatan follow-up dan jadwal follow-up.
7. Cek duplikat jika ada.

Kalimat demo:

> Role Sales fokus pada follow-up customer. Sales melihat lead, mengupdate status, mencatat
> follow-up, dan menjaga agar data prospek tidak duplikat.

---

# 7. Role Marketing

## 7.1 Dashboard Marketing

| Tampilan | Maksudnya |
| --- | --- |
| Kampanye Aktif | Campaign marketing yang sedang berjalan. |
| Lead Dihasilkan | Jumlah lead dari aktivitas marketing. |
| Rata-rata Open Rate | Rata-rata customer membuka campaign/email. |
| Biaya per Lead | Estimasi biaya untuk mendapatkan satu lead. |
| Performa Kampanye | Ringkasan hasil campaign. |

Cara demo Marketing:

1. Login sebagai Marketing.
2. Buka dashboard dan jelaskan metrik campaign.
3. Buka Manajemen Pelanggan.
4. Filter tag VIP/B2B/Retail.
5. Tunjukkan custom fields untuk segmentasi.
6. Demo import data customer dari CSV/Excel.
7. Demo export data untuk kebutuhan campaign.

Kalimat demo:

> Role Marketing memakai data customer untuk segmentasi dan campaign. Marketing bisa
> mengelompokkan customer dengan tag, memakai custom fields, import data campaign, dan export
> data untuk analisis.

---

# 8. Role Support

## 8.1 Dashboard Support

| Tampilan | Maksudnya |
| --- | --- |
| Tiket Terbuka | Jumlah masalah/customer case yang masih terbuka. |
| Tiket Belum Ditugaskan | Ticket yang belum punya PIC support. |
| Rata-rata Waktu Respon | Rata-rata waktu tim merespons customer. |
| Skor CSAT | Customer Satisfaction Score, nilai kepuasan pelanggan. |
| Tiket berdasarkan Prioritas | Ticket dikelompokkan tinggi/sedang/rendah. |
| Tiket Mendesak | Ticket yang mendekati batas SLA. |
| Ringkasan Tugas | Tugas support terlambat/menunggu/selesai. |

Cara demo Support:

1. Login sebagai Support.
2. Jelaskan dashboard Support.
3. Buka Manajemen Pelanggan.
4. Search customer berdasarkan nama/email.
5. Klik View customer.
6. Tunjukkan attachment.
7. Tunjukkan activity log.

Kalimat demo:

> Role Support lebih fokus melihat data customer, bukan mengubah data. Support membutuhkan
> informasi customer, attachment, dan activity log agar bisa membantu pelanggan dengan cepat.

---

# 9. Role Manager / Analyst

## 9.1 Dashboard Manager

| Tampilan | Maksudnya |
| --- | --- |
| Total Pendapatan | Estimasi total nilai bisnis/deal. |
| Pertumbuhan Penjualan | Perubahan performa sales. |
| Tingkat Kehilangan | Peluang/customer yang hilang. |
| Pelanggan Aktif | Customer dengan status aktif. |
| Pendapatan Proyeksi | Perkiraan pendapatan ke depan dari pipeline. |
| Performa Kampanye | Ringkasan performa marketing. |
| Sales Pipeline | Distribusi customer/deal di tahap sales. |
| Audit Log | Aktivitas sistem yang perlu dipantau. |

Kenapa Manager melihat data itu?

Karena Manager tidak fokus input data, tetapi mengambil keputusan. Manager butuh melihat:

- Berapa customer aktif.
- Berapa lead yang potensial.
- Apakah data banyak yang duplikat.
- Apakah sales dan marketing berjalan baik.
- Apakah ada aktivitas sistem yang perlu dicek.

Cara demo Manager:

1. Login sebagai Manager.
2. Jelaskan dashboard sebagai halaman monitoring.
3. Buka Manajemen Pelanggan.
4. Tunjukkan statistik customer.
5. Filter data berdasarkan status/tag/PIC.
6. Export data untuk laporan.
7. Buka Cek Duplikat.
8. Buka Audit Log.

Kalimat demo:

> Role Manager digunakan untuk monitoring dan laporan. Manager melihat statistik customer,
> export data, mengecek kualitas data, dan memantau aktivitas sistem.

---

# 10. Alur Demo yang Paling Aman

Gunakan urutan ini saat presentasi:

1. Jelaskan tujuan project.
2. Login Admin.
3. Buka Dashboard Admin.
4. Buka Manajemen Pelanggan.
5. Tambah/Edit customer.
6. Jelaskan field customer.
7. Demo tag, PIC, custom field, attachment.
8. Demo search/filter/favorite.
9. Demo import/export.
10. Demo duplicate detection dan smart merge.
11. Demo activity log.
12. Login Sales, jelaskan perbedaannya.
13. Login Marketing, jelaskan perbedaannya.
14. Login Support, jelaskan perbedaannya.
15. Login Manager, jelaskan perbedaannya.
16. Tutup dengan kesimpulan.

Kalimat penutup:

> Kesimpulannya, Customer Data Management adalah fondasi utama SmartCRM. Modul ini membuat
> data pelanggan lebih lengkap, rapi, mudah dicari, bisa digunakan oleh berbagai role, dan
> memiliki audit trail sehingga perubahan data bisa ditelusuri.

