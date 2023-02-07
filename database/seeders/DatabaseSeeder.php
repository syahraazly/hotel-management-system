<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Rooms;
use App\Models\User;
use App\Models\Type;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $users = [
            [
                'name' => 'recep1',
                'email' => 'recep@gmail.com',
                'role' => '2',
                'password' => bcrypt('123456')
            ],
            [
                'name' => 'admin1',
                'email' => 'admin@gmail.com',
                'role' => '1',
                'password' => bcrypt('123456')
            ],
            [
                'name' => 'syahra',
                'email' => 'syahra@gmail.com',
                'role' => '0',
                'password' => bcrypt('123456')
            ]
        ];

        foreach ($users as $key => $user) {
            User::create($user);
        }
        // $filePath = '/app/public/hotel';

        // SEED USER END
        
        $types = [
            [
                'type_name' => 'standart',
                'price' => '250000',
                'desc' => 'include bed, television, and private bathroom',
          
            ],
            [
                'type_name' => 'suite',
                'price' => '550000',
                'desc' => 'include bed, television, private bathroom, living area, and kitchen',
              
            ],
            [
                'type_name' => 'presidential',
                'price' => '755000',
                'desc' => 'include bed, television, private bathroom, living area, kitchen, and private balcony',
            
            ]
        ];
        foreach ($types as $key => $type){
            Type::create($type);
        }


        $rooms = [
            [
                'type_id' => '1',
                'room_number' => '101',
          
            ],
            [
                'type_id' => '1',
                'room_number' => '102',
            ],
            [
                'type_id' => '1',
                'room_number' => '103',
            ],
            [
                'type_id' => '2',
                'room_number' => '201',
            ],
            [
                'type_id' => '3',
                'room_number' => '301',
            ],
        ];

        foreach ($rooms as $key => $room){
            Rooms::create($room);
        }
    }
}
