# APS Project Marketplace

A **multi-role marketplace platform** built with Laravel, featuring complete e-commerce functionality with saldo management, order tracking, and role-based dashboards (User, Admin, Supervisor).

## Fitur Utama

### User
- Registrasi & login dengan role management
- Jelajah & cari produk dengan filter kategori
- Keranjang belanja & wishlist
- Checkout dengan berbagai metode pembayaran:
  - **QRIS** (termasuk DANA & GoPay via QRIS)
  - **Transfer Bank** (BCA, Mandiri, BRI, BNI, BSI, CIMB Niaga, Permata, Danamon, Maybank)
  - **COD** (Bayar di Tempat)
- **Saldo** — isi ulang saldo (top-up via QRIS atau transfer bank)
- **Penarikan Saldo** — cairkan saldo ke rekening bank
- Pelacakan pesanan via nomor resi (`/tracking`)
- Konfirmasi pesanan diterima
- Komplain & pengembalian barang
- Rating & ulasan produk

### Admin
- Dashboard dengan statistik penjualan
- Kelola produk, kategori, banner
- Kelola user
- Kelola transaksi & input resi pengiriman
- **Kelola Saldo User** — tambah/kurang/set saldo, setujui/tolak penarikan
- Kelola komplain & retur
- Kelola review
- Pengaturan toko

### Supervisor
- Dashboard monitoring transaksi
- Kelola transaksi & input resi
- Kelola komplain & retur
- Laporan & analitik

## Tech Stack

- **Backend:** Laravel 11
- **Frontend:** Blade, Tailwind CSS, Alpine.js, Font Awesome
- **Database:** MySQL
- **Build Tool:** Vite

## Screenshots (concept)

| Area | Tampilan |
|------|----------|
| Landing Page | Hero, produk flash sale, kategori |
| Produk | Grid produk, filter, pencarian |
| Checkout | Pilih alamat, metode bayar, ringkasan |
| Lacak Pesanan | Timeline status, sticker resi, info pengiriman |
| Admin Saldo | Statistik, tabel user, permintaan penarikan |

## Instalasi

```bash
git clone https://github.com/purnama19452024/APSPROJECT-MARKETPLACE.git
cd APSPROJECT-MARKETPLACE
composer install
cp .env.example .env
php artisan key:generate
# setup database di .env
php artisan migrate --seed
npm install && npm run build
php artisan storage:link
php artisan serve
```

## Role Default (seeder)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@admin.com | password |
| Supervisor | supervisor@supervisor.com | password |
| User | (registrasi mandiri) | - |
