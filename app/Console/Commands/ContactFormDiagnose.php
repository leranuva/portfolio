<?php

namespace App\Console\Commands;

use App\Models\ContactMessage;
use App\Models\Lead;
use App\Models\SiteSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ContactFormDiagnose extends Command
{
    protected $signature = 'contact:diagnose';

    protected $description = 'Diagnose contact form setup (tables, settings, recent submissions)';

    public function handle(): int
    {
        $this->info('=== Contact Form Diagnostic ===');
        $this->newLine();

        // 1. Tables
        $tables = ['leads', 'contact_messages', 'site_settings', 'lead_events'];
        foreach ($tables as $table) {
            $exists = Schema::hasTable($table);
            $this->line($exists ? "  ✓ Table <fg=green>{$table}</> exists" : "  ✗ Table <fg=red>{$table}</> missing");
        }
        $this->newLine();

        // 2. contact_email setting
        $contactEmail = SiteSetting::get('contact_email');
        if ($contactEmail) {
            $this->line("  ✓ contact_email: <fg=green>{$contactEmail}</>");
        } else {
            $this->line('  ✗ contact_email not set in Site Settings (admin emails will not be sent)');
        }
        $this->newLine();

        // 3. Recent leads & messages
        try {
            $leadCount = Lead::count();
            $msgCount = ContactMessage::count();
            $this->line("  Leads in DB: <fg=cyan>{$leadCount}</>");
            $this->line("  Contact messages in DB: <fg=cyan>{$msgCount}</>");

            if ($leadCount > 0) {
                $last = Lead::latest()->first();
                $this->line("  Last lead: {$last->email} at {$last->created_at}");
            }
            if ($msgCount > 0) {
                $last = ContactMessage::latest()->first();
                $this->line("  Last message: {$last->email} at {$last->created_at}");
            }
        } catch (\Throwable $e) {
            $this->error("  Error querying: {$e->getMessage()}");
        }
        $this->newLine();

        // 4. Recent log entries
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $this->line('  Tip: After submitting the form, check for [ContactForm] entries in:');
            $this->line("  <fg=gray>{$logPath}</>");
            $this->line('  If you see nothing, the Livewire request may not be reaching the server.');
        }

        $this->newLine();
        $this->info('=== End diagnostic ===');

        return Command::SUCCESS;
    }
}
