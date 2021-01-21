<?php

use Illuminate\Database\Seeder;
use App\User;
use App\DeliveryMan;
use Illuminate\Support\Facades\Hash;

class DeliveryMenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // One
      $user1 = new User;
      $user1->name = 'Delivery Man One';
      $user1->email = 'deliveryman1@gmail.com';
      $user1->password = Hash::make('12345678');
      $user1->save();

      $user1->assignRole('delivery_man');

      $delivery_man1 = new DeliveryMan;
      $delivery_man1->phone_no = '09-123456789';
      $delivery_man1->address = 'Baho Street, Mayangone Township';
      $delivery_man1->user_id = $user1->id;
      $delivery_man1->city_id = 1;

      $delivery_man1->save();

      $delivery_man1->townships()->attach([25,26]);


      // Two
      $user2 = new User;
      $user2->name = 'Delivery Man Two';
      $user2->email = 'deliveryman2@gmail.com';
      $user2->password = Hash::make('12345678');
      $user2->save();

      $user2->assignRole('delivery_man');

      $delivery_man2 = new DeliveryMan;
      $delivery_man2->phone_no = '09-123456789';
      $delivery_man2->address = 'Baho Street, Mayangone Township';
      $delivery_man2->user_id = $user2->id;
      $delivery_man2->city_id = 1;

      $delivery_man2->save();

      $delivery_man2->townships()->attach([2,3,4]);
    }
}
