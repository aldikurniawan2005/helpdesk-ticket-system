# Helpdesk Ticket System

Aplikasi web sederhana untuk membuat dan mengelola tiket bantuan teknis menggunakan Native PHP dan MySQL.

## Teknologi
- Native PHP
- MySQL
- HTML
- CSS
- JavaScript
- PDO untuk koneksi database

## Fitur
- Melihat daftar tiket
- Membuat tiket baru
- Mengedit subject, category, description, dan status tiket
- Menghapus tiket dengan konfirmasi
- Mencari tiket berdasarkan subject
- Menampilkan status Open, In Progress, dan Closed
- Menyimpan data ke MySQL

## Persyaratan
- XAMPP
- PHP
- MySQL atau MariaDB
- Web browser

## Instalasi

1. Install dan jalankan XAMPP.
2. Aktifkan Apache dan MySQL.
3. Salin folder project ke:

   C:\xampp\htdocs\helpdesk-ticket-system

4. Buka phpMyAdmin:

   http://localhost/phpmyadmin

5. Buat database dengan nama:

   helpdesk_db

6. Pilih database `helpdesk_db`, kemudian buka menu Import.
7. Pilih file `database.sql`, lalu jalankan proses import.
8. Periksa konfigurasi koneksi database pada file `db.php`.

   Konfigurasi default:
   - Host: localhost
   - Database: helpdesk_db
   - Username: root
   - Password: kosong (default XAMPP)

9. Buka aplikasi melalui browser:

   http://localhost/helpdesk-ticket-system/

## Cara Menggunakan

1. Buka aplikasi.
2. Klik Create Ticket untuk membuat tiket baru.
3. Isi subject, category, dan description.
4. Klik tombol untuk menyimpan tiket.
5. Gunakan kolom pencarian untuk mencari tiket berdasarkan subject.
6. Klik Edit untuk mengubah informasi atau status tiket.
7. Klik Delete untuk menghapus tiket dan konfirmasi tindakan tersebut.

## Database

Nama database: `helpdesk_db`

Nama tabel: `tickets`

Kolom:
- id
- subject
- category
- description
- status
- created_at
