<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Response;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Auth;
use JWTAuth;

class PostController extends Controller
{
  public function __construct()
  {
    $this->middleware('jwt.auth', ['only' => ['store','destroy']]);
  }
  public function index ()
  {
    $posts = Post::orderBy("id","desc")->take(6)->get();
    foreach($posts as $key => $post)
    {
      if(strlen($post->body)>100)
      {
        $post->body = substr($post->body,0,100)."...";
      }
    }

  return Response::json($posts);
}

public function store (Request $request)
{
  $rules = [
    'title' => 'required',
    'body' => 'required',
    'image' => 'required',
  ];

  $validator = Validator::make(Purifier::clean($request->all()), $rules);
  if($validator->fails())
  {
    return Response::json(["error" => "You need to fill out all fields."]);
  }

  $post = new Post;

  $post->title = $request->input('title');
  $post->body = $request->input('body');

  $image = $request->file('image');
  $imageName = $image->getClientOriginalName();
  $image->move('storage/', $imageName);
  $post->image = $request->root().'/storage/'.$imageName;
  $post->userID = Auth::user()->id;

  $post->save();

  return Response::json(["success" => "Congrats, You did it."]);

}

public function show ($id)
{
  $post = Post::find($id);

  return Response::json($post);
}

public function destroy ($id)
{
  $post = Post::find($id);

  $post->delete();

  return Response::json(["success" => "Deleted article."]);
}

}
