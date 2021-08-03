<?php

namespace App\Exceptions;

use Exception;

class MissingArrayKey extends Exception
{
    public function render($message)
    {
        abort(500, $message);
    }
}
