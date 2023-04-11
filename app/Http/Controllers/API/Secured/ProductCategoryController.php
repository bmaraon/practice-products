<?php

namespace App\Http\Controllers\API\Secured;

use App\Criterias\ProductCategory\WithUserAgeRestriction;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\ProductCategoryResource;
use App\Repositories\ProductCategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends BaseController
{
    /**
     * Class Constructor
     *
     * @return void
     *
     */
    public function __construct(ProductCategoryRepository $repository)
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
        // with user age restriction
        if ($request->filled('withUserAgeRestriction')) {
            $withUserAgeRestriction       = !empty($request->get('withUserAgeRestriction')) ? $request->get('withUserAgeRestriction') : 0;
            $includeWithoutAgeRestriction = 0;

            if ($request->filled('includeWithoutAgeRestriction')) {
                $includeWithoutAgeRestriction = !empty($request->get('includeWithoutAgeRestriction')) ? $request->get('includeWithoutAgeRestriction') : 0;
            }

            // invoke criteria
            if ($withUserAgeRestriction) {
                $this->repository->pushCriteria(new WithUserAgeRestriction($includeWithoutAgeRestriction, Auth::user()->age, $minUseAge = 18, $maxUserAge = 30));
            }
        }

        // limit records
        $page       = $request->filled('page') ? (int) $request->get('page') : 1;
        $perPage    = $request->filled('perPage') ? (int) $request->get('perPage') : 10;
        $categories = $this->repository->paginateRecords($page, $perPage);

        // return error response
        if (empty($categories)) {
            return $this->sendError('No product category list found.', ['error' => 'No product category list found.']);
        }

        // return response
        return $this->sendResponse(ProductCategoryResource::collection($categories), 'Product category list successfully fetched.');
    }
}
