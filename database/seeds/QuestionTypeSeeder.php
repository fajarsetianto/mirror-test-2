<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionType;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuestionType::insert([[
            'name' => 'Singkat',
            'view_build_mode' => '',
            'view_live_mode' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],[
            'name' => 'Paraghraf',
            'view_build_mode' => '',
            'view_live_mode' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],[
            'name' => 'Pilihan Ganda',
            'view_build_mode' => '',
            'view_live_mode' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],[
            'name' => 'Kotak Centang',
            'view_build_mode' => '',
            'view_live_mode' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],[
            'name' => 'Dropdown',
            'view_build_mode' => '',
            'view_live_mode' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],[
            'name' => 'File Upload',
            'view_build_mode' => '',
            'view_live_mode' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]]);
    }
}
