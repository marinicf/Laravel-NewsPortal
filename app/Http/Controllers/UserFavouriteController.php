<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\UserFavourite;

class UserFavouriteController extends Controller{

    public function index(){
        return Inertia::render('Favourite/Index', 
        [
            'articles' => null,
        ]);
    }

    public function store(Request $request){

    }
    public function destroy( $favArticleId ){

    }
}