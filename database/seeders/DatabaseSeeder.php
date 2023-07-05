<?php
namespace Database\Seeders;
use App\Models\Professor;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Professor::factory(100)->create();
    }
}
