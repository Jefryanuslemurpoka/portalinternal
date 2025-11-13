@extends('layouts.app')

@section('title', 'Workspace')
@section('page-title', 'Workspace')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-teal-600 to-cyan-700 rounded-2xl shadow-lg mb-4">
            <i class="fas fa-briefcase text-white text-3xl"></i>
        </div>
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Workspace</h1>
        <p class="text-gray-600 text-lg">Akses cepat ke semua aplikasi dan layanan</p>
    </div>

    <!-- Grid Menu -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        
        <!-- SSF -->
        <a href="https://internal.ssf.co.id/" 
           target="_blank"
           rel="noopener noreferrer"
           class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 flex flex-col items-center justify-center space-y-4 hover:-translate-y-2 border-2 border-transparent hover:border-teal-500">
            <div class="w-20 h-20 bg-gradient-to-br from-teal-600 to-cyan-700 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-building text-white text-3xl"></i>
            </div>
            <div class="text-center">
                <h3 class="text-xl font-bold text-gray-800 mb-1">SSF</h3>
                <p class="text-sm text-gray-500">Portal Internal SSF</p>
            </div>
            <div class="flex items-center text-teal-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                <span>Buka</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <!-- Local RDP -->
        <a href="https://rdp.local/" 
           target="_blank"
           rel="noopener noreferrer"
           class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 flex flex-col items-center justify-center space-y-4 hover:-translate-y-2 border-2 border-transparent hover:border-teal-500">
            <div class="w-20 h-20 bg-gradient-to-br from-teal-600 to-cyan-700 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-desktop text-white text-3xl"></i>
            </div>
            <div class="text-center">
                <h3 class="text-xl font-bold text-gray-800 mb-1">Local RDP</h3>
                <p class="text-sm text-gray-500">Remote Desktop Protocol</p>
            </div>
            <div class="flex items-center text-teal-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                <span>Buka</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <!-- Work File -->
        <a href="https://workfile.local/" 
           target="_blank"
           rel="noopener noreferrer"
           class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 flex flex-col items-center justify-center space-y-4 hover:-translate-y-2 border-2 border-transparent hover:border-teal-500">
            <div class="w-20 h-20 bg-gradient-to-br from-teal-600 to-cyan-700 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-folder-open text-white text-3xl"></i>
            </div>
            <div class="text-center">
                <h3 class="text-xl font-bold text-gray-800 mb-1">Work File</h3>
                <p class="text-sm text-gray-500">File Management System</p>
            </div>
            <div class="flex items-center text-teal-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                <span>Buka</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <!-- Group WhatsApp -->
        <a href="https://chat.whatsapp.com/your-group-link" 
           target="_blank"
           rel="noopener noreferrer"
           class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 flex flex-col items-center justify-center space-y-4 hover:-translate-y-2 border-2 border-transparent hover:border-teal-500">
            <div class="w-20 h-20 bg-gradient-to-br from-teal-600 to-cyan-700 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fab fa-whatsapp text-white text-3xl"></i>
            </div>
            <div class="text-center">
                <h3 class="text-xl font-bold text-gray-800 mb-1">Group WhatsApp</h3>
                <p class="text-sm text-gray-500">Grup Komunikasi Tim</p>
            </div>
            <div class="flex items-center text-teal-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                <span>Buka</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <!-- Webmail Purido -->
        <a href="https://webmail.purido.co.id/" 
           target="_blank"
           rel="noopener noreferrer"
           class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 flex flex-col items-center justify-center space-y-4 hover:-translate-y-2 border-2 border-transparent hover:border-teal-500">
            <div class="w-20 h-20 bg-gradient-to-br from-teal-600 to-cyan-700 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-envelope text-white text-3xl"></i>
            </div>
            <div class="text-center">
                <h3 class="text-xl font-bold text-gray-800 mb-1">Webmail Purido</h3>
                <p class="text-sm text-gray-500">Email PT Puri Digital Output</p>
            </div>
            <div class="flex items-center text-teal-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                <span>Buka</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <!-- Webmail SSF -->
        <a href="https://webmail.ssf.co.id/" 
           target="_blank"
           rel="noopener noreferrer"
           class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 flex flex-col items-center justify-center space-y-4 hover:-translate-y-2 border-2 border-transparent hover:border-teal-500">
            <div class="w-20 h-20 bg-gradient-to-br from-teal-600 to-cyan-700 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-at text-white text-3xl"></i>
            </div>
            <div class="text-center">
                <h3 class="text-xl font-bold text-gray-800 mb-1">Webmail SSF</h3>
                <p class="text-sm text-gray-500">Email SSF Corporate</p>
            </div>
            <div class="flex items-center text-teal-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                <span>Buka</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

    </div>

    <!-- Info Section -->
    <div class="bg-gradient-to-r from-teal-500 to-cyan-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-info-circle text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold mb-2">Informasi Penting</h3>
                <p class="text-sm text-teal-50">
                    Semua aplikasi akan dibuka di tab baru. Pastikan Anda sudah login ke masing-masing sistem sebelum mengaksesnya. 
                    Jika mengalami kendala akses, hubungi bagian IT Support.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection