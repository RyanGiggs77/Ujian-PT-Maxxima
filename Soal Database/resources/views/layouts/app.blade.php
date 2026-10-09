<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Akademik') · Sistem Informasi Akademik</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #172b36;
            --muted: #63747c;
            --line: #dce3e4;
            --accent: #146b58;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f6f5;
            color: var(--ink);
            font: 15px/1.6 system-ui, sans-serif;
        }

        a {
            color: var(--accent);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 230px;
            background: #172b36;
            color: #fff;
            padding: 32px 22px;
        }

        .brand {
            font-size: 24px;
            font-weight: 750;
            letter-spacing: -1px;
        }

        .brand small {
            display: block;
            font-size: 12px;
            letter-spacing: 1px;
            color: #b8c7cd;
            font-weight: 400;
        }

        nav {
            display: grid;
            gap: 6px;
            margin: 38px 0;
        }

        nav a {
            color: #d9e3e6;
            padding: 11px 14px;
            border-radius: 6px;
        }

        nav a.active {
            background: #2b464f;
            color: #fff;
            font-weight: 600;
        }

        .sidebar p {
            font-size: 12px;
            color: #b8c7cd;
        }

        main {
            margin-left: 230px;
            padding: 38px 42px;
            max-width: 1600px;
        }

        .eyebrow {
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 11px;
            margin: 0 0 8px;
        }

        h1 {
            font-size: 30px;
            line-height: 1.2;
            letter-spacing: -.7px;
            margin: 0;
        }

        h2 {
            font-size: 19px;
            margin: 0;
        }

        .subtle {
            color: var(--muted);
            margin: 8px 0 0;
        }

        .page-head {
            display: flex;
            gap: 20px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            margin-bottom: 24px;
            overflow: hidden;
        }

        .panel-head {
            padding: 20px 24px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .panel-body {
            padding: 24px;
        }

        .table-wrap {
            overflow: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        th {
            font-size: 11px;
            color: var(--muted);
            letter-spacing: .5px;
            text-transform: uppercase;
            background: #f9faf9;
        }

        th,
        td {
            text-align: left;
            padding: 15px 22px;
            border-bottom: 1px solid #edf0ef;
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        td.wrap {
            white-space: normal;
            min-width: 140px;
        }

        .empty {
            text-align: center;
            padding: 44px;
            color: var(--muted);
        }

        .button,
        button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border-radius: 5px;
            border: 1px solid var(--accent);
            background: var(--accent);
            color: white;
            font: inherit;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
        }

        .button:hover {
            text-decoration: none;
            background: #0d5847;
        }

        .button.secondary {
            color: var(--ink);
            background: white;
            border-color: var(--line);
        }

        button.delete {
            padding: 0;
            color: #ab3030;
            border: 0;
            background: none;
            font-size: 13px;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .filter {
            display: flex;
            align-items: end;
            flex-wrap: wrap;
            gap: 12px;
            padding: 20px 24px;
            border-bottom: 1px solid var(--line);
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin: 0 0 7px;
        }

        input,
        select,
        textarea {
            display: block;
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #bdcbd0;
            border-radius: 5px;
            background: white;
            color: var(--ink);
            font: inherit;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: 2px solid #92bcaf;
            outline-offset: 1px;
        }

        input[readonly] {
            background: #f1f3f2;
            color: var(--muted);
        }

        .field {
            margin-bottom: 22px;
        }

        .hint {
            color: var(--muted);
            font-size: 12px;
            margin: 6px 0 0;
        }

        .form-panel {
            max-width: 680px;
        }

        .form-footer {
            display: flex;
            gap: 12px;
            padding-top: 10px;
        }

        .notice {
            padding: 14px 18px;
            border-radius: 6px;
            margin-bottom: 22px;
        }

        .notice.success {
            background: #e6f3ec;
            color: #19553d;
        }

        .notice.error {
            background: #faece9;
            color: #8b3427;
        }

        .notice ul {
            margin: 6px 0 0;
            padding-left: 20px;
        }

        .badge {
            display: inline-block;
            border-radius: 4px;
            padding: 4px 9px;
            font-size: 11px;
            background: #edf0ef;
            color: #58666b;
        }

        .badge.pass {
            background: #e6f3ec;
            color: #19553d;
        }

        .badge.fail {
            background: #faece9;
            color: #8b3427;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            align-items: center;
            padding: 16px 24px;
            border-top: 1px solid var(--line);
            color: var(--muted);
            font-size: 12px;
        }

        .summary {
            display: flex;
            align-items: center;
            gap: 22px;
            padding: 24px;
            border-bottom: 1px solid var(--line);
        }

        .summary strong {
            font-size: 42px;
            line-height: 1;
            font-weight: 650;
            color: var(--accent);
        }

        @media(max-width:850px) {
            .sidebar {
                position: static;
                width: auto;
                padding: 18px 20px;
            }

            .brand {
                font-size: 20px;
            }

            nav {
                display: flex;
                flex-wrap: wrap;
                margin: 15px 0 0;
                gap: 4px;
            }

            nav a {
                padding: 7px 10px;
                font-size: 12px;
            }

            .sidebar p {
                display: none;
            }

            main {
                margin: 0;
                padding: 24px 16px;
            }

            .page-head {
                align-items: start;
            }

            h1 {
                font-size: 25px;
            }

            .panel-head,
            .panel-body {
                padding: 18px;
            }
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="brand">Akademik<small>SISTEM INFORMASI</small></div>
        <nav aria-label="Menu utama">
            <a href="{{ route('laporan.index') }}" @class(['active' => request()->routeIs('home', 'laporan.*')])>Laporan Akademik</a>
            <a href="{{ route('siswa.index') }}" @class(['active' => request()->routeIs('siswa.*')])>Siswa</a>
            <a href="{{ route('mata-pelajaran.index') }}" @class(['active' => request()->routeIs('mata-pelajaran.*')])>Mata Pelajaran</a>
            <a href="{{ route('ujian.index') }}" @class(['active' => request()->routeIs('ujian.*')])>Ujian</a>
            <a href="{{ route('peserta.index') }}" @class(['active' => request()->routeIs('peserta.*')])>Peserta & Nilai</a>
        </nav>
        {{-- <p>Batas kelulusan: {{ \App\Models\Peserta::KKM }}<br>Nilai kosong: belum dinilai.</p> --}}
    </aside>
    <main>
        @if (session('success'))
            <div class="notice success" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="notice error" role="alert"><strong>Periksa kembali data Anda.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
</body>

</html>
