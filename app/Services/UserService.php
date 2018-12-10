<?php

namespace App\Services;

use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;
use Symfony\Component\Routing\Exception\InvalidParameterException;

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

        return $user;
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