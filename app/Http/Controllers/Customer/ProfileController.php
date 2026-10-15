<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Profil kantor (customer): nama kantor, kota, alamat, dan PIC.
 */
class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('customer.profile', ['customer' => $request->user()->customerProfile]);
    }

    public function update(Request $request): RedirectResponse
    {
        $customer = $request->user()->customerProfile;

        $validated = $request->validate([
            'office_name' => ['required', 'string', 'max:150'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'pic_name' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'min:8', 'max:20'],
        ], [
            'office_name.required' => 'Nama kantor/instansi wajib diisi.',
            'city.required' => 'Kota wajib diisi.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.min' => 'Nomor telepon minimal 8 karakter.',
        ]);

        DB::transaction(function () use ($customer, $request, $validated) {
            $customer->update([
                'office_name' => $validated['office_name'],
                'city' => $validated['city'],
                'address' => $validated['address'] ?? null,
                'pic_name' => $validated['pic_name'] ?? null,
            ]);

            $request->user()->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
            ]);
        });

        return redirect()
            ->route('customer.profile.edit')
            ->with('success', 'Profil kantor berhasil diperbarui.');
    }
}
