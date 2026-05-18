<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar Perfil | {{ config('app.name', 'BPT') }}</title>
    <link rel="icon" href="/favicon.png">
    
    <!-- Fonts Gopher -->
    <style>
        @font-face {
            font-family: 'Gopher';
            src: url('/fonts/Gopher/Gopher-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Gopher';
            src: url('/fonts/Gopher/Gopher-Bold.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        
        body {
            font-family: 'Gopher', sans-serif;
            background-color: #111111;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        
        .profile-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 60px 24px;
        }
        
        .profile-title {
            color: #C9FF00;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 40px;
            letter-spacing: -0.02em;
            text-align: center;
        }
        
        .profile-card {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 16px;
            padding: 40px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }
        
        .card-header {
            margin-bottom: 24px;
        }
        
        .card-title {
            color: #C9FF00;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.01em;
        }
        
        .card-description {
            color: #999999;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            color: #C9FF00;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 8px;
        }
        
        .form-input {
            width: 100%;
            background-color: #e8e8f0;
            color: #000000;
            border: none;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 1rem;
            font-family: 'Gopher', sans-serif;
            transition: all 0.2s;
        }
        
        .form-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #C9FF00;
        }
        
        .form-error {
            color: #ff4444;
            font-size: 0.875rem;
            margin-top: 6px;
        }
        
        .form-success {
            color: #C9FF00;
            font-size: 0.875rem;
            margin-left: 12px;
        }
        
        .btn-container {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        
        .btn-primary {
            background-color: #C9FF00;
            color: #000000;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background-color: transparent;
            color: #ff4444;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: 2px solid #ff4444;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-danger:hover {
            background-color: #ff4444;
            color: #ffffff;
        }
        
        .btn-secondary {
            background-color: #2a2a2a;
            color: #ffffff;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-secondary:hover {
            background-color: #3a3a3a;
        }
        
        .verification-notice {
            background-color: #2a2a2a;
            border-left: 4px solid #C9FF00;
            padding: 16px;
            border-radius: 8px;
            margin-top: 12px;
        }
        
        .verification-notice p {
            color: #ffffff;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .verification-notice button {
            color: #C9FF00;
            text-decoration: underline;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-overlay.show {
            display: flex;
        }
        
        .modal-content {
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 16px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
        }
        
        .modal-title {
            color: #C9FF00;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 16px;
        }
        
        .modal-text {
            color: #999999;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 24px;
        }
        
        @media (max-width: 768px) {
            .profile-title {
                font-size: 2rem;
            }
            
            .profile-card {
                padding: 24px;
            }
            
            .btn-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn-primary,
            .btn-danger,
            .btn-secondary {
                width: 100%;
                text-align: center;
            }
        }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-header />

    <div class="profile-container">
        <h1 class="profile-title">Editar Perfil</h1>
        
        <!-- Información del Perfil -->
        <div class="profile-card">
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- Actualizar Contraseña -->
        <div class="profile-card">
            @include('profile.partials.update-password-form')
        </div>

        <!-- Eliminar Cuenta -->
        <div class="profile-card">
            @include('profile.partials.delete-user-form')
        </div>
    </div>

    <x-footer />
</body>
</html>
