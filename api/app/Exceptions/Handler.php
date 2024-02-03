<?php

namespace App\Exceptions;

use App\Models\Contact;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/contacts/*')) {
                return response()->json([
                    'message' => 'Data not found.'
                ], 404);
            }
        });
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/contacts/*/addresses')) {
                return response()->json([
                    'message' => 'Data not found.'
                ], 404);
            }
        });
        
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/contacts/*/addresses/*')) {
                return response()->json([
                    'message' => 'Data not found.'
                ], 404);
            }
        });

       
    }
}
