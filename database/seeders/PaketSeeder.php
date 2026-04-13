<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Paket;
use App\Models\Barang;

class PaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pakets = [
            [
                'nama_paket' => 'Paket Wedding Intimate',
                'deskripsi' => 'Paket pernikahan intimate untuk 100-150 tamu di area Batam. Termasuk:
- Sound System Professional
- Lighting & LED Wall
- Dekorasi Pelaminan Modern
- Panggung 4x6 meter
- Meja & Kursi Tamu (150 set)
- Tenda Sarnafil
- Backdrop Photobooth
- Koordinator Acara

Cocok untuk: Pernikahan intimate, Resepsi outdoor/indoor
Lokasi: Hotel, Gedung, atau Outdoor Venue di Batam',
                'harga_total' => 25000000,
            ],
            [
                'nama_paket' => 'Paket Wedding Grand',
                'deskripsi' => 'Paket pernikahan grand untuk 300-500 tamu di area Batam. Termasuk:
- Sound System Premium dengan Wireless Mic
- Lighting Professional & Moving Head
- LED Screen P3 Indoor/Outdoor
- Dekorasi Pelaminan Mewah & Standing Flower
- Panggung 8x10 meter
- Meja & Kursi VIP (500 set)
- Tenda Full AC
- Red Carpet & Barikade
- Generator Set 50 KVA
- Photographer & Videographer
- Koordinator & Crew Lengkap

Cocok untuk: Pernikahan grand, Resepsi mewah
Lokasi: Hotel Bintang 4-5, Convention Hall Batam',
                'harga_total' => 75000000,
            ],
            [
                'nama_paket' => 'Paket Birthday Party',
                'deskripsi' => 'Paket ulang tahun meriah untuk 50-100 orang di Batam. Termasuk:
- Sound System + DJ Equipment
- Lighting Disco & LED Par
- Dekorasi Balon & Backdrop Custom
- Panggung Mini 3x4 meter
- Meja Snack & Kursi
- Tenda Canopy
- Smoke Machine & Bubble Machine

Cocok untuk: Birthday party, Sweet 17, Prom night
Lokasi: Residence, Villa, Hotel, Cafe di Batam',
                'harga_total' => 8500000,
            ],
            [
                'nama_paket' => 'Paket Corporate Event',
                'deskripsi' => 'Paket acara perusahaan untuk 200-300 peserta di Batam. Termasuk:
- Sound System Conference
- Projector HD + Screen 3x4 meter
- Lighting Professional
- Panggung Presentasi 6x8 meter
- Meja & Kursi Auditorium
- Backdrop Branding
- Standing AC Portable
- Podium & Mic Wireless
- Registration Table
- Koordinator Event

Cocok untuk: Seminar, Training, Company Gathering, Product Launching
Lokasi: Hotel, Convention Center, Kantor di Batam',
                'harga_total' => 35000000,
            ],
            [
                'nama_paket' => 'Paket Konser & Festival',
                'deskripsi' => 'Paket konser musik atau festival untuk 500-1000 orang di Batam. Termasuk:
- Sound System Line Array Premium
- Stage Lighting Moving Head & LED Par
- LED Screen Outdoor P4 (6x4 meter)
- Stage Outdoor 12x10 meter
- Barikade & Security Line
- Smoke Machine & Special Effect
- Generator Set 100 KVA
- Tenda VIP & VVIP Area
- Backstage Tent
- Crew Teknis 15 orang

Cocok untuk: Konser musik, Festival gathering, Music event
Lokasi: Lapangan terbuka, Outdoor venue Batam',
                'harga_total' => 150000000,
            ],
            [
                'nama_paket' => 'Paket Family Gathering',
                'deskripsi' => 'Paket gathering keluarga besar atau reuni untuk 100-200 orang di Batam. Termasuk:
- Sound System Portable
- Lighting Standard
- Tenda Serba Guna 10x10
- Meja & Kursi 200 set
- Panggung Hiburan 4x6 meter
- Backdrop Banner Custom
- Standing Fan
- Games Equipment

Cocok untuk: Reuni, Family day, Syukuran, Halal bihalal
Lokasi: Villa, Resort, Pantai, Taman kota Batam',
                'harga_total' => 15000000,
            ],
            [
                'nama_paket' => 'Paket Exhibition & Bazaar',
                'deskripsi' => 'Paket pameran atau bazaar dengan 20-50 booth di Batam. Termasuk:
- Booth Pameran Modular (30 unit 3x3m)
- Sound System Area
- Lighting Spot per Booth
- Banner & Branding Gate
- Registration Counter
- Info Desk
- Crowd Control Line
- Power Point per Booth
- Koordinator Area

Cocok untuk: Exhibition, Expo, Bazaar, Trade show
Lokasi: Mall, Convention hall, Indoor arena Batam',
                'harga_total' => 45000000,
            ],
            [
                'nama_paket' => 'Paket Akad Nikah Sederhana',
                'deskripsi' => 'Paket akad nikah intimate untuk 50 tamu di area Batam. Termasuk:
- Sound System Mini + Wireless Mic
- Dekorasi Akad Background
- Kursi Pelaminan
- Kursi Tamu (50 set)
- Karpet & Standing Flower
- Photographer

Cocok untuk: Akad nikah intimate, Lamaran
Lokasi: Rumah, Masjid, Hotel di Batam',
                'harga_total' => 5500000,
            ],
        ];

        foreach ($pakets as $paketData) {
            $paket = Paket::create($paketData);
            
            // Attach barang-barang ke paket
            $this->attachBarangToPaket($paket);
        }
    }

    private function attachBarangToPaket($paket)
    {
        // Get IDs barang
        $soundLineArray = Barang::where('nama_barang', 'LIKE', '%Sound System Line Array%')->first()?->id;
        $soundPortable = Barang::where('nama_barang', 'LIKE', '%Sound System Portable%')->first()?->id;
        $wirelessMic = Barang::where('nama_barang', 'LIKE', '%Wireless Microphone%')->first()?->id;
        $ledPar = Barang::where('nama_barang', 'LIKE', '%LED Par Light%')->first()?->id;
        $movingHead = Barang::where('nama_barang', 'LIKE', '%Moving Head%')->first()?->id;
        $ledScreen = Barang::where('nama_barang', 'LIKE', '%LED Screen%')->first()?->id;
        $smokeMachine = Barang::where('nama_barang', 'LIKE', '%Smoke Machine%')->first()?->id;
        $panggung = Barang::where('nama_barang', 'LIKE', '%Panggung Portable%')->first()?->id;
        $tendaSarnafil = Barang::where('nama_barang', 'LIKE', '%Tenda Sarnafil%')->first()?->id;
        $tendaRoder = Barang::where('nama_barang', 'LIKE', '%Tenda Roder%')->first()?->id;
        $kursiFutura = Barang::where('nama_barang', 'LIKE', '%Kursi Futura%')->first()?->id;
        $kursiChiavari = Barang::where('nama_barang', 'LIKE', '%Kursi Chiavari%')->first()?->id;
        $mejaBundar = Barang::where('nama_barang', 'LIKE', '%Meja Bundar%')->first()?->id;
        $sofaTamu = Barang::where('nama_barang', 'LIKE', '%Sofa Tamu%')->first()?->id;
        $backdropWedding = Barang::where('nama_barang', 'LIKE', '%Backdrop Wedding%')->first()?->id;
        $standingFlower = Barang::where('nama_barang', 'LIKE', '%Standing Flower%')->first()?->id;
        $balonGate = Barang::where('nama_barang', 'LIKE', '%Balon Gate%')->first()?->id;
        $karpetMerah = Barang::where('nama_barang', 'LIKE', '%Karpet Merah%')->first()?->id;
        $projector = Barang::where('nama_barang', 'LIKE', '%Projector HD%')->first()?->id;
        $genset = Barang::where('nama_barang', 'LIKE', '%Generator Set%')->first()?->id;
        $acPortable = Barang::where('nama_barang', 'LIKE', '%Portable AC%')->first()?->id;
        $barikade = Barang::where('nama_barang', 'LIKE', '%Barikade%')->first()?->id;
        $photobooth = Barang::where('nama_barang', 'LIKE', '%Photobooth%')->first()?->id;
        $regTable = Barang::where('nama_barang', 'LIKE', '%Registation Table%')->first()?->id;
        $podium = Barang::where('nama_barang', 'LIKE', '%Podium%')->first()?->id;

        // Helper function untuk filter null
        $attachData = function($data) {
            return array_filter($data, function($key) {
                return $key !== null;
            }, ARRAY_FILTER_USE_KEY);
        };

        switch ($paket->nama_paket) {
            case 'Paket Wedding Intimate':
                $paket->barangs()->attach($attachData([
                    $soundPortable => ['jumlah' => 1],
                    $wirelessMic => ['jumlah' => 2],
                    $ledPar => ['jumlah' => 1],
                    $panggung => ['jumlah' => 24], // 4x6 meter = 24 pcs
                    $kursiFutura => ['jumlah' => 150],
                    $mejaBundar => ['jumlah' => 15],
                    $tendaSarnafil => ['jumlah' => 4],
                    $backdropWedding => ['jumlah' => 1],
                    $standingFlower => ['jumlah' => 6],
                    $photobooth => ['jumlah' => 1],
                ]));
                break;

            case 'Paket Wedding Grand':
                $paket->barangs()->attach($attachData([
                    $soundLineArray => ['jumlah' => 1],
                    $wirelessMic => ['jumlah' => 4],
                    $ledPar => ['jumlah' => 2],
                    $movingHead => ['jumlah' => 1],
                    $ledScreen => ['jumlah' => 1],
                    $panggung => ['jumlah' => 80], // 8x10 meter = 80 pcs
                    $kursiChiavari => ['jumlah' => 500],
                    $mejaBundar => ['jumlah' => 50],
                    $tendaRoder => ['jumlah' => 2],
                    $backdropWedding => ['jumlah' => 2],
                    $standingFlower => ['jumlah' => 20],
                    $karpetMerah => ['jumlah' => 3],
                    $barikade => ['jumlah' => 30],
                    $genset => ['jumlah' => 1],
                    $sofaTamu => ['jumlah' => 2],
                ]));
                break;

            case 'Paket Birthday Party':
                $paket->barangs()->attach($attachData([
                    $soundPortable => ['jumlah' => 1],
                    $wirelessMic => ['jumlah' => 2],
                    $ledPar => ['jumlah' => 1],
                    $smokeMachine => ['jumlah' => 1],
                    $panggung => ['jumlah' => 12], // 3x4 meter = 12 pcs
                    $kursiFutura => ['jumlah' => 100],
                    $mejaBundar => ['jumlah' => 10],
                    $tendaSarnafil => ['jumlah' => 1],
                    $balonGate => ['jumlah' => 1],
                    $photobooth => ['jumlah' => 1],
                ]));
                break;

            case 'Paket Corporate Event':
                $paket->barangs()->attach($attachData([
                    $soundPortable => ['jumlah' => 2],
                    $wirelessMic => ['jumlah' => 3],
                    $ledPar => ['jumlah' => 1],
                    $projector => ['jumlah' => 2],
                    $panggung => ['jumlah' => 48], // 6x8 meter = 48 pcs
                    $kursiFutura => ['jumlah' => 300],
                    $mejaBundar => ['jumlah' => 30],
                    $acPortable => ['jumlah' => 4],
                    $podium => ['jumlah' => 1],
                    $regTable => ['jumlah' => 2],
                ]));
                break;

            case 'Paket Konser & Festival':
                $paket->barangs()->attach($attachData([
                    $soundLineArray => ['jumlah' => 2],
                    $wirelessMic => ['jumlah' => 6],
                    $ledPar => ['jumlah' => 3],
                    $movingHead => ['jumlah' => 2],
                    $ledScreen => ['jumlah' => 2],
                    $smokeMachine => ['jumlah' => 3],
                    $panggung => ['jumlah' => 120], // 12x10 meter = 120 pcs
                    $barikade => ['jumlah' => 100],
                    $genset => ['jumlah' => 2],
                    $tendaRoder => ['jumlah' => 3],
                    $sofaTamu => ['jumlah' => 5],
                    $karpetMerah => ['jumlah' => 2],
                ]));
                break;

            case 'Paket Family Gathering':
                $paket->barangs()->attach($attachData([
                    $soundPortable => ['jumlah' => 1],
                    $wirelessMic => ['jumlah' => 2],
                    $ledPar => ['jumlah' => 1],
                    $panggung => ['jumlah' => 24], // 4x6 meter = 24 pcs
                    $kursiFutura => ['jumlah' => 200],
                    $mejaBundar => ['jumlah' => 20],
                    $tendaSarnafil => ['jumlah' => 4],
                ]));
                break;

            case 'Paket Exhibition & Bazaar':
                $paket->barangs()->attach($attachData([
                    $soundPortable => ['jumlah' => 2],
                    $ledPar => ['jumlah' => 2],
                    $regTable => ['jumlah' => 5],
                    $barikade => ['jumlah' => 50],
                ]));
                break;

            case 'Paket Akad Nikah Sederhana':
                $paket->barangs()->attach($attachData([
                    $soundPortable => ['jumlah' => 1],
                    $wirelessMic => ['jumlah' => 1],
                    $kursiFutura => ['jumlah' => 50],
                    $standingFlower => ['jumlah' => 4],
                    $karpetMerah => ['jumlah' => 1],
                ]));
                break;
        }
    }
}
