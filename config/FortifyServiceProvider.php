<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Registro de un nuevo usuario
        Fortify::createUsersUsing(CreateNewUser::class);

        // Actualizar la contraseña del usuario
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);

        // Actualizar la información del perfil del usuario
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);

        // Restablecer la contraseña del usuario
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Personaliza el login usando email
        Fortify::authenticateUsing(function (\Illuminate\Http\Request $request) {
            $user = \App\Models\User::where('email', $request->email)->first();

            if ($user && \Hash::check($request->password, $user->password)) {
                return $user;
            }
        });
    }
}
