<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SiswaController extends Controller
{
    public function index(): View
    {
        $daftarSiswa = Siswa::orderBy('nama')->paginate(15);

        return view('siswa.index', compact('daftarSiswa'));
    }

    public function create(): View
    {
        return view('siswa.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Siswa::create($request->validate([
            'nis' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9_-]+$/', Rule::unique('siswa', 'nis')],
            'nama' => ['required', 'string', 'max:50'],
            'alamat' => ['required', 'string', 'max:100'],
        ]));

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa): View
    {
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $siswa->update($request->validate([
            'nama' => ['required', 'string', 'max:50'],
            'alamat' => ['required', 'string', 'max:100'],
        ]));

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        return $this->deleteAcademicRecord($siswa, 'siswa.index');
    }
}
