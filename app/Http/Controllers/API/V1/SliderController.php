<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slider = Slider::query()
            ->select('id', 'bg_image', 'title')
            ->latest()->get();
        return response()->json([
            'data' => $slider
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $promo = Promo::findOrFail($id);
        return response()->json($promo);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promo $promo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePromoRequest $request, Promo $promo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promo $promo)
    {
        //
    }
}
