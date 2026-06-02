<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') — {{ config('app.name', 'Helpdesk SaaS') }}</title>

    <!-- Google Fonts — Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 1rem;
        }
        .auth-brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-brand .brand-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
            margin-bottom: .75rem;
            box-shadow: 0 8px 25px rgba(59,130,246,.35);
        }
        .auth-brand h2 {
            color: #fff;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: .25rem;
        }
        .auth-brand p {
            color: #94a3b8;
            font-size: .875rem;
        }
        .auth-card {
            background: #fff;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,.25);
        }
        .auth-card .form-label {
            font-weight: 500;
            font-size: .85rem;
            color: #374151;
        }
        .auth-card .form-control {
            border-radius: 10px;
            border: 1px solid #d1d5db;
            padding: .6rem 1rem;
            font-size: .875rem;
        }
        .auth-card .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,.15);
        }
        .auth-card .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            border-radius: 10px;
            padding: .65rem 1.5rem;
            font-weight: 600;
            font-size: .9rem;
            width: 100%;
        }
        .auth-card .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 4px 15px rgba(37,99,235,.4);
        }
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #94a3b8;
            font-size: .85rem;
        }
        .auth-footer a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 500;
        }
        .auth-footer a:hover {
            color: #93bbfc;
        }
        .input-icon-wrapper {
            position: relative;
        }
        .input-icon-wrapper i {
            position: absolute;
            left: .85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: .85rem;
        }
        .input-icon-wrapper .form-control {
            padding-left: 2.5rem;
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <!-- Brand -->
        <div class="auth-brand">
            <div class="brand-icon">
                <i class="fas fa-headset"></i>
            </div>
            <h2>{{ config('app.name', 'Helpdesk SaaS') }}</h2>
            <p>@yield('subtitle', 'Sign in to your account')</p>
        </div>

        <!-- Auth Card -->
        <div class="auth-card">
            @if(session('status'))
                <div class="alert alert-success mb-3" style="font-size:.85rem;border-radius:10px;">
                    {{ session('status') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mb-3" style="font-size:.85rem;border-radius:10px;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </div>

        @yield('footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
