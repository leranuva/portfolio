<?php

namespace App\Console\Commands;

use App\Models\PortfolioConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UpdateProfileImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portfolio:update-photo {image? : Nombre del archivo de imagen (opcional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza la foto de perfil del portfolio desde storage/app/public/profile/';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $config = PortfolioConfig::first();

        if (!$config) {
            $this->error('❌ No se encontró configuración del portfolio.');
            $this->info('Ejecuta primero: php artisan db:seed --class=PortfolioSeeder');
            return Command::FAILURE;
        }

        $profileDir = storage_path('app/public/profile');
        $imageName = $this->argument('image');
        $imagePath = null;
        $finalImageName = null;

        // Si se proporciona un nombre de archivo específico
        if ($imageName) {
            $imagePath = $profileDir . '/' . $imageName;
            if (!file_exists($imagePath)) {
                $this->error("❌ No se encontró el archivo: {$imageName}");
                return Command::FAILURE;
            }
            $finalImageName = 'profile/' . $imageName;
        } else {
            // Buscar imagen automáticamente
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $possibleNames = ['profile', 'foto', 'photo', 'avatar', 'picture'];

            foreach ($possibleNames as $name) {
                foreach ($imageExtensions as $ext) {
                    $path = $profileDir . '/' . $name . '.' . $ext;
                    if (file_exists($path)) {
                        $imagePath = $path;
                        $finalImageName = 'profile/' . basename($path);
                        break 2;
                    }
                }
            }

            // Si no se encuentra, buscar cualquier imagen en la carpeta
            if (!$imagePath) {
                $files = glob($profileDir . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
                if (!empty($files)) {
                    $imagePath = $files[0];
                    $finalImageName = 'profile/' . basename($files[0]);
                }
            }
        }

        if ($imagePath && $finalImageName) {
            $config->update(['profile_image' => $finalImageName]);
            $this->info('✅ Foto de perfil actualizada exitosamente!');
            $this->line("📁 Ruta: {$finalImageName}");
            $this->line("🌐 URL: " . asset('storage/' . $finalImageName));
            return Command::SUCCESS;
        } else {
            $this->warn('⚠️  No se encontró ninguna imagen en: ' . $profileDir);
            $this->newLine();
            $this->info('📝 INSTRUCCIONES:');
            $this->line('1. Coloca tu foto en: storage/app/public/profile/');
            $this->line('2. Nombres sugeridos: profile.jpg, foto.jpg, photo.jpg, avatar.jpg');
            $this->line('3. Formatos soportados: jpg, jpeg, png, gif, webp');
            $this->line('4. Ejecuta: php artisan portfolio:update-photo');
            $this->line('   O especifica el archivo: php artisan portfolio:update-photo mi-foto.jpg');
            return Command::FAILURE;
        }
    }
}
