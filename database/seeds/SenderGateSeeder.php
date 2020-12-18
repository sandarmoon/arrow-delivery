<?php

use Illuminate\Database\Seeder;
use App\SenderGate;

class SenderGateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      SenderGate::create(['name'=>'Aung Min ga lar']);
      SenderGate::create(['name'=>'Aung San']);
      SenderGate::create(['name'=>'Hlaing thar yar']);
    }
}
