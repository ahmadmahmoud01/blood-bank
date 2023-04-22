<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index() {

        return view('clients.index', (['clients' => Client::all()]));

    }
}
