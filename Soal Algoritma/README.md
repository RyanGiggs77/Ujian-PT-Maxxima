# Latihan Algoritma — PHP Native

Empat fitur dalam satu halaman, tanpa framework, database, atau JavaScript.

## Menjalankan

Di folder proyek, jalankan:

```sh
php -S localhost:8000
```

Buka http://localhost:8000 di browser. Bisa juga dijalankan melalui Laragon.

## File

- `index.php`: halaman, formulir, dan pemrosesan input.
- `functions.php`: fungsi algoritma dengan komentar sederhana.
- `style.css`: tampilan minimalis dan responsif.

## Logika

1. Pembalik teks menggunakan fungsi rekursif yang mengambil karakter terakhir.
2. Pengurutan menggunakan bubble sort dengan dua loop, tanpa `sort()`.
3. Pencarian terbesar/terkecil membandingkan nilai melalui loop, tanpa `max()` atau `min()`.
4. Umur menggunakan selisih tanggal dalam tahun, bulan, dan hari. Input menggunakan format **dd/mm/yyyy**: `12/08/1990` berarti 12 Agustus 1990. Tanggal dihitung berdasarkan zona waktu Asia/Jakarta.

Jumlah tahun kabisat adalah jumlah tanggal 29 Februari setelah tanggal lahir yang sudah terlewati hingga hari ini. Daftar tahunnya ditampilkan di bawah jumlah kabisat; jika kosong, tampil tulisan "Belum ada." Tahun kabisat habis dibagi 400, atau habis dibagi 4 tetapi tidak habis dibagi 100. Hasil menyesuaikan tanggal saat aplikasi dijalankan.

Daftar angka menerima bilangan bulat yang dipisahkan koma, termasuk nilai negatif dan nilai duplikat. Input tidak valid ditampilkan sebagai pesan pada formulir terkait.
