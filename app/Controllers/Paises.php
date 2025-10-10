<?php

namespace App\Controllers;

class Paises extends BaseController
{
    public function index()
    {
        echo view('paises');
    }

    public function listar(){
        echo view('listarpaises');
    }
}
