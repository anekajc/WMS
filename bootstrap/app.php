<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/*
| Struktur mengikuti newSML (Laravel 13). Menggantikan app/Http/Kernel.php,
| app/Providers/RouteServiceProvider.php, app/Console/Kernel.php dan
| app/Exceptions/Handler.php milik Laravel 5.6.
|
| Perbedaan dengan newSML yang sengaja dipertahankan dari ajcwms lama:
| - tamu yang membuka halaman ber-middleware auth tetap diarahkan ke /login
|   (route "login" dari Auth::routes() / laravel/ui), bukan ke "/";
| - alias "guest" tetap ke App\Http\Middleware\RedirectIfAuthenticated (ke /home);
| - grup "api" tetap throttle:60,1 + bindings.
*/

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);

        $middleware->group('api', [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Sama dengan newSML: error tak terduga pada AJAX dari user yang sudah login dikirim
        // balik sebagai JSON (pesan + file:baris) supaya terlihat di console browser lewat
        // resources/views/partials/ajax-error-logger.blade.php. Tetap tercatat di
        // storage/logs/laravel.log. Error HTTP (404/419/...), redirect auth dan validasi
        // tetap memakai penanganan bawaan Laravel.
        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->ajax()
                || $e instanceof HttpExceptionInterface
                || $e instanceof AuthenticationException
                || $e instanceof ValidationException) {
                return null;
            }

            try {
                if (! Auth::check()) {
                    return null;
                }
            } catch (Throwable) {
                return null;
            }

            return response()->json([
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => str_replace(base_path().DIRECTORY_SEPARATOR, '', $e->getFile()),
                'line' => $e->getLine(),
            ], 500);
        });
    })->create();
