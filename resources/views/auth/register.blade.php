<x-guest-layout>
    <h2 class="text-center mb-10" style="color: #C9FF00; font-size: 2.75rem; font-weight: 800; letter-spacing: -0.02em;">Crear Cuenta</h2>

    <form id="register-form" method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nombre -->
        <div class="mb-6">
            <label for="name" class="block font-bold mb-2" style="color: #C9FF00; font-size: 1rem;">Nombre</label>
            <input id="name" 
                   class="block w-full rounded-xl focus:outline-none focus:ring-2 focus:ring-[#C9FF00]" 
                   style="background-color: #e8e8f0; color: #000000; border: none; padding: 1rem 1.25rem; font-size: 1rem;"
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   autocomplete="given-name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Apellidos -->
        <div class="mb-6">
            <label for="apellidos" class="block font-bold mb-2" style="color: #C9FF00; font-size: 1rem;">Apellidos</label>
            <input id="apellidos" 
                   class="block w-full rounded-xl focus:outline-none focus:ring-2 focus:ring-[#C9FF00]" 
                   style="background-color: #e8e8f0; color: #000000; border: none; padding: 1rem 1.25rem; font-size: 1rem;"
                   type="text" 
                   name="apellidos" 
                   value="{{ old('apellidos') }}" 
                   required 
                   autocomplete="family-name" />
            <x-input-error :messages="$errors->get('apellidos')" class="mt-2" />
        </div>

        <!-- DNI -->
        <div class="mb-6">
            <label for="dni" class="block font-bold mb-2" style="color: #C9FF00; font-size: 1rem;">DNI</label>
            <input id="dni" 
                   class="block w-full rounded-xl focus:outline-none focus:ring-2 focus:ring-[#C9FF00]" 
                   style="background-color: #e8e8f0; color: #000000; border: none; padding: 1rem 1.25rem; font-size: 1rem;"
                   type="text" 
                   name="dni" 
                   value="{{ old('dni') }}" 
                   required />
            <x-input-error :messages="$errors->get('dni')" class="mt-2" />
        </div>

        <!-- Teléfono -->
        <div class="mb-6">
            <label for="telefono" class="block font-bold mb-2" style="color: #C9FF00; font-size: 1rem;">Teléfono</label>
            <input id="telefono" 
                   class="block w-full rounded-xl focus:outline-none focus:ring-2 focus:ring-[#C9FF00]" 
                   style="background-color: #e8e8f0; color: #000000; border: none; padding: 1rem 1.25rem; font-size: 1rem;"
                   type="tel" 
                   name="telefono" 
                   value="{{ old('telefono') }}" 
                   required 
                   autocomplete="tel" />
            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
        </div>

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
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block font-bold mb-2" style="color: #C9FF00; font-size: 1rem;">Confirmar Contraseña</label>
            <input id="password_confirmation" 
                   class="block w-full rounded-xl focus:outline-none focus:ring-2 focus:ring-[#C9FF00]" 
                   style="background-color: #e8e8f0; color: #000000; border: none; padding: 1rem 1.25rem; font-size: 1rem;"
                   type="password"
                   name="password_confirmation" 
                   required 
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Botón Registrarse -->
        <button type="submit" 
                class="w-full rounded-xl font-black transition-all hover:opacity-90 mb-8"
                style="background-color: #C9FF00; color: #000000; padding: 1rem; font-size: 1rem; letter-spacing: 0.05em; text-transform: uppercase;">
            CREAR CUENTA
        </button>

        <!-- Separador y Login -->
        <div class="text-center pt-6 mt-6" style="border-top: 1px solid #444444;">
            <p class="text-sm mb-4" style="color: #999999;">¿Ya tienes una cuenta?</p>
            <a class="text-sm hover:opacity-80 transition-opacity font-semibold" 
               style="color: #C9FF00;"
               href="{{ route('login') }}">
                Inicia sesión aquí
            </a>
        </div>
    </form>
</x-guest-layout>

<script>
    const form = document.getElementById('register-form');
    form.addEventListener('submit', function (e) {
        const dni = document.getElementById('dni').value.trim();
        const telefono = document.getElementById('telefono').value.trim();
        const email = document.getElementById('email').value.trim();

        const dniRegex = /^[0-9]{8}[A-Za-z]$/;
        const telefonoRegex = /^[6789][0-9]{8}$/;
        const emailRegex = /^[\w.-]+@[\w.-]+\.[A-Za-z]{2,}$/;

        const errores = [];
        if (!dniRegex.test(dni)) errores.push('DNI inválido. Formato: 8 dígitos y una letra (ej: 12345678Z).');
        if (!telefonoRegex.test(telefono)) errores.push('Teléfono inválido. Debe tener 9 dígitos y empezar por 6, 7, 8 o 9.');
        if (!emailRegex.test(email)) errores.push('Correo electrónico inválido.');

        if (errores.length) {
            e.preventDefault();
            alert(errores.join('\n'));
        }
    });
</script>