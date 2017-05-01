<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use Response;
use Illuminate\Support\Facades\Validator;
use Purifier;

class ArticlesController extends Controller
{
  public function index () //list of articles     //CRUD functions
  {
    $articles = Article::orderBy("id", "desc")->take(6)->get();
    foreach($articles as $key => $article)
    {
      if(strlen($article->body)>100)
      {
        $article->body = substr($article->body,0,100)."...";

      }
    }
    return Response::json($articles);

  }

  public function store (Request $request) //stores article
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

    $article = new Article;

    $article->title = $request->input('title');
    $article->body = $request->input('body');

    $image = $request->file('image');
    $imageName = $image->getClientOriginalName();
    $image->move('storage/', $imageName);
    $article->image = $request->root().'/storage/'.$imageName;

    $article->save();

    return Response::json(["success" => "Congrats, You did it."]);

  }


  public function update ($id, Request $request) //update ourt article
  {
    $article = Article::find($id);

    $article->title = $request->input('title');
    $article->body = $request->input('body');

    $image = $request->file('image');
    $imageName = $image->getClientOriginalName();
    $image->move('storage/', $imageName);
    $article->image = $request->root().'/storage/'.$imageName;
    $article->save();

    return Response::json(["success" => "Congrats, You did it."]);

  }


  public function show ($id) //show single article
  {
    $article = Article::find($id);

    return Response::json($article);
  }


  public function destroy ($id) //destroys article
  {
    $article = Article::find($id);

    $article->delete();

    return Response::json(["success" => "Deleted article."]);

  }


}
