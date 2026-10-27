<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Periksa apakah sudah menjalankan migrasi pertama kali
        // if ($this->app->runningInConsole() && !Schema::hasTable('migrations')) {
        //     // Jalankan seeder untuk akun superadmin
        //     $this->call(SuperAdminSeeder::class);
        // }
    }
}
