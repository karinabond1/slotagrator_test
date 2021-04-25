<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('objects_things')->insert([
            'object_name' => 'car',
            'status' => 1
        ]);
        DB::table('objects_things')->insert([
            'object_name' => 'blender',
            'status' => 1
        ]);
        DB::table('objects_things')->insert([
            'object_name' => 'voucher',
            'status' => 1
        ]);
        DB::table('objects_things')->insert([
            'object_name' => 'phone',
            'status' => 1
        ]);
        DB::table('objects_things')->insert([
            'object_name' => 'flat',
            'status' => 1
        ]);
        DB::table('objects_things')->insert([
            'object_name' => 'watch',
            'status' => 1
        ]);
    }
}
