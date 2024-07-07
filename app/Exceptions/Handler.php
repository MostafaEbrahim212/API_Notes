<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e) {
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
            } elseif ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            } elseif ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'message' => 'This action is unauthorized.',
                ], 403);
            } elseif ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'message' => 'Resource not found.',
                ], 404);
            } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return response()->json([
                    'message' => 'Resource not found.',
                ], 404);
            } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                return response()->json([
                    'message' => 'Method not allowed.',
                ], 405);
            } elseif ($e instanceof \Illuminate\Database\QueryException) {
                return response()->json([
                    'message' => 'Database error.',
                ], 500);
            } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                return response()->json([
                    'message' => 'Server error.',
                ], 500);
            } elseif ($e instanceof \Throwable) {
                return response()->json([
                    'message' => 'Server error.',
                ], 500);
            }
        });
    }
}
