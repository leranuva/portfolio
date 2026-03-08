<?php

namespace App\Console\Commands;

use App\Models\SiteSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    protected $signature = 'mail:test {email? : Email to send test to}';

    protected $description = 'Send a test email to verify SMTP configuration';

    public function handle(): int
    {
        $email = $this->argument('email') ?? SiteSetting::get('contact_email');

        if (empty($email)) {
            $this->error('No email specified. Use: php artisan mail:test tu@email.com');
            $this->info('Or set contact_email in Admin → Settings');
            return 1;
        }

        $this->info("Sending test email to: {$email}");
        $this->info("MAIL_MAILER: " . config('mail.default'));

        try {
            Mail::raw('Test email from ' . config('app.name') . '. If you receive this, SMTP is configured correctly.', function ($message) use ($email) {
                $message->to($email)
                    ->subject('Test email - ' . config('app.name'));
            });

            $this->info('Email sent successfully! Check your inbox (and spam folder).');
            return 0;
        } catch (\Throwable $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            $this->line($e->getTraceAsString());
            return 1;
        }
    }
}
