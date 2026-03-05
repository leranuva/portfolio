<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('site_settings')
            ->where('key', 'contact_github')
            ->update(['value' => 'https://github.com/leranuva']);

        Cache::forget('site_setting_contact_github');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('site_settings')
            ->where('key', 'contact_github')
            ->update(['value' => 'https://github.com/ramironuva']);

        Cache::forget('site_setting_contact_github');
    }
};
