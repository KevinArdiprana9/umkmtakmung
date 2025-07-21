<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UMKMController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            [
                'id' => 1,
                'name' => "Warung Sate Lilit Bu Made",
                'category' => "Makanan & Minuman",
                'description' => "Sate lilit khas Bali dengan bumbu tradisional yang telah diwariskan turun temurun. Dibuat dari daging ayam dan ikan segar dengan rempah-rempah pilihan.",
                'image' => "https://images.pexels.com/photos/5737241/pexels-photo-5737241.jpeg?auto=compress&cs=tinysrgb&w=400",
                'location' => "Banjar Tengah"
            ],
            [
                'id' => 2,
                'name' => "Kerajinan Anyaman Bambu Pak Wayan",
                'category' => "Kerajinan Tangan",
                'description' => "Kerajinan anyaman bambu berkualitas tinggi meliputi tas, tempat nasi, dan dekorasi rumah. Dibuat dengan teknik tradisional Bali yang autentik.",
                'image' => "https://images.pexels.com/photos/7005478/pexels-photo-7005478.jpeg?auto=compress&cs=tinysrgb&w=400",
                'location' => "Banjar Pegatepan"
            ],
            [
                'id' => 3,
                'name' => "Tani Organik Subak Takmung",
                'category' => "Pertanian",
                'description' => "Sayuran dan buah-buahan organik segar yang ditanam dengan sistem subak tradisional. Menggunakan pupuk alami dan metode pertanian berkelanjutan.",
                'image' => "https://images.pexels.com/photos/1300972/pexels-photo-1300972.jpeg?auto=compress&cs=tinysrgb&w=400",
                'location' => "Subak Abian"
            ],
            [
                'id' => 4,
                'name' => "Kopi Robusta Takmung",
                'category' => "Makanan & Minuman",
                'description' => "Kopi robusta premium dari kebun kopi lokal Desa Takmung. Disangrai dengan metode tradisional untuk menghasilkan cita rasa yang khas dan aroma yang menggoda.",
                'image' => "https://images.pexels.com/photos/1695052/pexels-photo-1695052.jpeg?auto=compress&cs=tinysrgb&w=400",
                'location' => "Banjar Tengah"
            ],
            [
                'id' => 5,
                'name' => "Furniture Kayu Jati Pak Ketut",
                'category' => "Furniture",
                'description' => "Furniture kayu jati berkualitas tinggi dengan desain tradisional dan modern. Dibuat oleh pengrajin berpengalaman dengan finishing yang halus dan tahan lama.",
                'image' => "https://images.pexels.com/photos/1866149/pexels-photo-1866149.jpeg?auto=compress&cs=tinysrgb&w=400",
                'location' => "Banjar Pegatepan"
            ],
            [
                'id' => 6,
                'name' => "Madu Hutan Takmung",
                'category' => "Pertanian",
                'description' => "Madu murni dari hutan sekitar Desa Takmung yang dipanen dengan metode tradisional. Kaya akan nutrisi dan memiliki khasiat untuk kesehatan.",
                'image' => "https://images.pexels.com/photos/33288/honey-jar-glass-open.jpg?auto=compress&cs=tinysrgb&w=400",
                'location' => "Subak Abian"
            ],
            [
                'id' => 7,
                'name' => "Kerupuk Lele",
                'category' => "Makanan & Minuman",
                'description' => "Kerupuk lele khas Bali dengan rasa gurih dan tekstur renyah. Menggunakan bahan baku ikan lele, produk ini masih sangat jarang ditemukan di pasaran.",
                'image' => "https://via.placeholder.com/400x300?text=Kerupuk+Lele",
                'location' => "Banjar Umasalakan"
            ],

        ];

        $search = $request->query('search');
        if ($search) {
            $data = array_filter($data, function ($item) use ($search) {
                return Str::contains(strtolower($item['name']), strtolower($search)) ||
                    Str::contains(strtolower($item['category']), strtolower($search)) ||
                    Str::contains(strtolower($item['description']), strtolower($search));
            });
        }

        return view('umkm', [
            'umkmList' => $data,
            'search' => $search,
            'total' => count($data),
        ]);
    }

    public function detail($id)
    {
        $database = [
            '1' => [
                'id' => 1,
                'name' => "Warung Sate Lilit Bu Made",
                'category' => "Makanan & Minuman",
                'description' => "Sate lilit khas Bali dengan bumbu tradisional...",
                'fullDescription' => "Warung Sate Lilit Bu Made telah menjadi ikon kuliner Desa Takmung selama lebih dari 20 tahun. Didirikan oleh Ibu Made Sari pada tahun 2003, warung ini menyajikan sate lilit autentik Bali yang dibuat dari daging ayam dan ikan segar pilihan. Bumbu rahasia yang digunakan merupakan resep turun temurun dari nenek moyang yang telah diwariskan secara turun temurun.\n\nSetiap hari, Bu Made dan timnya memulai persiapan sejak dini hari untuk memastikan kualitas dan kesegaran bahan baku. Daging ayam dan ikan dipilih langsung dari peternak dan nelayan lokal, sementara rempah-rempah seperti kemiri, ketumbar, kunyit, dan cabai didapat dari kebun sendiri. Proses pembuatan sate lilit dilakukan dengan teknik tradisional menggunakan batang serai sebagai tusukan, memberikan aroma khas yang menggugah selera",
                'image' => "https://images.pexels.com/photos/5737241/pexels-photo-5737241.jpeg?auto=compress&cs=tinysrgb&w=800",
                'location' => "Banjar Tengah",
                'address' => "Jl. Raya Takmung No. 15, Banjar Tengah, Desa Takmung",
                'phone' => "+62 361 234 5678",
                'email' => "satelilit.bumade@gmail.com",
                'operatingHours' => "08:00 - 21:00 WITA",
                'established' => "2003",
                'owner' => "Ni Made Sari",
                'employees' => 5,
                'specialties' => ["Sate Lilit Ayam", "Sate Lilit Ikan", "Nasi Campur Bali", "Es Daluman"],
                'gallery' => [
                    "https://images.pexels.com/photos/5737241/pexels-photo-5737241.jpeg?auto=compress&cs=tinysrgb&w=600",
                    "https://images.pexels.com/photos/1633525/pexels-photo-1633525.jpeg?auto=compress&cs=tinysrgb&w=600",
                    "https://images.pexels.com/photos/1640777/pexels-photo-1640777.jpeg?auto=compress&cs=tinysrgb&w=600"
                ],
                'achievements' => [
                    "Juara 1 Festival Kuliner Bangli 2022",
                    "Sertifikat Halal MUI Bali",
                    "Penghargaan UMKM Terbaik Desa Takmung 2023"
                ]
            ],
            '4' => [
                'id' => 4,
                'name' => "Kopi Robusta Takmung",
                'category' => "Makanan & Minuman",
                'description' => "Kopi robusta premium dari kebun kopi lokal Desa Takmung.",
                'fullDescription' => "Kopi Robusta Takmung adalah usaha keluarga yang telah mengembangkan budidaya kopi robusta...",
                'image' => "https://images.pexels.com/photos/1695052/pexels-photo-1695052.jpeg?auto=compress&cs=tinysrgb&w=800",
                'location' => "Banjar Tengah",
                'address' => "Jl. Subak Abian No. 28, Banjar Tengah, Desa Takmung",
                'phone' => "+62 361 345 6789",
                'email' => "kopirobusta.takmung@gmail.com",
                'operatingHours' => "07:00 - 17:00 WITA",
                'established' => "1995",
                'owner' => "I Wayan Sutrisna",
                'employees' => 8,
                'specialties' => ["Kopi Robusta Biji", "Kopi Sangrai Medium", "Kopi Sangrai Dark", "Kopi Bubuk Premium"],
                'gallery' => [
                    "https://images.pexels.com/photos/1695052/pexels-photo-1695052.jpeg?auto=compress&cs=tinysrgb&w=600",
                    "https://images.pexels.com/photos/159888/coffee-beans-coffee-roasting-cafe-159888.jpeg?auto=compress&cs=tinysrgb&w=600",
                    "https://images.pexels.com/photos/209090/pexels-photo-209090.jpeg?auto=compress&cs=tinysrgb&w=600"
                ],
                'achievements' => [
                    "Sertifikat Organik Indonesia",
                    "Juara 2 Kompetisi Kopi Nusantara 2023",
                    "Kemitraan dengan Coffee Shop Bali"
                ]
            ],
            '7' => [
                'id' => 7,
                'name' => "Kerupuk Lele",
                'category' => "Makanan & Minuman",
                'description' => "Kerupuk berbahan dasar ikan lele dengan rasa khas dan tekstur unik.",
                'fullDescription' => "Kerupuk Lele adalah usaha rumahan yang berdiri sejak tahun 2013 dan mulai ditekuni secara serius pada 2021. Produk ini menggunakan bahan baku ikan lele, yang masih sangat langka digunakan untuk kerupuk di Bali.\n\nProses produksi dilakukan dengan menjaga kualitas rasa dan tekstur yang renyah. Selain sebagai produk inovatif, Kerupuk Lele juga sudah mendapatkan penghargaan dari pihak kedinasan, fakultas, dan pemerintah karena telah memiliki izin resmi.\n\nPenjualan dilakukan secara langsung maupun COD. Produk tersedia setiap hari kerja, kecuali Minggu atau saat upacara adat seperti Nyepi dan Kuningan.",
                'image' => "https://via.placeholder.com/800x400?text=Kerupuk+Lele",
                'location' => "Banjar Umasalakan",
                'address' => "Jalan Melasti 2 nomor 10, Banjar Umasalakan, Desa Takmung, Kecamatan Banjarangkan, Kabupaten Klungkung, Provinsi Bali",
                'phone' => "+62 821 4570 1911",
                'email' => "",
                'operatingHours' => "08:00 - 16:00",
                'established' => "2013",
                'owner' => "—",
                'employees' => 10,
                'specialties' => [
                    "Kerupuk Lele Original",
                    "Rasa dan Tekstur Khas",
                ],
                'gallery' => [
                    "https://via.placeholder.com/600x400?text=Foto+Produk+1",
                    "https://via.placeholder.com/600x400?text=Foto+Produk+2",
                    "https://via.placeholder.com/600x400?text=Tempat+Produksi",
                ],
                'achievements' => [
                    "Penghargaan dari Kedinasan",
                    "Penghargaan Fakultas",
                    "Izin Resmi Pemerintah"
                ]
            ],
        ];

        if (!isset($database[$id])) {
            return view('umkmdetail-notfound');
        }

        return view('umkmdetail', [
            'umkm' => $database[$id],
        ]);
    }
}
