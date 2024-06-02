<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::query()->with('user:id,name')
            ->select(['id', 'user_id', 'title', 'description', 'view_count'])
            ->simplePaginate(12);
        return response()->json([
            "data" => $blogs,
        ]);
    }

    public function show($id){
        $blog = Blog::query()
            ->with(['user', 'category', 'category.blogs','comments.user', 'comments.replayComments', 'comments.replayComments.user', 'comments' => function($comment){
                return $comment->where('comment_id', null);
            }])->findOrFail($id);

        $blog->view_count = $blog->view_count + 1;
        $blog->save();


        $resentBlogs = Blog::query()->select('id', 'title', 'image', 'view_count', 'created_at')->latest()->take(5)->get();
        $popularBlogs = Blog::query()->select('id', 'title', 'image', 'view_count', 'created_at')->orderByDesc('view_count')->take(5)->get();


        return response()->json([
            "data" => $blog,
            'resent' => $resentBlogs,
            'popular' => $popularBlogs
        ]);
    }
}
