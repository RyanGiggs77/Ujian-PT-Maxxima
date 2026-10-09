<?php

function balikString($teks)
{
    if ($teks === '') {
        return '';
    }

    $karakter = preg_split('//u', $teks, -1, PREG_SPLIT_NO_EMPTY);
    $terakhir = array_pop($karakter);

    return $terakhir . balikString(implode('', $karakter));
}

function bacaAngka($input)
{
    $bagian = explode(',', $input);
    $angka = [];

    foreach ($bagian as $nilai) {
        $nilai = trim($nilai);

        if (!preg_match('/^-?\d+$/', $nilai) || filter_var($nilai, FILTER_VALIDATE_INT) === false) {
            throw new InvalidArgumentException('Masukkan bilangan bulat yang dipisahkan koma, contoh: 3, 7, -3, 5, 9, 5.');
        }

        $angka[] = (int) $nilai;
    }

    return $angka;
}


function urutkanAngka($angka)
{
    $jumlah = count($angka);

    for ($i = 0; $i < $jumlah - 1; $i++) {
        for ($j = 0; $j < $jumlah - $i - 1; $j++) {
            if ($angka[$j] < $angka[$j + 1]) {
                $sementara = $angka[$j];
                $angka[$j] = $angka[$j + 1];
                $angka[$j + 1] = $sementara;
            }
        }
    }

    return $angka;
}

function cariAngka($angka, $pilihan)
{
    if ($pilihan !== 'A' && $pilihan !== 'B') {
        throw new InvalidArgumentException('Pilih angka terbesar atau terkecil.');
    }

    $hasil = $angka[0];

    foreach ($angka as $nilai) {
        if (($pilihan === 'A' && $nilai > $hasil) || ($pilihan === 'B' && $nilai < $hasil)) {
            $hasil = $nilai;
        }
    }

    return $hasil;
}

function hitungUmur($input, $hariIni = null)
{
    $lahir = DateTimeImmutable::createFromFormat('!d/m/Y', $input);

    if (!$lahir || $lahir->format('d/m/Y') !== $input) {
        throw new InvalidArgumentException('Tanggal lahir harus valid dan berformat dd/mm/yyyy, contoh: 12/08/1990.');
    }

    $hariIni = $hariIni ?? new DateTimeImmutable('today');

    if ($lahir > $hariIni) {
        throw new InvalidArgumentException('Tanggal lahir tidak boleh melewati hari ini.');
    }

    $umur = $lahir->diff($hariIni);
    $kabisat = 0;
    // Simpan tahun kabisat yang sudah dilewati sejak lahir.
    $listKabisat = [];

    for ($tahun = (int) $lahir->format('Y'); $tahun <= (int) $hariIni->format('Y'); $tahun++) {
        if ($tahun % 400 === 0 || ($tahun % 4 === 0 && $tahun % 100 !== 0)) {
            $tanggalKabisat = new DateTimeImmutable(sprintf('%04d-02-29', $tahun));

            if ($tanggalKabisat > $lahir && $tanggalKabisat <= $hariIni) {
                $kabisat++;
                $listKabisat[] = $tahun;
            }
        }
    }

    return [
        'tahun' => $umur->y,
        'bulan' => $umur->m,
        'hari' => $umur->d,
        'kabisat' => $kabisat,
        'listKabisat' => $listKabisat
    ];
}

function aman($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}
