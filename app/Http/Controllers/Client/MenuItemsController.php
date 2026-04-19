<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class MenuItemsController extends Controller
{

    public function index()
    {
        return view('client.content.menu-section');
    }


}
