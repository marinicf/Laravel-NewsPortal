<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use jcobhams\NewsApi\NewsApi;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;

/**
 * @group User Settings
 *
 * APIs for managing user settings.
 */
class UserSettingController extends Controller
{
    /**
     * Show the form for editing the user settings.
     *
     * @authenticated
     *
     * @return \Inertia\Response
     *
     * @OA\Get(
     *      path="/userSettings",
     *      operationId="userSettings.edit",
     *      tags={"User Settings"},
     *      summary="Show the form for editing user settings.",
     *      description="Returns the form for editing user settings including preferred category, country, and language.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="allLanguages", type="array", @OA\Items(type="string")),
     *              @OA\Property(property="allCategories", type="array", @OA\Items(type="string")),
     *              @OA\Property(property="allCountries", type="array", @OA\Items(type="string")),
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="error", type="string")
     *          )
     *      )
     * )
     */
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
/**
     * Update the user settings.
     *
     * @authenticated
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception if there is an issue updating user settings.
     *
     * @OA\Patch(
     *      path="/userSettings",
     *      operationId="userSettings.update",
     *      tags={"User Settings"},
     *      summary="Update the user settings.",
     *      description="Updates the user settings including preferred category, country, and language.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"category", "country", "language"},
     *              @OA\Property(property="category", type="string", description="Preferred news category"),
     *              @OA\Property(property="country", type="string", description="Preferred news country"),
     *              @OA\Property(property="language", type="string", description="Preferred news language"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=302,
     *          description="Successful operation. Redirects to the user settings edit page.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="User settings updated successfully."),
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="error", type="string")
     *          )
     *      )
     * )
     */
    public function update(Request $request){

        $user = auth()->user();

        $user->category = $request->get('category');
        $user->country = $request->get('country');
        $user->language = $request->get('language');
        
        $user->save();
        
        return Redirect::route('userSettings.edit');
    }
}
