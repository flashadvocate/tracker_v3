<?php

namespace App\Http\Controllers;

use App\Member;
use App\Role;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    use AuthorizesRequests;

    public function edit(Member $member)
    {
        $this->authorize('update', $member->user);

        $user = $member->user;
        $division = $member->division;

        $roles = auth()->user()->isDeveloper()
            ? Role::all()->pluck('label', 'id')
            : Role::where('id', '<', auth()->user()->role->id)->pluck('label', 'id');

        return view(
            'member.edit-user',
            compact('user', 'division', 'member', 'roles')
        );
    }

    /**
     * Updates a given user's role
     *
     * @param Request $request
     * @return Response
     */
    public function updateRole(Request $request)
    {
        $user = User::find($request->user);
        $this->authorize('update', $user);

        // cannot grant role greater than or equal to your own
        // disregard for developers
        if (!auth()->user()->isDeveloper()
            && $request->role >= auth()->user()->role_id
        ) {
            return response()->json(['error' => 'Not authorized.'], 403);
        }

        $user->assignRole(Role::find($request->role));
        $user->recordActivity('role_granted_to', $user->member);
    }
}
