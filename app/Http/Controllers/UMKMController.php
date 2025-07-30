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
            $imagePath = $request->file('image_file')->store('umkm/images', 'public');
        }
        $ownerPhotoPath = null;
        if ($request->hasFile('owner_photo_file')) {
            $file = $request->file('owner_photo_file');
            $ownerPhotoPath = $file->store('umkm/owner_photos', 'public');
        }

        $umkm = Umkm::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'full_description' => $request->full_description,
            'image' => $imagePath ? "/storage/" . $imagePath : null,
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
                $path = $img->store('umkm/gallery', 'public');
                $umkm->galleries()->create([
                    'image_url' => "/storage/" . $path
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

        // Update image utama jika ada
        if ($request->hasFile('image_file')) {
            // Hapus foto lama jika ada
            if ($umkm->image && Storage::disk('public')->exists(str_replace('/storage/', '', $umkm->image))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $umkm->image));
            }

            $imagePath = $request->file('image_file')->store('umkm/images', 'public');
            $umkm->image = '/storage/' . $imagePath;
        }

        if ($request->filled('hapus_owner_photo') && $request->hapus_owner_photo == '1') {
            if ($umkm->owner_photo) {
                Storage::delete(str_replace('/storage/', 'public/', $umkm->owner_photo));
                $umkm->owner_photo = null;
            }
        }

        if ($request->hasFile('owner_photo_file')) {
            if ($umkm->owner_photo && Storage::disk('public')->exists(str_replace('/storage/', '', $umkm->owner_photo))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $umkm->owner_photo));
            }

            $ownerPhotoPath = $request->file('owner_photo_file')->store('umkm/owner_photos', 'public');
            $umkm->owner_photo = '/storage/' . $ownerPhotoPath;
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

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                $path = $img->store('umkm/gallery', 'public');
                $umkm->galleries()->create([
                    'image_url' => '/storage/' . $path,
                ]);
            }
        }

        if ($request->has('hapus_gambar')) {
            foreach ($request->hapus_gambar as $idGambar) {
                $galeri = UmkmGallery::find($idGambar);
                if ($galeri) {
                    Storage::delete(str_replace('/storage/', 'public/', $galeri->image_url));
                    $galeri->delete();
                }
            }
        }

        return redirect('/admin')->with('success', 'UMKM berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $umkm = Umkm::with(['galleries', 'achievements', 'specialties'])->findOrFail($id);

        if ($umkm->image && Storage::disk('public')->exists(str_replace('/storage/', '', $umkm->image))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $umkm->image));
        }

        if ($umkm->owner_photo && Storage::disk('public')->exists(str_replace('/storage/', '', $umkm->owner_photo))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $umkm->owner_photo));
        }

        foreach ($umkm->galleries as $galeri) {
            if ($galeri->image_url && Storage::disk('public')->exists(str_replace('/storage/', '', $galeri->image_url))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $galeri->image_url));
            }
            $galeri->delete();
        }

        $umkm->achievements()->delete();
        $umkm->specialties()->delete();

        $umkm->delete();

        return redirect('/admin')->with('success', 'UMKM berhasil dihapus.');
    }
}
