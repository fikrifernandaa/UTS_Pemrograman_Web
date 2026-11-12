# Pustaka.ku – Katalog Media & Simulasi Sewa E-Book

Prototipe UTS Pemrograman Web (Ganjil 2026/2027). Front-end HTML/CSS/JS + backend **PHP 8 OOP tanpa framework**, penyimpanan **JSON**.

## Menjalankan
Butuh PHP 8.1+ (`php -v`). Dari folder proyek:

    php -S localhost:8000 -t public

atau di VS Code: `Ctrl+Shift+B` (task *Jalankan server PHP*). Buka http://localhost:8000

Akun demo: `rani.putri@mail.id` / `pustaka123` (juga `budi.santoso@mail.id` / `budi12345`).

## Struktur
    public/            index.php (front controller), assets/css, assets/js
    src/Contracts/     Rentable (interface)
    src/Models/        Media (abstract) → EBook → EBookPremium; User (abstract) → Member; Rental
    src/Repositories/  JsonRepository (abstract) → Book/Member/RentalRepository
    src/Services/      RentalService (logika sewa, kembali, perpanjang)
    src/Exceptions/    RentalException
    src/Support/       View (render & escape)
    views/             layout, login, dashboard, katalog (+ modal)
    data/              books.json, members.json, rentals.json

## Pemetaan rubrik
- **Front-End (30)**: elemen semantik (header/nav/main/section/article/dialog/fieldset), validasi form login (JS + PHP), layout responsif, JS: modal `<dialog>`, hitung harga & jatuh tempo, pencarian instan, filter kategori.
- **PHP OOP (40)**: namespace `PustakaKu\…` + autoloader PSR-4; encapsulation (properti private + getter/setter); inheritance (`Media`→`EBook`→`EBookPremium`, `User`→`Member`, `JsonRepository`); polymorphism (`hitungHarga()` berbeda per kelas); interface `Rentable`; exception khusus.
- **Struktur (30)**: kode di `src/`, satu kelas satu berkas, view terpisah dari logika.

Data di `data/*.json` berubah saat transaksi; salin ulang berkas aslinya jika ingin mengembalikan data awal.
