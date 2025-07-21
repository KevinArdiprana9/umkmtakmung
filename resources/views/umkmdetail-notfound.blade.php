@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">UMKM tidak ditemukan</h2>
        <a href="/umkm"
           class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            ← Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
