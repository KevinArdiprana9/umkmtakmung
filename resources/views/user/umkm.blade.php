<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UMKM Desa Takmung</title>
    @vite('resources/css/app.css')
    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
</head>

<body class="bg-gray-50 text-gray-900">

    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <span class="ml-2 text-xl font-bold text-gray-900">UMKM Desa Takmung</span>
                </div>
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ url('/login') }}"
                        class="ml-4 inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm font-medium transition">
                        Login
                    </a>
                </div>
                <div class="md:hidden">
                    <button onclick="toggleMenu()" class="text-gray-600 hover:text-red-600 p-2 rounded-md">
                        ☰
                    </button>
                </div>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 px-4 py-3 space-y-2">
            <a href="{{ url('/login') }}"
                class="block text-red-600 hover:text-red-700 text-sm font-medium">
                Login
            </a>
        </div>
    </nav>

    <header class="bg-gradient-to-r from-red-600 to-red-700 text-white text-center py-16 px-4">
        <h1 class="text-4xl font-bold mb-4">Potensi UMKM Desa Takmung</h1>
        <p class="text-lg max-w-3xl mx-auto text-red-100">Temukan kekayaan usaha mikro, kecil, dan menengah yang menjadi tulang punggung ekonomi masyarakat Desa Takmung</p>
    </header>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <form method="GET" action="{{ url('/') }}">
            <div class="relative max-w-2xl mx-auto">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari usaha, kategori, atau produk..."
                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 text-lg">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    🔍
                </div>
            </div>
        </form>
    </div>

    <div class="max-w-7xl mx-auto px-4 pb-16">
        <div class="mb-8">
            <h2 class="text-3xl font-bold mb-2">Usaha Lokal</h2>
            <p class="text-gray-600">Menampilkan {{ $total }} usaha</p>
        </div>

        @if (count($umkmList))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($umkmList as $umkm)
            <div class="bg-white border rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden group">
                <img src="{{ asset('public/' . $umkm->image) }}" alt="{{ $umkm->name }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                <div class="p-6">
                    <div class="flex justify-between mb-3 text-sm">
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">{{ $umkm['category'] }}</span>
                        <span class="text-gray-500">📍 {{ $umkm->location }}</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 group-hover:text-red-600">{{ $umkm['name'] }}</h3>
                    <p class="text-gray-600 mb-4">{{ \Illuminate\Support\Str::limit($umkm['description'], 120) }}</p>
                    <a href="{{ url('/' . $umkm->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-16 text-gray-600">
            <div class="text-4xl mb-2">🔍</div>
            <h3 class="text-xl font-semibold mb-1">Tidak ada usaha ditemukan</h3>
            <p>Coba sesuaikan kata kunci pencarian atau jelajahi semua usaha.</p>
        </div>
        @endif
    </div>

    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 py-12 flex flex-col md:flex-row justify-between gap-8">
            <div>
                <h4 class="font-bold text-lg mb-2">UMKM Desa Takmung</h4>
                <p class="text-gray-300 max-w-2xl text-justify">
                    Website ini merupakan platform resmi yang bertujuan untuk mendukung dan mempromosikan usaha mikro, kecil, dan menengah (UMKM) yang berasal dari Desa Takmung. Dengan menyediakan informasi lengkap mengenai produk, layanan, serta profil para pelaku UMKM lokal, kami berkomitmen untuk mendorong pertumbuhan ekonomi desa, memperluas jangkauan pasar, serta membangun kesadaran masyarakat akan pentingnya mendukung produk-produk lokal berkualitas.
                </p>
            </div>
            <div>
                <h4 class="font-semibold text-lg mb-2">Kontak</h4>
                <ul class="text-gray-300 space-y-1">
                    <li>✉️ takmungwebsite@gmail.com</li>
                    <li>📍 Desa Takmung, Klungkung, Bali</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 text-center py-4 text-sm text-gray-400">
            &copy; 2025 UMKM Desa Takmung. Hak cipta dilindungi.
        </div>
    </footer>

</body>

</html>