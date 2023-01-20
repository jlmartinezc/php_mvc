<?php 

namespace App\Controllers;

use src\View\View;

class BienesController{
    
    public function __construct(){
    }

    public function index(){
        $hola = 'aaaaaa';
        View::render('bienes/index', compact('hola'));
    }

}