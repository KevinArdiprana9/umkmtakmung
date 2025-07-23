<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | UMKM Desa Takmung</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col justify-between">
            <div>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h1 class="text-xl font-bold text-red-600">Admin Panel</h1>
                    <p class="text-sm text-gray-500">UMKM Takmung</p>
                </div>
                <nav class="mt-6 space-y-2 px-4">
                    <a href="{{ url('/admin') }}" class="block px-4 py-2 rounded text-gray-700 hover:bg-red-100 font-medium">
                        🏪 Kelola UMKM
                    </a>
                </nav>
            </div>

            <form method="POST" action="{{ url('/logout') }}" class="px-4 mb-4">
                @csrf
                <button type="submit"
                    class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-red-100 font-medium rounded">
                    🚪 Logout
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 bg-gray-50">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Daftar UMKM</h2>
                <a href="{{ url('/admin/umkm/tambah') }}"
                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                    + Tambah UMKM
                </a>
            </div>

            @if ($umkmList->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($umkmList as $umkm)
                <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition overflow-hidden">
                    <img src="{{ $umkm->image }}" alt="{{ $umkm->name }}"
                        class="w-full h-40 object-cover">
                    <div class="p-4">
                        <span class="text-sm text-red-600 font-medium">{{ $umkm->category }}</span>
                        <h3 class="text-lg font-semibold text-gray-800 mt-1">{{ $umkm->name }}</h3>
                        <p class="text-sm text-gray-600 mt-2">
                            {{ \Illuminate\Support\Str::limit($umkm->description, 80) }}
                        </p>
                        <div class="mt-4 flex justify-between">
                            <a href="{{ url('/umkm/' . $umkm->id) }}"
                                class="text-sm text-blue-600 hover:underline">Lihat</a>
                            {{-- Bisa tambahkan tombol edit/hapus di sini jika diperlukan --}}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                Belum ada UMKM yang terdaftar.
            </div>
            @endif
        </main>
    </div>
</body>

</html>