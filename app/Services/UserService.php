<?php

namespace App\Services;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserService
{
    /**
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
            return $user;
        }

        $errorMessage = UserService::parseErrorMessages($submittedData->getMessageBag()->getMessages());
        throw new \Exception($errorMessage);
    }

    /**
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