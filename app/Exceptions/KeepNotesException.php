<?php

namespace App\Exceptions;

use Exception;

class KeepNotesException extends Exception
{
    public function render($request, Exception $exception)
    {
        //if ($exception instanceof ModelNotFoundException) {
          //  return response()->json(['error' => 'Entry for '.str_replace('App\\', '', $exception->getModel()).' not found'], 404);
        // } else if ($exception instanceof InvalidUserException) {
        //     return response()->json(['error' => $exception->getMessage()], 404);
        //} else
        if ($exception instanceof InvalidUserException) {
            return response()->json(['error' => 'we can not find the user with that e-mail address'], 401);
        }

        return parent::render($request, $exception);
    }
}
