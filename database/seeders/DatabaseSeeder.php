<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Container;
use App\Models\TrackingLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // === USERS ===
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@wowoclean.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Operator Lapangan',
            'email' => 'operator@wowoclean.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // === CONTAINERS ===
        $containers = [
            ['kode_container' => 'WC-B3-001', 'jenis_limbah' => 'Chemical',    'kapasitas' => 500,  'lokasi' => 'Gudang Utama - Zona A',     'status' => 'Active'],
            ['kode_container' => 'WC-B3-002', 'jenis_limbah' => 'Medical',     'kapasitas' => 300,  'lokasi' => 'RS Pusat - Zona B',         'status' => 'Active'],
            ['kode_container' => 'WC-B3-003', 'jenis_limbah' => 'Electronic',  'kapasitas' => 800,  'lokasi' => 'Pabrik Elektronik - Zona C','status' => 'Full'],
            ['kode_container' => 'WC-B3-004', 'jenis_limbah' => 'Chemical',    'kapasitas' => 1000, 'lokasi' => 'Lab Kimia - Zona D',        'status' => 'Maintenance'],
            ['kode_container' => 'WC-B3-005', 'jenis_limbah' => 'Radioactive', 'kapasitas' => 200,  'lokasi' => 'Reaktor Nuklir - Zona E',   'status' => 'Active'],
            ['kode_container' => 'WC-B3-006', 'jenis_limbah' => 'Medical',     'kapasitas' => 450,  'lokasi' => 'Klinik Utara - Zona F',     'status' => 'Archived'],
            ['kode_container' => 'WC-B3-007', 'jenis_limbah' => 'Chemical',    'kapasitas' => 750,  'lokasi' => 'Pabrik Kimia - Zona G',     'status' => 'Active'],
            ['kode_container' => 'WC-B3-008', 'jenis_limbah' => 'Electronic',  'kapasitas' => 600,  'lokasi' => 'Warehouse IT - Zona H',     'status' => 'Maintenance'],
        ];

        foreach ($containers as $c) {
            Container::create($c);
        }

        // === TRACKING LOGS ===
        $logs = [
            ['container_id' => 1, 'tanggal' => '2026-05-01', 'lokasi' => 'Gudang Utama',           'catatan' => 'Container diterima dan diperiksa',         'operator' => 'Budi Santoso',   'status_perjalanan' => 'Received'],
            ['container_id' => 1, 'tanggal' => '2026-05-05', 'lokasi' => 'TPA Zona A',             'catatan' => 'Dipindahkan ke area pemrosesan',           'operator' => 'Andi Wijaya',    'status_perjalanan' => 'In Transit'],
            ['container_id' => 1, 'tanggal' => '2026-05-10', 'lokasi' => 'Fasilitas Pengolahan',   'catatan' => 'Proses pengolahan limbah dimulai',          'operator' => 'Sari Dewi',      'status_perjalanan' => 'Processing'],
            ['container_id' => 2, 'tanggal' => '2026-05-02', 'lokasi' => 'RS Pusat',               'catatan' => 'Pengambilan limbah medis rutin',            'operator' => 'Rina Susanti',   'status_perjalanan' => 'Received'],
            ['container_id' => 2, 'tanggal' => '2026-05-08', 'lokasi' => 'Incinerator Pusat',      'catatan' => 'Dikirim untuk insinerasi',                  'operator' => 'Dedi Kurniawan','status_perjalanan' => 'In Transit'],
            ['container_id' => 3, 'tanggal' => '2026-05-03', 'lokasi' => 'Pabrik Elektronik',      'catatan' => 'Container sudah penuh, siap diangkut',     'operator' => 'Hendra Putra',   'status_perjalanan' => 'Full'],
            ['container_id' => 3, 'tanggal' => '2026-05-12', 'lokasi' => 'Recycling Center',       'catatan' => 'Dipindahkan ke pusat daur ulang',           'operator' => 'Maya Sari',      'status_perjalanan' => 'In Transit'],
            ['container_id' => 4, 'tanggal' => '2026-05-04', 'lokasi' => 'Lab Kimia',              'catatan' => 'Maintenance rutin container',                'operator' => 'Rizky Pratama',  'status_perjalanan' => 'Maintenance'],
            ['container_id' => 5, 'tanggal' => '2026-05-06', 'lokasi' => 'Reaktor Nuklir',         'catatan' => 'Inspeksi keamanan radiasi',                 'operator' => 'Dr. Taufik',     'status_perjalanan' => 'Inspection'],
            ['container_id' => 5, 'tanggal' => '2026-05-15', 'lokasi' => 'Bunker Penyimpanan',     'catatan' => 'Dipindahkan ke bunker penyimpanan aman',    'operator' => 'Ahmad Faisal',   'status_perjalanan' => 'In Transit'],
            ['container_id' => 6, 'tanggal' => '2026-05-07', 'lokasi' => 'Klinik Utara',           'catatan' => 'Container di-archive setelah pengolahan',   'operator' => 'Putri Amelia',   'status_perjalanan' => 'Completed'],
            ['container_id' => 7, 'tanggal' => '2026-05-09', 'lokasi' => 'Pabrik Kimia',           'catatan' => 'Pengisian container baru dimulai',          'operator' => 'Joko Widodo',    'status_perjalanan' => 'Received'],
            ['container_id' => 7, 'tanggal' => '2026-05-18', 'lokasi' => 'Storage Zona G',         'catatan' => 'Monitoring level limbah 60%',               'operator' => 'Lina Marlina',   'status_perjalanan' => 'Monitoring'],
            ['container_id' => 8, 'tanggal' => '2026-05-11', 'lokasi' => 'Warehouse IT',           'catatan' => 'Maintenance karena kebocoran minor',        'operator' => 'Fajar Nugroho',  'status_perjalanan' => 'Maintenance'],
            ['container_id' => 8, 'tanggal' => '2026-05-20', 'lokasi' => 'Workshop Reparasi',      'catatan' => 'Perbaikan seal container selesai',          'operator' => 'Bambang Suryadi','status_perjalanan' => 'Repaired'],
        ];

        foreach ($logs as $log) {
            TrackingLog::create($log);
        }
    }
}
