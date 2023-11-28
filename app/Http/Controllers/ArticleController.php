<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use jcobhams\NewsApi\NewsApi;
use Inertia\Inertia;
use Inertia\Response;
/**
 * @OA\Info(
 *      title="News portal",
 *      version="1.0.0",
 *      description="Documentation for the NewsAPI"
 * )
 */
class ArticleController extends Controller
{       /**
    * @OA\Get(
    *      path="/articles",
    *      operationId="articles.index",
    *      tags={"Articles"},
    *      summary="Get a listing of articles based on user preferences and search criteria",
    *      description="Returns a list of articles based on the user's preferences and search criteria.",
    *      @OA\Parameter(
    *          name="q",
    *          in="query",
    *          description="Keywords or a phrase to search for.",
    *          required=true,
    *          @OA\Schema(type="string")
    *      ),
    *      @OA\Parameter(
    *          name="language",
    *          in="query",
    *          description="The 2-letter ISO-639-1 code of the language.",
    *          required=false,
    *          @OA\Schema(type="string")
    *      ),
    *      @OA\Parameter(
    *          name="sources",
    *          in="query",
    *          description="A comma-separated string of identifiers for the news sources or blogs.",
    *          required=false,
    *          @OA\Schema(type="string")
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *           @OA\JsonContent(
    *         type="object",
    *         @OA\Property(property="articles", type="array", @OA\Items(
    *             @OA\Property(property="source", type="object",
    *                 @OA\Property(property="id", type="string", example="engadget"),
    *                 @OA\Property(property="name", type="string", example="Engadget")
    *             ),
    *             @OA\Property(property="author", type="string", example="Mat Smith"),
    *             @OA\Property(property="title", type="string", example="The Morning After: Microsoft recruits recently fired OpenAI CEO, Sam Altman"),
    *             @OA\Property(property="description", type="string", example="OpenAI’s board of directors announced Friday it had fired CEO Sam Altman. But he’s doing okay. By Monday morning, he had joined Microsoft “to lead a new advanced AI research team,” according to the company."),
    *             @OA\Property(property="url", type="string", example="https://www.engadget.com/the-morning-after-microsoft-recruits-recently-fired-openai-ceo-sam-altman-121523955.html"),
    *             @OA\Property(property="urlToImage", type="string", example="https://s.yimg.com/ny/api/res/1.2/TdeBQRLZ0qN0hkvKFENN6A--/YXBwaWQ9aGlnaGxhbmRlcjt3PTEyMDA7aD04NTc-/https://s.yimg.com/os/creatr-uploaded-images/2023-11/7661fd60-85cc-11ee-bddf-d93b4819eea6"),
    *             @OA\Property(property="publishedAt", type="string", format="date-time", example="2023-11-20T12:15:23Z"),
    *             @OA\Property(property="content", type="string", example="OpenAI’s board of directors announced Friday it had fired CEO Sam Altman. But he’s doing okay. By Monday morning, he had joined Microsoft “to lead a new advanced AI research team,” according to the company… [+3202 chars]")
    *         ))
    *      )
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
    *
    * )
    *
    * Display a listing of articles based on user preferences and search criteria.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\View\View
    *
    * @throws \Exception if there is an issue with the News API request.
    */
    public function index(): Response{
        $q='*';

        $newsapi = new NewsApi($apiKey = env('NEWS_API_KEY'));

        $allSortbyOptions = $newsapi->getSortBy();

        $allLanguages = $newsapi->getLanguages();

        $response = $newsapi->getEverything($q, null, null, null, null, null, null, null, null, null);

        //Return view with articles and all data required for view.
        return Inertia::render('Article/Index', 
        [
            'articles' => $response->articles,
            'allSortByOptions' => $allSortbyOptions, 
            'keyword'=> $q, 
            'allLanguages' => $allLanguages, 
            /*'currentLanguage' => $language, 
            'currentSortBy' => $sortBy*/
        ]);
    }

    public function paginated(Request $request){

        $user = auth()->user();

        $q = $request->get('q') ? $request->get('q') : '*';
        $source= /*$request->get('sources') ? $request->get('sources') :*/ null;
        $from=null;
        $to=null;
        $language = $request->get('language') ? $request->get('language') : $user->language;
        $sortBy= $request->get('sortBy') ? $request->get('sortBy') : null;
        $perPage=$request->get('perPage') ? $request->get('perPage') : 5;
        $page= $request->get('page') ? $request->get('page') : 1;

        $path = http_build_query($request->all());

        $newsapi = new NewsApi($apiKey = env('NEWS_API_KEY'));

        $response = $newsapi->getEverything($q, $source, null, null, $from, $to, $language, $sortBy, $perPage, $page);
        $paginated = new LengthAwarePaginator($response->articles, $response->totalResults, $perPage, $page, ['path' => $request->url()]);

        return Inertia::render('Article/Paginated', 
        [
            'articles' => $paginated,
        ]);
        //return $paginated;
    }

}
