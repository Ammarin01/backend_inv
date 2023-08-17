<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ลบข้อมูลเก่าออกไปก่อน
        DB::table('users')->delete();

        $data = [
            'fullname' => 'Samit Koyom',
            'username' => 'iamsamit',
            'email' => 'samit@email.com',
            'password' => Hash::make('123456'),
            'tel' => '0878895487',
            'avatar' => 'https://via.placeholder.com/400x400.png/005429?text=udses',
            'role' => '1',
            'remember_token' => 'XBWyeaiest'
        ];
        User::create($data);

        // ทำการเรียกตัว UserFactory ที่จะทำการ Faker ข้อมูลให้
        User::factory(99)->create();
    }
}