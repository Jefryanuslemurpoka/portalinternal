@extends('layouts.app')

@section('title', 'Tambah Pemasukan')
@section('page-title', 'Tambah Data Pemasukan')

@section('content')
<div class="space-y-6">

    <div>
        <a href="{{ route('finance.pemasukan.index') }}" class="text-teal-600 hover:text-teal-700 font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
        <i class="fas fa-file-invoice text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-bold text-gray-700 mb-2">Form Tambah Pemasukan</h3>
        <p class="text-gray-500">Halaman ini sedang dalam pengembangan</p>
    </div>

</div>
@endsection