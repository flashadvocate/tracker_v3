<?php

namespace App\Policies;

use App\Models\Division;
use App\Models\Squad;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class SquadPolicy.
 */
class SquadPolicy
{
    use HandlesAuthorization;

    /**
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->isRole(['admin'])
            || $user->isDeveloper()
        ) {
            return true;
        }
    }

    /**
     * @return bool
     */
    public function update(User $user, Squad $squad)
    {
        if ($user->member->division_id === $squad->platoon->division_id && !$user->isRole(['officer', 'member'])) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function delete(User $user, Squad $squad)
    {
        return false;
        // mimic create permissions
        // return $this->create($user, $squad->division);
    }

    /**
     * @return bool
     */
    public function create(User $user)
    {
        if ($user->isRole(['sr_ldr'])) {
            return true;
        }

        return false;
    }
}
