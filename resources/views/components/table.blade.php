@props([
    'id' => 'dataTable',
    'title' => '',
    'headers' => [],
    'addButton' => null,
    'addRoute' => '#',
    'addText' => 'Tambah Data',
])

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    
    <!-- Header dengan Search & Add Button -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            
            <!-- Title -->
            @if($title)
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $title }}</h2>
            </div>
            @endif

            <!-- Search & Actions -->
            <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
                
                <!-- Search Input -->
                <div class="relative flex-1 md:w-64">
                    <input 
                        type="text" 
                        id="searchInput{{ $id }}"
                        placeholder="Cari data..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>

                <!-- Filter Slot -->
                @isset($filters)
                    {{ $filters }}
                @endisset

                <!-- Add Button -->
                @if($addButton)
                    {{ $addButton }}
                @else
                    <a href="{{ $addRoute }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition duration-300 shadow-md hover:shadow-lg whitespace-nowrap">
                        <i class="fas fa-plus mr-2"></i>
                        {{ $addText }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="tableBody{{ $id }}">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @isset($pagination)
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $pagination }}
    </div>
    @endisset

</div>

<script>
    // Live Search Functionality
    document.getElementById('searchInput{{ $id }}').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#tableBody{{ $id }} tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>