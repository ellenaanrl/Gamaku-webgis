# 🚀 Laravel Project Setup with PostgreSQL + PostGIS

Panduan ini membantumu men-setup project Laravel yang menggunakan PostgreSQL (dengan optional dukungan PostGIS untuk data spasial) dari awal hingga berjalan.

---

## 🔧 1. Prerequisites

- PHP >= 8.1
- Composer
- Laravel CLI (`composer global require laravel/installer`)
- PostgreSQL (dengan atau tanpa PostGIS)
- Node.js & npm (optional, untuk frontend)

---

## 🛠️ 2. Clone & Install Laravel

```bash
git clone https://github.com/your-username/your-project.git
cd your-project
composer install
````

---

## 📁 3. Copy Environment File & Set Configuration

```bash
cp .env.example .env
```

Edit `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=gamaku
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

---

## 🗃️ 4. Install PostgreSQL (Linux / Windows)

### 🔹 Linux (Ubuntu)

```bash
sudo apt update
sudo apt install postgresql postgresql-contrib
```

#### 🔸 (Optional) Install PostGIS

```bash
sudo apt install postgresql-12-postgis-3
# jika tidak tersedia:
sudo apt install postgresql-12-postgis-2.5
```

### 🔹 Windows

1. **Download PostgreSQL**
   👉 [https://www.enterprisedb.com/downloads/postgres-postgresql-downloads](https://www.enterprisedb.com/downloads/postgres-postgresql-downloads)

2. **Install dengan StackBuilder**
   Setelah install selesai, jalankan **StackBuilder** → pilih PostgreSQL instance → install **PostGIS**.

3. **Aktifkan PostGIS**
   Masuk ke pgAdmin atau `psql` lalu jalankan:

   ```sql
   CREATE EXTENSION postgis;
   ```

---

## 🧭 5. Create Database & Enable PostGIS

```bash
sudo -u postgres psql
```

```sql
CREATE DATABASE gamaku;
\c gamaku
CREATE EXTENSION postgis;  -- optional jika pakai tipe geometry
\q
```

---

## 📦 6. Run Migration & Seeder

```bash
php artisan migrate --seed
```

Atau hanya migrate saja:

```bash
php artisan migrate
```
hanya seed saja:
```bash
php artisan db:seed
```
Menjalankan seeder tertentu:

```bash
php artisan db:seed --class=UserSeeder
```

---

## 🚀 7. Jalankan Laravel Server

```bash
php artisan serve
```

Akses di:
📍 [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🧪 (Optional) Docker Setup with PostGIS

Tambahkan ke `docker-compose.yml`:

```yaml
services:
  postgres:
    image: postgis/postgis:12-3.3
    ports:
      - "5432:5432"
    environment:
      POSTGRES_DB: gamaku
      POSTGRES_USER: postgres
      POSTGRES_PASSWORD: your_password
    volumes:
      - pgdata:/var/lib/postgresql/data

volumes:
  pgdata:
```

Lalu jalankan:

```bash
docker-compose up -d
```

---

## ✅ Common Laravel Commands

```bash
php artisan migrate           # Jalankan migrasi
php artisan migrate:fresh     # Reset database & migrasi ulang
php artisan db:seed           # Jalankan semua seeder
php artisan db:seed --class=NamaSeeder  # Jalankan seeder tertentu
php artisan tinker            # Mode interaktif untuk debugging
```

---

## 🛟 Troubleshooting

| Error                                          | Solusi                                                                                            |
| ---------------------------------------------- | ------------------------------------------------------------------------------------------------- |
| `could not find driver`                        | Jalankan: `sudo apt install php-pgsql`                                                            |
| `type "geometry" does not exist`               | Jalankan: `CREATE EXTENSION postgis;` di dalam database                                           |
| `ERROR: could not open extension control file` | Pastikan PostGIS terinstall, atau gunakan versi yang sesuai (contoh: `postgresql-12-postgis-2.5`) |
| `Access denied in DBeaver`                     | Pastikan ekstensi PostGIS aktif dan user punya hak akses                                          |

---

## 📌 Catatan Tambahan

* Untuk menggunakan kolom `geometry`, `point`, dll — pastikan PostGIS aktif.
* Jika `.env` kamu berubah, jalankan:

```bash
php artisan config:clear
php artisan cache:clear
```

---

## ✨ Selamat Membangun Aplikasi!
