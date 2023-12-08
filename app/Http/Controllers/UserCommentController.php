<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\UserComment;

class UserCommentController extends Controller
{
    /**
 * @OA\Post(
 *     path="/comments",
 *     operationId="storeComment",
 *     tags={"Comments"},
 *     summary="Store a new comment",
 *     description="Creates a new comment",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Comment data",
 *         @OA\JsonContent(
 *             required={"userId", "url", "comment"},
 *             @OA\Property(property="userId", type="integer", example=1),
 *             @OA\Property(property="url", type="string", example="https://example.com"),
 *             @OA\Property(property="comment", type="string", example="This is a comment"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comment created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Comment created successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Validation error"),
 *             @OA\Property(property="errors", type="object", example={"field_name": {"Error message"}})
 *         )
 *     )
 * )
 */
    public function store(Request $request){
        $request->validate([
            'url' => 'required|string',
            'comment' => 'required|string',
        ]);
        
        UserComment::create([
            'user_id' => $request->get('userId'),
            'url' => $request->get('url'),
            'comment_text' => $request->get('comment'),
        ]);

        return redirect()->back();
    }
/**
 * @OA\Delete(
 *     path="/comments/{comment_id}",
 *     operationId="destroyComment",
 *     tags={"Comments"},
 *     summary="Delete a comment",
 *     description="Deletes a comment by ID",
 *     @OA\Parameter(
 *         name="comment_id",
 *         in="path",
 *         description="ID of the comment to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comment deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Comment deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Comment not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Comment not found")
 *         )
 *     )
 * )
 */
    public function destroy($comment_id){
        $comment = UserComment::find($comment_id);
        $comment->delete();

        return redirect()->back();
    }
    /**
 * @OA\Get(
 *     path="/comments/{comment_id}/edit",
 *     operationId="editComment",
 *     tags={"Comments"},
 *     summary="Edit comment form",
 *     description="Displays the form to edit a comment",
 *     @OA\Parameter(
 *         name="comment_id",
 *         in="path",
 *         description="ID of the comment to edit",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Edit comment form displayed successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Edit comment form displayed successfully"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Comment not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Comment not found")
 *         )
 *     )
 * )
 */
    public function edit($comment_id){
        $selectedComment = UserComment::where('id',$comment_id)->first();
        return Inertia::render('Article/EditComment',compact('selectedComment'));
    }
/**
 * @OA\Schema(
 *     schema="UserComment",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="url", type="string"),
 *     @OA\Property(property="comment_text", type="string"),
 * )
 */

/**
 * @OA\Patch(
 *     path="/comments/{comment_id}",
 *     operationId="updateComment",
 *     tags={"Comments"},
 *     summary="Update a comment",
 *     description="Updates a comment by ID",
 *     @OA\Parameter(
 *         name="comment_id",
 *         in="path",
 *         description="ID of the comment to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Comment data",
 *         @OA\JsonContent(
 *             required={"comment"},
 *             @OA\Property(property="comment", type="string", example="Updated comment text")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comment updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Comment updated successfully"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Comment not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Comment not found")
 *         )
 *     )
 * )
 */
    public function update($comment_id, Request $request){
        $request->validate([
            'comment' => 'required|string',
        ]);

        $comment  = UserComment::where('id',$comment_id)->first();
        $comment->comment_text = $request->get('comment');
        $comment->save();
        
        return to_route('articles.index');
    }
}
