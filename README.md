# Anxiety Expert System

Situs ini adalah aplikasi web statis untuk mendeteksi dini tingkat anxiety pada remaja menggunakan pendekatan certainty factor dan forward chaining.

## Cara menjalankan lokal

Jika Anda menggunakan XAMPP, letakkan folder proyek di `C:\xampp\htdocs\anxiety-expert-system` dan pastikan Apache serta MySQL aktif. Selanjutnya buka:

- http://127.0.0.1/anxiety-expert-system/
- http://127.0.0.1/anxiety-expert-system/view_diagnoses.php untuk melihat data tersimpan
- http://127.0.0.1/anxiety-expert-system/db_status.php untuk memeriksa koneksi database

Jika Anda ingin menggunakan server PHP lokal tanpa XAMPP, jalankan dari folder proyek:

```bash
C:\xampp\php\php.exe -S 127.0.0.1:8000
```

Kemudian buka http://127.0.0.1:8000

## Publikasi online

Proyek ini sudah disiapkan untuk dipublikasikan ke GitHub Pages melalui workflow otomatis di folder .github/workflows.

### Langkah selanjutnya

1. Buat repository di GitHub.
2. Hubungkan repository lokal ke GitHub.
3. Push ke branch main.
4. Aktifkan GitHub Pages dari tab Settings > Pages.
5. Pilih source dari GitHub Actions.
