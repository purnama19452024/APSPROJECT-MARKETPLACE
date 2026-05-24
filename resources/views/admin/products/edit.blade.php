<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Produk - {{ config('app.name') }}</title>
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
                <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm p-6">
                    <h1 class="text-xl font-bold text-gray-800 mb-4">Edit Produk</h1>
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                                <input type="text" name="name" value="{{ $product->name }}" class="w-full border rounded-lg p-2 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="category_id" class="w-full border rounded-lg p-2 text-sm" required>
                                    @foreach(\App\Models\Category::all() as $cat)
                                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="description" rows="4" class="w-full border rounded-lg p-2 text-sm">{{ $product->description }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Harga</label>
                                <input type="number" name="price" value="{{ $product->price }}" class="w-full border rounded-lg p-2 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Harga Diskon</label>
                                <input type="number" name="discount_price" value="{{ $product->discount_price }}" class="w-full border rounded-lg p-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stok</label>
                                <input type="number" name="stock" value="{{ $product->stock }}" class="w-full border rounded-lg p-2 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Berat (gram)</label>
                                <input type="number" name="weight" value="{{ $product->weight }}" class="w-full border rounded-lg p-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kondisi</label>
                                <select name="condition" class="w-full border rounded-lg p-2 text-sm">
                                    <option value="baru" {{ $product->condition == 'baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="bekas" {{ $product->condition == 'bekas' ? 'selected' : '' }}>Bekas</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Tambah Gambar</label>
                                <input type="file" name="images[]" multiple class="w-full border rounded-lg p-2 text-sm">
                                @if($product->images->count())
                                    <div class="flex space-x-2 mt-2">
                                        @foreach($product->images as $img)
                                            <img src="{{ $img->image_url }}" alt="" class="w-16 h-16 rounded-lg object-cover">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div>
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="rounded border-gray-300">
                                    <span class="text-sm text-gray-700">Produk Unggulan</span>
                                </label>
                            </div>
                            <div>
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="is_flash_sale" value="1" {{ $product->is_flash_sale ? 'checked' : '' }} class="rounded border-gray-300">
                                    <span class="text-sm text-gray-700 flex items-center gap-1.5"><i class="fas fa-bolt text-yellow-500"></i> Flash Sale</span>
                                </label>
                                <p class="text-xs text-gray-400 mt-1 ml-6">Centang jika produk ini ingin ditampilkan di Flash Sale</p>
                            </div>
                            <div>
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="rounded border-gray-300">
                                    <span class="text-sm text-gray-700">Aktif</span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-6 flex space-x-3">
                            <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-600">Perbarui</button>
                            <a href="{{ route('admin.products.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400">Batal</a>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
