<?php

namespace Control\Middleware;


use Slim\Middleware;

class Authentication extends Middleware
{

    /**
     * Authentication constructor.
     */
    public function __construct()
    {
    }

    /**
     * Call
     *
     * Perform actions specific to this middleware and optionally
     * call the next downstream middleware.
     */
    public function call()
    {


        $this->next->call();
    }
}