<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MigrateStorageToPublic extends Command
{
    protected $signature = 'storage:migrate-to-public
                            {--dry-run : Show what would be copied without copying}';

    protected $description = 'Copy files from storage/app/public to public/ (for hosting without symlink support)';

    protected array $directories = ['blog', 'profile', 'cv', 'projects'];

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $storagePath = storage_path('app/public');
        $publicPath = public_path();

        if (! is_dir($storagePath)) {
            $this->warn('storage/app/public does not exist.');

            return Command::SUCCESS;
        }

        $copied = 0;
        foreach ($this->directories as $dir) {
            $source = $storagePath . '/' . $dir;
            $target = $publicPath . '/' . $dir;

            if (! is_dir($source)) {
                continue;
            }

            $files = File::allFiles($source);
            foreach ($files as $file) {
                $relativePath = $file->getRelativePathname();
                $targetFile = $target . '/' . $relativePath;

                if (file_exists($targetFile) && file_get_contents($targetFile) === file_get_contents($file->getPathname())) {
                    continue;
                }

                if ($dryRun) {
                    $this->line("Would copy: {$dir}/{$relativePath}");
                } else {
                    File::ensureDirectoryExists(dirname($targetFile));
                    File::copy($file->getPathname(), $targetFile);
                    $this->line("Copied: {$dir}/{$relativePath}");
                }
                $copied++;
            }
        }

        if ($dryRun) {
            $this->info("Dry run: {$copied} file(s) would be copied.");
        } else {
            $this->info("Done. {$copied} file(s) copied.");
        }

        return Command::SUCCESS;
    }
}
