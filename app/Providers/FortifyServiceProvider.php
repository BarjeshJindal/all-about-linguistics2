<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;          // ← missing import
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // No bindings needed here.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /* -------------------------------------------------
         | Fortify feature classes
         |------------------------------------------------- */
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        /* -------------------------------------------------
         | Rate limiting
         |------------------------------------------------- */
        RateLimiter::for('login', fn (Request $r) =>
            Limit::perMinute(5)->by(
                Str::transliterate(Str::lower($r->input(Fortify::username())) . '|' . $r->ip())
            )
        );

        RateLimiter::for('two-factor', fn (Request $r) =>
            Limit::perMinute(5)->by($r->session()->get('login.id'))
        );

        /* -------------------------------------------------
         | Login & register views
         |------------------------------------------------- */


   Fortify::loginView(function () {
                return view('auth.login'); // Use the same login view
            });

      // Handle authentication based on route
            Fortify::authenticateUsing(function (Request $request) {
                $isAdminRoute = request()->is('admin/*'); // Check if URL is /admin/login
   
                if ($isAdminRoute) {
                    // Admin Login
                    $admin = Admin::where('email', $request->email)->first();
                    if ($admin && Hash::check($request->password, $admin->password)) {
                        Auth::guard('admin')->login($admin);
                        
                        return $admin;
                    }
                } else {
                    // User Login
                    $user = User::where('email', $request->email)->first();
                    if ($user && Hash::check($request->password, $user->password)) {
                        return $user;
                    }
                }
       
                return null;
            });


            
            
       
         
    }
}
