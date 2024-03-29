<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;

class UserController extends Controller
{

    /**
     * @SWG\Get(
     *   path="/users",
     *   summary="Get all users",
     *   operationId="GetAllUser",
     *   tags={"Users"},
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }


    /**
     * @SWG\Post(
     *   path="/users",
     *   summary="Save a user",
     *   operationId="SaveUser",
     *   tags={"Users"},
     *   @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     description="User name",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="email",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="password",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $api_token = '';
        $duplicateCheck = 1;
        while($duplicateCheck != 0) {
            $api_token = str_random(60);
            $duplicateCheck = User::where('api_token', $api_token)->count();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return [
                'message' => 'Input validation failed',
                'errors' => $validator->errors()
            ];
        }

        return User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'api_token' => $api_token,
            ]);
    }

    /**
     * @SWG\Get(
     *   path="/users/{userId}",
     *   summary="Get a User",
     *   operationId="GetUser",
     *   tags={"Users"},
     *   @SWG\Parameter(
     *     name="userId",
     *     in="path",
     *     description="User id",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     *
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @SWG\Put(
     *   path="/users/{userId}",
     *   summary="Update a user",
     *   operationId="UpdateUser",
     *   tags={"Users"},
     *   @SWG\Parameter(
     *     name="userId",
     *     in="path",
     *     description="User id",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     description="User name",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="password",
     *     required=true,
     *     type="number"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return [
                'message' => 'Input validation failed',
                'errors' => $validator->errors()
            ];
        }

        $thisUser = User::findOrFail($id);
        $thisUser->name = $request->name;
        $thisUser->password = bcrypt($request->password);
        $thisUser->save();
        return $thisUser;
    }

    /**
     * @SWG\Delete(
     *   path="/users/{userId}",
     *   summary="Delete a User",
     *   operationId="DeleteUser",
     *   tags={"Users"},
     *   @SWG\Parameter(
     *     name="userId",
     *     in="path",
     *     description="User id",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $thisUser = User::findOrFail($id);
        $thisUser->delete();
        return;
    }
}
