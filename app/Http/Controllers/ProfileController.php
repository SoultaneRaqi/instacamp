<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
class ProfileController extends Controller
{
    public function show(User $user): View
    {
       return view(view:'profiles.index', data: compact(var_name:'user')); 
    }

    public function edit(User $user): View
    {
        if (auth()->id() !== $user->id) { 
            abort(code:403, message:'Unauthorized action.');
        }

        return view(view:'profiles.edit', data: compact(var_name:'user')); 
    }

    public function update(Request $request, User $user): RedirectResponse
    {
         if (auth()->id() !== $user->id) { 
            abort(code:403, message:'Unauthorized action.');
        }

        $data = $request->validate(rules: [
            'name' => 'required',
            'username' => 'required',
            'bio' => 'nullable',
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        
        if ($request->hasFile(key:'profile_image')) {
            if ($user->profile_image) {
                // FIX: Remove 'path:' from Storage::delete()
                Storage::delete('uploads/' .$user->profile_image); 
            }
            // Keep named arguments here as they align with the store() method signature
            $imagePath = $request->file(key:'profile_image')->store(path:'uploads', options:'public'); 
            $data['profile_image'] = $imagePath;
        }

        $user->update(attributes: $data);
        return redirect(to:'/profile/' . $user->id);
    }
}


