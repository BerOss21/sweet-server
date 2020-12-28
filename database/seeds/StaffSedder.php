<?php

use Illuminate\Database\Seeder;

class StaffSedder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\models\Staff::class, 4)->create();
    }
}
