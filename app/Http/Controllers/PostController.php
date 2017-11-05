<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Validator;

class PostController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // returns all posts with their comments
        // returns an empty array if there are no posts

        $posts = Post::with('comments')->get();
            return response()->json([
                'message' => 'Posts retrieved successfully',
                'code' => '000',
                'data'=> $posts
                ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
        
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'title' => 'required|unique:posts',
            'body'=> 'required|min:10',
            'user_id' => 'required'
            ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed. Check \'data\' to see which fields failed',
                'code' => '001',
                'data'=> $validator->errors()
                ],200);
        }
        try {
             $post = Post::create([
            'title' => request()->title,
            'user_id' => request()->user_id,
            'body' => request()->body
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'code' => '111',
                'data'=> []
                ],200);
        }  
        return response()->json([
                'message' => 'Post Created Successfully.',
                'code' => '000',
                'data'=> []
                ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);
            return response()->json([
                'message' => 'Post retrieved Successfully.',
                'code' => '000',
                'data'=> $post
                ],200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Post not found.',
                'code' => '002',
                'data'=> []
                ],200);
        }
        catch (NotFoundHttpException $e) {
            return response()->json([
                'message' => 'Post not found.',
                'code' => '002',
                'data'=> []
                ],200);
       }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    // public function edit(Post $post)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->update(['title'=> request()->title, 'body'=> request()->body]);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Post not found.',
                'code' => '002',
                'data'=> []
                ],200);
        }
         catch (\Exception $e) {
            return response()->json([
                'message' => 'Post Update Failed.',
                'code' => '111',
                'data'=> $post
                ],200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Post not found.',
                'code' => '002',
                'data'=> []
                ],200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'code' => '111',
                'data'=> $post
                ],200);
        }
            return response()->json([
                'message' => 'Post Deleted.',
                'code' => '000',
                'data'=> []
                ],200);
        
    }
}
