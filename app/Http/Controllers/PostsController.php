<?php

namespace App\Http\Controllers;
use App\Post;
use App\Category;
use App\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\Posts\CreatePostRequest;
use App\Http\Requests\Posts\UpdatePostRequest;

class PostsController extends Controller
{
    public function __construct(){
        $this->middleware('verifyCategoryCount')->only('create','store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $posts)
    {
        return view('posts.index')->with('posts', $posts->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('posts.create')->with('categories',Category::all())->with('tags',Tag::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {

        $image = $request->image->store('posts');
        $post =Post::create([
          'title' => $request->title,
          'description' => $request->description,
          'content' => $request->content,
          'publish_at' => $request->publish_at,
          'image' => $image,
          'category_id' => $request->category,
          'user_id' => auth()->user()->id
        ]);
        if ($request->tags) {
          $post->tags()->attach($request->tags);
        }

        session()->flash('success', 'Post created successfully.');

        return redirect(route('posts.index'));
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        /*dd($post->tags->pluck('id')->toArray());*/
        return view('posts.create')->with('post',$post )->with('categories',Category::all())->with('tags',Tag::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request,Post $post)
    {
        $data = $request->only(['title', 'description', 'publish_at', 'content']);

        if($request->hasfile('image')){
         $image =  $request->image->store('posts');
         $post->deleteImage();
         $data['image'] = $image;
        }
        
       if($request->tags){
        $post->tags()->sync($request->tags);
       }



         $post->update($data);
         
        // flash message
        session()->flash('success', 'Post updated successfully.');

        // redirect user
        return redirect(route('posts.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $post = Post::withTrashed()->where('id', $id)->firstOrFail();
         
        if ($post->trashed()) {
           // Storage::delete($post->image);
          $post->deleteImage();
          $post->forceDelete();
        } else {
          $post->delete();
        }

        session()->flash('success', 'Post deleted successfully.');

        return redirect(route('posts.index'));
    

    }

    public function trashed()
    {
      $trashed = Post::onlyTrashed()->get();

      return view('posts.index')->with('posts', $trashed);
    }

    public function restore($id)
    {
      $post = Post::withTrashed()->where('id', $id)->firstOrFail();
      
      $post->restore();

      session()->flash('success', 'Post restored successfully.');

      return redirect()->back();
    }
    

}
