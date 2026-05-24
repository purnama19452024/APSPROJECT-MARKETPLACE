<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Produk - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen">
        @include('admin.partials.sidebar')
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header')
            <main class="flex-1 overflow-y-auto p-6">
                @if($errors->any())
                    <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-start gap-2">
                        <i class="fas fa-exclamation-circle mt-0.5"></i>
                        <div>
                            <p class="font-medium">Terjadi kesalahan:</p>
                            <ul class="list-disc list-inside text-sm mt-1">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm p-6">
                    <h1 class="text-xl font-bold text-gray-800 mb-4">Tambah Produk</h1>
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded-lg p-2 text-sm @error('name') border-red-400 @enderror" required>
                                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="category_id" class="w-full border rounded-lg p-2 text-sm @error('category_id') border-red-400 @enderror" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach(\App\Models\Category::all() as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="description" rows="4" class="w-full border rounded-lg p-2 text-sm @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                                @error('description')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Harga</label>
                                <input type="number" name="price" value="{{ old('price') }}" class="w-full border rounded-lg p-2 text-sm @error('price') border-red-400 @enderror" required>
                                @error('price')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Harga Diskon</label>
                                <input type="number" name="discount_price" value="{{ old('discount_price') }}" class="w-full border rounded-lg p-2 text-sm @error('discount_price') border-red-400 @enderror">
                                @error('discount_price')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stok</label>
                                <input type="number" name="stock" value="{{ old('stock', 1) }}" class="w-full border rounded-lg p-2 text-sm @error('stock') border-red-400 @enderror" required>
                                @error('stock')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Berat (gram)</label>
                                <input type="number" name="weight" value="{{ old('weight') }}" class="w-full border rounded-lg p-2 text-sm @error('weight') border-red-400 @enderror">
                                @error('weight')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kondisi</label>
                                <select name="condition" class="w-full border rounded-lg p-2 text-sm @error('condition') border-red-400 @enderror">
                                    <option value="baru" {{ old('condition') == 'baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="bekas" {{ old('condition') == 'bekas' ? 'selected' : '' }}>Bekas</option>
                                </select>
                                @error('condition')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Gambar Produk</label>
                                <input type="file" name="images[]" multiple accept="image/*"
                                       class="w-full border rounded-lg p-2 text-sm @error('images') border-red-400 @enderror @error('images.*') border-red-400 @enderror">
                                <p class="text-xs text-gray-400 mt-1">Format: JPG/PNG. Maks 5MB per file. Pilih minimal 1 gambar.</p>
                                @error('images')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                @error('images.*')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300">
                                    <span class="text-sm text-gray-700">Produk Unggulan</span>
                                </label>
                            </div>
                            <div>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_flash_sale" value="1" {{ old('is_flash_sale') ? 'checked' : '' }} class="rounded border-gray-300">
                                    <span class="text-sm text-gray-700 flex items-center gap-1.5"><i class="fas fa-bolt text-yellow-500"></i> Flash Sale</span>
                                </label>
                                <p class="text-xs text-gray-400 mt-1 ml-6">Centang jika produk ini ingin ditampilkan di Flash Sale</p>
                            </div>
                            <div>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300">
                                    <span class="text-sm text-gray-700">Aktif</span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <button type="submit" class="bg-orange-500 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-orange-600 transition"><i class="fas fa-save mr-1"></i>Simpan</button>
                            <a href="{{ route('admin.products.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-300 transition">Batal</a>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
