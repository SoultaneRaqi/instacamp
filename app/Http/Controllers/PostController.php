<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use League\CommonMark\Extension\Attributes\Node\Attributes;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PostController extends Controller
{
    //only authenticated users can access this controller
    public function __construct()
    {
        $this->middleware( middleware:'auth'); 
    }

    public function index(): View
    {
        $posts = Post::with(relations: 'user')->latest()->get();
        return view(view:'posts.index', data: compact(var_name:'posts'));
    }

    public function create(): View
    {
        return view(view:'posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(rules: [
            'caption' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->file(key:'image')->store(path:'uploads', options:'public');
        $data['image'] = basename(path:$imagePath);

        auth()->user()->posts()->create(attributes: [
            'caption' => $data['caption'],
            'image_path' => $imagePath,
        ]);

        return redirect(to:'/profile/' . auth()->user()->id);
    }

    public function show(Post $post): View
    {
        return view(view:'posts.show', data: compact(var_name:'post'));
    }

    public function edit(Post $post): View
    {
        if (auth()->id() !== $post->user_id) {
            abort(code:403 ,  message:'Unauthorized action.');
        }
        return view(view:'posts.edit', data: compact(var_name:'post'));
    }



    public function update(Request $request, Post $post): RedirectResponse
    {
        if (auth()->id() !== $post->user_id) {
            abort(code:403 ,  message:'Unauthorized action.');
        }

        $data = $request->validate(rules: [
            'caption' => 'required',
        ]);

        /*if ($request->hasFile(key:'image')) {
            Storage::delete(path:$post->image_path);
            $imagePath = $request->file(key:'image')->store(path:'uploads', options:'public');
            $data['image'] = basename(path:$imagePath);
        }*/

        $post->update(attributes: $data);

        return redirect(to:'/posts/' . $post->id);
    }


    public function destroy(Post $post): RedirectResponse
    {
        if (auth()->id() !== $post->user_id) {
            abort(code:403 ,  message:'Unauthorized action.');
        }
        
        
        Storage::disk(name:'public')->delete($post->image_path); 

        $post->delete();
        return redirect(to:'/profile/' . auth()->user()->id);
    }




}