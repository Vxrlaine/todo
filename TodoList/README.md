# TodoListApp

[https://github.com/user-attachments/assets/c904dee6-23de-4ac2-877b-1dda80cfe0d8](https://github.com/user-attachments/assets/649a25b9-d860-4e69-9710-8e90c5ccf576)

TodoListApp adalah aplikasi manajemen tugas (to-do list) yang terdiri dari dua bagian utama:
- **Frontend:** Aplikasi Flutter (`taskflut`)
- **Backend:** REST API menggunakan Laravel (`todo`)

## Daftar Isi

- [Fitur](#fitur)
- [Struktur Proyek](#struktur-proyek)
- [Instalasi](#instalasi)
  - [Backend (Laravel)](#backend-laravel)
  - [Frontend (Flutter)](#frontend-flutter)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Kontribusi](#kontribusi)
- [Lisensi](#lisensi)

---

## Fitur

- CRUD tugas (Create, Read, Update, Delete)
- Autentikasi pengguna
- Sinkronisasi data antara aplikasi dan server
- UI responsif dan modern

## Struktur Proyek

```
TodoListApp/
│
├── taskflut/      # Source code aplikasi Flutter (frontend)
│
└── todo/          # Source code Laravel (backend/API)
```

## Instalasi

### Backend (Laravel)

1. Masuk ke folder `todo`:
    ```sh
    cd todo
    ```

2. Install dependencies:
    ```sh
    composer install
    ```

3. Salin file environment:
    ```sh
    cp .env.example .env
    ```

4. Generate application key:
    ```sh
    php artisan key:generate
    ```

5. Atur konfigurasi database di file `.env`.

6. Jalankan migrasi database:
    ```sh
    php artisan migrate
    ```

7. (Opsional) Jalankan server lokal:
    ```sh
    php artisan serve
    ```

### Frontend (Flutter)

1. Masuk ke folder `taskflut`:
    ```sh
    cd taskflut
    ```

2. Install dependencies:
    ```sh
    flutter pub get
    ```

3. Jalankan aplikasi:
    ```sh
    flutter run
    ```

## Menjalankan Aplikasi

Pastikan backend Laravel sudah berjalan sebelum menjalankan aplikasi Flutter agar aplikasi dapat terhubung ke API.

## Kontribusi

Kontribusi sangat terbuka! Silakan buat pull request atau buka issue untuk diskusi fitur/bug.

## Lisensi

Proyek ini menggunakan lisensi MIT. Lihat detail pada [todo/README.md](todo/README.md).
