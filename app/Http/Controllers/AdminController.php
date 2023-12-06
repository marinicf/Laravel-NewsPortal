<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserFavourite;
use App\Models\UserComment;
use Inertia\Inertia;
use Inertia\Response;
/**
 * @group Admin
 *
 * APIs for managing admin-related tasks.
 */
class AdminController extends Controller
{
/**
     * Display a listing of non-admin users.
     *
     * @authenticated
     *
     * @return \Inertia\Response
     *
     * @OA\Get(
     *      path="/adminDashboard",
     *      operationId="admin.index",
     *      tags={"Admin"},
     *      summary="Display a listing of non-admin users.",
     *      description="Returns a list of non-admin users for admin dashboard.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="users", type="array", @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="John Doe"),
     *                  @OA\Property(property="email", type="string", example="john@example.com"),
     *              )),
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
    public function index(Request $request)
    {
        $users = User::where('is_admin', false)->get();
        return Inertia::render('Dashboard', ['users' => $users]);
    }
    /**
 * Display the specified user's information and favourite articles.
 *
 * @authenticated
 *
 * @param  int  $userId
 * @return \Inertia\Response
 *
 * @OA\Get(
 *      path="/admin/{userId}",
 *      operationId="admin.show",
 *      tags={"Admin"},
 *      summary="Display the specified user's information and favourite articles.",
 *      description="Returns information about a specific user and their favourite articles.",
 *      @OA\Parameter(
 *          name="userId",
 *          in="path",
 *          required=true,
 *          description="ID of the user",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="user", type="object",
 *                  @OA\Property(property="id", type="integer", example=1),
 *                  @OA\Property(property="name", type="string", example="John Doe"),
 *                  @OA\Property(property="email", type="string", example="john@example.com"),
 *              ),
 *              @OA\Property(property="userFavourites", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="id", type="integer", example=1),
 *                      @OA\Property(property="title", type="string", example="Article Title"),
 *                      @OA\Property(property="url", type="string", example="https://example.com/article"),
 *                      @OA\Property(property="author", type="string", example="Author Name"),
 *                      @OA\Property(property="description", type="string", example="Article Description"),
 *                      @OA\Property(property="image_url", type="string", example="https://example.com/image.jpg"),
 *                      @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-01T12:34:56Z"),
 *                  )
 *              ),
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found - The requested resource could not be found",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="error", type="string")
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
    public function show($userId){
        $selectedUser = User::where('id',$userId)->first();
        $userFavouritesArticles = UserFavourite::where('user_id',$userId)->get();
        $userComments = UserComment::where('user_id',$userId)->get();
        return Inertia::render('Admin/UserInfo',['user'=> $selectedUser, 'userFavourites'=>$userFavouritesArticles, 'userComments' =>$userComments]);
    }

    public function edit($comment_id){
        $selectedComment = UserComment::where('id',$comment_id)->first();
        return Inertia::render('Admin/EditComment',compact('selectedComment'));
    }

    public function update($comment_id, Request $request){
        $comment  = UserComment::where('id',$comment_id)->first();
        $comment->comment_text = $request->get('comment');
        $comment->save();
        
        return to_route('admin.index');
    }
}
