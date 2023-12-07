<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\UserFavourite;

/**
 * @group User Favourites
 *
 * APIs for managing user favourite articles.
 */
class UserFavouriteController extends Controller{

    /**
     * Display a listing of the user's favourite articles.
     *
     * @authenticated
     *
     * @return \Inertia\Response
     *
     * @OA\Get(
     *      path="/favourites",
     *      operationId="favourites.index",
     *      tags={"Favourites"},
     *      summary="Get a listing of user's favourite articles.",
     *      description="Returns a list of articles that the user has marked as favourites.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="title", type="string", example="Article Title"),
     *                  @OA\Property(property="url", type="string", example="https://example.com/article"),
     *                  @OA\Property(property="author", type="string", example="Author Name"),
     *                  @OA\Property(property="description", type="string", example="Article Description"),
     *                  @OA\Property(property="image_url", type="string", example="https://example.com/image.jpg"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-01T12:34:56Z"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *         response=404,
     *         description="Not Found - The requested resource could not be found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="error", type="string")
     *          )
     *      )
     * )
     *
     * @throws \Exception if there is an issue retrieving user's favourite articles.
     */
    public function index(){
        $user = auth()->user();

        $favouriteArticles = UserFavourite::where('user_id', $user->id)->get();
        return Inertia::render('Favourite/Index', 
        [
            'articles' => $favouriteArticles,
        ]);
    }
    /**
     * Store user's favourite articles.
     *
     * @authenticated
     *
     * @return \Inertia\Response
     *
     * @OA\Post(
     *      path="/favourites",
     *      operationId="favourites.store",
     *      tags={"Favourites"},
     *      summary="Store a newly created favourite article in databes.",
     *      description="Returns redirect to article.index.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *      ),
     *      @OA\Response(
     *         response=404,
     *         description="Not Found - The requested resource could not be found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="error", type="string")
     *          )
     *      )
     * )
     *
     * @throws \Exception if there is an issue retrieving user's favourite articles.
     */
    public function store(Request $request){

        $user = auth()->user();
        
        //Check if article exists in database
        if (!UserFavourite::where('url', $request->get('url'))->exists() ) {
            // If favourite article is not found add it
            UserFavourite::create([
                'title' => $request->get('title'),
                'url' => $request->get('url'),
                'author' => $request->get('author'),
                'description' => $request->get('description'),
                'image_url' => $request->get('urlToImage'),
                'user_id' => $user->id,
            ]);
        }
        //return to_route('articles.index');
        return redirect()->back();
    }
    /**
     * Remove the specified favourite article from storage.
     *
     * @authenticated
     * 
     * @urlParam favourite_id integer required The ID of the favourite article.
     *
     * @param  int  $favourite_id
     * @return \Inertia\Response
     *
     * @OA\Delete(
     *      path="/favourites{favourite_id}",
     *      operationId="favourites.destroy",
     *      tags={"Favourites"},
     *      summary="Remove the specified favourite article from database.",
     *      description="Returns redirect to article.index.",
     * @OA\Parameter(
     *         name="favourite_id",
     *         in="path",
     *         description="ID of the favorite article to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *      ),
     *      @OA\Response(
     *         response=404,
     *         description="Not Found - The requested resource could not be found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="error", type="string")
     *          )
     *      )
     * )
     *
     * @throws \Exception if there is an issue retrieving user's favourite articles.
     */
    public function destroy($favourite_id){
        
        $favouriteArticle = UserFavourite::find($favourite_id);
        $favouriteArticle->delete();

        return redirect()->back();
    }
}