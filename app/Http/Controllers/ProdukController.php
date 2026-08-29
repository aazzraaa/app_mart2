<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::orderBy('id', 'desc')->get();

        return Inertia::render('Produk/Index', [
            'produk' => $produk,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'kategori'    => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_url'    => 'nullable|url|max:500',
            'foto_type'   => 'required|in:file,url',
        ]);

        unset($validated['foto'], $validated['foto_url'], $validated['foto_type']);

        if ($request->foto_type === 'file' && $request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('produk', 'public');
        } elseif ($request->foto_type === 'url' && $request->filled('foto_url')) {
            $validated['foto'] = $request->foto_url;
        }

        Produk::create($validated);

        return Redirect::route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Produk $produk)
    {
        return Inertia::render('Produk/Show', [
            'produk' => $produk,
        ]);
    }

    public function edit(Produk $produk)
    {
        return Inertia::render('Produk/Edit', [
            'produk' => $produk,
        ]);
    }

    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'kategori'    => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_url'    => 'nullable|url|max:500',
            'foto_type'   => 'required|in:file,url,keep',
        ]);

        unset($validated['foto'], $validated['foto_url'], $validated['foto_type']);

        if ($request->foto_type === 'file' && $request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($produk->foto && !str_starts_with($produk->foto, 'http')) {
                \Storage::disk('public')->delete($produk->foto);
            }
            $validated['foto'] = $request->file('foto')->store('produk', 'public');
        } elseif ($request->foto_type === 'url' && $request->filled('foto_url')) {
            if ($produk->foto && !str_starts_with($produk->foto, 'http')) {
                \Storage::disk('public')->delete($produk->foto);
            }
            $validated['foto'] = $request->foto_url;
        }
        // foto_type === 'keep' → tidak ubah foto

        $produk->update($validated);

        return Redirect::route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        // Hapus file foto dari storage
        if ($produk->foto && !str_starts_with($produk->foto, 'http')) {
            \Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return Redirect::route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}