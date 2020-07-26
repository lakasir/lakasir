<?php

namespace App\Exceptions;

use Exception;

class ServiceActionsException extends Exception
{
    public function render($message)
    {
        abort(500, $message);
    }
}
