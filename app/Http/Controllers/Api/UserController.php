<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\User as UserResource;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }    
    
    
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return UserResource::collection(
            User::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return Response
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] =  bcrypt($validated['password']);        
        $validated['email_verified_at'] = Carbon::now();
        
        return User::create($validated);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return User
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return User
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $validated = $request->validated();
        
        // Updating the password is optional
        if (isset($validated['password'])) {
            $validated['password'] =  bcrypt($validated['password']);
        }
            
        // Only an admin can set a role (managers only create regular users)
        if ($request->user()->role !== User::ROLE_ADMIN) {
            unset($validated['role']);
        }
        
        $user->update($validated);

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return bool
     * @throws Exception
     */
    public function destroy(User $user)
    {
        return $user->delete();
    }
}
