<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\User;
use Illuminate\Support\Facades\Hash;
class UserCreate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = "Admin";
        $user->email = "superuser@gmail.com";
        $user->email_verified_at = Date('Y-m-d H:i:s');
        $user->password = Hash::make(12345);
        $user->save();
    }
}
