<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\UserComment;

class UserCommentController extends Controller
{
    public function store(Request $request){
        
        UserComment::create([
            'user_id' => $request->get('userId'),
            'url' => $request->get('url'),
            'comment_text' => $request->get('comment'),
        ]);

        return to_route('articles.index');
        //return $request;
    }

    public function destroy($comment_id){
        $comment = UserComment::find($comment_id);
        $comment->delete();

        return redirect()->back();
    }

}
