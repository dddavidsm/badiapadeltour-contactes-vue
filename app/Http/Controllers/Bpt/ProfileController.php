<?php

namespace App\Http\Controllers\Bpt;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile dashboard.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $pedidos = $user->pedidos()->orderBy('created_at', 'desc')->get();
        $reservas = $user->reservas()->with(['pista.complejo'])->orderBy('fecha_reserva', 'desc')->get();
        
        return view('profile.show', [
            'user' => $user,
            'pedidos' => $pedidos,
            'reservas' => $reservas,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('dashboard')->with('status', 'profile-updated');
    }

    /**
     * Update the user's body measurements and sports profile.
     */
    public function updateMedidas(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'talla_pie' => 'nullable|integer|min:35|max:48',
            'talla_camiseta' => 'nullable|string|in:XS,S,M,L,XL,XXL',
            'talla_pantalon' => 'nullable|string|in:XS,S,M,L,XL,XXL',
            'altura' => 'nullable|numeric|min:120|max:250',
            'peso' => 'nullable|numeric|min:30|max:200',
            'nivel_juego' => 'nullable|string|in:principiante,intermedio,avanzado,profesional',
            'mano_dominante' => 'nullable|string|in:diestra,zurda',
        ]);

        $request->user()->fill($validated);
        $request->user()->save();

        return Redirect::route('dashboard')->with('status', 'medidas-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
