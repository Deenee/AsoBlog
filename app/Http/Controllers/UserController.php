<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getComments($id)
    {
    	return User::find($id)->with('posts')->with('comments')->get();
    }

    public function getAllPosts()
    {
    	return Post::with('comments')->get();
    }
}
