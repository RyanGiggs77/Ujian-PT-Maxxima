<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MataPelajaranController extends Controller
{
    public function index(): View
    {
        $daftarMataPelajaran = MataPelajaran::orderBy('nama_matpel')->paginate(15);

        return view('mata-pelajaran.index', compact('daftarMataPelajaran'));
    }

    public function create(): View
    {
        return view('mata-pelajaran.create');
    }

    public function store(Request $request): RedirectResponse
    {
        MataPelajaran::create($request->validate(['nama_matpel' => ['required', 'string', 'max:50']]));

        return redirect()->route('mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mataPelajaran): View
    {
        return view('mata-pelajaran.edit', compact('mataPelajaran'));
    }

    public function update(Request $request, MataPelajaran $mataPelajaran): RedirectResponse
    {
        $mataPelajaran->update($request->validate(['nama_matpel' => ['required', 'string', 'max:50']]));

        return redirect()->route('mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran): RedirectResponse
    {
        return $this->deleteAcademicRecord($mataPelajaran, 'mata-pelajaran.index');
    }
}
