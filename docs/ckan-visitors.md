# Integrasi Statistik Pengunjung CKAN

Dokumen ini menjelaskan bagaimana sistem menerima dan menampilkan statistik pengunjung CKAN pada dashboard `home/index.blade.php`.

## Ringkasan Arsitektur

-   Data pengunjung CKAN dikirim oleh aplikasi CKAN melalui endpoint `POST /api/ckan/visitors`.
-   Endpoint menyimpan data ke tabel `ckan_visitors` menggunakan service `App\Services\CkanService`.
-   Dashboard (`HomeController@index`) membaca data dari model `CkanVisitor` dan menampilkannya dalam tiga periode: harian, bulanan, dan tahunan.

## Struktur Database

Tabel `ckan_visitors`:

-   `iid` (string, opsional) – identifier data CKAN.
-   `url` (string, opsional) – tautan ke resource CKAN.
-   `count` (unsigned big integer) – jumlah kunjungan.
-   `period` (enum: `daily`, `monthly`, `yearly`) – kategori periode data.
-   `period_date` (date) – tanggal representatif periode.
-   `created_at` & `updated_at`.

Constraint unik: kombinasi `period`, `period_date`, dan `iid` mencegah duplikasi.

## Endpoint API

### URL

```
POST /api/ckan/visitors
```

### Header Opsional

```
X-CKAN-SECRET: <secret>
```

Header wajib dicantumkan jika variabel lingkungan `CKAN_SHARED_SECRET` ditetapkan pada aplikasi ini. Nilai harus identik (dengan perbandingan `hash_equals`).

### Payload

Property `records` berisi array objek:

```json
{
    "records": [
        {
            "period": "daily",
            "date": "2025-11-10",
            "count": 123,
            "iid": "resource-id",
            "url": "https://ckan.example/resource"
        }
    ]
}
```

#### Validasi

-   `records`: wajib, array.
-   `period`: wajib, salah satu dari `daily`, `monthly`, `yearly`.
-   `date`: wajib, bertipe tanggal (string yang dapat di-parse oleh Carbon).
-   `count`: wajib, integer ≥ 0.
-   `iid`: opsional, string maks 255 karakter.
-   `url`: opsional, URL valid.

### Respons Sukses

```json
{
    "status": "success"
}
```

Status HTTP: `201 Created`.

### Perilaku Penyimpanan

-   Data dikelompokkan berdasarkan `period` oleh controller.
-   Service `CkanService::syncVisitors` menormalisasi tanggal sesuai periode:
    -   `daily` → awal hari.
    -   `monthly` → awal bulan.
    -   `yearly` → awal tahun.
-   Penyimpanan menggunakan `upsert` pada kombinasi (`period`, `period_date`, `iid`).

## Tampilan Dashboard

File: `resources/views/home/index.blade.php`

-   Menampilkan tabel per periode, mencantumkan `IID`, `URL`, dan `Count`.
-   Menyediakan total agregat per periode (`sum('count')`).
-   URL otomatis menjadi tautan jika tersedia.

## Konfigurasi Lingkungan

Tambahkan variabel berikut ke `.env`:

```
CKAN_SHARED_SECRET=optional-secret
```

`CKAN_SHARED_SECRET` bersifat opsional. Jika tidak diset, endpoint tidak memerlukan header autentikasi.

Variabel lain yang sebelumnya digunakan untuk fetch langsung (`CKAN_VISITOR_ENDPOINT`, `CKAN_API_KEY`, `CKAN_TIMEOUT`) tidak lagi diperlukan oleh dashboard, namun bisa dipertahankan bila ingin menambahkan sinkronisasi aktif dari sisi aplikasi.

## Alur Kerja Pengiriman Data

1. CKAN menghitung statistik kunjungan sesuai periode.
2. CKAN mengirim data melalui HTTP POST ke endpoint di atas.
3. Aplikasi Laravel mem-validasi, menyimpan/ memperbarui data.
4. Dashboard otomatis menampilkan data terbaru tanpa perlu proses manual.

## Pengujian Manual

Contoh menggunakan `curl`:

```bash
curl -X POST https://satu-data.example/api/ckan/visitors \
  -H "Content-Type: application/json" \
  -H "X-CKAN-SECRET: optional-secret" \
  -d '{
        "records": [
          { "period": "daily", "date": "2025-11-10", "count": 100, "iid": "dataset-1", "url": "https://ckan.example/dataset-1" },
          { "period": "monthly", "date": "2025-11-01", "count": 2500, "iid": "dataset-1", "url": "https://ckan.example/dataset-1" }
        ]
      }'
```

## Catatan Pengembangan

-   Pastikan migrasi dijalankan: `php artisan migrate`.
-   Jika ingin menjalankan proses sinkronisasi otomatis dari CKAN (mis. cron job), manfaatkan method `syncVisitors` dengan data yang diambil dari API CKAN atau sumber lain.
-   Pertimbangkan penambahan logging atau monitoring pada endpoint bila diperlukan audit trail.
