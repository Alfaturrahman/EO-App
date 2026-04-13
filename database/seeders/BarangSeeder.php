<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangs = [
            // Sound System
            [
                'nama_barang' => 'Sound System Line Array 12 inch',
                'deskripsi' => 'Sound system professional untuk outdoor/indoor hingga 1000 orang. Include mixer, power amplifier, wireless mic 4 channel.',
                'harga_sewa' => 15000000,
                'stok' => 3,
            ],
            [
                'nama_barang' => 'Sound System Portable JBL 15 inch',
                'deskripsi' => 'Sound system portable untuk acara indoor 100-300 orang. Include mixer mini, wireless mic 2 channel, stand speaker.',
                'harga_sewa' => 3500000,
                'stok' => 8,
            ],
            [
                'nama_barang' => 'Wireless Microphone Shure',
                'deskripsi' => 'Microphone wireless professional, anti feedback, baterai tahan 8 jam.',
                'harga_sewa' => 300000,
                'stok' => 20,
            ],
            
            // Lighting
            [
                'nama_barang' => 'LED Par Light RGBW (Set 12 unit)',
                'deskripsi' => 'LED Par light RGBW untuk stage lighting, DMX control, include stand dan kabel.',
                'harga_sewa' => 4500000,
                'stok' => 5,
            ],
            [
                'nama_barang' => 'Moving Head Beam 230W (Set 4 unit)',
                'deskripsi' => 'Moving head beam untuk stage effect professional, DMX 512, rotation 360 derajat.',
                'harga_sewa' => 8000000,
                'stok' => 2,
            ],
            [
                'nama_barang' => 'LED Screen Outdoor P4 (6x4 meter)',
                'deskripsi' => 'LED videotron outdoor P4 high resolution, brightness 5000 nits, include controller, processor, dan struktur.',
                'harga_sewa' => 25000000,
                'stok' => 2,
            ],
            [
                'nama_barang' => 'Smoke Machine 3000W',
                'deskripsi' => 'Smoke machine professional untuk stage effect, DMX control, warming time 8 menit.',
                'harga_sewa' => 1500000,
                'stok' => 6,
            ],
            
            // Panggung & Struktur
            [
                'nama_barang' => 'Panggung Portable 1x1 meter (Per Pcs)',
                'deskripsi' => 'Panggung modular dengan frame besi kuat, top plywood triplek, tinggi adjustable 40-120cm.',
                'harga_sewa' => 150000,
                'stok' => 100,
            ],
            [
                'nama_barang' => 'Tenda Sarnafil 6x6 meter',
                'deskripsi' => 'Tenda sarnafil putih untuk outdoor event, tahan air dan UV, include tali dan paku ground.',
                'harga_sewa' => 2500000,
                'stok' => 15,
            ],
            [
                'nama_barang' => 'Tenda Roder 10x10 meter',
                'deskripsi' => 'Tenda roder full AC system untuk outdoor, tahan angin, include dinding samping kaca transparan.',
                'harga_sewa' => 8000000,
                'stok' => 5,
            ],
            
            // Furniture
            [
                'nama_barang' => 'Kursi Futura Putih (Per Pcs)',
                'deskripsi' => 'Kursi plastik futura warna putih untuk acara formal, kuat, ringan, stackable.',
                'harga_sewa' => 8000,
                'stok' => 1000,
            ],
            [
                'nama_barang' => 'Meja Bundar Diameter 180cm',
                'deskripsi' => 'Meja bundar untuk 10-12 orang, top fiberplastik, rangka besi kokoh.',
                'harga_sewa' => 150000,
                'stok' => 80,
            ],
            [
                'nama_barang' => 'Kursi Chiavari Gold/Silver (Per Pcs)',
                'deskripsi' => 'Kursi chiavari premium warna gold/silver untuk wedding atau acara mewah.',
                'harga_sewa' => 35000,
                'stok' => 500,
            ],
            [
                'nama_barang' => 'Sofa Tamu Set (4 pcs)',
                'deskripsi' => 'Set sofa tamu untuk VIP lounge, 1 sofa 3 seater + 1 sofa 2 seater + 2 single seater, include meja tamu.',
                'harga_sewa' => 2000000,
                'stok' => 10,
            ],
            
            // Dekorasi
            [
                'nama_barang' => 'Backdrop Wedding 4x3 meter',
                'deskripsi' => 'Backdrop custom design untuk wedding dengan rangka besi dan printing kain velvet.',
                'harga_sewa' => 3500000,
                'stok' => 8,
            ],
            [
                'nama_barang' => 'Standing Flower Besar (Per Pcs)',
                'deskripsi' => 'Standing flower premium dengan bunga segar, tinggi 2 meter, include stand.',
                'harga_sewa' => 750000,
                'stok' => 30,
            ],
            [
                'nama_barang' => 'Balon Gate Entrance',
                'deskripsi' => 'Dekorasi balon gate untuk entrance acara, custom color, tinggi 4 meter.',
                'harga_sewa' => 1500000,
                'stok' => 5,
            ],
            [
                'nama_barang' => 'Karpet Merah VIP (Per Roll 10m)',
                'deskripsi' => 'Karpet merah untuk red carpet entrance, lebar 2 meter, panjang 10 meter per roll.',
                'harga_sewa' => 500000,
                'stok' => 20,
            ],
            
            // Equipment
            [
                'nama_barang' => 'Projector HD 5000 Lumens + Screen',
                'deskripsi' => 'Projector HD resolution 1920x1080, brightness 5000 lumens, include screen 3x4 meter tripod.',
                'harga_sewa' => 2500000,
                'stok' => 10,
            ],
            [
                'nama_barang' => 'Generator Set 50 KVA',
                'deskripsi' => 'Genset silent 50 KVA untuk power backup event outdoor, BBM solar, include operator.',
                'harga_sewa' => 5000000,
                'stok' => 4,
            ],
            [
                'nama_barang' => 'Portable AC Standing 5 PK',
                'deskripsi' => 'AC portable standing untuk cooling area VIP atau tenda, cooling capacity 60m2.',
                'harga_sewa' => 1500000,
                'stok' => 12,
            ],
            [
                'nama_barang' => 'Barikade Crowd Control (Per Pcs)',
                'deskripsi' => 'Barikade besi untuk crowd control dan security line, panjang 2 meter, cat kuning hitam.',
                'harga_sewa' => 50000,
                'stok' => 200,
            ],
            [
                'nama_barang' => 'Photobooth Frame + Props',
                'deskripsi' => 'Frame photobooth custom design 2x2 meter dengan 50 pcs props lucu untuk foto acara.',
                'harga_sewa' => 800000,
                'stok' => 6,
            ],
            [
                'nama_barang' => 'Registation Table/Counter',
                'deskripsi' => 'Meja registration counter dengan backdrop branding, include kursi operator.',
                'harga_sewa' => 500000,
                'stok' => 15,
            ],
            [
                'nama_barang' => 'Podium Mimbar Acrylic',
                'deskripsi' => 'Podium mimbar acrylic transparan untuk MC atau pembicara, modern design.',
                'harga_sewa' => 350000,
                'stok' => 8,
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
