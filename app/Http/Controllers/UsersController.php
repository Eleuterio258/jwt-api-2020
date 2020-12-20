<?php

namespace App\Http\Controllers;

use App\Models\Produtos;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function all()
    {
        $usuario = User::all('name','email','role');
        return $usuario;
    }


}
