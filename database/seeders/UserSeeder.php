<?php

namespace Database\Seeders;

use Woo\ACL\Models\User;
use Woo\ACL\Repositories\Interfaces\ActivationInterface;
use Woo\Base\Supports\BaseSeeder;
use Schema;

class UserSeeder extends BaseSeeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        User::truncate();

        $user = new User();
        $user->first_name = 'System';
        $user->last_name = 'Admin';
        $user->email = 'admin@Woo.com';
        $user->username = 'Woo';
        $user->password = bcrypt('159357');
        $user->super_user = 1;
        $user->manage_supers = 1;
        $user->save();

        $activationRepository = app(ActivationInterface::class);

        $activation = $activationRepository->createUser($user);

        $activationRepository->complete($user, $activation->code);
    }
}
