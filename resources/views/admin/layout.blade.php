<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>

        @yield('title', 'PROCAFES | Administración')

    </title>

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Google Fonts --}}
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>

        :root{

            --primary:#C89B3C;
            --primary-dark:#8B5A20;
            --secondary:#F7F3EA;
            --sidebar:#2E2216;
            --sidebar-hover:#453222;
            --sidebar-active:#D8AE4A;
            --text:#2F2F2F;
            --border:#ECECEC;
            --card:#FFFFFF;
            --background:#F5F6FA;

            --radius:18px;

            --shadow:0 10px 30px rgba(0,0,0,.08);

        }

        *{

            margin:0;
            padding:0;
            box-sizing:border-box;

        }

        html,
        body{

            height:100%;

        }

        body{

            font-family:'Poppins',sans-serif;

            background:var(--background);

            color:var(--text);

            overflow-x:hidden;

        }

        a{

            text-decoration:none;

        }

        /*=========================
            TOPBAR
        =========================*/

        .admin-topbar{

            height:72px;

            background:#FFFFFF;

            border-bottom:1px solid var(--border);

            display:flex;

            align-items:center;

            justify-content:space-between;

            padding:0 30px;

            position:sticky;

            top:0;

            z-index:1000;

            box-shadow:0 3px 12px rgba(0,0,0,.04);

        }

        .logo{

            display:flex;

            align-items:center;

            gap:14px;

        }

        .logo img{

            width:45px;

            height:45px;

            object-fit:contain;

        }

        .logo h4{

            margin:0;

            font-weight:700;

            color:var(--primary-dark);

        }

        .logo small{

            display:block;

            color:#777;

            font-size:13px;

        }

        .top-actions{

            display:flex;

            align-items:center;

            gap:15px;

        }

        .btn-store{

            border:none;

            background:var(--primary);

            color:#fff;

            border-radius:12px;

            padding:10px 18px;

            font-weight:600;

            transition:.25s;

        }

        .btn-store:hover{

            background:var(--primary-dark);

        }

        .btn-dashboard{

            border:1px solid var(--border);

            background:#fff;

            border-radius:12px;

            padding:10px 18px;

            color:#555;

            transition:.25s;

        }

        .btn-dashboard:hover{

            background:#F4F4F4;

        }

        .btn-logout{

            border:none;

            background:#E53935;

            color:#fff;

            border-radius:12px;

            padding:10px 18px;

            transition:.25s;

        }

        .btn-logout:hover{

            background:#C62828;

        }

        /*=========================
            CONTENIDO
        =========================*/

        .page-content{

            padding:30px;

        }

        .content-card{

            background:#fff;

            border-radius:var(--radius);

            box-shadow:var(--shadow);

            border:none;

        }

        /*=========================
            SIDEBAR
        ==========================*/

        .admin-sidebar{

            min-height:calc(100vh - 72px);

            background:var(--sidebar);

            padding:30px 18px;

        }

        .sidebar-header{

            text-align:center;

            margin-bottom:35px;

        }

        .sidebar-avatar{

            width:90px;

            height:90px;

            border-radius:50%;

            object-fit:cover;

            border:4px solid rgba(255,255,255,.12);

            margin-bottom:15px;

        }

        .sidebar-header h6{

            color:#fff;

            margin-bottom:4px;

            font-weight:600;

        }

        .sidebar-header small{

            color:#C8C8C8;

        }

        .sidebar-menu{

            display:flex;

            flex-direction:column;

            gap:8px;

        }

        .sidebar-menu a{

            color:#DDD;

            padding:14px 18px;

            border-radius:14px;

            display:flex;

            align-items:center;

            gap:14px;

            transition:.30s;

            font-weight:500;

        }

        .sidebar-menu a i{

            font-size:20px;

        }

        .sidebar-menu a:hover{

            background:var(--sidebar-hover);

            color:#FFF;

        }

        .sidebar-menu a.active{

            background:var(--sidebar-active);

            color:#2E2216;

            font-weight:700;

        }

        /*=========================================
            CONTENIDO
        =========================================*/

        .page-content{

            background:#F5F6FA;

            min-height:calc(100vh - 72px);

            padding:35px;

        }

        .page-header{

            display:flex;

            justify-content:space-between;

            align-items:center;

            margin-bottom:30px;

        }

        .page-title{

            font-size:28px;

            font-weight:700;

            color:#333;

            margin:0;

        }

        .page-subtitle{

            color:#8A8A8A;

            margin-top:5px;

            font-size:15px;

        }

        /*=========================================
            TARJETAS
        =========================================*/

        .content-card{

            background:#FFFFFF;

            border:none;

            border-radius:18px;

            box-shadow:0 10px 25px rgba(0,0,0,.05);

            padding:25px;

        }

        .card-title-admin{

            font-size:18px;

            font-weight:600;

            color:#444;

            margin-bottom:20px;

        }

        /*=========================================
            BREADCRUMB
        =========================================*/

        .breadcrumb-admin{

            display:flex;

            align-items:center;

            gap:8px;

            margin-bottom:25px;

            font-size:14px;

            color:#888;

        }

        .breadcrumb-admin a{

            color:var(--primary-dark);

            text-decoration:none;

        }

        .breadcrumb-admin i{

            font-size:12px;

        }

        /*=========================================
            FOOTER
        =========================================*/

        .admin-footer{

            margin-top:40px;

            padding:20px;

            text-align:center;

            color:#999;

            font-size:14px;

        }

    </style>

    @stack('styles')

</head>

<body>

    {{-- ===============================
        TOPBAR
    ================================ --}}

    <header class="admin-topbar">

        <div class="logo">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="PROCAFES">

            <div>

                <h4>

                    PROCAFES

                </h4>

                <small>

                    Panel Administrativo

                </small>

            </div>

        </div>

        <div class="top-actions">

            <a
                href="{{ route('home') }}"
                target="_blank"
                class="btn btn-store">

                <i class="bi bi-shop me-2"></i>

                Ver tienda

            </a>

            <form
                action="{{ route('logout') }}"
                method="POST">

                @csrf

                <button
                    class="btn btn-logout">

                    <i class="bi bi-box-arrow-right me-2"></i>

                    Salir

                </button>

            </form>

        </div>

    </header>

    {{-- ===============================
        CONTENEDOR
    ================================ --}}

    <div class="container-fluid">

        <div class="row">

            {{-- ===============================
                SIDEBAR
            ================================ --}}

            <aside class="col-lg-2 col-md-3 admin-sidebar">

                <div class="sidebar-header">

                    <img
                        src="{{ asset('images/avatar_admin.png') }}"
                        class="sidebar-avatar"
                        alt="Administrador">

                    <h6>

                        {{ auth()->user()->nombre_completo ?? auth()->user()->name }}

                    </h6>

                    <small>

                        Administrador

                    </small>

                </div>

                <nav class="sidebar-menu">

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                        <i class="bi bi-speedometer2"></i>

                        Dashboard

                    </a>

                    <a
                        href="{{ route('admin.products.index') }}"
                        class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">

                        <i class="bi bi-box-seam"></i>

                        Productos

                    </a>

                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">

                        <i class="bi bi-tags"></i>

                        Categorías

                    </a>

                    <a
                        href="{{ route('admin.brands.index') }}"
                        class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">

                        <i class="bi bi-award"></i>

                        Marcas

                    </a>

                    <a
                        href="{{ route('admin.users.index') }}"
                        class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">

                        <i class="bi bi-people"></i>

                        Usuarios

                    </a>

                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">

                        <i class="bi bi-bag-check"></i>

                        Pedidos

                    </a>

                    <a
                        href="{{ route('admin.billing.index') }}"
                        class="{{ request()->routeIs('admin.billing.*') ? 'active' : '' }}">

                        <i class="bi bi-receipt"></i>

                        Facturación

                    </a>

                </nav>

            </aside>

            {{-- ===============================
                CONTENIDO
            ================================ --}}

            <main class="col-lg-10 col-md-9 page-content">

        <div class="page-header">

            <div>

                <h2 class="page-title">

                    @yield('title','Panel Administrativo')

                </h2>

                <div class="page-subtitle">

                    Bienvenido nuevamente,
                    <strong>{{ auth()->user()->nombre_completo ?? auth()->user()->name }}</strong>

                </div>

            </div>

            <div class="text-end">

                <small class="text-muted">

                    {{ now()->translatedFormat('l d \\d\\e F \\d\\e Y') }}

                </small>

            </div>

        </div>

        <div class="breadcrumb-admin">

            <a href="{{ route('admin.dashboard') }}">

                Inicio

            </a>

            <i class="bi bi-chevron-right"></i>

            <span>

                @yield('title','Dashboard')

            </span>

        </div>

        <div class="content-card">

            @yield('content')

        </div>

        <div class="admin-footer">

            © {{ date('Y') }}

            <strong>PROCAFES</strong>

            · Panel Administrativo

        </div>

    </main>

            