<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {   //view es una funcion propia de CI
        return view('welcome_message');
    }
}
