<?php

namespace App\Http\Controllers\API\Secured;

use App\Criterias\User\FilterByAge;
use App\Criterias\User\FilterByAgeRange;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class UserController extends BaseController
{
    /**
     * Class Constructor
     *
     * @return void
     *
     */
    public function __construct(UserRepository $repository)
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
        // filter by exact age
        if ($request->filled('age')) {
            $age = !empty($request->get('age')) ? $request->get('age') : 0;

            // invoke criteria
            $this->repository->pushCriteria(new FilterByAge($age));
        }

        // filter by age range
        if ($request->filled('minAge') && $request->filled('maxAge')) {
            $minAge = !empty($request->get('minAge')) ? $request->get('minAge') : 0;
            $maxAge = !empty($request->get('maxAge')) ? $request->get('maxAge') : 0;

            // invoke criteria
            $this->repository->pushCriteria(new FilterByAgeRange($minAge, $maxAge));
        }

        // limit records
        $page    = $request->filled('page') ? (int) $request->get('page') : 1;
        $perPage = $request->filled('perPage') ? (int) $request->get('perPage') : 10;
        $users   = $this->repository->paginateRecords($page, $perPage);

        // return error response
        if (empty($users)) {
            return $this->sendError('No users found.', ['error' => 'No users found.']);
        }

        // return response
        return $this->sendResponse(UserResource::collection($users), 'User list successfully fetched.');
    }

    /**
     * Get user by Id
     *
     * @param string $id
     * @return JsonResponse
     *
     */
    public function show(string $id)
    {
        $user = $this->repository->getRecordbyId((int) $id);

        // return error response
        if (empty($user)) {
            return $this->sendError('No user found.', ['error' => 'No user found.']);
        }

        // return response
        return $this->sendResponse(new UserResource($user), 'User details successfully fetched.');
    }

    /**
     * Get logged in user
     *
     * @return JsonResponse
     *
     */
    public function getLoggedInUser()
    {
        $loggedInUser = Auth::user();

        // return error response
        if (!isset($loggedInUser->id)) {
            return $this->sendError('No logged in user found.', ['error' => 'No logged in user found.']);
        }

        // return response
        return $this->sendResponse(new UserResource($loggedInUser ?? null), 'Logged in user details successfully fetched.');
    }
}
