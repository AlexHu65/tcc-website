<?php

use Database\Seeders\HomeGalleryExampleSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        (new HomeGalleryExampleSeeder())->run();
    }

    public function down(): void
    {
        (new HomeGalleryExampleSeeder())->rollback();
    }
};
