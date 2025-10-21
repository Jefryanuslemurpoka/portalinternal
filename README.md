# 🧩 Portal Internal

## 📖 Deskripsi Proyek
**Portal Internal** adalah sistem berbasis web yang dikembangkan menggunakan **Laravel** untuk membantu manajemen data dan aktivitas internal perusahaan.  
Proyek ini dirancang agar memudahkan akses informasi, pengelolaan tugas, dan koordinasi antarbagian dalam organisasi.  
Saat ini, proyek masih dalam tahap **pengembangan** (*development phase*) dan akan terus diperbarui secara bertahap.

---

## 🚧 Status Proyek
> **Status:** Dalam Tahap Pengembangan *(Development in Progress)*

### 🔧 Fitur yang Sedang Dikerjakan
- [x] Instalasi dan konfigurasi Laravel  
- [x] Setup Laravel Breeze (Blade)  
- [ ] Modul autentikasi (Login & Register)  
- [ ] Dashboard utama  
- [ ] Manajemen data internal  
- [ ] Role & permission system  
- [ ] Integrasi API internal  

---

## 🛠️ Teknologi yang Digunakan
| Komponen | Teknologi |
|-----------|------------|
| **Framework Backend** | Laravel 11 |
| **Bahasa Pemrograman** | PHP 8.2+ |
| **Database** | MySQL / MariaDB |
| **Autentikasi** | Laravel Breeze (Blade) |
| **Frontend Tools** | Blade, Vite, NPM |
| **Dependency Manager** | Composer |
| **Server Lokal** | Artisan Serve |

---

## ⚙️ Cara Menjalankan Proyek

1. **Clone Repository**
   ```bash
   git clone https://github.com/[username]/portalinternal.git
   cd portalinternal
````

2. **Install Dependencies**

   ```bash
   composer install
   npm install
   ```

3. **Copy File `.env` dan Generate Key**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database di `.env`**, lalu jalankan migrasi:

   ```bash
   php artisan migrate
   ```

5. **Jalankan Server**

   ```bash
   php artisan serve
   ```

6. **(Opsional)** Jalankan Vite untuk asset frontend:

   ```bash
   npm run dev
   ```

---

## 🧾 Catatan Pengembangan

| Tanggal    | Aktivitas                         |
| ---------- | --------------------------------- |
| 2025-10-20 | Instalasi Laravel Breeze (Blade)  |
| 2025-10-20 | Perbaikan error `routes/auth.php` |
| 2025-10-20 | Konfigurasi awal autentikasi      |

---

## 👨‍💻 Pengembang

**Nama:** Jefryanus Lemur
📍 Jakarta, Indonesia
📧 [jeanlee0990@gmail.com](mailto:jeanlee0990@gmail.com)
📱 081511238645

---

## 🤝 Panduan Kontribusi

1. Fork repository ini.
2. Buat branch baru untuk fitur yang ingin dikembangkan:

   ```bash
   git checkout -b fitur-nama-fitur
   ```
3. Lakukan perubahan dan commit:

   ```bash
   git commit -m "Menambahkan fitur X"
   ```
4. Push branch ke GitHub:

   ```bash
   git push origin fitur-nama-fitur
   ```
5. Buat **Pull Request** untuk direview.

---

## 🧾 Lisensi

Proyek ini dilisensikan di bawah **MIT License** — Anda bebas menggunakan, menyalin, memodifikasi, atau mendistribusikan proyek ini selama mencantumkan atribusi kepada pengembang asli.

```
