<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
class CommentController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth');
   }

   public function store(Request $request, Post $post): RedirectResponse
   {
      $data = $request->validate(rules: [
         'comment' => 'required|max:255',
      ]);

      $post->comments()->create(attributes: [
         'comment' => $data['comment'],
         'user_id' => auth()->id(),
      ]);

      return redirect(to: '/posts/' . $post->id);
   }


   public function destroy(Comment $comment): RedirectResponse
   {
      if (auth()->id() !== $comment->user_id && auth()->id() !== $comment->post->user_id) {
          abort(code:403 ,  message:'Unauthorized action.');
      }   
      $comment->delete();
      return redirect(to: '/posts/' . $comment->postId);
   }
}
