<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostIndexResource;
use App\Http\Resources\PostShowResource;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderColumn = request('order_column', 'created_at'); 
        if (! in_array($orderColumn, ['id', 'title', 'created_at'])) { 
            $orderColumn = 'created_at';
        } 
        $orderDirection = request('order_direction', 'desc'); 
        if (! in_array($orderDirection, ['asc', 'desc'])) { 
            $orderDirection = 'desc';
        } 

        $posts = Post::with('category')
            ->when(request('category'), function (Builder $query) { 
                $query->where('category_id', request('category'));
            }) 
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(5);
 
        return PostIndexResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try {
            $posts = Post::create($request->all());
    
            return PostShowResource::make($posts);
            //code...
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return PostShowResource::make($post);
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
