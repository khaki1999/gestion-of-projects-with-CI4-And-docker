<?php

namespace App\Controllers;

class Home extends BaseController
{   
    protected $helpers = ['url', 'form', 'CIMail','CIFunctions '];
    public function index(): string
    {
        return view('welcome_message');
    }

    
}
