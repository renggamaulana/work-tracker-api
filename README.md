# 📦 Work Tracker Backend – Laravel REST API

**Work Tracker** adalah aplikasi backend untuk mencatat pekerjaan pegawai serta menghitung total remunerasi, termasuk fitur pembagian pembayaran secara **prorata** jika pekerjaan dilakukan oleh lebih dari satu pegawai.

---

## 🔧 Teknologi

- **Framework**: Laravel 11
- **Database**: MySQL
- **API Format**: RESTful JSON
- **Relasi**: One-to-Many (`WorkLog` → `WorkContributors`)

---

## 🧩 Arsitektur Solusi

### 📌 Alur Data

```text
[Frontend Form Input]
       ↓ (Axios POST JSON)
[Laravel Controller (WorkLogController)]
       ↓
[Validasi (FormRequest)]
       ↓
[Hitung Total Remunerasi]
       ↓
[Simpan ke DB → work_logs, work_contributors]
       ↓
[Respons JSON via Resource → Frontend]

🧠 Penjelasan Desain
💡 Alasan Pendekatan
- Normalisasi data: WorkLog menyimpan info pekerjaan, WorkContributor menyimpan kontribusi per pegawai.

- Relasi 1-to-many: fleksibel untuk pekerjaan tim atau individu.

- Form Request: memisahkan validasi dari controller.

- API Resource: menjaga struktur response tetap bersih.

🧮 Logika Perhitungan Remunerasi
Remunerasi dihitung sebagai:

total = (Σ contributors.hours_spent × hourly_rate) + additional_charges
Distribusi bersifat prorata: setiap pegawai diberi bagian sesuai jumlah jam kerjanya terhadap total jam.

🛠️ Setup & Deploy

1. Clone Repository
git clone https://github.com/yourusername/taskpay-backend.git
cd taskpay-backend

2. Install Dependencies
composer install

3. Setup Environment

cp .env.example .env
php artisan key:generate
Lalu edit file .env sesuai database lokal:

DB_DATABASE=work_tracker
DB_USERNAME=root
DB_PASSWORD=yourpassword
Buat database taskpay di MySQL.

4. Migrate & Seed
php artisan migrate --seed
Seeder akan membuat contoh data pekerjaan dan contributors.

5. Jalankan Server di terminal

php artisan serve
Aplikasi API tersedia di:
📍 http://localhost:8000/api/work-logs

🔄 Endpoint API
Method	Endpoint	Deskripsi
GET	/api/work-logs	List semua pekerjaan
GET	/api/work-logs/{id}	Detail pekerjaan + contributors
POST	/api/work-logs	Tambah pekerjaan dan pegawai
PUT	/api/work-logs/{id}	Update pekerjaan dan pegawai
DELETE	/api/work-logs/{id}	Hapus pekerjaan dan kontributor

🧪 Contoh Request Body

{
  "task_description": "Build dashboard UI",
  "date": "2025-05-15",
  "hourly_rate": 100000,
  "additional_charges": 25000,
  "contributors": [
    { "employee_name": "Adit", "hours_spent": 3 },
    { "employee_name": "Sari", "hours_spent": 2 }
  ]
}

⚠️ Tantangan & Solusi
1. Bagaimana membagi pembayaran jika pekerjaan dikerjakan oleh banyak pegawai?
✅ Solusi: Gunakan WorkContributor, lalu hitung total jam × tarif, lalu tambahkan biaya tambahan → hasil dibagi prorata ke semua pegawai.

2. Validasi input nested array contributors
✅ Solusi: Gunakan Laravel FormRequest (StoreWorkLogRequest, UpdateWorkLogRequest) dengan validasi seperti:

'contributors.*.employee_name' => 'required|string',
'contributors.*.hours_spent' => 'required|numeric|min:0.1'
3. Output JSON terlalu mentah
✅ Solusi: Gunakan WorkLogResource agar respons rapi, tidak menampilkan field yang tidak dibutuhkan frontend.

4. Saat update, contributor lama tidak boleh nyangkut
✅ Solusi: Di update() controller, semua contributor lama dihapus, kemudian ditulis ulang dari data baru.

---







