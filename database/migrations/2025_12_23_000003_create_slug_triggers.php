<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER before_insert_categories
            BEFORE INSERT ON categories
            FOR EACH ROW
            BEGIN
                IF NEW.slug IS NULL OR NEW.slug = "" THEN
                    SET NEW.slug = LOWER(REPLACE(NEW.name, " ", "-"));
                END IF;
            END
        ');
        
        DB::unprepared('
            CREATE TRIGGER before_update_categories
            BEFORE UPDATE ON categories
            FOR EACH ROW
            BEGIN
                IF NEW.slug IS NULL OR NEW.slug = "" THEN
                     SET NEW.slug = LOWER(REPLACE(NEW.name, " ", "-"));
                END IF;
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_insert_categories');
        DB::unprepared('DROP TRIGGER IF EXISTS before_update_categories');
    }
};
