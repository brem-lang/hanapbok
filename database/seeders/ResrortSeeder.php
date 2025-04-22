<?php

namespace Database\Seeders;

use App\Models\Resort;
use Illuminate\Database\Seeder;

class ResrortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resorts1 = [
            'Lapyahan sa Koop',
            'Sunset Haven',
            'Rain and Angel Paradise Beach Resort',
            'Sand and Sea Beach Resort',
            'C-Shore Villa',
            'Ponds Beach Resort',
            'Happy Beach Resort',
            'Dolores Beach Resort',
            'Dayok Beach Resort',
            'Batiano Beach Resort',
            'Idol\'s Beach Resort',
            'Rosana Beach Resort',
            'Brunidor Beach Resort',
            'Tagnanan Carp Beneficiaries Cooperative',
            '4 Sister\'s Tinago Beach Resort',
            'Happy Daze Resort Hotel',
            'MJS Beach Resort',
            'Zurruccah Beach Resort',
            'Diano Beach Resort',
            'Tagco Multipurpose Cooperative',
            'Kahok Dianhok Beach Resort',
            'La Familia Wooden Huts Beach Resort 1',
            'La Familia Wooden Huts Beach Resort 2',
            'YU by the Sea',
            'TJ\'s Little Bora Beach Resort',
            'DSBC Beach Resort',
            'Idex Beach Resort',
            'Sea Front Beach Resort',
            'Sedil Beach Resort',
            'Eroy Beach Haven',
            'OCOT Bora Bora Beach Resort',
            'Jack & Juns Beach Resort',
            'Meeco\'s Beach Resort',
            'JamFam Beach Haven Beach Resort',
            'Palmes Beach Resort',
            'JKEA BEACH RESORT',
        ];

        $resorts2 = [
            'Dusit Thani - Lubi Plantation',
            'Beach View Resort',
            'Sea Eagle Beach Resort',
            "Jark Nature's View Beach Resort",
            'Ana-Nastassia Beach Resort',
            'New Sentro Beach Resort',
            'Sea Breeze Beach Resort',
            'Manaklay Beach Resort',
            'Katagman Beach Resort',
        ];

        $resorts3 = [
            ' Casa de Mykaela Beach Resort',
            'Khaza de Miranda Beach Resort',
            'Rizliv Place',

        ];

        foreach ($resorts1 as $resort) {
            Resort::create([
                'name' => $resort,
                'barangay' => 'Barangay Tagnanan',
                'description' => $resort,
            ]);
        }

        foreach ($resorts2 as $resort) {
            Resort::create([
                'name' => $resort,
                'barangay' => 'Barangay Pindasan',
                'description' => $resort,
            ]);
        }

        foreach ($resorts3 as $resort) {
            Resort::create([
                'name' => $resort,
                'barangay' => 'Barangay Del Pilar',
                'description' => $resort,
            ]);
        }

        Resort::create([
            'name' => 'Gacasan Beach Resort',
            'barangay' => 'Barangay San Antonio',
            'description' => ' Gacasan Beach Resort',
        ]);
    }
}
