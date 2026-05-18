<section>
    <div class="card-header">
        <h2 class="card-title">Eliminar Cuenta</h2>
        <p class="card-description">
            Una vez que se elimine tu cuenta, todos sus recursos y datos se eliminarán permanentemente. 
            Antes de eliminar tu cuenta, descarga cualquier dato o información que desees conservar.
        </p>
    </div>

    <button
        type="button"
        class="btn-danger"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Eliminar Cuenta</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <div class="modal-content">
                <h2 class="modal-title">¿Estás seguro de que quieres eliminar tu cuenta?</h2>

                <p class="modal-text">
                    Una vez que se elimine tu cuenta, todos sus recursos y datos se eliminarán permanentemente. 
                    Por favor ingresa tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.
                </p>

                <div class="form-group">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-input"
                        placeholder="Contraseña"
                    />
                    @error('password', 'userDeletion')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="btn-container" style="justify-content: flex-end;">
                    <button type="button" class="btn-secondary" x-on:click="$dispatch('close')">
                        Cancelar
                    </button>

                    <button type="submit" class="btn-danger">
                        Eliminar Cuenta
                    </button>
                </div>
            </div>
        </form>
    </x-modal>
</section>
