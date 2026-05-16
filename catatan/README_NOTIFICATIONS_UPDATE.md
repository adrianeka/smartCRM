# ⚠️ PENTING: Pembaruan Modul Notifikasi & Database

**Kepada seluruh tim developer (khususnya yang menggunakan fitur Notifikasi):**

Terkait pengerjaan **BR-006 (Dashboard & Notifications menggunakan Filament)**, kita perlu mengaktifkan fitur Native Database Notifications milik Filament (untuk fitur icon lonceng/bell icon di admin panel).

Sistem Filament mewajibkan adanya tabel bernama `notifications` dengan skema bawaan Laravel (berisi kolom `type`, `notifiable_type`, `data` JSON, dll). 

Untuk menghindari konflik dengan tabel notifikasi kustom yang sebelumnya sudah dibuat di branch `develop`, berikut adalah penyesuaian yang telah dilakukan:

## Apa yang berubah?
1. **Rename Tabel**: Tabel kustom `notifications` (yang berisi title, message, source_module) **telah diubah namanya** menjadi `app_notifications`.
2. **Tabel Baru**: Tabel `notifications` yang sekarang ada di database adalah tabel *native* milik Laravel/Filament.
3. **Update Model**: Model `App\Models\Notification` telah diperbarui dengan menambahkan `protected $table = 'app_notifications';`.
4. **Migration**: Penyesuaian ini digabung ke dalam satu file migration yang rapi (`2026_05_16_074839_prepare_filament_database_notifications.php`).

## Apa yang harus diperhatikan tim lain?
- **Jika menggunakan Eloquent Model** (`Notification::create`, `Notification::where...`): **TIDAK PERLU ADA YANG DIUBAH**. Semua logika tetap berjalan 100% normal. API Endpoint untuk notifikasi juga sudah dipastikan tetap aman.
- **Jika menggunakan Query Builder / Raw SQL**: Jika ada yang menggunakan `DB::table('notifications')`, mohon segera diperbarui menjadi `DB::table('app_notifications')`.

Harap diperhatikan saat melakukan integrasi lintas modul. Terima kasih!
