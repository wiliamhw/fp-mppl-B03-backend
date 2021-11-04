<?php

namespace App\Exceptions;

use App\Exceptions\Concerns\HandleApiExceptions;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    use HandleApiExceptions;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function register()
    {
        $this->reportable(function (Throwable $error) {
            if ($this->shouldReport($error) && app()->bound('sentry')) {
                app('sentry')->captureException($error);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable               $exception
     *
     * @throws \Throwable
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception): Response
    {
        if ($request->expectsJson() || Str::startsWith($request->getRequestUri(), ['/api/', '/cms-api/'])) {
            return $this->renderApiException($exception);
        }

        return parent::render($request, $exception);
    }
}
