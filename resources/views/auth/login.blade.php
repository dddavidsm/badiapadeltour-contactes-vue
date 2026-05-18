<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <h2 class="text-center mb-10" style="color: #C9FF00; font-size: 2.75rem; font-weight: 800; letter-spacing: -0.02em;">Iniciar Sesión</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-6">
            <label for="email" class="block font-bold mb-2" style="color: #C9FF00; font-size: 1rem;">Email</label>
            <input id="email" 
                   class="block w-full rounded-xl focus:outline-none focus:ring-2 focus:ring-[#C9FF00]" 
                   style="background-color: #e8e8f0; color: #000000; border: none; padding: 1rem 1.25rem; font-size: 1rem;"
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block font-bold mb-2" style="color: #C9FF00; font-size: 1rem;">Contraseña</label>
            <input id="password" 
                   class="block w-full rounded-xl focus:outline-none focus:ring-2 focus:ring-[#C9FF00]" 
                   style="background-color: #e8e8f0; color: #000000; border: none; padding: 1rem 1.25rem; font-size: 1rem;"
                   type="password"
                   name="password"
                   required 
                   autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me y Forgot Password -->
        <div class="flex items-center justify-between mb-6">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" 
                       type="checkbox" 
                       class="rounded border-[#555555] text-[#C9FF00] focus:ring-[#C9FF00] focus:ring-offset-0" 
                       style="background-color: #e8e8f0;"
                       name="remember">
                <span class="ms-2 text-sm" style="color: #ffffff;">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm hover:opacity-80 transition-opacity" 
                   style="color: #C9FF00; font-weight: 600;"
                   href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <!-- Botón Iniciar Sesión -->
        <button type="submit" 
                class="w-full rounded-xl font-black transition-all hover:opacity-90 mb-8"
                style="background-color: #C9FF00; color: #000000; padding: 1rem; font-size: 1rem; letter-spacing: 0.05em; text-transform: uppercase;">
            INICIAR SESIÓN
        </button>

        <!-- Separador y Registro -->
        <div class="text-center pt-6 mt-6" style="border-top: 1px solid #444444;">
            <p class="text-sm mb-5" style="color: #999999;">¿No tienes una cuenta?</p>
            <a href="{{ route('register') }}" 
               class="block w-full px-6 py-3 rounded-xl font-bold transition-all border-2"
               style="background-color: transparent; color: #C9FF00; border-color: #C9FF00; letter-spacing: 0.02em;"
               onmouseover="this.style.backgroundColor='#C9FF00'; this.style.color='#000000';"
               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#C9FF00';">
                Crear Cuenta
            </a>
        </div>
    </form>
</x-guest-layout>
