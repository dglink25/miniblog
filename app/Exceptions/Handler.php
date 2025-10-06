<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Exceptions\PostTooLargeException;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($e instanceof PostTooLargeException) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'media' => "Votre envoi dépasse la taille maximale autorisée (100 Mo). Réduisez la taille des fichiers."
                ]);
        }

        return parent::render($request, $e);
    }
}
