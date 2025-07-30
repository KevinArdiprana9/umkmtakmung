<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Umkm as UMKM;
use Illuminate\Support\Facades\Storage;
use App\Models\UmkmGallery;

class UMKMController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = UMKM::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $umkmList = $query->get();

        return view('user.umkm', [
            'umkmList' => $umkmList,
            'search' => $search,
            'total' => $umkmList->count(),
        ]);
    }

    public function detail($id)
    {
        $umkm = UMKM::with(['galleries', 'achievements'])
            ->find($id);

        if (!$umkm) {
            return view('user.umkmdetail-notfound');
        }

        return view('user.umkmdetail', [
            'umkm' => $umkm,
        ]);
    }

    public function adminDashboard()
    {
        $umkmList = Umkm::latest()->get();
        return view('admin.dashboard', compact('umkmList'));
    }

    public function create()
    {
        return view('admin.upsert');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'description' => 'required',
            'full_description' => 'required',
            'location' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'operating_start' => 'required|date_format:H:i',
            'operating_end' => 'required|date_format:H:i',
            'established' => 'required',
            'employees' => 'required|integer',
            'image_file' => 'nullable|image|max:2048',
            'owner_photo_file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gallery.*' => 'nullable|image|max:2048',
            'google_maps_link' => 'nullable|string|url',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $imageFile = $request->file('image_file');
            $filename = uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('upload/umkm/images'), $filename);
            $imagePath = 'upload/umkm/images/' . $filename;
        }

        $ownerPhotoPath = null;
        if ($request->hasFile('owner_photo_file')) {
            $ownerFile = $request->file('owner_photo_file');
            $filename = uniqid() . '.' . $ownerFile->getClientOriginalExtension();
            $ownerFile->move(public_path('upload/umkm/owner_photos'), $filename);
            $ownerPhotoPath = 'upload/umkm/owner_photos/' . $filename;
        }

        $umkm = Umkm::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'full_description' => $request->full_description,
            'image' => $imagePath,
            'location' => $request->location,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'owner' => $request->owner,
            'owner_photo' => $ownerPhotoPath,
            'operating_hours' => $request->operating_start . ' - ' . $request->operating_end,
            'established' => $request->established,
            'employees' => $request->employees,
            'google_maps_link' => $request->google_maps_link,
        ]);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                $filename = uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move(public_path('upload/umkm/gallery'), $filename);
                $umkm->galleries()->create([
                    'image_url' => 'upload/umkm/gallery/' . $filename
                ]);
            }
        }

        if ($request->filled('specialties')) {
            foreach (explode(',', $request->specialties) as $item) {
                $umkm->specialties()->create([
                    'name' => trim($item)
                ]);
            }
        }

        if ($request->filled('achievements')) {
            foreach (explode(',', $request->achievements) as $ach) {
                $umkm->achievements()->create([
                    'name' => trim($ach)
                ]);
            }
        }

        return redirect('/admin')->with('success', 'UMKM berhasil ditambahkan');
    }

    public function edit($id)
    {
        $umkm = Umkm::with(['galleries', 'specialties', 'achievements'])->findOrFail($id);

        if ($umkm->operating_hours) {
            [$start, $end] = explode(' - ', $umkm->operating_hours);
            $umkm->operating_start = trim((string) $start);
            $umkm->operating_end = trim((string) $end);
        }

        $umkm->specialties_string = $umkm->specialties->pluck('name')->implode(', ');
        $umkm->achievements_string = $umkm->achievements->pluck('title')->implode(', ');

        return view('admin.upsert', compact('umkm'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'description' => 'required',
            'full_description' => 'required',
            'location' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'operating_start' => 'required',
            'operating_end' => 'required',
            'established' => 'required',
            'employees' => 'required|integer',
            'image_file' => 'nullable|image|max:2048',
            'owner_photo_file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gallery.*' => 'nullable|image|max:2048',
            'google_maps_link' => 'nullable|string|url',
        ]);

        $umkm = Umkm::with(['specialties', 'achievements', 'galleries'])->findOrFail($id);

        // Hapus image utama jika ada
        if ($request->hasFile('image_file')) {
            if ($umkm->image && file_exists(public_path($umkm->image))) {
                unlink(public_path($umkm->image));
            }

            $imageFile = $request->file('image_file');
            $filename = uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('upload/umkm/images'), $filename);
            $umkm->image = 'upload/umkm/images/' . $filename;
        }

        // Hapus foto pemilik jika diminta
        if ($request->filled('hapus_owner_photo') && $request->hapus_owner_photo == '1') {
            if ($umkm->owner_photo && file_exists(public_path($umkm->owner_photo))) {
                unlink(public_path($umkm->owner_photo));
            }
            $umkm->owner_photo = null;
        }

        // Ganti foto pemilik
        if ($request->hasFile('owner_photo_file')) {
            if ($umkm->owner_photo && file_exists(public_path($umkm->owner_photo))) {
                unlink(public_path($umkm->owner_photo));
            }

            $ownerFile = $request->file('owner_photo_file');
            $filename = uniqid() . '.' . $ownerFile->getClientOriginalExtension();
            $ownerFile->move(public_path('upload/umkm/owner_photos'), $filename);
            $umkm->owner_photo = 'upload/umkm/owner_photos/' . $filename;
        }

        $umkm->update([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'full_description' => $request->full_description,
            'location' => $request->location,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'owner' => $request->owner,
            'operating_hours' => $request->operating_start . ' - ' . $request->operating_end,
            'established' => $request->established,
            'employees' => $request->employees,
            'google_maps_link' => $request->google_maps_link
        ]);

        $umkm->specialties()->delete();
        if ($request->filled('specialties')) {
            foreach (explode(',', $request->specialties) as $item) {
                $umkm->specialties()->create([
                    'name' => trim($item),
                ]);
            }
        }

        $umkm->achievements()->delete();
        if ($request->filled('achievements')) {
            foreach (explode(',', $request->achievements) as $item) {
                $umkm->achievements()->create([
                    'title' => trim($item),
                ]);
            }
        }

        // Tambah galeri baru
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                $filename = uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move(public_path('upload/umkm/gallery'), $filename);
                $umkm->galleries()->create([
                    'image_url' => 'upload/umkm/gallery/' . $filename,
                ]);
            }
        }

        // Hapus gambar galeri
        if ($request->has('hapus_gambar')) {
            foreach ($request->hapus_gambar as $idGambar) {
                $galeri = UmkmGallery::find($idGambar);
                if ($galeri && file_exists(public_path($galeri->image_url))) {
                    unlink(public_path($galeri->image_url));
                    $galeri->delete();
                }
            }
        }

        return redirect('/admin')->with('success', 'UMKM berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $umkm = Umkm::with(['galleries', 'achievements', 'specialties'])->findOrFail($id);

        // Hapus image utama jika ada
        if ($umkm->image && file_exists(public_path($umkm->image))) {
            unlink(public_path($umkm->image));
        }

        // Hapus foto pemilik jika ada
        if ($umkm->owner_photo && file_exists(public_path($umkm->owner_photo))) {
            unlink(public_path($umkm->owner_photo));
        }

        // Hapus semua gambar galeri
        foreach ($umkm->galleries as $galeri) {
            if ($galeri->image_url && file_exists(public_path($galeri->image_url))) {
                unlink(public_path($galeri->image_url));
            }
            $galeri->delete();
        }

        // Hapus relasi
        $umkm->achievements()->delete();
        $umkm->specialties()->delete();

        // Hapus data utama
        $umkm->delete();

        return redirect('/admin')->with('success', 'UMKM berhasil dihapus.');
    }
}
