<?php

namespace Database\Seeders;

use App\Models\Roles;
use App\Models\Producer;
use App\Models\TypeAnnounce;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::reguard();
        // $this->call(Permission::class);
        // $this->call(Role::class);
        // $this->call(ProjectSeeder::class);
        // $this->call(BuildingSeeder::class);
        // $this->call(FloorSeeder::class);
        // $this->call(RoomTypeSeeder::class);
         $this->call(RoomSeeder::class);
        // $this->call(BedSeeder::class);
        // $this->call(Asset_typeSeeder::class);
        // $this->call(Unit_assetSeeder::class);
        // $this->call(ProducerSeeder::class);
        // $this->call(AssetSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(ReceiptsReasonSeeder::class);
        // $this->call(ReceiptsSeeder::class);
        // $this->call(TypeAnnouceSeeder::class);
        // $this->call(AnnouncementSeeder::class);
        Model::reguard();
    }
}
