<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\User;
use App\Models\UserFavourite;
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
    *      summary="Get a listing of articles.",
    *      description="Returns a list of articles based on the user's preferences and search criteria.",
    *      @OA\Parameter(
    *          name="q",
    *          in="query",
    *          description="Keywords or a phrase to search for.",
    *          required=true,
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
        $keyword='*';
        $prePage = 10;

        $newsapi = new NewsApi($apiKey = env('NEWS_API_KEY'));

        $allSortbyOptions = $newsapi->getSortBy();

        $allLanguages = $newsapi->getLanguages();

        $allFavourites = UserFavourite::where('user_id',auth()->user()->id)->get();

        $response = $newsapi->getEverything($keyword, null, null, null, null, null, null, null, $prePage, null);

        //Return view with articles and all data required for view.
        return Inertia::render('Article/Index', 
        [
            'articles' => $response->articles,
            'allSortByOptions' => $allSortbyOptions, 
            'keyword'=> $keyword, 
            'allLanguages' => $allLanguages,
            'allFavourites' => $allFavourites, 
        ]);
    }
    /**
    * @OA\Get(
    *     path="/articles/paginated",
    *     operationId="articles.paginated",
    *     tags={"Articles"},
    *     summary="Get a listing of articles based on user preferences and search criteria",
    *     description="Returns a list of articles based on the user's preferences and search criteria.",
    *     @OA\Parameter(
    *         name="q",
    *         in="query",
    *         description="Keywords or a phrase to search for.",
    *         required=true,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="language",
    *         in="query",
    *         description="The 2-letter ISO-639-1 code of the language.",
    *         required=false,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="sources",
    *         in="query",
    *         description="A comma-separated string of identifiers for the news sources or blogs.",
    *         required=false,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="domains",
    *         in="query",
    *         description="A comma-separated string of domains (e.g., bbc.co.uk) to restrict the search to.",
    *         required=false,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="exclude_domains",
    *         in="query",
    *         description="A comma-separated string of domains (e.g., bbc.co.uk) to exclude from the search.",
    *         required=false,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="from",
    *         in="query",
    *         description="A date and optional time for the oldest article allowed (in the format YYYY-MM-DD or YYYY-MM-DDTHH:mm:ss).",
    *         required=false,
    *         @OA\Schema(type="string", format="date-time")
    *     ),
    *     @OA\Parameter(
    *         name="to",
    *         in="query",
    *         description="A date and optional time for the newest article allowed (in the format YYYY-MM-DD or YYYY-MM-DDTHH:mm:ss).",
    *         required=false,
    *         @OA\Schema(type="string", format="date-time")
    *     ),
    *     @OA\Parameter(
    *         name="sortBy",
    *         in="query",
    *         description="The order to sort the articles (possible values: 'relevancy', 'popularity', 'publishedAt').",
    *         required=false,
    *         @OA\Schema(type="string", enum={"relevancy", "popularity", "publishedAt"})
    *     ),
    *     @OA\Parameter(
    *         name="perPage",
    *         in="query",
    *         description="The number of articles to return per page.",
    *         required=false,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Parameter(
    *         name="page",
    *         in="query",
    *         description="The page number to retrieve.",
    *         required=false,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Successful operation",
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(property="articles", type="array", @OA\Items(
    *                 @OA\Property(property="source", type="object",
    *                     @OA\Property(property="id", type="string", example="engadget"),
    *                     @OA\Property(property="name", type="string", example="Engadget")
    *                 ),
    *                 @OA\Property(property="author", type="string", example="Mat Smith"),
    *                 @OA\Property(property="title", type="string", example="The Morning After: Microsoft recruits recently fired OpenAI CEO, Sam Altman"),
    *                 @OA\Property(property="description", type="string", example="OpenAI’s board of directors announced Friday it had fired CEO Sam Altman. But he’s doing okay. By Monday morning, he had joined Microsoft “to lead a new advanced AI research team,” according to the company."),
    *                 @OA\Property(property="url", type="string", example="https://www.engadget.com/the-morning-after-microsoft-recruits-recently-fired-openai-ceo-sam-altman-121523955.html"),
    *                 @OA\Property(property="urlToImage", type="string", example="https://s.yimg.com/ny/api/res/1.2/TdeBQRLZ0qN0hkvKFENN6A--/YXBwaWQ9aGlnaGxhbmRlcjt3PTEyMDA7aD04NTc-/https://s.yimg.com/os/creatr-uploaded-images/2023-11/7661fd60-85cc-11ee-bddf-d93b4819eea6"),
    *                 @OA\Property(property="publishedAt", type="string", format="date-time", example="2023-11-20T12:15:23Z"),
    *                 @OA\Property(property="content", type="string", example="OpenAI’s board of directors announced Friday it had fired CEO Sam Altman. But he’s doing okay. By Monday morning, he had joined Microsoft “to lead a new advanced AI research team,” according to the company… [+3202 chars]")
    *             ))
    *         )
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Not Found - The requested resource could not be found",
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(property="error", type="string")
    *         )
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Internal server error",
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(property="error", type="string")
    *         )
    *     )
    * )
    *
    * Display a listing of articles based on user preferences and search criteria.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\View\View
    *
    * @throws \Exception if there is an issue with the News API request.
    */
    public function paginated(Request $request): Response{

        $user = auth()->user();

        $keyword = $request->get('q') ? $request->get('q') : '*';
        $source= /*$request->get('sources') ? $request->get('sources') :*/ null;
        $from= $request->get('from') ? $request->get('from') : null;
        $to=$request->get('to') ? $request->get('to') : null;
        $language = $request->get('language') ? $request->get('language') : $user->language;
        $sortBy= $request->get('sortBy') ? $request->get('sortBy') : 'publishedAt';
        $perPage=$request->get('perPage') ? $request->get('perPage') : 5;
        $page= $request->get('page') ? $request->get('page') : 1;

        $path = http_build_query($request->all());

        $newsapi = new NewsApi($apiKey = env('NEWS_API_KEY'));

        $response = $newsapi->getEverything($keyword, $source, null, null, $from, $to, $language, $sortBy, $perPage, $page);
        $path =  $request->url();
        $query = $request->all();

        //Hardcoded due to restrictions on NewsAPI
        $total = $response->totalResults < 100 ? $response->totalResults : 100;

        $paginated = new LengthAwarePaginator($response->articles, $total, $perPage, $page, ['path'=>$path, 'query'=>$query]);

        return Inertia::render('Article/Paginated', 
        [
            'articles' => $paginated,
        ]);
    }
}