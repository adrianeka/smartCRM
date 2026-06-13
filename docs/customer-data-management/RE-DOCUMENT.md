# Requirement Engineering Document

## Identitas Modul

- Nama modul: Customer Data Management
- Branch kerja: `feature/customer`
- Tanggung jawab: menyimpan, mengelola, mencari, mengelompokkan, mengimpor, mengekspor, dan menjaga kualitas data pelanggan.
- Peran utama: Admin, Sales, Marketing, Support, Manager/Analyst.

## Tujuan

Customer Data Management menjadi pusat data pelanggan SmartCRM. Semua modul lain dapat memakai data
ini sebagai rujukan utama untuk aktivitas sales pipeline, campaign marketing, support ticket,
dashboard analytics, dan integrasi eksternal.

## Scope Fungsional

| Kode | Kebutuhan | Status |
| --- | --- | --- |
| CDM-01 | CRUD data pelanggan lengkap dengan validasi | Tersedia |
| CDM-02 | 20+ field standar pelanggan | Tersedia |
| CDM-03 | Import data pelanggan massal dari CSV/Excel dengan template | Tersedia |
| CDM-04 | Export data pelanggan CSV, Excel, JSON | Tersedia |
| CDM-05 | Dynamic custom fields typed untuk atribut tambahan | Tersedia |
| CDM-06 | Advanced search dan filter multi kriteria | Tersedia |
| CDM-07 | Duplicate detection dan smart merge | Tersedia |
| CDM-08 | Tagging/segmentation pelanggan | Tersedia |
| CDM-09 | Assignment pelanggan ke PIC Sales/Admin | Tersedia |
| CDM-10 | Favorite customer dan favorite list | Tersedia |
| CDM-11 | Attachment pelanggan dengan preview/download | Tersedia |
| CDM-12 | Activity log perubahan data pelanggan | Tersedia |
| CDM-13 | Dashboard statistics widget | Tersedia |

## Aktor dan Hak Akses

| Role | Akses Utama |
| --- | --- |
| Admin / super_admin | Akses penuh: CRUD, import, export, duplicate check, merge, user/role management. |
| Sales | Membuat, mengubah, melihat, assign/follow-up customer, cek dan merge duplikat. |
| Marketing | Melihat, membuat, mengubah data segmentasi, import/export untuk campaign. |
| Support | Melihat detail pelanggan dan riwayat sebagai konteks layanan pelanggan. |
| Manager/Analyst | Melihat data, export laporan, cek duplikat, dan membaca statistik. |

## Data Utama

Field standar yang dipakai saat ini:

- `customer_code`
- `full_name`
- `job_title`
- `email`
- `website`
- `phone`
- `whatsapp`
- `company_name`
- `industry`
- `identity_number`
- `tax_number`
- `gender`
- `birth_date`
- `address`
- `city`
- `province`
- `postal_code`
- `country`
- `status`
- `customer_type`
- `source`
- `lead_score`
- `preferred_contact_method`
- `last_contacted_at`
- `next_follow_up_at`
- `notes`
- `assigned_user_id`
- `custom_fields`
- `is_favorite`
- `created_at`
- `updated_at`

Relasi pendukung:

- `tags` untuk segmentasi.
- `customer_custom_fields` untuk atribut dinamis.
- `customer_attachments` untuk file pelanggan.
- `activity_log` dari Spatie untuk audit trail.
- `users` untuk assignment PIC.

## Business Rules

1. `customer_code`, `full_name`, dan `email` wajib diisi.
2. `customer_code` dan `email` harus unik.
3. Status pelanggan menggunakan nilai `Lead`, `Active`, `Customer`, atau `Inactive`.
4. Customer dapat memiliki banyak tag.
5. Customer dapat memiliki banyak custom field.
6. Customer dapat ditugaskan ke satu PIC Sales/Admin.
7. Duplicate score dihitung dari kesamaan email, nomor telepon, nama, dan perusahaan.
8. Merge memindahkan tag, custom field, dan attachment dari data duplikat ke data utama.
9. Setiap create, update, delete, dan merge harus tercatat di activity log.

## Alur Utama

### CRUD Customer

1. User membuka menu Manajemen Pelanggan.
2. User menambah atau mengubah data.
3. Sistem menjalankan validasi field wajib dan uniqueness.
4. Sistem menyimpan data pelanggan.
5. Sistem mencatat aktivitas perubahan.

### Import Customer

1. Marketing/Admin memilih Import CSV/Excel.
2. User mengunggah file sesuai template kolom.
3. Sistem memvalidasi dan memproses file.
4. Data valid masuk ke database.
5. Data dapat langsung dicari, difilter, ditag, atau ditugaskan ke PIC.

### Duplicate Check dan Merge

1. Admin/Sales/Manager membuka halaman Cek Duplikat.
2. Sistem menampilkan kandidat duplikat berdasarkan score.
3. User memilih pasangan data yang akan digabung.
4. Sistem memindahkan data pelengkap ke customer utama.
5. Data duplikat dihapus dan aktivitas merge dicatat.

## Acceptance Criteria

- User sesuai role dapat masuk dashboard dan hanya melihat fitur yang relevan.
- Admin dapat CRUD customer dari Filament.
- Import CSV/Excel dapat memasukkan data massal.
- Export CSV/Excel/JSON dapat mengunduh data pelanggan.
- Filter status, perusahaan, PIC, tag, dan search dapat digunakan.
- Halaman cek duplikat menampilkan kandidat dan tombol merge.
- Activity log mencatat perubahan penting.
- API customer dapat digunakan modul lain untuk integrasi.

## Batasan Saat Ini

- Attachment sudah tersedia di API dan UI Filament. Metadata upload dari UI dibuat sederhana agar tetap mudah didemokan.
- Custom field typed sudah tersedia untuk text, dropdown, date, checkbox, dan file. Untuk dropdown, opsi disimpan sebagai daftar berbasis koma pada form.
