<?php
date_default_timezone_set('Asia/Jakarta');
require_once __DIR__ . '/functions.php';

$hasil = [];
$error = [];
$input = ['teks' => '', 'urut' => '', 'cari' => '', 'pilihan' => 'A', 'lahir' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($input as $nama => $default) {
        $input[$nama] = isset($_POST[$nama]) && is_string($_POST[$nama]) ? trim($_POST[$nama]) : $default;
    }

    $fitur = isset($_POST['fitur']) && is_string($_POST['fitur']) ? $_POST['fitur'] : '';

    try {
        switch ($fitur) {
            case 'balik':
                if ($input['teks'] === '' || strlen($input['teks']) > 1000) {
                    throw new InvalidArgumentException('Masukkan teks dengan panjang maksimal 1.000 byte.');
                }
                $hasil['balik'] = balikString($input['teks']);
                break;
            case 'urut':
                $hasil['urut'] = implode(', ', urutkanAngka(bacaAngka($input['urut'])));
                break;
            case 'cari':
                $hasil['cari'] = cariAngka(bacaAngka($input['cari']), $input['pilihan']);
                break;
            case 'umur':
                $hasil['umur'] = hitungUmur($input['lahir']);
                break;
        }
    } catch (InvalidArgumentException $e) {
        $error[$fitur] = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Algoritma</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main>
        <header>
            <span class="eyebrow"></span>
            <h1>Algoritma<span>.</span></h1>
            <p></p>
        </header>

        <div class="grid">
            <section class="card">
                <div class="heading"><span class="number">01</span>
                    <h2>Pembalik teks</h2>
                </div>
                <p class="description">Membalik urutan karakter menggunakan rekursi.</p>
                <form method="post" action="#balik" id="balik">
                    <input type="hidden" name="fitur" value="balik">
                    <label for="teks">Teks</label>
                    <input id="teks" name="teks" value="<?= aman($input['teks']) ?>" placeholder="Contoh: INDONESIA" maxlength="250" required>
                    <button type="submit">Balik teks <span aria-hidden="true">→</span></button>
                </form>
                <?php if (isset($error['balik'])): ?><p class="error" role="alert"><?= aman($error['balik']) ?></p><?php endif; ?>
                <?php if (isset($hasil['balik'])): ?><div class="result" role="status"><span>Hasil pembalikan</span><strong><?= aman($hasil['balik']) ?></strong></div><?php endif; ?>
            </section>

            <section class="card">
                <div class="heading"><span class="number">02</span>
                    <h2>Pengurutan angka</h2>
                </div>
                <p class="description">Mengurutkan angka dari kecil ke besar melalui loop.</p>
                <form method="post" action="#urut" id="urut">
                    <input type="hidden" name="fitur" value="urut">
                    <label for="angka-urut">Daftar angka</label>
                    <input id="angka-urut" name="urut" value="<?= aman($input['urut']) ?>" placeholder="Contoh: 1, 3, 2, 9, 5" maxlength="1000" required>
                    <!-- <p class="hint">Pisahkan bilangan bulat dengan koma.</p> -->
                    <button type="submit">Urutkan angka <span aria-hidden="true">→</span></button>
                </form>
                <?php if (isset($error['urut'])): ?><p class="error" role="alert"><?= aman($error['urut']) ?></p><?php endif; ?>
                <?php if (isset($hasil['urut'])): ?><div class="result" role="status"><span>Urutan angka</span><strong><?= aman($hasil['urut']) ?></strong></div><?php endif; ?>
            </section>

            <section class="card">
                <div class="heading"><span class="number">03</span>
                    <h2>Terbesar &amp; terkecil</h2>
                </div>
                <p class="description">Membandingkan angka satu per satu melalui loop.</p>
                <form method="post" action="#cari" id="cari">
                    <input type="hidden" name="fitur" value="cari">
                    <label for="angka-cari">Daftar angka</label>
                    <input id="angka-cari" name="cari" value="<?= aman($input['cari']) ?>" placeholder="Contoh: 3, 7, -3, 5, 9, 5" maxlength="1000" required>
                    <label for="pilihan">Cari angka</label>
                    <select id="pilihan" name="pilihan">
                        <option value="A" <?= $input['pilihan'] === 'A' ? 'selected' : '' ?>>A — Terbesar</option>
                        <option value="B" <?= $input['pilihan'] === 'B' ? 'selected' : '' ?>>B — Terkecil</option>
                    </select>
                    <button type="submit">Cari angka <span aria-hidden="true">→</span></button>
                </form>
                <?php if (isset($error['cari'])): ?><p class="error" role="alert"><?= aman($error['cari']) ?></p><?php endif; ?>
                <?php if (isset($hasil['cari'])): ?><div class="result" role="status"><span>Angka <?= $input['pilihan'] === 'A' ? 'terbesar' : 'terkecil' ?></span><strong><?= aman($hasil['cari']) ?></strong></div><?php endif; ?>
            </section>

            <section class="card">
                <div class="heading"><span class="number">04</span>
                    <h2>Perhitungan umur</h2>
                </div>
                <p class="description">Menghitung umur sampai hari ini, <?= date('d-m-Y') ?>.</p>
                <form method="post" action="#umur" id="umur">
                    <input type="hidden" name="fitur" value="umur">
                    <label for="lahir">Tanggal lahir</label>
                    <input type="text" id="lahir" name="lahir" value="<?= aman($input['lahir']) ?>" placeholder="12/08/1990" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}" maxlength="10" required aria-describedby="format-lahir">
                    <p class="hint" id="format-lahir">Format dd/mm/yyyy (hari/bulan/tahun).</p>
                    <button type="submit">Hitung umur <span aria-hidden="true">→</span></button>
                </form>
                <?php if (isset($error['umur'])): ?><p class="error" role="alert"><?= aman($error['umur']) ?></p><?php endif; ?>
                <?php if (isset($hasil['umur'])): $umur = $hasil['umur']; ?>
                    <div class="result" role="status"><span>Umur saat ini</span><strong><?= $umur['tahun'] ?> tahun, <?= $umur['bulan'] ?> bulan, <?= $umur['hari'] ?> hari</strong>
                        <p><?= $umur['kabisat'] ?> tahun kabisat terlewati.</p>
                        <?php if (count($umur['listKabisat']) > 0): ?>
                            <p>Tahun kabisat: <?= aman(implode(', ', $umur['listKabisat'])) ?></p>
                        <?php else: ?>
                            <p>Tahun kabisat: Belum ada.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <p class="note">Kabisat dihitung dari tanggal 29 Februari yang terlewati setelah lahir.</p>
            </section>
        </div>
        <footer></footer>
    </main>
</body>

</html>
