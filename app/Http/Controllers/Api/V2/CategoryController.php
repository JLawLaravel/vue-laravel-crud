<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryIndexResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;

/**
 * @group Categories
 *
 * Managing Categories
 */

 #[Group('Categories', 'Managing Categories')]

class CategoryController extends Controller
{
    /**
     * Get Categories
     *
     * Getting the list of the categories
     * 
     * @queryParam page Which page to show. Example: 12
     */
    #[Endpoint('Get Categories', <<<DESC
        Getting the list of the categories
    DESC)]
    #[QueryParam('page', 'int', 'Which page to show.', example: 12)]

    /**
     * @OA\Get(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Get list of categories",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *     )
     * )
     */

    public function index()
    {
        $category = Category::where('id', '<', 3)->get();

        return CategoryIndexResource::collection($category);
    }

    /**
     * Store a newly created resource in storage.
     */

     /**
     * POST categories
     *
     * @bodyParam name string required Name of the category. Example: "Clothing
     */

    #[BodyParam('name', 'string', 'Name of the category.', true, 'Clothing')]
    
    public function store(StoreCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
