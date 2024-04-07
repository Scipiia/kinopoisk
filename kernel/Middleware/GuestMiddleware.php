<?php

namespace App\Kernel\Middleware;

class GuestMiddleware extends AbstractMiddleware
{

    public function handle(): void
    {
        if ($this->auth->check()) {
            $this->redirect->to("/home");
        }
    }
}