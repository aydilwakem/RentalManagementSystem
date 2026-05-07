<?php

namespace App\Helpers;

class Toast
{
    /**
     * Create a new class instance.
     */
    public static function success($message)
    {
        session()->flash("sucess", $message);
    }
    public static function error($message)
    {
        session()->flash("error", $message);
    }
}
