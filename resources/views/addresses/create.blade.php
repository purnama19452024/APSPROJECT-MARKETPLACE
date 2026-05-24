@extends('layouts.landing')

@section('title', 'Tambah Alamat')

@section('content')
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('addresses.index') }}" class="text-orange-500 hover:text-orange-600 text-sm mb-4 inline-block"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
    <h1 class="text-2xl font-bold text-gray-800 mb-6"><i class="fas fa-plus-circle text-orange-500 mr-2"></i>Tambah Alamat</h1>

    <form action="{{ route('addresses.store') }}" method="POST" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Label Alamat</label>
                <select name="label" class="w-full border rounded-lg p-2.5 text-sm @error('label') border-red-400 @enderror" required>
                    <option value="">Pilih label</option>
                    <option value="Rumah" {{ old('label') == 'Rumah' ? 'selected' : '' }}>Rumah</option>
                    <option value="Kantor" {{ old('label') == 'Kantor' ? 'selected' : '' }}>Kantor</option>
                    <option value="Kost" {{ old('label') == 'Kost' ? 'selected' : '' }}>Kost</option>
                    <option value="Lainnya" {{ old('label') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('label')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima</label>
                <input type="text" name="recipient_name" value="{{ old('recipient_name') }}" class="w-full border rounded-lg p-2.5 text-sm @error('recipient_name') border-red-400 @enderror" required>
                @error('recipient_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon Penerima</label>
                <input type="text" name="recipient_phone" value="{{ old('recipient_phone') }}" class="w-full border rounded-lg p-2.5 text-sm @error('recipient_phone') border-red-400 @enderror">
                @error('recipient_phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="w-full border rounded-lg p-2.5 text-sm @error('postal_code') border-red-400 @enderror">
                @error('postal_code')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
            <select name="province_code" id="province" class="w-full border rounded-lg p-2.5 text-sm @error('province_code') border-red-400 @enderror" onchange="loadCities(this.value)">
                <option value="">Pilih Provinsi</option>
                @foreach($provinces as $p)
                    <option value="{{ $p->code }}" {{ old('province_code') == $p->code ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
            <input type="hidden" name="province" id="province_name" value="{{ old('province') }}">
            @error('province_code')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten</label>
                <select name="city_code" id="city" class="w-full border rounded-lg p-2.5 text-sm @error('city_code') border-red-400 @enderror" onchange="loadDistricts(this.value)" disabled>
                    <option value="">Pilih Kota/Kabupaten</option>
                </select>
                <input type="hidden" name="city" id="city_name" value="{{ old('city') }}">
                @error('city_code')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                <select name="district_code" id="district" class="w-full border rounded-lg p-2.5 text-sm @error('district_code') border-red-400 @enderror" disabled>
                    <option value="">Pilih Kecamatan</option>
                </select>
                <input type="hidden" name="district" id="district_name" value="{{ old('district') }}">
                @error('district_code')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap (jalan, gedung, no. rumah, RT/RW)</label>
            <textarea name="address" rows="3" class="w-full border rounded-lg p-2.5 text-sm @error('address') border-red-400 @enderror" required placeholder="Contoh: Jl. Merdeka No. 123, RT 01 RW 02">{{ old('address') }}</textarea>
            @error('address')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }} class="rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                <span class="text-sm text-gray-700">Jadikan alamat utama</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-xl font-semibold hover:bg-orange-600 transition"><i class="fas fa-save mr-2"></i>Simpan Alamat</button>
    </form>
</section>
@endsection

@push('scripts')
<script>
    var cityLoaded = '{{ old("city_code") }}';
    var districtLoaded = '{{ old("district_code") }}';

    function loadCities(provinceCode) {
        var citySel = document.getElementById('city');
        var distSel = document.getElementById('district');
        citySel.disabled = true;
        citySel.innerHTML = '<option value="">Memuat...</option>';
        distSel.disabled = true;
        distSel.innerHTML = '<option value="">Pilih Kecamatan</option>';

        if (!provinceCode) {
            citySel.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            document.getElementById('province_name').value = '';
            return;
        }

        var selProv = document.querySelector('#province option:checked');
        document.getElementById('province_name').value = selProv ? selProv.text : '';

        fetch('/api/cities/' + provinceCode)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                citySel.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                data.forEach(function(c) {
                    citySel.innerHTML += '<option value="' + c.code + '">' + c.name + '</option>';
                });
                citySel.disabled = false;
                if (cityLoaded && cityLoaded == citySel.value) loadDistricts(cityLoaded);
                if (cityLoaded) { citySel.value = cityLoaded; loadDistricts(cityLoaded); }
            });
    }

    function loadDistricts(cityCode) {
        var distSel = document.getElementById('district');
        distSel.disabled = true;
        distSel.innerHTML = '<option value="">Memuat...</option>';

        if (!cityCode) {
            distSel.innerHTML = '<option value="">Pilih Kecamatan</option>';
            document.getElementById('city_name').value = '';
            return;
        }

        var selCity = document.querySelector('#city option:checked');
        document.getElementById('city_name').value = selCity ? selCity.text : '';

        fetch('/api/districts/' + cityCode)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                distSel.innerHTML = '<option value="">Pilih Kecamatan</option>';
                data.forEach(function(d) {
                    distSel.innerHTML += '<option value="' + d.code + '">' + d.name + '</option>';
                });
                distSel.disabled = false;
                if (districtLoaded) distSel.value = districtLoaded;
            });
    }

    document.getElementById('district').addEventListener('change', function() {
        var sel = this.querySelector('option:checked');
        document.getElementById('district_name').value = sel ? sel.text : '';
    });

    if (cityLoaded) { loadCities(document.getElementById('province').value); }
</script>
@endpush
