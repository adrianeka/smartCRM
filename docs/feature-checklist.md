# SmartCRM79 Feature Checklist

## 1. Import Data Pelanggan Massal

Status: Ya.

Yang tersedia:
- Import CSV/Excel dari halaman Customer Management.
- Contoh file import 50 data: `docs/customer-import-50-demo.csv`.
- Seeder demo 50 customer: `CustomerDemoSeeder`.

Cara demo:
- Login sebagai Marketing atau Super Admin.
- Buka Customer Management.
- Klik Import CSV/Excel.
- Gunakan file `docs/customer-import-50-demo.csv`.

## 2. Pelacakan Riwayat Otomatis

Status: Ya.

Yang tersedia:
- Model Customer memakai Spatie Activitylog.
- Aktivitas created, updated, deleted tercatat otomatis.
- Super Admin dan Manager/Analyst bisa membuka Audit Log.

Cara demo:
- Login sebagai Sales.
- Edit salah satu customer.
- Login sebagai Manager.
- Buka Audit Log.

## 3. Kolom Data Fleksibel

Status: Ya.

Yang tersedia:
- Custom fields dinamis memakai relasi `customer_custom_fields`.
- Di form Customer tersedia bagian Atribut Data Tambahan.
- Bisa menambah field seperti Instagram, Kategori, NPWP, Campaign Source.

Catatan:
- Implementasi di dashboard memakai repeater key-value agar lebih mudah dipakai daripada textarea JSON mentah.
- API tetap mendukung payload custom fields berbentuk array/object.

## 4. Pencarian Dinamis dan Filter Lanjutan

Status: Ya.

Yang tersedia:
- Search kode, nama, email, phone, dan company.
- Filter status.
- Filter perusahaan.
- Filter PIC Sales/Admin.
- Filter tag segmentasi.

## 5. Dashboard Analytics Widget

Status: Ya.

Yang tersedia di Customer Management:
- Total Customers.
- Active Leads.
- New This Month.
- Unassigned.

## 6. Customer Segmentation / Tagging

Status: Ya.

Yang tersedia:
- Model Tag dan pivot customer_tag.
- Form Customer bisa memilih atau membuat tag baru.
- Tag demo: VIP, Prioritas Tinggi, B2B, Retail, Prospek Hangat.
- Tabel Customer menampilkan tag dalam badge.

## 7. Sales/Admin Assignment

Status: Ya.

Yang tersedia:
- Kolom `assigned_user_id` di tabel customers.
- Form Customer punya field Ditugaskan ke Sales/Admin.
- Tabel Customer menampilkan PIC Sales/Admin.
- Filter PIC tersedia.

## 8. Duplicate Detection dan Smart Merge

Status: Ya.

Yang tersedia:
- Tombol Cek Duplikat di Customer Management.
- Endpoint `/api/v1/customers/duplicates`.
- Action Merge Duplikat pada baris customer untuk Super Admin dan Sales.
- Data demo duplikat: CUST-DEMO-001 dan CUST-DEMO-002.
