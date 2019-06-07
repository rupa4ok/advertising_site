<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class RoleCommand extends Command
{
    protected $signature = 'user:role {email} {role}';

    protected $description = 'Set role for user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->argument('role');

        if (! $user = User::where('email', $email)->first()) {
            $this->error('Undefined user'.$email);

            return false;
        }

        try {
            $user->changeRole($role);
        } catch (\DomainException $e) {
            return false;
        }

        $this->info('Role is successfully changed');

        return true;
    }
}
