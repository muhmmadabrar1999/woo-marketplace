<?php

namespace Woo\ACL\Events;

use Woo\ACL\Models\Role;
use Woo\ACL\Models\User;
use Woo\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class RoleAssignmentEvent extends Event
{
    use SerializesModels;

    public Role $role;

    public User $user;

    public function __construct(Role $role, User $user)
    {
        $this->role = $role;
        $this->user = $user;
    }
}
