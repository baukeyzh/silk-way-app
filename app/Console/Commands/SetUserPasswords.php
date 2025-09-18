<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SetUserPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:set-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set passwords for test users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = [
            'driver@example.com' => 'password123',
            'asd@dsa.qwe' => 'password123',
        ];

        foreach ($users as $email => $password) {
            $user = User::where('email', $email)->first();
            
            if ($user) {
                $user->update(['password' => Hash::make($password)]);
                $this->info("Password set for user: {$email}");
            } else {
                $this->warn("User not found: {$email}");
            }
        }

        $this->info('All passwords have been set!');
        $this->info('Use "password123" as password for test users.');
    }
}
