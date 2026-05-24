<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Province;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', auth()->id())->latest()->get();
        return view('addresses.index', compact('addresses'));
    }

    public function create()
    {
        if (Address::where('user_id', auth()->id())->count() >= 5) {
            return back()->with('error', 'Maksimal 5 alamat');
        }
        $provinces = Province::orderBy('name')->get();
        return view('addresses.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        if (Address::where('user_id', auth()->id())->count() >= 5) {
            return back()->with('error', 'Maksimal 5 alamat');
        }

        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'recipient_phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:1000',
            'province' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'province_code' => 'nullable|string|max:2',
            'city_code' => 'nullable|string|max:5',
            'district_code' => 'nullable|string|max:8',
            'is_default' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();

        if ($request->boolean('is_default')) {
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        Address::create($validated);

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil ditambahkan');
    }

    public function edit(Address $address)
    {
        if ($address->user_id !== auth()->id()) abort(403);
        $provinces = Province::orderBy('name')->get();
        return view('addresses.edit', compact('address', 'provinces'));
    }

    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'recipient_phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:1000',
            'province' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'province_code' => 'nullable|string|max:2',
            'city_code' => 'nullable|string|max:5',
            'district_code' => 'nullable|string|max:8',
            'is_default' => 'boolean',
        ]);

        if ($request->boolean('is_default')) {
            Address::where('user_id', auth()->id())->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil diperbarui');
    }

    public function destroy(Address $address)
    {
        if ($address->user_id !== auth()->id()) abort(403);
        $address->delete();
        return back()->with('success', 'Alamat berhasil dihapus');
    }
}
