<?php

use App\Exceptions\ActiveSubscriptionAlreadyExistsException;
use App\Exceptions\ActiveSubscriptionNotFoundException;
use App\Exceptions\ScheduleConflictException;
use App\Exceptions\HasVicationException;
use App\Exceptions\UnauthorizedException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*')
        );

        $exceptions->dontReport([
            ScheduleConflictException::class,
            ActiveSubscriptionAlreadyExistsException::class,
            ActiveSubscriptionNotFoundException::class,
            HasVicationException::class,
        ]);

        $exceptions->render(function (
            ScheduleConflictException $e,
            Request $request
        ) {
            return redirect()
                ->route('schedules.index')
                ->with('message', $e->getMessage());
        });

        $exceptions->render(function (
            ActiveSubscriptionAlreadyExistsException $e,
            Request $request
        ) {
            return redirect()
                ->route('subscriptions.index')
                ->with('message', $e->getMessage());
        });

        $exceptions->render(function (
            ActiveSubscriptionNotFoundException $e,
            Request $request
        ) {
            return redirect()
                ->route('subscriptions.index')
                ->with('message', $e->getMessage());
        });

        $exceptions->render(function (
            HasVicationException $e,
            Request $request
        ) {
            return redirect()
                ->route('vications.index')
                ->with('message', $e->getMessage());
        });
        $exceptions->render(function (
            UnauthorizedException $e,
            Request $request
        ) {
            return redirect()->back()->with('message','you are unauthorized for this action');
        });
    })->create();
