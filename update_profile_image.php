<?php
/**
 * Script para actualizar la foto de perfil del portfolio
 * 
 * INSTRUCCIONES:
 * 1. Coloca tu foto en: storage/app/public/profile/profile.jpg
 * 2. Ejecuta este script: php update_profile_image.php
 * 
 * Formatos soportados: jpg, jpeg, png, gif
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$config = App\Models\PortfolioConfig::first();

if (!$config) {
    echo "❌ No se encontró configuración del portfolio.\n";
    exit(1);
}

// Buscar imagen en la carpeta profile
$profileDir = storage_path('app/public/profile');
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
$imagePath = null;
$imageName = null;

foreach ($imageExtensions as $ext) {
    $possiblePaths = [
        $profileDir . '/profile.' . $ext,
        $profileDir . '/foto.' . $ext,
        $profileDir . '/photo.' . $ext,
        $profileDir . '/avatar.' . $ext,
    ];
    
    foreach ($possiblePaths as $path) {
        if (file_exists($path)) {
            $imagePath = $path;
            $imageName = 'profile/' . basename($path);
            break 2;
        }
    }
}

// Si no se encuentra, buscar cualquier imagen en la carpeta
if (!$imagePath) {
    $files = glob($profileDir . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    if (!empty($files)) {
        $imagePath = $files[0];
        $imageName = 'profile/' . basename($files[0]);
    }
}

if ($imagePath && $imageName) {
    $config->update(['profile_image' => $imageName]);
    echo "✅ Foto de perfil actualizada exitosamente!\n";
    echo "📁 Ruta: " . $imageName . "\n";
    echo "🌐 URL: " . asset('storage/' . $imageName) . "\n";
} else {
    echo "⚠️  No se encontró ninguna imagen en: " . $profileDir . "\n";
    echo "\n📝 INSTRUCCIONES:\n";
    echo "1. Coloca tu foto en: storage/app/public/profile/\n";
    echo "2. Nombres sugeridos: profile.jpg, foto.jpg, photo.jpg, avatar.jpg\n";
    echo "3. Formatos soportados: jpg, jpeg, png, gif\n";
    echo "4. Vuelve a ejecutar este script después de agregar la imagen.\n";
}

