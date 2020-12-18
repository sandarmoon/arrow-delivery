<?php

use Illuminate\Database\Seeder;
use App\SenderPostoffice;

class SenderPostofficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      SenderPostoffice::create(['name'=>'Hlaing office']);
      SenderPostoffice::create(['name'=>'San Chaung office']);
      SenderPostoffice::create(['name'=>'Latha Office']);
    }
}
