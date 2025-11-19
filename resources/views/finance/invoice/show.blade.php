@extends('layouts.app')

@section('title', 'Detail Invoice')
@section('page-title', 'Detail Invoice')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-center">
        <a href="{{ route('finance.invoice.index') }}" class="text-teal-600 hover:text-teal-700 font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <a href="#" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg shadow-lg transition">
            <i class="fas fa-download mr-2"></i>Download PDF
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
        <i class="fas fa-file-invoice text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-bold text-gray-700 mb-2">Detail Invoice</h3>
        <p class="text-gray-500">Halaman ini sedang dalam pengembangan</p>
    </div>

</div>
@endsection