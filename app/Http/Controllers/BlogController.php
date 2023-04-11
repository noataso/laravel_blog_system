<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs=Blog::latest()->paginate(3);
        $auth_user=\Auth::user()->name;

        return view('index',compact('blogs','auth_user'))
        ->with('i',(request()->input('page',1)-1)*3);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'content'=>'required',
            'image'=>'required',
        ]);
        $dir='images';
        $path=$request->file('image')->store('public/'.$dir);

        $blog=new Blog;
        $blog->title=$request->input(['title']);
        $blog->content=$request->input(['content']);
        $blog->user_id=\Auth::user()->id;
        $blog->image=$path;
        $blog->save();
        return redirect()->route('blogs.index')
        ->with('success','登録しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        if($blog->user_id!=\Auth::user()->id){
            exit();
        }
        return view('edit',compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title'=>'required',
            'content'=>'required',
        ]);
        if($request->file('image')){
            $dir='images';
            $path=$request->file('image')->store('public/'.$dir);
        }
        $blog->title=$request->input(['title']);
        $blog->content=$request->input(['content']);
        $blog->user_id=\Auth::user()->id;
        if($request->file('image')){
            $blog->image=$path;
        }
        $blog->save();
        $page=request()->input('page');
        return redirect()->route('blogs.index',['page'=>$page])
        ->with('success','更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();
        $page=request()->input('page');
        return redirect()->route('blogs.index',['page'=>$page])
        ->with('success',$blog->title.'を削除しました');
    }
}
