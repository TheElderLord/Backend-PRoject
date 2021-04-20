<?php

namespace App\Http\Controllers;

use App\tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    
    public function index()
    {
        $tags = Tag::all();
        return view('tag.index')->with([
            'tags'=>$tags
        ]);
    }

   
    public function create()
    {
        return view('tag.create');
    }

  
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|min:3',
            'style' => 'required|min:3',
        ]);

        $tag =  new Tag([
            'name' => $request->name,
            'style' => $request->style,
        ]);

        $tag->save();

        return $this->index()->with([
            "message_success" => "This tag <b>". $tag->name."</b> has been create successfully"
        ]);
    }

  
    public function show(Tag $tag)
    {
        return view('tag.show')->with([
            'tag' => $tag
        ]);
    }

   
    public function edit(Tag $tag)
    {
        return view('tag.edit')->with([
            'tag' => $tag
        ]);
    }

   
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|min:3',
            'style' => 'required|min:3',
        ]);

        $tag->update([
            'name' => $request->name,
            'style' => $request->style,
        ]);



        return $this->index()->with([
            "message_success" => "This Tag <b>". $tag->name."</b> has been updated successfully"
        ]);
    }

    
    public function destroy(Tag $tag)
    {
        $OldTag = $tag->name;
        $tag->delete();

        return $this->index()->with([
            "message_success" => "This tag <b>". $OldTag."</b> has been deleted."
        ]);
    }
}
