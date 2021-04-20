<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
  
   public function show(User $user)
    {
        return view('user.show')->with([
            'user' => $user
        ]);
    }

    public function edit(User $user)
    {
        return view('user.edit')->with([
            'user' => $user,
            'message_success' => Session::get('message_success'),
            'message_warning' => Session::get('message_warning')
        ]);
    }

   
    public function update(Request $request, User $user)
    {
        $request->validate([
            'motto' => 'required|min:5',
            'about_me' => 'required|min:5',
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        if ($request->image) {
            $this->saveImages($request->image, $user->id);
        }

        $user->update([
            'motto' => $request->motto,
            'about_me' => $request->about_me,
        ]);


        return redirect('/home')->with([
            "message_success" => "This user <b>". $user->name."</b> has been updated successfully"
        ]);
    }

   
    

    public function saveImages($imageInput, $user_id){

        $image = Image::make($imageInput);
        if ( $image->width() > $image->height() ) { 
            $image->widen(1200)
                ->save(public_path() . "/img/users/" . $user_id . "_large.jpg")
                ->widen(400)->pixelate(12)
                ->save(public_path() . "/img/users/" . $user_id . "_pixelated.jpg");
            $image = Image::make($imageInput);
            $image->widen(60)
                ->save(public_path() . "/img/users/" . $user_id . "_thumb.jpg");
        } else { // Portrait
            $image->heighten(900)
                ->save(public_path() . "/img/users/" . $user_id . "_large.jpg")
                ->heighten(400)->pixelate(12)
                ->save(public_path() . "/img/users/" . $user_id . "_pixelated.jpg");
            $image = Image::make($imageInput);
            $image->heighten(60)
                ->save(public_path() . "/img/users/" . $user_id . "_thumb.jpg");
        }

    }

    public function deleteImages($user_id){
        if(file_exists(public_path() . "/img/users/" . $user_id . "_large.jpg"))
            unlink(public_path() . "/img/users/" . $user_id . "_large.jpg");
        if(file_exists(public_path() . "/img/users/" . $user_id . "_thumb.jpg"))
            unlink(public_path() . "/img/users/" . $user_id . "_thumb.jpg");
        if(file_exists(public_path() . "/img/users/" . $user_id . "_pixelated.jpg"))
            unlink(public_path() . "/img/users/" . $user_id . "_pixelated.jpg");

        return back()->with(
            [
                'message_success' => "The Image was deleted."
            ]
        );
    }
}
