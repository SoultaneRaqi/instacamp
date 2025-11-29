<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Operator;
use Symfony\Component\HttpFoundation\RedirectResponse;
class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware( middleware:'auth');
    }

    public function store(Post $post): RedirectResponse
    {
        $post->likes()->create([
            'user_id' => auth()->id()
        ]);
        return back();
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->likes()->where(column: 'user_id',operator: auth()->id())->delete();
        return back();
    }
}
