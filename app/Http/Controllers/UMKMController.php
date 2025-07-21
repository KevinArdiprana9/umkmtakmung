<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\UMKM;

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
}
