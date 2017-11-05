<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Validator;

class CommentController extends Controller
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
        $comments = Comment::all();
            return response()->json([
                'message' => 'Comments retrieved successfully.',
                'code' => '000',
                'data'=> $comments
                ],200);
    }

    // *
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
     
    // public function create()
    // {
    //     //
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
            'body'=> 'required',
            'user_id' => 'required',
            'post_id' => 'exists:posts'
            ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed. Check \'data\' to see which fields failed',
                'code' => '001',
                'data'=> []
                ],200);
        }
        try {
             $comment = Comment::create([
            'user_id' => request()->user_id,
            'post_id' => request()->post_id,
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
                'message' => 'Comment Created Successfully.',
                'code' => '000',
                'data'=> []
                ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            return response()->json([
                'message' => 'Comment retrieved Successfully.',
                'code' => '000',
                'data'=> $comment
                ],200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Comment not found.',
                'code' => '002',
                'data'=> []
                ],200);
        }
        catch (NotFoundHttpException $e) {
            return response()->json([
                'message' => 'Comment not found.',
                'code' => '002',
                'data'=> []
                ],200);
       }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    // public function edit(Comment $comment)
    // {
    //     //return view to edit the comment
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $comment->update(['body'=> request()->body]);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Comment not found.',
                'code' => '002',
                'data'=> []
                ],200);
        }
         catch (\Exception $e) {
            return response()->json([
                'message' => 'Comment Update Failed.',
                'code' => '111',
                'data'=> $comment
                ],200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $comment = Post::findOrFail($id);
            $comment->delete();
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Comment not found.',
                'code' => '002',
                'data'=> []
                ],200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'code' => '111',
                'data'=> $comment
                ],200);
        }
            return response()->json([
                'message' => 'Comment Deleted.',
                'code' => '000',
                'data'=> []
                ],200);
    }
}
