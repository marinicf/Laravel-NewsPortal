<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use jcobhams\NewsApi\NewsApi;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;

class UserSettingController extends Controller
{
    public function edit(){

        $newsapi = new NewsApi($apiKey = env('NEWS_API_KEY'));

        $allLanguages = $newsapi->getLanguages();

        $allCategories = $newsapi->getCategories();

        $allCountries = $newsapi->getCountries();

        return Inertia::render('Profile/NewSettingEdit', 
        [
            'allLanguages' => $allLanguages,
            'allCategories' => $allCategories, 
            'allCountries' => $allCountries, 
        ]);
    }

    public function update(Request $request){

        $user = auth()->user();

        $user->category = $request->get('category');
        $user->country = $request->get('country');
        $user->language = $request->get('language');
        
        $user->save();
        
        return Redirect::route('userSettings.edit');
    }
}
