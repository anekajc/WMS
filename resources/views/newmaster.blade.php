<!DOCTYPE html>
<html dir="ltr" lang="en-US">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="SemiColonWeb" />

    <!-- Stylesheets
	============================================= -->
  <!-- href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Poppins:300,400,500,600,700|PT+Serif:400,400i&display=swap" -->
    <link

      href="{!! URL::asset('public/css/canvas/fontSPL.css') !!}"
      rel="stylesheet"
      type="text/css"
    />

    <link rel="stylesheet" href="{!! URL::asset('public/css/semantic.css') !!}">
    <link rel="stylesheet" href="{!! URL::asset('public/css/select2.min.css') !!}">
    <link rel="stylesheet" href="{!! URL::asset('public/css/datatables.min.css') !!}">
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.css"> -->
    <link rel="stylesheet" href="{!! URL::asset('public/css/canvas/jquery.dataTables.css') !!}">

    <link rel="stylesheet" href="{!! URL::asset('public/css/jquery-ui.min.css') !!}">

    <link rel="stylesheet" media='all' href="{!! URL::asset('public/css/canvas/bootstrap.css') !!}">
    <link rel="stylesheet" href="{!! URL::asset('public/css/canvas/style.css') !!}">
    <link rel="stylesheet" href="{!! URL::asset('public/css/canvas/dark.css') !!}">
    <link rel="stylesheet" href="{!! URL::asset('public/css/canvas/font-icons.css') !!}">
    <link rel="stylesheet" href="{!! URL::asset('public/css/canvas/animate.css') !!}">
    <link rel="stylesheet" href="{!! URL::asset('public/css/canvas/magnific-popup.css') !!}">
    <link rel="stylesheet" href="{!! URL::asset('public/css/canvas/custom.css') !!}">
    <link rel="stylesheet" href="{!! URL::asset('public/css/alertify.css') !!}">
    <link rel="stylesheet" href="{!! URL::asset('public/css/canvas/bootstrap-icons.css') !!}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    {{-- Ditambahkan 2026-10-19: aset tampilan baru (sidebar gaya newSML). Aset lama di atas tetap. --}}
    <link rel="stylesheet" href="{!! URL::asset('public/css/header-mobile.css') !!}?v={{ @filemtime(base_path('public/css/header-mobile.css')) ?: '1' }}" />
    <link rel="stylesheet" href="{!! URL::asset('public/css/newmaster.css') !!}?v={{ @filemtime(base_path('public/css/newmaster.css')) ?: '1' }}">
    <link rel="stylesheet" href="{!! URL::asset('public/css/po-table-header.css') !!}?v={{ @filemtime(base_path('public/css/po-table-header.css')) ?: '1' }}">
    {{-- Pola halaman menu newpo (tab pil, tabel daftar, modal) untuk semua menu - lihat file-nya. --}}
    <link rel="stylesheet" href="{!! URL::asset('public/css/scrollbar-autohide.css') !!}?v={{ @filemtime(base_path('public/css/scrollbar-autohide.css')) ?: '1' }}">
    <link rel="stylesheet" href="{!! URL::asset('public/css/wms-tampilan-baru.css') !!}?v={{ @filemtime(base_path('public/css/wms-tampilan-baru.css')) ?: '1' }}">
    <!-- CSS BOOTSTRAP 5.3 -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->

    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Document Title
	============================================= -->
    <title>WMS AJC</title>

    {{-- Ditambahkan 2026-10-19: js/canvas/functions.js mencari plugin di path relatif "js/" (dari URL
         halaman), jadi plugins.easing.js 404. scwJsPath = variabel resmi template Canvas untuk folder
         plugin-nya; lewat URL::asset supaya benar di server (termasuk bila dipasang di subfolder). --}}
    <script>var scwJsPath = "{!! URL::asset('public/js/canvas') !!}";</script>
    @yield('css')
    <!-- <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
          background: none;
          color: black!important;
          border-radius: 4px;
          border: 1px solid #FFFFFF;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:active {
          background: none;
          color: black!important;
        }
    </style> -->
    {{-- Ditambahkan 2026-10-19: gaya sidebar + header (disalin dari tampilan baru gaya newSML). --}}
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --sidebar-col: 64px;
            --sidebar-exp: 240px;
            --hdr: 52px;
            --blue: #1a73e8;
            --sidebar-bg: #1e2a3a;
            --sidebar-hover: #2a3a50;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --bg: #f3f4f6;
            --white: #fff;
            --border: #e5e7eb;
            --radius: 12px;
        }

        body {
            font-family: "Segoe UI", system-ui, sans-serif;
            background: var(--bg);
            color: var(--text-main);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* -- SIDEBAR -- */
        .sidebar {
            width: var(--sidebar-col);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            transition: width 0.22s ease;
            overflow: hidden;
            z-index: 100;
            flex-shrink: 0;
        }

        .sidebar:hover { width: var(--sidebar-exp); }

        .sidebar-logo {
            height: var(--hdr);
            display: flex;
            align-items: center;
            padding: 0 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            gap: 10px;
            flex-shrink: 0;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: var(--blue);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: white;
            font-size: 12px;
            flex-shrink: 0;
        }

        .logo-text {
            color: white;
            font-weight: 700;
            font-size: 15px;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.18s;
        }

        .sidebar:hover .logo-text { opacity: 1; }

        .sidebar-nav {
            flex: 1;
            padding: 8px 0;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .nav-group { position: relative; }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 11px 16px;
            gap: 12px;
            cursor: pointer;
            border-left: 3px solid transparent;
            transition: background 0.15s, border-color 0.15s;
            white-space: nowrap;
        }

        .nav-item:hover { background: var(--sidebar-hover); }

        .nav-group.active>.nav-item {
            background: rgba(26, 115, 232, 0.18);
            border-left-color: var(--blue);
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            opacity: 0.7;
            color: white;
        }

        .nav-group.active>.nav-item .nav-icon { opacity: 1; color: #60a5fa; }

        .nav-label {
            color: rgba(255, 255, 255, 0.75);
            font-size: 13px;
            font-weight: 500;
            opacity: 0;
            transition: opacity 0.18s;
            flex: 1;
        }

        .sidebar:hover .nav-label { opacity: 1; }
        .nav-group.active>.nav-item .nav-label { color: #fff; }

        .nav-chevron {
            width: 14px;
            height: 14px;
            color: rgba(255, 255, 255, 0.35);
            opacity: 0;
            transition: opacity 0.18s, transform 0.2s;
            flex-shrink: 0;
        }

        .sidebar:hover .nav-chevron { opacity: 1; }

        /* newmaster.css masih membawa akordeon hover; di sini submenu hanya terbuka lewat klik
           (.open) - sama dengan newSML. */
        .nav-group>.nav-item .nav-chevron { transform: none !important; }
        .nav-group.open>.nav-item .nav-chevron { transform: rotate(90deg) !important; color: rgba(255, 255, 255, 0.6); }

        .nav-children {
            overflow: hidden;
            transition: max-height 0.25s ease;
            background: rgba(0, 0, 0, 0.15);
        }

        .nav-children { max-height: 0 !important; }
        .sidebar:hover .nav-group.open>.nav-children { max-height: 600px !important; }

        .nav-child {
            display: flex;
            align-items: center;
            padding: 8px 16px 8px 48px;
            gap: 8px;
            cursor: pointer;
            font-size: 12.5px;
            color: rgba(255, 255, 255, 0.6);
            transition: background 0.12s, color 0.12s;
            white-space: nowrap;
        }

        .nav-child:hover { background: rgba(255, 255, 255, 0.06); color: #fff; }
        .nav-child.active-child { color: #93c5fd; background: rgba(26, 115, 232, 0.12); }

        .nav-child::before {
            content: "";
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: currentColor;
            flex-shrink: 0;
            opacity: 0.6;
        }

        .nav-child.has-sub { justify-content: flex-start; }
        .nav-child.has-sub .nav-child-label { flex: 1; text-align: left; }
        .nav-child.has-sub .nav-child-arrow { margin-left: auto; }

        .nav-child-arrow {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
            opacity: 0.6;
        }

        .nav-child-arrow svg { display: block; width: 100%; height: 100%; }

        #sidebar.flyout-pinned { width: var(--sidebar-exp, 240px) !important; }

        #sidebar.flyout-pinned .logo-text,
        #sidebar.flyout-pinned .nav-label,
        #sidebar.flyout-pinned .nav-chevron { opacity: 1 !important; }

        #sidebar.flyout-pinned .nav-group.flyout-owner>.nav-children { max-height: 600px !important; }

        .nav-flyout {
            position: fixed !important;
            min-width: 200px;
            max-width: 280px;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 8px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            padding: 6px;
            z-index: 9999 !important;
            margin: 0;
            opacity: 0;
            visibility: hidden;
            transform: translateX(-4px);
            transition: opacity 0.12s ease, transform 0.12s ease, visibility 0.12s ease;
            pointer-events: none;
        }

        .nav-flyout.flyout-visible {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
            pointer-events: auto;
        }

        .nav-flyout-item {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13px;
            white-space: nowrap;
            color: #333;
            cursor: pointer;
            transition: background 0.12s ease;
        }

        .nav-flyout-item:hover { background: rgba(0, 0, 0, 0.06); }

        /* -- MAIN -- */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-width: 0;
        }

        .header {
            height: var(--hdr);
            background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 16px;
            flex-shrink: 0;
        }

        .header .page-title {
            font-size: 19px;
            font-weight: 800;
            color: #1f2430;
            margin: 0;
        }

        .breadcrumb {
            font-size: 13px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            padding: 0;
            margin: 0;
        }

        .breadcrumb b { color: var(--text-main); font-weight: 600; }
        .bc-sep { opacity: 0.4; }

        .header-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Tombol tambahan halaman (section 'buttons') dari layout lama - dulu di navbar abu-abu. */
        .header-right .hdr-buttons {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
            margin: 0;
        }

        .period-badge {
            background: #eff6ff;
            color: var(--blue);
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            border: 1px solid #bfdbfe;
        }

        .avatar {
            width: 32px;
            height: 32px;
            background: var(--blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .logout-link {
            color: #e3342f;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
            text-decoration: none;
        }

        .logout-link:hover { opacity: 0.75; color: #e3342f; }

        /* -- CONTENT -- */
        .content {
            flex: 1;
            overflow-y: auto;
            padding: 28px 32px;
        }

        /* Halaman beranda modul (klik ikon modul di sidebar) */
        .mod-page-title { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
        .mod-page-subtitle { font-size: 13px; color: var(--text-muted); margin-bottom: 28px; }

        .mod-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px;
        }

        .mod-card {
            position: relative;
            background: var(--white);
            border-radius: var(--radius);
            padding: 24px 16px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            border: 1.5px solid var(--border);
            transition: transform 0.15s, box-shadow 0.15s, border-color 0.15s;
            text-align: center;
        }

        .mod-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.09);
            border-color: transparent;
        }

        .mod-card-arrow {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 14px;
            height: 14px;
            opacity: 0.4;
            color: var(--text-muted);
        }

        .mod-card-arrow svg { display: block; width: 100%; height: 100%; }

        .mod-card-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mod-card-icon svg { width: 28px; height: 28px; }

        .mod-card-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            line-height: 1.3;
        }

        .c-blue { background: #eff6ff; color: #1d4ed8; }
        .c-green { background: #f0fdf4; color: #15803d; }
        .c-orange { background: #fff7ed; color: #c2410c; }
        .c-purple { background: #f5f3ff; color: #6d28d9; }
        .c-teal { background: #f0fdfa; color: #0f766e; }
        .c-pink { background: #fdf2f8; color: #9d174d; }
        .c-yellow { background: #fefce8; color: #a16207; }
        .c-red { background: #fef2f2; color: #b91c1c; }
        .c-indigo { background: #eef2ff; color: #3730a3; }
        .c-cyan { background: #ecfeff; color: #0e7490; }

        .mod-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: none;
            border: none;
            color: #ff0000;
            font-size: 14px;
            cursor: pointer;
            margin-bottom: 16px;
            padding: 0;
        }

        .mod-back-btn:hover { color: #730202; }
        .mod-back-btn svg { width: 16px; height: 16px; flex-shrink: 0; }

        /* Tombol newSML: sudut membulat, huruf tebal kecil. */
        .btn {
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            transition: opacity 0.15s;
        }

        .btn:hover { opacity: 0.85; }
    </style>
    {{-- Ditambahkan 2026-10-19: pembungkus lama (#wrapper, .content-wrap, .row gutter-40) tetap
         dipakai supaya halaman lama tidak berubah susunannya; di sini hanya disesuaikan dengan
         susunan sidebar + header. --}}
    <style>
        body.stretched #wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            background: var(--bg);
        }

        #content .content-wrap { padding: 0; }

        #content-blade > .content-wrap > .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        #content-blade > .content-wrap > .container-fluid > .row { margin: 0; }

        /* Isi halaman yang bukan kolom (mis. .container-fluid) selebar area konten. */
        #content-blade > .content-wrap > .container-fluid > .row > :not([class*="col"]) {
            flex: 0 0 100%;
            max-width: 100%;
        }
    </style>
  </head>

  <body class="stretched" >
    @php
        // Pohon menu ajcwms ($menul0 dari tiap controller) -> array sederhana untuk sidebar.
        // Isi & hak akses menu tidak berubah: yang tampil tetap hanya menu yang dimiliki user.
        $wmsMenuNode = function ($m) use (&$wmsMenuNode) {
            return [
                'key' => (string) $m['KODEMENU'],
                'label' => (string) $m['Keterangan'],
                'href' => (string) $m['href'],
                'children' => array_map($wmsMenuNode, is_array($m['child'] ?? null) ? $m['child'] : (array) ($m['child'] ?? [])),
            ];
        };
        $wmsMenu = [];
        foreach (($menul0 ?? []) as $i => $m0) {
            $node = $wmsMenuNode($m0);
            $node['key'] = 'm' . $i . '-' . $node['key']; // kode menu & kode menu report bisa sama
            $wmsMenu[] = $node;
        }
        $namaBulanHeader = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ][(int) ($periode->bulan ?? 0)] ?? '';
    @endphp
    <!-- Document Wrapper
	============================================= -->
    <div id="wrapper" class="clearfix">
      {{-- Ditambahkan 2026-10-19: sidebar kiri + header (gaya newSML). Menu tetap dari $menul0 (new_menu/new_aksesmenu). --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo" onclick="goHome()" style="cursor:pointer;">
            <div class="logo-icon">WMS</div>
            <span class="logo-text">WMS AJC</span>
        </div>
        <nav class="sidebar-nav" id="nav"></nav>
    </aside>

    <div class="main">
        <header class="header">
            <div class="header-left">
                <div id="pageTitleBreadcrumb" class="page-title">@yield('page-title')</div>
                <div class="breadcrumb" id="breadcrumb"><span>Beranda</span></div>
            </div>
            <div class="header-right">
                <ul class="hdr-buttons">
                    @yield('buttons')
                </ul>
                <div class="period-badge" id="div_periode"><span class="pb-full">Username: {{ \Auth::user()->username }}
                    &nbsp;–&nbsp; Periode: {{ $namaBulanHeader }} {!! $periode->tahun ?? '' !!}</span><span class="pb-short">{{ mb_substr($namaBulanHeader, 0, 3) }} {!! $periode->tahun ?? '' !!}</span></div>
                <div id="avatar" class="avatar">{{ mb_substr(\Auth::user()->username, 0, 1) }}</div>
                <a class="logout-link" href="{!! url('logout') !!}" title="Log Out">
                    <i class="bi bi-power"></i><span class="logout-text"> Log Out</span>
                </a>
            </div>
        </header>
      {{-- Ditambahkan 2026-10-19: header + navbar lama di bawah dinonaktifkan (dikomentari, tidak
           dihapus) dan digantikan sidebar + header di atas. @yield('buttons') dan #div_periode
           sekarang ada di header baru - kalau dirender dua kali, id ganda membuat JS halaman
           mengambil elemen yang salah.
      <!-- Header
		============================================= -->
      <header id="header" class="full-header">
        <div id="header-wrap">
          <div class="container">
            <div class="header-row">
              <!-- Logo
						============================================= -->
              <div id="logo">
                <a
                  href="index.html"
                  class="standard-logo"
                  data-dark-logo="images/logo-dark.png"
                  >WMS AJC</a
                >
                <a
                  href="index.html"
                  class="retina-logo"
                  data-dark-logo="images/logo-dark@2x.png"
                  >WMS AJC</a
                >
              </div>
              <!-- #logo end -->

              <div class="header-misc">
                <!-- Top Search
							============================================= -->
              <div id="top-search" class="header-misc-icon">
                  <ul class="menu-container" style="justify-content: left !important; align-items: left !important;">
                      <a class="menu-link" class="fa" href="{!! url('logout') !!}">↪</a>

                      <!-- <img href="{!! url('logout') !!}" src="https://cdn-icons-png.flaticon.com/128/1828/1828427.png" alt="Logout" width="30" height="30"> -->
                    <!-- <i class="bi bi-box-arrow-right"></i> -->
                    <!-- <a href="{!! url('logout') !!}"> -->
                    <!-- <i onclick="function () { console.log(123) }"  class="bi bi-box-arrow-right"></i> -->
                  <!-- </a> -->
                    <!-- <button  style="height: 40px; margin-left: 10px" class="btn btn-secondary btn-sm" type="button"  onclick="buttonLogoutMaster()"  ><i class="bi bi-box-arrow-right"></i></button> -->
                    <!-- <a class="btn btn-secondary" style="height: 40px;" href="{!! url('logout') !!}" role="button"><i class="bi bi-box-arrow-right"></i></a> -->
                    <!-- window.location.href -->

                  <ul>
              </div>
                <!-- #top-search end -->

                <!-- Top Cart
							============================================= -->

                <!-- #top-cart end -->
              </div>

              <div id="primary-menu-trigger">
                <svg class="svg-trigger" viewBox="0 0 100 100">
                  <path
                    d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20"
                  ></path>
                  <path d="m 30,50 h 40"></path>
                  <path
                    d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20"
                  ></path>
                </svg>
              </div>

              <!-- Primary Navigation
						============================================= -->
              <nav class="primary-menu">
                <ul class="menu-container" style="justify-content: left !important; align-items: left !important;">

                  @foreach ($menul0 as $menu0)
                    @if (count($menu0['child']) == 0)
                    <li class="menu-item">
                      <a class="menu-link" href="{{url($menu0->href)}}"><div>{{$menu0['Keterangan']}}</div></a>
                    </li>
                    @else
                    <li class="menu-item">
                      <a class="menu-link" href="{{url($menu0->href)}}"><div>{{$menu0['Keterangan']}}</div></a>
                      <ul class="sub-menu-container">
                        @foreach ($menu0['child'] as $menu1)
                          @if (count($menu1['child']) == 0)
                          <li class="menu-item">
                            <a class="menu-link" href="{{url($menu1->href)}}"
                              ><div>{{ $menu1['Keterangan'] }}</div></a>
                          </li>
                          @else
                          <li class="menu-item">
                            <a class="menu-link" href="{{url($menu1->href)}}"
                              ><div>{{ $menu1['Keterangan'] }}</div></a>
                              <ul class="sub-menu-container">
                                @foreach ($menu1['child'] as $menu2)
                                <li class="menu-item">
                                  <a class="menu-link" href="{{url($menu2->href)}}"
                                    ><div>{{ $menu2['Keterangan'] }}</div></a>
                                </li>
                                @endforeach
                              </ul>
                          </li>
                          @endif
                        @endforeach
                      </ul>
                    </li>
                    @endif
                  @endforeach
                  <li class="menu-item">
                    <a class="menu-link" href="{!! url('logout') !!}"><div>Logout</div></a>
                  </li>



              </nav>
              <!-- #primary-menu end -->


            </div>
          </div>
        </div>
      </header>

    <header id="header" class="full-header" style="z-index: 1">
      <div id="header-wrap">
        <!-- <div class="container"> -->
          <!-- <div class="header-row"> -->

            <nav class="navbar navbar-expand-lg navbar-dark " style="height: 39px; background-color: #5A5A5A;">
            <!-- <div class="container"> -->
               <ul class="navbar-nav mr-auto">
                   <div style="color: white" id="div_periode">
                    Username : {!! \Auth::user()->username !!}     -     Periode : {!! $periode->bulan !!} / {!! $periode->tahun !!}
                   </div>

               </ul>
               <div class="d-flex align-items-center align-middle" style="vertical-align: middle;">
                  <ul class="navbar-nav ml-auto">
                    @yield('buttons')


                  </ul>
                </div>
              </nav>
          <!-- </div> -->
        <!-- </div> -->
      </div>
    </header>




      <!-- #header end -->

      <!-- Page Title
		============================================= -->

      <!-- #page-title end -->

      <!-- Content
		============================================= -->
      --}}
      {{-- <section id="content" class="mt-3 mb-6"> - diganti div#content di bawah (area konten yang discroll). --}}
        <div class="content" id="content">
            <div id="content-dynamic" style="display:none;"></div>
            <div id="content-blade">
        <div class="content-wrap">
          <!-- <div class="container clearfix"> -->
          <div class="container-fluid px-5 clearfix">
            <div class="row gutter-40 col-mb-80">
              <!-- Post Content
						============================================= -->
            <!-- <div class="line"></div> -->
            @yield('content')
            <!-- <div class="postcontent col-lg-9">

							<div class="row">
								<div class="col-md-4 mb-5 mb-md-0 text-center">
									<div class="rounded-skill mb-0" data-color="#00ACEE" data-size="150" data-percent="90" data-width="2" data-speed="3000"><i class="icon-twitter2"></i></div>
								</div>

								<div class="col-md-4 mb-5 mb-md-0 text-center">
									<div class="rounded-skill mb-0" data-color="#3B5998" data-size="150" data-percent="75" data-width="3" data-speed="4000"><i class="icon-facebook2"></i></div>
								</div>

								<div class="col-md-4 mb-5 mb-md-0 text-center">
									<div class="rounded-skill mb-0" data-color="#EA4C89" data-size="150" data-percent="80" data-width="4" data-speed="2000"><i class="icon-dribbble2"></i></div>
								</div>
							</div>

							<div class="line"></div>

							<div class="row">
								<div class="col-sm-6 mb-5 mb-sm-0 text-center">
									<div class="rounded-skill mb-0" data-color="#DD4B39" data-size="200" data-percent="70" data-width="3" data-speed="2500">Static Text</div>
								</div>

								<div class="col-sm-6 mb-5 mb-sm-0 text-center">
									<div class="rounded-skill mb-0" data-color="#3F729B" data-size="200" data-percent="85" data-width="3" data-speed="6500">
										<div class="counter counter-inherit"><span data-from="1" data-to="85" data-refresh-interval="50" data-speed="6000"></span>%</div>
									</div>
								</div>
							</div>

							<div class="divider"><i class="icon-circle"></i></div>

							<h3>Skills</h3>

							<ul class="skills">
								<li data-percent="80">
									<span>Wordpress</span>
									<div class="progress">
										<div class="progress-percent"><div class="counter counter-inherit counter-instant"><span data-from="0" data-to="80" data-refresh-interval="30" data-speed="1100"></span>%</div></div>
									</div>
								</li>
								<li data-percent="60">
									<span>CSS3</span>
									<div class="progress">
										<div class="progress-percent"><div class="counter counter-inherit counter-instant"><span data-from="0" data-to="60" data-refresh-interval="30" data-speed="1100"></span>%</div></div>
									</div>
								</li>
								<li data-percent="90">
									<span>HTML5</span>
									<div class="progress">
										<div class="progress-percent"><div class="counter counter-inherit counter-instant"><span data-from="0" data-to="90" data-refresh-interval="30" data-speed="1100"></span>%</div></div>
									</div>
								</li>
								<li data-percent="70">
									<span>jQuery</span>
									<div class="progress">
										<div class="progress-percent"><div class="counter counter-inherit counter-instant"><span data-from="0" data-to="70" data-refresh-interval="30" data-speed="1100"></span>%</div></div>
									</div>
								</li>
							</ul>

						</div> -->


              <!-- .postcontent end -->

              <!-- Sidebar
						============================================= -->

              <!-- .sidebar end -->
            </div>
          </div>
        </div>
            </div>
        </div>
      {{-- </section> --}}
    </div>
    {{-- /.main --}}
      <!-- #content end -->

      <!-- Footer
		============================================= -->
      <!-- <footer id="footer" class="dark">
        <div class="container">
        </div>

        <div id="copyrights">
          <div class="container">
            <div class="row col-mb-30">
              <div class="col-md-6 text-center text-md-left">
                Copyrights &copy; 2020 All Rights Reserved by Canvas Inc.<br />
                <div class="copyright-links">
                  <a href="#">Terms of Use</a> / <a href="#">Privacy Policy</a>
                </div>
              </div>

              <div class="col-md-6 text-center text-md-right">
                <div
                  class="d-flex justify-content-center justify-content-md-end"
                >
                  <a
                    href="#"
                    class="social-icon si-small si-borderless si-facebook"
                  >
                    <i class="icon-facebook"></i>
                    <i class="icon-facebook"></i>
                  </a>

                  <a
                    href="#"
                    class="social-icon si-small si-borderless si-twitter"
                  >
                    <i class="icon-twitter"></i>
                    <i class="icon-twitter"></i>
                  </a>

                  <a
                    href="#"
                    class="social-icon si-small si-borderless si-gplus"
                  >
                    <i class="icon-gplus"></i>
                    <i class="icon-gplus"></i>
                  </a>

                  <a
                    href="#"
                    class="social-icon si-small si-borderless si-pinterest"
                  >
                    <i class="icon-pinterest"></i>
                    <i class="icon-pinterest"></i>
                  </a>

                  <a
                    href="#"
                    class="social-icon si-small si-borderless si-vimeo"
                  >
                    <i class="icon-vimeo"></i>
                    <i class="icon-vimeo"></i>
                  </a>

                  <a
                    href="#"
                    class="social-icon si-small si-borderless si-github"
                  >
                    <i class="icon-github"></i>
                    <i class="icon-github"></i>
                  </a>

                  <a
                    href="#"
                    class="social-icon si-small si-borderless si-yahoo"
                  >
                    <i class="icon-yahoo"></i>
                    <i class="icon-yahoo"></i>
                  </a>

                  <a
                    href="#"
                    class="social-icon si-small si-borderless si-linkedin"
                  >
                    <i class="icon-linkedin"></i>
                    <i class="icon-linkedin"></i>
                  </a>
                </div>

                <div class="clear"></div>

                <i class="icon-envelope2"></i> info@canvas.com
                <span class="middot">&middot;</span>
                <i class="icon-headphones"></i> +1-11-6541-6369
                <span class="middot">&middot;</span>
                <i class="icon-skype2"></i> CanvasOnSkype
              </div>
            </div>
          </div>
        </div>
      </footer> -->


    </div>
    <!-- #wrapper end -->

    <!-- Go To Top
	============================================= -->
    <div id="gotoTop" class="icon-angle-up"></div>

    <!-- External JavaScripts
    ============================================= -->
    <!-- JS POPPER BOOTSTRAP 5.3 -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script> -->

    <!-- JS BOOTSTRAP 5.3 -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script> -->
    
    <script src="{!! URL::asset('public/js/canvas/jquery.js') !!}"></script>
    <script src="{!! URL::asset('public/js/jquery.min.js') !!}"></script>
    <script src="{!! URL::asset('public/js/jquery-3.3.1.min.js') !!}"></script>
    <script src="{!! URL::asset('public/js/select2.min.js') !!}"></script>
    <script src="{!! URL::asset('public/js/popper.min.js') !!}"></script>
    <script src="{!! URL::asset('public/js/bootstrap.min.js') !!}"></script>
    <script src="{!! URL::asset('public/js/alertify.js') !!}"></script>
    <script src="{!! URL::asset('public/js/autoNumeric.js') !!}"></script>
    <script src="{!! URL::asset('public/js/datatables.min.js') !!}"></script>
    <script src="{!! URL::asset('public/js/jquery-ui.min.js') !!}"></script>
    <script src="{!! URL::asset('public/js/qrcode.min.js') !!}"></script>

    <!-- Footer Scripts
	============================================= -->
  <script src="{!! URL::asset('public/js/canvas/functions.js') !!}"></script>
  <script src="{!! URL::asset('public/js/canvas/JsBarcode.all.min.js') !!}"></script>

    {{-- Ditambahkan 2026-10-19: ikon + skrip sidebar. --}}
    <script src="{!! URL::asset('public/js/sidebar-icons.js') !!}?v={{ @filemtime(base_path('public/js/sidebar-icons.js')) ?: '1' }}"></script>
    {{-- Tabel daftar pola newpo (toolbar Cari + Tampilkan, halaman) - dimuat sebelum @yield('js'). --}}
    <script src="{!! URL::asset('public/js/wms-tampilan-baru.js') !!}?v={{ @filemtime(base_path('public/js/wms-tampilan-baru.js')) ?: '1' }}"></script>

    <script>
      // ── Sidebar (port dari newSML newmasterTest) ─────────────────────────
      const modules = @json($wmsMenu);
      const currentHref = @json(trim(request()->path(), '/'));
      const cardColors = ['c-blue','c-green','c-orange','c-purple','c-teal','c-pink','c-yellow','c-red','c-indigo','c-cyan'];

      const moduleIcons = [
        ['master', 'users'], ['in bound', 'package'], ['inbound', 'package'], ['out bound', 'send'],
        ['outbound', 'send'], ['gudang', 'warehouse'], ['dashboard', 'bar-chart'], ['report', 'file-text'],
        ['laporan', 'file-text'], ['setting', 'settings'], ['utilitas', 'tool'],
      ];

      const childIconMap = [
        ['gudang', 'warehouse'], ['barang', 'box'], ['periode', 'settings'], ['user', 'users'],
        ['password', 'lock'], ['barcode', 'tag'], ['penerimaan', 'package'], ['qc', 'check-square'],
        ['put away', 'map-pin'], ['retur', 'rotate-ccw'], ['terima', 'package'], ['transfer', 'repeat'],
        ['picking', 'clipboard'], ['packing', 'package'], ['spb', 'send'], ['sample', 'package'],
        ['konsi', 'package'], ['kemasan', 'layers'], ['permintaan', 'clipboard'], ['pemakaian', 'package'],
        ['opname', 'check-square'], ['inspeksi', 'check-square'], ['dashboard', 'bar-chart'],
        ['report', 'file-text'], ['laporan', 'file-text'], ['stok', 'stocks'], ['kartu', 'file-text'],
      ];

      function pickIcon(map, label) {
        const l = (label || '').toLowerCase();
        for (const [kw, ic] of map) { if (l.includes(kw)) return ic; }
        return 'box';
      }

      function urlTo(href) {
        return '{{ url('') }}/' + String(href || '').replace(/^\//, '');
      }

      function goTo(encodedHref) {
        const href = decodeURIComponent(encodedHref);
        if (href && href !== 'undefined' && href !== '' && href !== '#') window.location.href = urlTo(href);
      }

      function nodeMatchesCurrent(node) {
        const h = (node.href || '').replace(/^\/+|\/+$/g, '');
        return h !== '' && h !== '#' && h.toLowerCase() === currentHref.toLowerCase();
      }

      function subtreeHasCurrent(node) {
        return nodeMatchesCurrent(node) || (node.children || []).some(subtreeHasCurrent);
      }

      function toggleModuleSubmenu(key) {
        const ng = document.getElementById('ng-' + key);
        if (!ng) return;
        const willOpen = !ng.classList.contains('open');
        document.querySelectorAll('.nav-group.open').forEach(g => g.classList.remove('open'));
        if (willOpen) ng.classList.add('open');
      }

      function applyActiveState() {
        document.querySelectorAll('.nav-group').forEach(g => g.classList.remove('active', 'open'));
        const mod = modules.find(subtreeHasCurrent);
        if (!mod) return;
        const ng = document.getElementById('ng-' + mod.key);
        if (ng) ng.classList.add('active', 'open');
        const crumb = document.getElementById('breadcrumb');
        if (crumb) crumb.innerHTML = `<span>Beranda</span><span class="bc-sep">›</span><span>${mod.label}</span>`;

        // Halaman lama tidak punya @@section('page-title') - judul header diambil dari nama menunya.
        // (Diubah 2026-10-27: dulu tertulis tanpa "@@", Blade menjalankannya sebagai @@section sungguhan
        //  sehingga halaman /home terpotong. "@@" membuat Blade menulis teksnya saja.)
        const judul = document.getElementById('pageTitleBreadcrumb');
        if (judul && judul.textContent.trim() === '') {
          const cari = (n) => nodeMatchesCurrent(n) ? n : (n.children || []).map(cari).find(Boolean);
          const menuAktif = cari(mod);
          if (menuAktif) judul.textContent = menuAktif.label;
        }
      }

      function renderNav() {
        const nav = document.getElementById('nav');
        nav.innerHTML = modules.map(m => `
          <div class="nav-group" id="ng-${m.key}">
            <div class="nav-item" onclick="toggleModuleSubmenu('${m.key}')">
              <span class="nav-icon" onclick="event.stopPropagation(); showModuleHome('${m.key}')">${icon(pickIcon(moduleIcons, m.label))}</span>
              <span class="nav-label">${m.label}</span>
              <span class="nav-chevron">${icon('chevron')}</span>
            </div>
            <div class="nav-children">
              ${(m.children || []).map(c => {
                const hasSub = c.children && c.children.length > 0;
                const isCurrent = !hasSub && nodeMatchesCurrent(c);
                return `
                <div class="nav-child ${hasSub ? 'has-sub' : ''} ${isCurrent ? 'active-child' : ''}"
                     data-flyout-id="${hasSub ? 'flyout-' + c.key : ''}"
                     onclick="event.stopPropagation(); ${hasSub ? '' : `goTo('${encodeURIComponent(c.href || '')}')`}">
                  <span class="nav-child-label">${c.label}</span>
                  ${hasSub ? `<span class="nav-child-arrow">${icon('chevron')}</span>` : ''}
                </div>`;
              }).join('')}
            </div>
          </div>
        `).join('');

        let flyoutRoot = document.getElementById('flyout-root');
        if (!flyoutRoot) {
          flyoutRoot = document.createElement('div');
          flyoutRoot.id = 'flyout-root';
          document.body.appendChild(flyoutRoot);
        }
        flyoutRoot.innerHTML = modules.flatMap(m =>
          (m.children || []).filter(c => c.children && c.children.length > 0).map(c => `
            <div class="nav-flyout" id="flyout-${c.key}">
              ${c.children.map(sub => `
                <div class="nav-flyout-item" onclick="goTo('${encodeURIComponent(sub.href || '')}')">${sub.label}</div>
              `).join('')}
            </div>
          `)
        ).join('');

        attachFlyoutHoverHandlers();
        applyActiveState();
      }

      function attachFlyoutHoverHandlers() {
        const allFlyouts = Array.from(document.querySelectorAll('.nav-flyout'));
        const hideTimers = new Map();
        const HIDE_DELAY = 400;
        const sidebarEl = document.getElementById('sidebar');

        function syncSidebarPin() {
          const open = allFlyouts.some(f => f.classList.contains('flyout-visible'));
          sidebarEl.classList.toggle('flyout-pinned', open);
          if (!open) document.querySelectorAll('.nav-group.flyout-owner').forEach(g => g.classList.remove('flyout-owner'));
        }

        document.querySelectorAll('.nav-child.has-sub').forEach(rowEl => {
          const flyoutEl = document.getElementById(rowEl.getAttribute('data-flyout-id'));
          if (!flyoutEl) return;

          function showFlyout() {
            clearTimeout(hideTimers.get(flyoutEl));
            allFlyouts.forEach(f => { if (f !== flyoutEl) { clearTimeout(hideTimers.get(f)); f.classList.remove('flyout-visible'); } });
            const ownerGroup = rowEl.closest('.nav-group');
            if (ownerGroup) ownerGroup.classList.add('flyout-owner');
            const rect = rowEl.getBoundingClientRect();
            const w = flyoutEl.offsetWidth || 220;
            const h = flyoutEl.offsetHeight || 0;
            let left = rect.right - 6;
            let top = rect.top;
            if (left + w > window.innerWidth) left = rect.left - w + 6;
            if (top + h > window.innerHeight) top = Math.max(8, window.innerHeight - h - 8);
            flyoutEl.style.left = left + 'px';
            flyoutEl.style.top = top + 'px';
            flyoutEl.classList.add('flyout-visible');
            syncSidebarPin();
          }

          function scheduleHide() {
            hideTimers.set(flyoutEl, setTimeout(() => { flyoutEl.classList.remove('flyout-visible'); syncSidebarPin(); }, HIDE_DELAY));
          }

          rowEl.addEventListener('mouseenter', showFlyout);
          rowEl.addEventListener('mouseleave', scheduleHide);
          flyoutEl.addEventListener('mouseenter', () => clearTimeout(hideTimers.get(flyoutEl)));
          flyoutEl.addEventListener('mouseleave', scheduleHide);
        });
      }

      // ── Beranda modul: kartu menu (klik ikon modul) ─────────────────────
      let activeModuleKey = null;
      let moduleViewStack = [];

      function showModuleHome(key) {
        if (!modules.find(m => m.key === key)) return;
        activeModuleKey = key;
        moduleViewStack = [];
        document.querySelectorAll('.nav-group').forEach(g => g.classList.remove('active'));
        const ng = document.getElementById('ng-' + key);
        if (ng) ng.classList.add('active');
        renderModuleView();
      }

      function moduleDrillInto(childKey) {
        const parent = moduleViewStack[moduleViewStack.length - 1] || modules.find(m => m.key === activeModuleKey);
        const node = (parent.children || []).find(c => c.key === childKey);
        if (node) { moduleViewStack.push(node); renderModuleView(); }
      }

      function moduleGoBack() {
        if (moduleViewStack.length > 0) { moduleViewStack.pop(); renderModuleView(); }
        else goHome();
      }

      function renderModuleView() {
        const mod = modules.find(m => m.key === activeModuleKey);
        if (!mod) return;
        const currentNode = moduleViewStack[moduleViewStack.length - 1] || mod;
        const trail = [mod.label].concat(moduleViewStack.map(n => n.label));
        document.getElementById('breadcrumb').innerHTML = '<span>Beranda</span><span class="bc-sep">›</span>' +
          trail.map((l, i) => i === trail.length - 1 ? `<b>${l}</b>` : `<span>${l}</span><span class="bc-sep">›</span>`).join('');

        const cards = (currentNode.children || []).map((c, i) => {
          const color = cardColors[i % cardColors.length];
          const hasSub = c.children && c.children.length > 0;
          const action = hasSub ? `moduleDrillInto('${c.key}')` : `goTo('${encodeURIComponent(c.href || '')}')`;
          return `
            <div class="mod-card" onclick="${action}">
              <div class="mod-card-icon ${color}">${icon(pickIcon(childIconMap, c.label))}</div>
              <div class="mod-card-label">${c.label}</div>
              ${hasSub ? `<span class="mod-card-arrow">${icon('chevron')}</span>` : ''}
            </div>`;
        }).join('');

        document.getElementById('content-blade').style.display = 'none';
        const dyn = document.getElementById('content-dynamic');
        dyn.style.display = 'block';
        dyn.innerHTML = `
          <button class="mod-back-btn" onclick="moduleGoBack()">${icon('arrow-left')} Kembali</button>
          <div class="mod-page-title">${currentNode.label}</div>
          <div class="mod-page-subtitle"></div>
          <div class="mod-card-grid">${cards}</div>`;
      }

      function goHome() {
        activeModuleKey = null;
        moduleViewStack = [];
        const dyn = document.getElementById('content-dynamic');
        dyn.style.display = 'none';
        dyn.innerHTML = '';
        document.getElementById('content-blade').style.display = 'block';
        document.getElementById('breadcrumb').innerHTML = '<span>Beranda</span>';
        applyActiveState();
      }

      renderNav();
    </script>
  <!-- <script type="text/javascript" src="http://www.example.co.uk/assets/js/autoNumeric.js"></script> -->

    <script type="text/javascript">
      // document.onkeydown = function(e) {
      //   if(event.keyCode == 123) { return false; }
      //   if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){ return false; }
      //   if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){ return false; }
      //   if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){ return false; }
      // }
      // $("button").addClass('btn-sm'); $(".form-control").addClass('form-control-sm');
      // $(document).on('hidden.bs.modal', '.modal', function () { $('.modal:visible').length && $(document.body).addClass('modal-open'); });
      // $('.modal').modal({ show: false, keyboard: false, backdrop: 'static' }); $("title").html($("#title_page").html());
      // $(function () { $('[data-toggle="tooltip"]').tooltip() }); $("[rel='tooltip']").tooltip();
      //
      // function numberWithCommas(n) { var parts=n.toString().split("."); return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : ""); }
      // function toInteger(n) { return parseInt(n.replace(/,/g, "")); } function toFloat(n) { return parseFloat(n.replace(/,/g, "")); }
      // function round(value, precision) { var multiplier = Math.pow(10, precision || 0); return Math.round(value * multiplier) / multiplier; } function middleTD() {}
      // function format_date(date) { if (date == "" || date == null) { return ""; } return date.split("-")[2] + "/" + date.split("-")[1] + "/" + date.split("-")[0]; }
      // function format_timestamp(date) { if (date == "" || date == null) return ""; tgl = date.split(" ")[0]; waktu = date.split(" ")[1]; return tgl.split("-")[2] + "/" + tgl.split("-")[1] + "/" + tgl.split("-")[0] + " " + waktu; }
      // $(function() {
      //     new AutoNumeric('.format-Rp', {
      //         currencySymbol : ' Rp',
      //         decimalCharacter : ',',
      //         digitGroupSeparator : '.',
      //     });
      // });

      // function buttonLogoutMaster () {
      //   console.log(window.location.origin)
      //   windows.location.redirect = http://wmsystem.saranaprimalestari.com:8081/ajcwms/home
      //
      // }
    </script>
    <script type="text/javascript">
      // Ditambahkan 2026-10-20: beberapa halaman (Gudang, Barcode Barang, dll.) memanggil middleTD() saat
      // Refresh, tapi definisinya ikut terkomentari di atas sehingga Refresh berhenti dengan error.
      // Fungsi kosong yang sama dengan versi lama; halaman yang punya middleTD sendiri tetap memakai miliknya.
      if (typeof window.middleTD !== 'function') { window.middleTD = function () {} }
    </script>
    @include('partials.ajax-error-logger')
    @yield('js')
  </body>
</html>
