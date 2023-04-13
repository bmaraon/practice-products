<?php

namespace App\Http\Controllers\API\Secured;

use App\Criterias\Product\FilterByCategory;
use App\Criterias\Product\WithUserAgeRestriction;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use App\Traits\ConvertionTraits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends BaseController
{
    use ConvertionTraits;

    /**
     * Class Constructor
     *
     * @return void
     *
     */
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all users
     *
     * @param Request
     * @return JsonResponse
     *
     */
    public function index(Request $request)
    {
        $categoryIds = [];

        // with user age restriction
        if ($request->filled('withUserAgeRestriction')) {
            $withUserAgeRestriction       = !empty($request->get('withUserAgeRestriction')) ? $request->get('withUserAgeRestriction') : 0;
            $includeWithoutAgeRestriction = 0;

            if ($request->filled('includeWithoutAgeRestriction')) {
                $includeWithoutAgeRestriction = !empty($request->get('includeWithoutAgeRestriction'))
                ? $request->get('includeWithoutAgeRestriction') : $includeWithoutAgeRestriction;
            }

            // filter by categoryIds
            if ($request->filled('categoryIds')) {
                $categoryIds = !empty($request->get('categoryIds')) ? explode(',', $request->get('categoryIds')) : $categoryIds;
                $categoryIds = count($categoryIds) ? array_map([$this, 'convertToInt'], $categoryIds) : $categoryIds;
            }

            // invoke criteria
            if ($withUserAgeRestriction) {
                $this->repository->pushCriteria(
                    new WithUserAgeRestriction(
                        $includeWithoutAgeRestriction,
                        Auth::user()->age,
                        $minUseAge = 18,
                        $maxUserAge = 30,
                        $categoryIds,
                    )
                );
            }
        } else {
            // filter by categoryIds
            if ($request->filled('categoryIds')) {
                $categoryIds = !empty($request->get('categoryIds')) ? explode(',', $request->get('categoryIds')) : $categoryIds;
                $categoryIds = count($categoryIds) ? array_map([$this, 'convertToInt'], $categoryIds) : $categoryIds;

                // invoke criteria
                $this->repository->pushCriteria(new FilterByCategory($categoryIds));
            }
        }

        // limit records
        $page     = $request->filled('page') ? (int) $request->get('page') : 1;
        $perPage  = $request->filled('perPage') ? (int) $request->get('perPage') : 10;
        $products = $this->repository->paginateRecords($page, $perPage);

        // return error response
        if (empty($products)) {
            return $this->sendError('No products found.', ['error' => 'No products found.']);
        }

        // return response
        return $this->sendResponse(ProductResource::collection($products), 'Product list successfully fetched.');
    }

    /**
     * Get product by Id
     *
     * @param string $id
     * @return JsonResponse
     *
     */
    public function show(string $id)
    {
        $product = $this->repository->getRecordbyId((int) $id);

        // return error response
        if (empty($product)) {
            return $this->sendError('No product found.', ['error' => 'No product found.']);
        }

        // return response
        return $this->sendResponse(new ProductResource($product), 'Product details successfully fetched.');
    }
}
