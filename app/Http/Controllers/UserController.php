<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;
    public function __construct()
    {
        $this->userService = new UserService(request()->user());
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = request()->user();

        return send_response(true, [$user], MCH_model_retrieved('User'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $userRequest, User $user)
    {
        $validatedData = $userRequest->validated();

        [$status, $data, $message, $status_code] = $this->userService->update_user($validatedData, $user);

        return send_response($status, $data, $message, $status_code);
    }

    public function init_delete()
    {
        [$status, $data, $message, $status_code] = $this->userService->init_delete();

        return send_response($status, $data, $message, $status_code);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserRequest $userRequest)
    {
        $user = $userRequest->user();

        [$status, $data, $message, $status_code] = $this->userService->delete($user);

        return send_response($status, $data, $message, $status_code);
    }
}
