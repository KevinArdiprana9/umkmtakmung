<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ isset($umkm) ? 'Edit UMKM' : 'Tambah UMKM' }} | Admin</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen py-10 px-4">
    <div class="max-w-3xl mx-auto bg-white shadow-md p-8 rounded-lg border border-gray-200">
        <h2 class="text-2xl font-bold text-red-600 mb-6">{{ isset($umkm) ? 'Edit UMKM' : 'Tambah UMKM Baru' }}</h2>

        @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ isset($umkm) ? url('/admin/umkm/update/' . $umkm->id) : url('/admin/umkm/tambah') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-1">Nama UMKM</label>
                    <input type="text" name="name" class="w-full border p-2 rounded" required value="{{ old('name', $umkm->name ?? '') }}">
                </div>
                <div>
                    <label class="block font-medium mb-1">Kategori</label>
                    <select name="category" class="w-full border p-2 rounded" required>
                        <option value="" disabled {{ isset($umkm) ? '' : 'selected' }}>Pilih kategori UMKM</option>
                        @foreach (["Makanan & Minuman", "Kerajinan Tangan", "Fashion", "Pertanian", "Peternakan", "Perikanan", "Jasa", "Lainnya"] as $cat)
                        <option value="{{ $cat }}" {{ old('category', $umkm->category ?? '') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Deskripsi Singkat</label>
                    <textarea name="description" class="w-full border p-2 rounded" rows="2" required>{{ old('description', $umkm->description ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Deskripsi Lengkap</label>
                    <textarea name="full_description" class="w-full border p-2 rounded" rows="4" required>{{ old('full_description', $umkm->full_description ?? '') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Foto Utama</label>
                    <div class="relative border-2 border-dashed border-gray-300 p-6 rounded-lg bg-gray-50 hover:bg-gray-100 transition-all cursor-pointer text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m5 4v4m0 0h4m-4 0H9" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">Klik atau tarik gambar ke sini untuk unggah</p>
                        <input type="file" name="image_file" accept="image/*" class="absolute top-0 left-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(event)">
                    </div>

                    <div class="mt-4 {{ isset($umkm) ? '' : 'hidden' }}" id="main-preview-wrapper">
                        <p class="text-sm text-gray-500 mb-1">Preview:</p>
                        <img id="main-preview" src="{{ isset($umkm->image) ? asset('public/' . $umkm->image) : '#' }}"
                            alt="Preview Foto Utama"
                            class="w-64 h-48 object-cover rounded-lg border">
                    </div>
                </div>
                <div>
                    <label class="block font-medium mb-1">Lokasi (Desa Adat)</label>
                    <input type="text" name="location" class="w-full border p-2 rounded" required value="{{ old('location', $umkm->location ?? '') }}">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Alamat Lengkap</label>
                    <input type="text" name="address" class="w-full border p-2 rounded" required value="{{ old('address', $umkm->address ?? '') }}">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Link Google Maps (opsional)</label>
                    <input type="text" name="google_maps_link" class="w-full border p-2 rounded"
                        value="{{ old('google_maps_link', $umkm->google_maps_link ?? '') }}"
                        placeholder="https://goo.gl/maps/...">
                </div>
                <div>
                    <label class="block font-medium mb-1">No. Telepon</label>
                    <input type="text" name="phone" class="w-full border p-2 rounded" required value="{{ old('phone', $umkm->phone ?? '') }}">
                </div>
                <div>
                    <label class="block font-medium mb-1">Email (opsional)</label>
                    <input type="email" name="email" class="w-full border p-2 rounded" value="{{ old('email', $umkm->email ?? '') }}">
                </div>
                <div>
                    <label class="block font-medium mb-1">Nama Pemilik</label>
                    <input type="text" name="owner" class="w-full border p-2 rounded" value="{{ old('owner', $umkm->owner ?? '') }}">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium mb-2">Hari Operasional</label>
                    @php
                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

                    $selected = old('operating_days') ?? ($umkm->operating_days ?? []);
                    $selectedDays = is_array($selected) ? $selected : [$selected];
                    @endphp
                    <div class="flex flex-wrap gap-2">
                        @foreach ($days as $day)
                        <div x-data="{ checked: {{ in_array($day, $selectedDays) ? 'true' : 'false' }} }">
                            <label
                                class="px-4 py-2 rounded-lg border text-sm cursor-pointer transition"
                                :class="checked ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-700 border-gray-300'">
                                <input type="checkbox"
                                    name="operating_days[]"
                                    value="{{ $day }}"
                                    x-model="checked"
                                    class="hidden">
                                {{ $day }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block font-medium mb-1">Jam Operasional</label>
                    <div class="flex items-center gap-2">
                        <input id="operating_start" type="text" name="operating_start" placeholder="06:00"
                            class="w-full border p-2 rounded"
                            required value="{{ $umkm->operating_start ?? '' }}">

                        <input id="operating_end" type="text" name="operating_end" placeholder="17:00"
                            class="w-full border p-2 rounded"
                            required value="{{ $umkm->operating_end ?? '' }}">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Pilih jam buka dan tutup</p>
                </div>
                <div>
                    <label class="block font-medium mb-1">Tahun Berdiri</label>
                    <input type="text" name="established" class="w-full border p-2 rounded" required value="{{ old('established', $umkm->established ?? '') }}">
                </div>
                <div>
                    <label class="block font-medium mb-1">Jumlah Pegawai</label>
                    <input type="number" name="employees" class="w-full border p-2 rounded" required value="{{ old('employees', $umkm->employees ?? '') }}">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Foto Pemilik Usaha</label>
                    <div class="relative border-2 border-dashed border-gray-300 p-6 rounded-lg bg-gray-50 hover:bg-gray-100 transition-all cursor-pointer text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m5 4v4m0 0h4m-4 0H9" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">Klik atau tarik gambar ke sini untuk unggah</p>
                        <input type="file" name="owner_photo_file" accept="image/*"
                            class="absolute top-0 left-0 w-full h-full opacity-0 cursor-pointer"
                            onchange="previewOwnerPhoto(event)">
                    </div>

                    <div class="mt-4 {{ isset($umkm) && $umkm->owner_photo ? '' : 'hidden' }}" id="owner-photo-preview-wrapper">
                        <p class="text-sm text-gray-500 mb-1">Preview:</p>

                        <div class="relative w-64" id="owner-photo-container">
                            <img id="owner-photo-preview"
                                src="{{ isset($umkm->owner_photo) ? asset('public/' . $umkm->owner_photo) : '#' }}"
                                alt="Preview Foto Pemilik"
                                class="w-64 h-48 object-cover rounded-lg border">

                            <button type="button"
                                class="absolute top-1 right-1 bg-red-600 text-white text-xs px-2 py-1 rounded hover:bg-red-700"
                                onclick="hapusFotoOwner()">
                                ❌
                            </button>

                            <input type="hidden" name="hapus_owner_photo" id="hapus_owner_photo" value="0">
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">
                        Galeri Foto <span class="text-sm text-gray-500">(bisa pilih lebih dari 1)</span>
                    </label>

                    <div
                        class="relative border-2 border-dashed border-gray-400 p-6 rounded-lg bg-gray-50 text-center hover:bg-gray-100 transition-all cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m5 4v4m0 0h4m-4 0H9" />
                        </svg>
                        <span class="text-sm text-gray-600">Klik atau drag and drop ke sini untuk unggah gambar baru</span>
                        <input type="file" name="gallery[]" accept="image/*" multiple
                            class="absolute top-0 left-0 w-full h-full opacity-0 cursor-pointer"
                            onchange="previewGallery(event)">
                    </div>

                    <div id="gallery-preview" class="mt-4 grid grid-cols-3 gap-3"></div>

                    @if (!empty($umkm->galleries))
                    <span class="text-sm text-gray-600">Gambar Lama</span>
                    <div id="existing-gallery" class="mt-4 grid grid-cols-3 gap-3">
                        @foreach ($umkm->galleries as $gallery)
                        <div class="relative group">
                            <img src="{{ asset('public/' . $gallery->image_url) }}" class="w-full h-32 object-cover rounded border">
                            <button type="button"
                                class="absolute top-1 right-1 bg-red-600 text-white text-xs px-2 py-1 rounded opacity-100 transition"
                                onclick="hapusGambarLama('{{ $gallery->id }}', this)">❌</button>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Produk Unggulan (Pisahkan dengan koma)</label>
                    <input type="text" name="specialties" class="w-full border p-2 rounded" value="{{ old('specialties', $umkm->specialties_string ?? '') }}">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Penghargaan (Pisahkan dengan koma)</label>
                    <input type="text" name="achievements" class="w-full border p-2 rounded" value="{{ old('achievements', $umkm->achievements_string ?? '') }}">
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ url('/admin') }}" class="text-gray-600 hover:text-red-500">← Kembali ke Dashboard</a>
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">Simpan</button>
            </div>
            <div id="hapus-gambar-wrapper"></div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('main-preview');
            const wrapper = document.getElementById('main-preview-wrapper');
            if (file && file.type.startsWith('image/')) {
                preview.src = URL.createObjectURL(file);
                wrapper.classList.remove('hidden');
            } else {
                preview.src = '#';
                wrapper.classList.add('hidden');
            }
        }

        function previewOwnerPhoto(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('owner-photo-preview');
            const wrapper = document.getElementById('owner-photo-preview-wrapper');

            if (file && file.type.startsWith('image/')) {
                preview.src = URL.createObjectURL(file);
                wrapper.style.display = 'block';
            } else {
                preview.src = '#';
                wrapper.style.display = 'none';
            }
        }

        function previewGallery(event) {
            const files = event.target.files;
            const container = document.getElementById('gallery-preview');
            container.innerHTML = '';

            const existing = document.getElementById('existing-gallery');
            if (existing) {
                existing.style.display = 'none';
            }

            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.className = 'w-full h-32 object-cover rounded border';
                    container.appendChild(img);
                }
            });
        }

        function hapusGambarLama(id, button) {
            const wrapper = document.getElementById('hapus-gambar-wrapper');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'hapus_gambar[]';
            input.value = id;
            wrapper.appendChild(input);

            button.parentElement.remove();
        }

        function hapusFotoOwner() {
            document.getElementById('hapus_owner_photo').value = '1';

            const container = document.getElementById('owner-photo-container');
            if (container) {
                container.style.display = 'none';
            }
        }

        flatpickr("#operating_start", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });
        flatpickr("#operating_end", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });
    </script>
</body>

</html>