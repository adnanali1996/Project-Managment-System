<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use App\Comment;
class CommentController extends Controller
{
    //
    public function create (Request $request){
        $this->validate( $request, [
            'user_id' => 'required',
            'task_id'       => 'required',
            'comment' => 'required',
           
        ]) ;
        Comment::create([
            'user_id' => $request->user_id,
            'task_id'    => $request->task_id,
            'body' => $request->comment,
        ]);
        Session::flash('success', 'Feedback Given') ;
        return redirect()->back();
    }
}
