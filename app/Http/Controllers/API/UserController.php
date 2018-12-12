<?php

namespace App\Http\Controllers\API;

use App\Services\UserService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return UserCollection
     */
    public function index()
    {
        return new UserCollection(User::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  UserService $userService
     * @return UserResource
     */
    public function store(Request $request, UserService $userService)
    {
        try {
            $user = $userService::createUser($request);
            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'status' => 400,
                    'message' => $e->getMessage()
                ]
            ], 400);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return UserResource
     */
    public function show($id)
    {
        $user = Redis::get(User::CACHEKEY . $id);

        if (empty($user)) {

            $user = User::find($id);

            if (empty($user)) {
                return response()->json([
                    'error' => [
                        'status' => 404,
                        'message' => 'Resource not found'
                    ]
                ], 404);
            }

            Redis::set(User::CACHEKEY . $id, $user);
            $user = Redis::get(User::CACHEKEY . $id);
        }

        return response()->json([
            'data' => json_decode($user)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  UserService $userService
     * @return UserResource
     */
    public function update(Request $request, UserService $userService, $id)
    {
        try {
            $user = $userService::updateUser($request, $id);
            return new UserResource($user);
        } catch (InvalidParameterException $e) {
            return response()->json([
                'error' => [
                    'status' => 404,
                    'message' => $e->getMessage()
                ]
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'status' => 400,
                    'message' => $e->getMessage()
                ]
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return UserResource
     */
    public function destroy($id)
    {
        $user = User::find($id);
        Redis::del(User::CACHEKEY . $id);

        if (empty($user)) {
            return response()->json([
                'error' => [
                    'status' => 404,
                    'message' => 'Resource not found.'
                ]
            ], 404);
        }

        $user->delete();
        return new UserResource($user);
    }
}
