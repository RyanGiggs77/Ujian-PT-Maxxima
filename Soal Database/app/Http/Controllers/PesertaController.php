<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Siswa;
use App\Models\Ujian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PesertaController extends Controller
{
    public function index(): View
    {
        $daftarPeserta = Peserta::with(['siswa', 'ujian.mataPelajaran'])->orderByDesc('id')->paginate(15);

        return view('peserta.index', compact('daftarPeserta'));
    }

    public function create(): View
    {
        $daftarSiswa = Siswa::orderBy('nama')->get();
        $daftarUjian = Ujian::with('mataPelajaran')->orderBy('tanggal')->get();

        return view('peserta.create', compact('daftarSiswa', 'daftarUjian'));
    }

    public function store(Request $request): RedirectResponse
    {
        Peserta::create($this->validated($request));

        return redirect()->route('peserta.index')->with('success', 'Peserta berhasil ditambahkan.');
    }

    public function edit(Peserta $peserta): View
    {
        $daftarSiswa = Siswa::orderBy('nama')->get();
        $daftarUjian = Ujian::with('mataPelajaran')->orderBy('tanggal')->get();

        return view('peserta.edit', compact('peserta', 'daftarSiswa', 'daftarUjian'));
    }

    public function update(Request $request, Peserta $peserta): RedirectResponse
    {
        $peserta->update($this->validated($request, $peserta));

        return redirect()->route('peserta.index')->with('success', 'Peserta dan nilai berhasil diperbarui.');
    }

    public function destroy(Peserta $peserta): RedirectResponse
    {
        return $this->deleteAcademicRecord($peserta, 'peserta.index');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Peserta $peserta = null): array
    {
        return $request->validate([
            'id_ujian' => ['required', 'integer', Rule::exists('ujian', 'id_ujian')],
            'nis' => [
                'required', 'string', Rule::exists('siswa', 'nis'),
                Rule::unique('peserta', 'nis')
                    ->where(fn ($query) => $query->where('id_ujian', $request->input('id_ujian')))
                    ->ignore($peserta?->id),
            ],
            'nilai' => ['nullable', 'numeric', 'decimal:0,2', 'between:0,100'],
        ]);
    }
}
