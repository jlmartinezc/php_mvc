<?php

namespace App\Controllers;

use src\View\View;

class SaloonController{
    
    public function __construct(){        
    }

    public function index(){
        View::render('saloon/index');
    }
}