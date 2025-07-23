<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\UMKM;
use Illuminate\Support\Facades\Storage;
use App\Models\UmkmGallery;
use App\Models\UmkmAchievement;
use App\Models\UmkmSpecialty;

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
            return view('umkmdetail-notfound');
        }

        return view('user.umkmdetail', [
            'umkm' => $umkm,
        ]);
    }

    public function adminDashboard()
    {
        $umkmList = Umkm::latest()->get(); // atau pakai paginate() jika perlu
        return view('admin.dashboard', compact('umkmList'));
    }

    public function create()
    {
        return view('admin.tambah');
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
            'operating_hours' => 'required',
            'established' => 'required',
            'employees' => 'required|integer',
            'image_file' => 'nullable|image|max:2048',
            'gallery.*' => 'nullable|image|max:2048',
        ]);

        // Simpan foto utama
        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('umkm/images', 'public');
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
            'operating_hours' => $request->operating_hours,
            'established' => $request->established,
            'employees' => $request->employees,
        ]);

        // Simpan Galeri
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                $path = $img->store('umkm/gallery', 'public');
                $umkm->galleries()->create([
                    'image_url' => "/storage/" . $path
                ]);
            }
        }

        // Simpan Produk Unggulan
        if ($request->filled('specialties')) {
            foreach (explode(',', $request->specialties) as $item) {
                $umkm->specialties()->create([
                    'name' => trim($item)
                ]);
            }
        }

        // Simpan Penghargaan
        if ($request->filled('achievements')) {
            foreach (explode(',', $request->achievements) as $ach) {
                $umkm->achievements()->create([
                    'name' => trim($ach)
                ]);
            }
        }

        return redirect('/admin')->with('success', 'UMKM berhasil ditambahkan');
    }
}
