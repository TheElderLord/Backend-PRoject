<?php

namespace App\Http\Controllers;

use App\Hobby;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class HobbyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }

public function index()
    {
       
        $hobbies = Hobby::orderBy('created_at', 'DESC')->paginate(10);

        return view('hobby.index')->with([
                'hobbies'=>$hobbies
            ]);
    }

  
    public function create()
    {
        return view('hobby.create');
    }

  
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|min:3',
            'description' => 'required|min:5',
        ]);

        $hobby =  new Hobby([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        if ($request->image) {
            $this->saveImages($request->image, $hobby->id);
        }

        $hobby->save();


        return redirect('/hobby/' . $hobby->id)->with([
            "message_warning" => "Please assign tags now."
        ]);
    }

 
    public function show(Hobby $hobby)
    {
        $allTags = Tag::all();
        $usedTags = $hobby->tags;
        $availableTags = $allTags->diff($usedTags);

        return view('hobby.show')->with([
            'hobby' => $hobby,
            'availableTags' => $availableTags,
            'message_success' => Session::get('message_success'),
            'message_warning' => Session::get('message_warning')
        ]);
    }

    
    public function edit(Hobby $hobby)
    {
        return view('hobby.edit')->with([
            'hobby' => $hobby,
            'message_success' => Session::get('message_success'),
            'message_warning' => Session::get('message_warning')
        ]);
    }

   public function update(Request $request, Hobby $hobby)
    {
        $request->validate([
            'name' => 'required|min:3',
            'description' => 'required|min:5',
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        if ($request->image) {
            $this->saveImages($request->image, $hobby->id);
        }

        $hobby->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);


        return $this->index()->with([
            "message_success" => "This hobby <b>". $hobby->name."</b> has been updated successfully"
        ]);
    }

   
    public function destroy(Hobby $hobby)
    {
        $OldHobby = $hobby->name;
        $hobby->delete();

        return $this->index()->with([
            "message_success" => "This hobby <b>". $OldHobby."</b> has been deleted."
        ]);
    }

    public function saveImages($imageInput, $hobby_id){

        $image = Image::make($imageInput);
        if ( $image->width() > $image->height() ) { 
            $image->widen(1200)
                ->save(public_path() . "/img/hobbies/" . $hobby_id . "_large.jpg")
                ->widen(400)->pixelate(12)
                ->save(public_path() . "/img/hobbies/" . $hobby_id . "_pixelated.jpg");
            $image = Image::make($imageInput);
            $image->widen(60)
                ->save(public_path() . "/img/hobbies/" . $hobby_id . "_thumb.jpg");
        } else { // Portrait
            $image->heighten(900)
                ->save(public_path() . "/img/hobbies/" . $hobby_id . "_large.jpg")
                ->heighten(400)->pixelate(12)
                ->save(public_path() . "/img/hobbies/" . $hobby_id . "_pixelated.jpg");
            $image = Image::make($imageInput);
            $image->heighten(60)
                ->save(public_path() . "/img/hobbies/" . $hobby_id . "_thumb.jpg");
        }

    }

    public function deleteImages($hobby_id){
        if(file_exists(public_path() . "/img/hobbies/" . $hobby_id . "_large.jpg"))
            unlink(public_path() . "/img/hobbies/" . $hobby_id . "_large.jpg");
        if(file_exists(public_path() . "/img/hobbies/" . $hobby_id . "_thumb.jpg"))
            unlink(public_path() . "/img/hobbies/" . $hobby_id . "_thumb.jpg");
        if(file_exists(public_path() . "/img/hobbies/" . $hobby_id . "_pixelated.jpg"))
            unlink(public_path() . "/img/hobbies/" . $hobby_id . "_pixelated.jpg");

        return back()->with(
            [
                'message_success' => "The Image was deleted."
            ]
        );
    }

}
