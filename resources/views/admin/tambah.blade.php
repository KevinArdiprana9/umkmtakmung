<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah UMKM | Admin</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen py-10 px-4">
    <div class="max-w-3xl mx-auto bg-white shadow-md p-8 rounded-lg border border-gray-200">
        <h2 class="text-2xl font-bold text-red-600 mb-6">Tambah UMKM Baru</h2>

        @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ url('/admin/umkm/tambah') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-1">Nama UMKM</label>
                    <input type="text" name="name" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label class="block font-medium mb-1">Kategori</label>
                    <select name="category" class="w-full border p-2 rounded" required>
                        <option value="" disabled selected>Pilih kategori UMKM</option>
                        <option value="Kuliner">Kuliner</option>
                        <option value="Kerajinan Tangan">Kerajinan Tangan</option>
                        <option value="Fashion">Fashion</option>
                        <option value="Pertanian">Pertanian</option>
                        <option value="Peternakan">Peternakan</option>
                        <option value="Perikanan">Perikanan</option>
                        <option value="Jasa">Jasa</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Deskripsi Singkat</label>
                    <textarea name="description" class="w-full border p-2 rounded" rows="2" required></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Deskripsi Lengkap</label>
                    <textarea name="full_description" class="w-full border p-2 rounded" rows="4" required></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Foto Utama</label>
                    <input type="file" name="image_file" accept="image/*" class="w-full border p-2 rounded" onchange="previewImage(event)">
                    <div class="mt-2">
                        <img id="main-preview" src="#" alt="Preview Foto Utama" class="hidden w-64 h-48 object-cover rounded border">
                    </div>
                </div>
                <div>
                    <label class="block font-medium mb-1">Lokasi (Banjar)</label>
                    <input type="text" name="location" class="w-full border p-2 rounded" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Alamat Lengkap</label>
                    <input type="text" name="address" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label class="block font-medium mb-1">No. Telepon</label>
                    <input type="text" name="phone" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label class="block font-medium mb-1">Email (opsional)</label>
                    <input type="email" name="email" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-medium mb-1">Nama Pemilik</label>
                    <input type="text" name="owner" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-medium mb-1">Jam Operasional</label>
                    <input type="text" name="operating_hours" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label class="block font-medium mb-1">Tahun Berdiri</label>
                    <input type="text" name="established" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label class="block font-medium mb-1">Jumlah Pegawai</label>
                    <input type="number" name="employees" class="w-full border p-2 rounded" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Galeri Foto <span class="text-sm text-gray-500">(bisa pilih lebih dari 1)</span></label>

                    <!-- Styled upload area -->
                    <div class="relative border-2 border-dashed border-red-400 p-6 rounded-lg bg-red-50 text-center hover:bg-red-100 transition-all cursor-pointer">
                        <span class="text-sm text-gray-600">Klik untuk memilih gambar (bisa lebih dari 1)</span>
                        <input type="file" name="gallery[]" accept="image/*" multiple
                            class="absolute top-0 left-0 w-full h-full opacity-0 cursor-pointer"
                            onchange="previewGallery(event)">
                    </div>

                    <!-- Preview section -->
                    <div id="gallery-preview" class="mt-4 grid grid-cols-3 gap-3"></div>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Produk Unggulan (Pisahkan dengan koma)</label>
                    <input type="text" name="specialties" placeholder="Contoh: Sate Lilit, Nasi Campur" class="w-full border p-2 rounded">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Penghargaan (Pisahkan dengan koma)</label>
                    <input type="text" name="achievements" placeholder="Contoh: Juara 1 Lomba, Sertifikat Halal" class="w-full border p-2 rounded">
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ url('/admin') }}" class="text-gray-600 hover:text-red-500">← Kembali ke Dashboard</a>
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">Simpan</button>
            </div>
        </form>
    </div>
</body>
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('main-preview');

        if (file && file.type.startsWith('image/')) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        } else {
            preview.src = '#';
            preview.classList.add('hidden');
        }
    }

    function previewGallery(event) {
        const files = event.target.files;
        const container = document.getElementById('gallery-preview');
        container.innerHTML = '';

        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'w-full h-32 object-cover rounded border';
                container.appendChild(img);
            }
        });
    }
</script>

</html>