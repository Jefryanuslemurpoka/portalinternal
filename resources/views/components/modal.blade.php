@props([
    'id' => 'modal',
    'title' => 'Modal',
    'size' => 'md',
    'type' => 'form', // 'form', 'confirm', 'info'
])

@php
    $sizeClasses = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        'full' => 'max-w-6xl',
    ];
    
    $typeColors = [
        'form' => 'from-blue-600 to-purple-600',
        'confirm' => 'from-red-600 to-pink-600',
        'info' => 'from-green-600 to-teal-600',
    ];
@endphp

<!-- Modal Backdrop -->
<div id="{{ $id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    
    <!-- Overlay -->
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        
        <!-- Background Overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" onclick="closeModal('{{ $id }}')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <!-- Modal Panel -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle {{ $sizeClasses[$size] }} w-full">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-r {{ $typeColors[$type] }} px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        @if($type === 'confirm')
                            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                            </div>
                        @elseif($type === 'info')
                            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-info-circle text-white text-lg"></i>
                            </div>
                        @endif
                        <h3 class="text-xl font-bold text-white">
                            {{ $title }}
                        </h3>
                    </div>
                    <button type="button" onclick="closeModal('{{ $id }}')" class="text-white/80 hover:text-white focus:outline-none transition">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                {{ $slot }}
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-end space-x-3">
                    @isset($footerButtons)
                        {{ $footerButtons }}
                    @else
                        @if($type === 'confirm')
                            <button type="button" onclick="closeModal('{{ $id }}')" class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition duration-300">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-pink-600 text-white font-semibold rounded-lg hover:from-red-700 hover:to-pink-700 transition duration-300 shadow-md">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        @elseif($type === 'info')
                            <button type="button" onclick="closeModal('{{ $id }}')" class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-teal-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-teal-700 transition duration-300 shadow-md">
                                OK, Mengerti
                            </button>
                        @else
                            <button type="button" onclick="closeModal('{{ $id }}')" class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition duration-300">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition duration-300 shadow-md">
                                <i class="fas fa-save mr-2"></i>Simpan
                            </button>
                        @endif
                    @endisset
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('[role="dialog"]:not(.hidden)');
            modals.forEach(modal => closeModal(modal.id));
        }
    });
</script>