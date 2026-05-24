<header class="bg-white shadow-sm border-b border-gray-200 px-6 py-3">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
        <div class="flex items-center space-x-2 text-sm">
            <img src="{{ auth()->user()->photo_url }}" alt="" class="w-8 h-8 rounded-full object-cover cursor-pointer hover:ring-2 hover:ring-blue-300 transition" onclick="openImagePreview('{{ auth()->user()->photo_url }}', '{{ auth()->user()->name }}')">
            <span class="text-gray-700">{{ auth()->user()->name }}</span>
        </div>

        <div id="imagePreviewModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/70 backdrop-blur-sm" onclick="closeImagePreview(event)">
            <div class="relative max-w-lg mx-4" onclick="event.stopPropagation()">
                <button onclick="closeImagePreview()" class="absolute -top-3 -right-3 w-8 h-8 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-100 transition z-10">
                    <i class="fas fa-times text-gray-600"></i>
                </button>
                <img id="previewImage" src="" alt="" class="w-full rounded-2xl shadow-2xl border-4 border-white">
                <p id="previewCaption" class="text-white text-sm text-center mt-3 font-medium"></p>
            </div>
        </div>
        <script>
            function openImagePreview(src, name) {
                document.getElementById('previewImage').src = src;
                document.getElementById('previewCaption').textContent = name || '';
                var modal = document.getElementById('imagePreviewModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            function closeImagePreview(e) {
                if (e && e.target !== e.currentTarget) return;
                var modal = document.getElementById('imagePreviewModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        </script>
    </div>
</header>
