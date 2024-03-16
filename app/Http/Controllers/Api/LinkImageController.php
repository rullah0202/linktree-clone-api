<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;

class LinkImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg'
        ]);

        try {
            $link = Link::where('id',$request->input('id'))
                ->where('user_id',auth()->user()->id)
                ->first();
            $link = (new FileService)->updateImage($link,$request);
            $link->save();

            return response()->json(['LINK IMAGE UPDATED SUCCESSFULLY'],200);
        } catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()],400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
