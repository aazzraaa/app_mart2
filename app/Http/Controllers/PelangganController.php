<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PelangganController extends Controller
{
    public function index()
    {
        $pelangganList = Pelanggan::orderBy('id_pelanggan', 'desc')->get();

        return Inertia::render('Pelanggan/Index', [
            'pelangganList' => $pelangganList,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'kota'           => 'required|string|max:255',
        ]);

        Pelanggan::create($validated);

        return Redirect::route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * LIHAT DETAIL PELANGGAN
     */
    public function show(Pelanggan $pelanggan)
    {
        return Inertia::render('Pelanggan/Show', [
            'pelanggan' => $pelanggan,
        ]);
    }

    /**
     * FORM EDIT PELANGGAN
     */
    public function edit(Pelanggan $pelanggan)
    {
        return Inertia::render('Pelanggan/Edit', [
            'pelanggan' => $pelanggan,
        ]);
    }

    /**
     * SIMPAN PERUBAHAN
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'kota'           => 'required|string|max:255',
        ]);

        $pelanggan->update($validated);

        return Redirect::route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    /**
     * HAPUS PELANGGAN
     */
    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return Redirect::route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}