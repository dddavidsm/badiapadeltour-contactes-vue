<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contacto - Badia Padel Tour</title>
    <link rel="icon" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --electric: #c9ff00; --black: #0f0f0f; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Gopher', 'Inter', sans-serif; color: #fff; background: #0f0f0f; }
        .container { width: min(1200px, calc(100% - 96px)); margin: 0 auto; }
        
        .contacto-section { padding: 80px 0 60px; }
        .contacto-title { margin: 0 0 56px; font-size: 64px; color: var(--electric); font-weight: 800; letter-spacing: -0.02em; line-height: 1.1; }
        
        .contacto-content { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; margin-bottom: 60px; }
        
        .contacto-info { display: flex; flex-direction: column; gap: 32px; }
        .info-item { display: flex; align-items: flex-start; gap: 16px; }
        .info-icon { width: 32px; height: 32px; flex-shrink: 0; filter: brightness(0) saturate(100%) invert(92%) sepia(93%) saturate(1352%) hue-rotate(359deg) brightness(119%) contrast(106%); }
        .info-content { }
        .info-title { margin: 0 0 8px; font-size: 20px; font-weight: 800; color: #fff; }
        .info-text { margin: 0; font-size: 16px; font-weight: 500; color: #999; line-height: 1.5; }
        
        .contacto-form { background: #1a1a1a; border-radius: 14px; padding: 40px; }
        .form-title { margin: 0 0 28px; font-size: 22px; font-weight: 800; color: #fff; text-transform: uppercase; letter-spacing: 0.02em; }
        .form-group { margin-bottom: 20px; }
        .form-input { width: 100%; background: #2a2a2a; border: none; border-radius: 6px; padding: 14px 16px; font-size: 15px; color: #fff; font-family: 'Gopher', 'Inter', sans-serif; outline: none; transition: background 0.2s; }
        .form-input::placeholder { color: #666; }
        .form-input:focus { background: #333; }
        .form-textarea { min-height: 120px; resize: vertical; }
        .form-submit { background: var(--electric); color: #000; border: none; border-radius: 8px; padding: 14px 0; font-weight: 800; font-size: 14px; width: 100%; text-transform: uppercase; letter-spacing: 0.02em; cursor: pointer; transition: background 0.2s; }
        .form-submit:hover { background: #b8ee00; }
        
        .mapa-section { width: 100%; height: 480px; border-radius: 14px; overflow: hidden; margin-bottom: 60px; }
        .mapa-section iframe { width: 100%; height: 100%; border: none; }
        
        @media (max-width: 960px) {
            .contacto-content { grid-template-columns: 1fr; gap: 40px; }
            .contacto-title { font-size: 48px; }
        }
        
        @media (max-width: 640px) {
            .container { width: calc(100% - 48px); }
            .contacto-title { font-size: 40px; }
            .contacto-form { padding: 28px; }
            .mapa-section { height: 320px; }
        }
    </style>
</head>
<body>
    @include('components.header')

    <section class="contacto-section">
        <div class="container">
            <h1 class="contacto-title">Contacto</h1>
            
            <div class="contacto-content">
                <div class="contacto-info">
                    <div class="info-item">
                        <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 00-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z"/>
                        </svg>
                        <div class="info-content">
                            <h3 class="info-title">Llámanos</h3>
                            <p class="info-text">+ 41 76 622 14 73</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        <div class="info-content">
                            <h3 class="info-title">Escríbenos</h3>
                            <p class="info-text">helo@badiapadeltour.com</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        <div class="info-content">
                            <h3 class="info-title">Visítanos</h3>
                            <p class="info-text">Carrer Eivissa, Barcelona, 00831</p>
                        </div>
                    </div>
                </div>
                
                <div class="contacto-form">
                    <h2 class="form-title">Get In Touch</h2>
                    
                    @if ($message = Session::get('success'))
                        <div style="background-color: #4CAF50; color: white; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; text-align: center;">
                            {{ $message }}
                        </div>
                    @endif

                    @if ($message = Session::get('error'))
                        <div style="background-color: #f44336; color: white; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; text-align: center;">
                            {{ $message }}
                        </div>
                    @endif
                    
                    <form action="{{ route('contacto.enviar') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="name" class="form-input" placeholder="Name" required>
                        </div>
                        <div class="form-group">
                            <input type="e   
                            
                            
                            mail" name="email" class="form-input" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" class="form-input form-textarea" placeholder="Message" required></textarea>
                        </div>
                        <button type="submit" class="form-submit">Enviar</button>
                    </form>
                </div>
            </div>
            
            <div class="mapa-section">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2989.5873925476954!2d2.0922344156740794!3d41.50019887925355!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12a4959f0e8c5c4d%3A0x6c5f0c7e0c7e0c7e!2sBadia%20del%20Vall%C3%A8s%2C%20Barcelona!5e0!3m2!1sen!2ses!4v1609459200000!5m2!1sen!2ses" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    @include('components.footer')
</body>
</html>