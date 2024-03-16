<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LinksCollection;
use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $linksPerPage = 3;
            $links = Link::where('user_id',auth()->user()->id)->simplePaginate($linksPerPage);
            $pageCount = count(Link::all()) / $linksPerPage;

            return response()->json([
                'paginate' => new LinksCollection($links),
                'page_count' => ceil($pageCount)
            ],200);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()],400);
        }
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
            'name' => 'required|max:20',
            'url' => 'required|active_url'
        ]);

        try {
            $link = new Link;

            $link->user_id = auth()->user()->id;
            $link->name = $request->input('name');
            $link->url = $request->input('url');
            $link->image = '/link-placeholder.png';

            $link->save();

            return response()->json(['NEW LINK CREATED'],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()],400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {

            $link = Link::with('user')->findOrFail($id);
            return response()->json($link,200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in LinkController.show',
                'error' =>$e->getMessage()
            ],400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Link $link)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Link $link)
    {
        $request->validate([
            'name' => 'required|max:20',
            'url' => 'required'
        ]);

        try {
            $link->name = $request->input('name');
            $link->url = $request->input('url');

            $link->save();

            return response()->json(['LINK DETAILS UPDATED SUCCESSFULLY'],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Link $link)
    {
        try {
            if (
                !is_null($link->image)
                && file_exists(public_path() . $link->image
                && $link->image != '/user-placeholder.png'
                && $link->image != '/link-placeholder.png'
            )) {
                unlink(public_path() . $link->image);
            }
            $link->delete();

            return response()->json('LINK DETAILS DELETEED', 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
