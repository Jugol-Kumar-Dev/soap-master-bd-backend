<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CategoryCollection;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return CategoryCollection
     */
    public function index()
    {


        $categories = Category::query()
            ->with(['courses', 'courses.category'])

            ->when(Request::input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })->when(Request::input('latest'), function($query, $search){
                $query->latest()->take($search ?? 10);
            })->when(Request::input('oldest'), function($query, $search){
                $query->oldest()->take($search ?? 10);
            });

        if(Request::input('latest') || Request::input('oldest')){
            return new CategoryCollection($categories->get());
        }

        return new CategoryCollection($categories->paginate(Request::input('perPage') ?? 10)->withQueryString());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return CategoryResource
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
