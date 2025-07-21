@extends('layouts.app')

@section('content')
@if (!$umkm)
<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">UMKM tidak ditemukan</h2>
        <a href="/umkm"
            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            ← Kembali ke Beranda
        </a>
    </div>
</div>
@else
<div class="min-h-screen bg-gray-50">

    <div class="relative">
        <div class="h-96 overflow-hidden relative">
            <img src="{{ $umkm->image }}" alt="{{ $umkm->name }}" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

            <div class="absolute bottom-0 left-0 right-0 p-8">
                <div class="max-w-4xl mx-auto text-white">
                    <div class="flex items-center mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-600 text-white">
                            {{ $umkm->category }}
                        </span>
                        <div class="flex items-center ml-4">
                            📍 {{ $umkm->location }}
                        </div>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2">{{ $umkm->name }}</h1>
                    <p class="text-xl text-white/90">{{ $umkm->description }}</p>
                </div>
            </div>
        </div>
    </div>


    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-12 grid lg:grid-cols-3 gap-12">
        <!-- Left Section -->
        <div class="lg:col-span-2">
            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <!-- Jam Operasional -->
                <div class="bg-white shadow-sm rounded-xl p-5 border text-center">
                    <div class="text-red-600 text-2xl mb-2">⏰</div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-1">Jam Operasional</h4>
                    <p class="text-gray-800 text-sm">{{ $umkm->operating_hours }}</p>
                </div>

                <!-- Tim -->
                <div class="bg-white shadow-sm rounded-xl p-5 border text-center">
                    <div class="text-red-600 text-2xl mb-2">👥</div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-1">Tim</h4>
                    <p class="text-gray-800 text-sm">{{ $umkm->employees }} Karyawan</p>
                </div>

                <!-- Berdiri -->
                <div class="bg-white shadow-sm rounded-xl p-5 border text-center">
                    <div class="text-red-600 text-2xl mb-2">🏅</div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-1">Berdiri</h4>
                    <p class="text-gray-800 text-sm">Tahun {{ $umkm->established }}</p>
                </div>
            </div>

            <!-- Tentang Usaha -->
            <div class="bg-white rounded-lg p-8 shadow-sm border border-gray-100 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Tentang Usaha</h2>
                <div class="prose prose-gray max-w-none">
                    {!! nl2br(e($umkm->full_description)) !!}
                </div>
            </div>

            <!-- Produk Unggulan -->
            <div class="bg-white rounded-lg p-8 shadow-sm border border-gray-100 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Keunggulan UMKM</h2>
                <ul class="grid md:grid-cols-2 gap-3">
                    @foreach ($umkm->specialties->pluck('name') as $item)
                    <li class="flex items-center p-3 bg-red-50 rounded-lg">⭐ {{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Galeri -->
            <div x-data="{ show: false, imgSrc: '' }" class="bg-white rounded-lg p-8 shadow-sm border border-gray-100 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Galeri</h2>
                <div class="grid md:grid-cols-3 gap-4">
                    @foreach ($umkm->galleries->pluck('image_url') as $img)
                    <div class="aspect-w-5 aspect-h-4 overflow-hidden rounded-lg cursor-pointer" @click="show = true; imgSrc = '{{ $img }}'">
                        <img src="{{ $img }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
                    </div>
                    @endforeach
                </div>

                <!-- Modal Preview Gambar -->
                <div
                    x-show="show"
                    x-transition
                    class="fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center px-4"
                    style="backdrop-filter: blur(4px);">
                    <div class="bg-white rounded-lg overflow-hidden max-w-2xl w-full">
                        <!-- Gambar -->
                        <div class="w-full max-h-[80vh] overflow-hidden flex items-center justify-center p-4">
                            <img
                                :src="imgSrc"
                                alt="Preview"
                                class="max-w-full max-h-[70vh] object-contain rounded" />
                        </div>

                        <!-- Tombol Tutup -->
                        <div class="bg-gray-100 text-center p-4 border-t">
                            <button @click="show = false" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                                Tutup Gambar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Penghargaan & Sertifikasi -->
            <div class="bg-white rounded-lg p-8 shadow-sm border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Penghargaan & Sertifikasi</h2>
                <ul class="space-y-2">
                    @foreach ($umkm->achievements->pluck('title') as $ach)
                    <li class="flex items-center bg-red-50 border-l-4 border-red-600 px-4 py-2 rounded">
                        🏅 {{ $ach }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 sticky top-6">
            <div class="bg-white rounded-lg p-6 shadow-sm border mb-6">
                <h3 class="text-xl font-bold mb-6">Informasi Kontak</h3>
                <ul class="space-y-4 text-sm text-gray-700">
                    <li><strong>Alamat:</strong> {{ $umkm->address }}
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($umkm->address) }}"
                            target="_blank" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            📍 Lihat di Google Maps
                        </a>
                        <iframe
                            width="100%" height="200"
                            style="border:0; border-radius: 0.5rem"
                            loading="lazy" allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps?q={{ urlencode($umkm->address) }}&output=embed">
                        </iframe>

                    </li>
                    <li>
                        <strong>Telepon:</strong>
                        <p class="text-gray-800 text-sm mb-1">{{ $umkm->phone }}</p>
                        <a href="https://wa.me/{{ str_replace('+', '', $umkm->phone) }}" target="_blank"
                            class="text-green-600 hover:text-green-700 text-sm font-medium">
                            💬 Chat via WhatsApp
                        </a>
                    </li>
                    <li><strong>Pemilik:</strong> {{ $umkm->owner }}</li>


                </ul>
                <a href="/umkm" class="block mt-6 w-full bg-red-600 hover:bg-red-700 text-white py-2 text-center rounded-lg">
                    ← Kembali ke Daftar UMKM
                </a>
            </div>
        </div>
    </div>
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 py-12 grid md:grid-cols-3 gap-8">
            <div>
                <h4 class="font-bold text-lg mb-2">UMKM Desa Takmung</h4>
                <p class="text-gray-300">Mendukung usaha lokal dan memajukan perekonomian masyarakat Desa Takmung.</p>
            </div>
            <div>
                <h4 class="font-semibold text-lg mb-2">Tautan Cepat</h4>
                <ul class="text-gray-300 space-y-1">
                    <li><a href="#" class="hover:text-red-400">Profil Desa</a></li>
                    <li><a href="#" class="hover:text-red-400">Pemerintahan</a></li>
                    <li><a href="#" class="hover:text-red-400">Peraturan</a></li>
                    <li><a href="#" class="hover:text-red-400">Hubungi Kami</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-lg mb-2">Kontak</h4>
                <ul class="text-gray-300 space-y-1">
                    <li>📞 +62 361 xxx xxxx</li>
                    <li>✉️ info@desatakmung.id</li>
                    <li>📍 Desa Takmung, Klungkung, Bali</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 text-center py-4 text-sm text-gray-400">
            &copy; 2024 UMKM Desa Takmung. Hak cipta dilindungi.
        </div>
    </footer>

</div>
@endif
@endsection