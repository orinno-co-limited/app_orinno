<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * Session/CSRF token expiry (419) is silently recovered instead of shown
     * to the user: the session is refreshed and the request is retried by
     * sending the user back to the page they were on, with their form input
     * preserved. Non-technical users otherwise land on a bare "419 Page
     * Expired" screen with no way forward but to navigate back manually.
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof TokenMismatchException) {
            return redirect()
                ->back()
                ->withInput($request->except(['password', 'password_confirmation', '_token']))
                ->with('warning', 'Your session had timed out, so please try that again.');
        }

        return parent::render($request, $e);
    }
}
