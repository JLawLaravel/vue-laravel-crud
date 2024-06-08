<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostIndexResource;
use App\Http\Resources\PostShowResource;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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

        // Search for every column
        $posts = Post::with('category')
            ->when(request('search_category'), function (Builder $query) { 
                $query->where('category_id', request('search_category'));
            })
            ->when(request('search_id'), function (Builder $query) {
                $query->where('id', request('search_id'));
            })
            ->when(request('search_title'), function (Builder $query) {
                $query->where('title', 'like', '%' . request('search_title') . '%');
            })
            ->when(request('search_content'), function (Builder $query) {
                $query->where('content', 'like', '%' . request('search_content') . '%');
            }) 
            ->when(request('search_global'), function (Builder $query) { 
                $query->whereAny([
                        'id',
                        'title',
                        'content',
                    ], 'LIKE', '%' . request('search_global') . '%');
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
        Gate::authorize('posts.create'); 

        if ($request->hasFile('thumbnail')) { 
            $filename = $request->file('thumbnail')->getClientOriginalName();
            info($filename);
        } 

        $post = Post::create($request->all());

        return PostShowResource::make($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        Gate::authorize('posts.update');

        return PostShowResource::make($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Post $post, StorePostRequest $request)
    {
        Gate::authorize('posts.update');

        $post->update($request->all());

        return new PostShowResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('posts.delete');
        
        $post->delete();

        return response()->noContent();
    }
}
