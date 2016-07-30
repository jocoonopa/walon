<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CharactersTableSeeder::class);
        $this->call(AssignsTableSeeder::class);
    }
}

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Model\User::class, 10)->create();
    }
}

class CharactersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $data = [
            [
                'id'     => 1,
                'name'     => '梅林',
                'des'      => $faker->realText(),
                'image'   => $faker->imageUrl(),
                'is_good' => true
            ],
            [
                'id'     => 2,
                'name'     => '派西維爾',
                'des'      => $faker->realText(),
                'image'   => $faker->imageUrl(),
                'is_good' => true
            ],
            [
                'id'     => 3,
                'name'     => '忠臣',
                'des'      => $faker->realText(),
                'image'   => $faker->imageUrl(),
                'is_good' => true
            ],
            [
                'id'     => 4,
                'name'     => '莫德雷德',
                'des'      => $faker->realText(),
                'image'   => $faker->imageUrl(),
                'is_good' => false
            ],
            [
                'id'     => 5,
                'name'     => '魔甘娜',
                'des'      => $faker->realText(),
                'image'   => $faker->imageUrl(),
                'is_good' => false
            ],
            [
                'id'     => 6,
                'name'     => '刺客',
                'des'      => $faker->realText(),
                'image'   => $faker->imageUrl(),
                'is_good' => false
            ],
            [
                'id'     => 7,
                'name'     => '奧伯倫',
                'des'      => $faker->realText(),
                'image'   => $faker->imageUrl(),
                'is_good' => false
            ],
            [
                'id'     => 8,
                'name'     => '爪牙',
                'des'      => $faker->realText(),
                'image'   => $faker->imageUrl(),
                'is_good' => false
            ],
        ];

        if (0 === App\Model\Character::count()) {
            DB::table('characters')->insert($data);
        }        
    }
}

class AssignsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'count'        => 5,
                'good_choices' => json_encode([1,2,3]),
                'bad_choices'  => json_encode([6,8]),
                'places'       => json_encode([2,3,2,3,3])
            ],
            [
                'count'        => 6,
                'good_choices' => json_encode([1,2,3,3]),
                'bad_choices'  => json_encode([5,6]),
                'places'       => json_encode([2,3,4,3,4])
            ],
            [
                'count'        => 7,
                'good_choices' => json_encode([1,2,3,3]),
                'bad_choices'  => json_encode([5,6,7]),
                'places'       => json_encode([2,3,3,4,4])
            ],
            [
                'count'        => 8,
                'good_choices' => json_encode([1,2,3,3,3]),
                'bad_choices'  => json_encode([5,6,8]),
                'places'       => json_encode([3,4,4,5,5])
            ],
            [
                'count'        => 9,
                'good_choices' => json_encode([1,2,3,3,3,3]),
                'bad_choices'  => json_encode([4,5,6]),
                'places'       => json_encode([3,4,4,5,5])
            ],
            [
                'count'        => 10,
                'good_choices' => json_encode([1,2,3,3,3,3]),
                'bad_choices'  => json_encode([4,5,6,7]),
                'places'       => json_encode([3,4,4,5,5])
            ]
        ];

        if (0 === App\Model\Assign::count()) {
            DB::table('assigns')->insert($data);
        }
    }
}
