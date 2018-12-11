<?php

namespace App\Services;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class UserService
{
    /**
     * Check if request has valid data and create a new User.
     *
     * @param Request $request
     * @throws \Exception
     * @return User
     */
    public static function createUser(Request $request): User
    {
        $submittedData = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'country' => 'required',
            'state' => 'required|max:2',
            'city' => 'required',
            'address' => 'required',
            'number' => 'required',
            'zipcode' => 'required'
        ]);

        if (!$submittedData->fails()) {
            $user = User::create($request->all());
            Redis::set(User::CACHEKEY . $user->id, $user);
            return $user;
        }

        $errorMessage = UserService::parseErrorMessages($submittedData->getMessageBag()->getMessages());
        throw new \Exception($errorMessage);
    }

    /**
     * Check if request has valid data, if resource exists and update the given User
     *
     * @param Request $request
     * @param int $id
     * @return User
     * @throws \Exception
     */
    public static function updateUser(Request $request, int $id): User
    {
        $user = User::find($id);

        if (empty($user)) {
            throw new InvalidParameterException('Resource not found.');
        }

        $submittedData = Validator::make($request->all(), [
            'email' => 'unique:users,email',
            'state' => 'max:2',
        ]);

        if ($submittedData->fails()) {
            $errorMessage = UserService::parseErrorMessages($submittedData->getMessageBag()->getMessages());
            throw new \Exception($errorMessage);
        }

        $user->fill($request->all());
        $user->save();
        Redis::set(User::CACHEKEY . $user->id, $user);

        return $user;
    }

    /**
     * Helper function to parse arrays of error messages in to a single string.
     *
     * @param array $errors
     * @return string
     */
    public static function parseErrorMessages(array $errors): string
    {
        $errorMessage = '';

        foreach ($errors as $key => $error) {
            $errorMessage .= 'Error in parameter ' . $key . ': ' . $error[0] . ' ';
        }

        return rtrim($errorMessage, ' ');
    }
}