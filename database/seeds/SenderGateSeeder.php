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
      SenderGate::create(['name'=>'Aungmingalar']);
      SenderGate::create(['name'=>'Aungsan']);
      SenderGate::create(['name'=>'Hlaingtharyar']);
      SenderGate::create(['name'=>'Bayintnaung']);
    }
}
