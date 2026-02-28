<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Hotfix for POST data parsing in Android environment
if (($_SERVER['REQUEST_METHOD'] ?? null) === 'POST' && empty($_POST)) {
    if (! isset($_SERVER['CONTENT_TYPE']) && isset($_SERVER['HTTP_CONTENT_TYPE'])) {
        $_SERVER['CONTENT_TYPE'] = $_SERVER['HTTP_CONTENT_TYPE'];
    }
    if (! isset($_SERVER['CONTENT_LENGTH']) && isset($_SERVER['HTTP_CONTENT_LENGTH'])) {
        $_SERVER['CONTENT_LENGTH'] = $_SERVER['HTTP_CONTENT_LENGTH'];
    }

    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    $shouldParse = ($contentType === '')
        || str_starts_with($contentType, 'application/x-www-form-urlencoded')
        || str_starts_with($contentType, 'text/plain');

    if ($shouldParse) {
        $rawBody = file_get_contents('php://input');
        if (is_string($rawBody) && $rawBody !== '') {
            parse_str($rawBody, $parsed);
            if (is_array($parsed)) {
                $_POST = $parsed;
            }
        }
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
