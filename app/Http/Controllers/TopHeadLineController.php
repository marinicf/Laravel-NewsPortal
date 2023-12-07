<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use jcobhams\NewsApi\NewsApi;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\UserFavourite;
use App\Models\UserComment;
class TopHeadLineController extends Controller
{
     /**
     * @OA\Get(
     *      path="/topHeadlines",
     *      operationId="topHeadlines.index",
     *      tags={"TopHeadlines"},
     *      summary="Get a list of articles based on user preferences",
     *      description="Returns a list of articles based on the user's country and category preferences.",
     *      @OA\Parameter(
     *          name="category",
     *          in="query",
     *          description="The selected category",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="country",
     *          in="query",
     *          description="The selected country",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="page_size",
     *          in="query",
     *          description="The number of results to return per page (request)",
     *          required=false,
     *          @OA\Schema(type="number")
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Use this to page through the results if the total results found is greater than the page size.",
     *          required=false,
     *          @OA\Schema(type="number")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="articles", type="array", @OA\Items(type="object",
     *                  @OA\Property(property="source", type="object",
     *                      @OA\Property(property="id", type="string", example="source_id_example"),
     *                      @OA\Property(property="name", type="string", example="Yahoo Entertainment")
     *                  ),
     *                  @OA\Property(property="author", type="string", example="Edwin Chan"),
     *                  @OA\Property(property="title", type="string", example="Musk Defends Himself on X After Antisemitic Furor Deepens - Yahoo Finance"),
     *                  @OA\Property(property="description", type="string", example="(Bloomberg) -- Elon Musk railed against “bogus” media reports accusing him of antisemitism, issuing his strongest response yet after endorsing antisemitic..."),
     *                  @OA\Property(property="url", type="string", example="https://finance.yahoo.com/news/musk-defends-himself-x-antisemitic-035726993.html"),
     *                  @OA\Property(property="urlToImage", type="string", example="https://s.yimg.com/ny/api/res/1.2/p_j_tPXC.zii92Y9Ck3XTw--/YXBwaWQ9aGlnaGxhbmRlcjt3PTEyMDA7aD04ODM-/https://media.zenfs.com/en/bloomberg_technology_68/e3d581a28a7356a3d18b50e2b529faec"),
     *                  @OA\Property(property="publishedAt", type="string", example="2023-11-20T07:32:00Z"),
     *                  @OA\Property(property="content", type="string", example="(Bloomberg) -- Elon Musk railed against bogus media reports accusing him of antisemitism, issuing his strongest response yet after endorsing antisemitic content in a post on X that provoked outrage a… [+1624 chars]")
     *              ))
     *          )
     *      ),
     *     @OA\Response(
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
     */
    public function index(Request $request){
        $user = auth()->user();
        $q = $request->get('q') ? $request->get('q') : null;
        $source = $request->get('source') ? $request->get('source') : null;
        $country = $request->get('country') ? $request->get('country') : $user->country;
        $category = $request->get('category') ? $request->get('category') : $user->category;
        $page_size = 5;
        $page = 1;

        $newsapi = new NewsApi($apiKey = env('NEWS_API_KEY'));

        $categories = $newsapi->getCategories();

        $countries = $newsapi->getCountries();

        $sources = $newsapi->getSources($category, null,$country);

        $favourites = UserFavourite::where('user_id',auth()->user()->id)->get();

        $comments = UserComment::all();

        $users = User::all('id','name');

        //If sources are selected from dropdown set country and category to null because getTopHeadlines() requires it.
        if($source){
            $category = null;
            $country = null;
        }

        $response = $newsapi->getTopHeadlines($q, $source, $country, $category, $page_size, $page);

        //Return view with articles array for display and allCategories, allCountriesa and allSources for select options.
        return Inertia::render('TopHeadline/Index', [   
            'articles' => $response->articles, 
            'allCategories' => $categories, 
            'allCountries' => $countries, 
            'keyword'=> $q, 
            'allSources' => $sources->sources, 
            'currentCountry'=> $country, 
            'currentCategory' => $category,
            'currentSource' => $source,
            'allFavourites' => $favourites,
            'allComments'=> $comments,
            'allUsersNames' => $users
        ]);
    }
}
