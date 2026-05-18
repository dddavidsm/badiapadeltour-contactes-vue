<?php

namespace App\Console\Commands;

use App\Models\Tienda\Producto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixProductImages extends Command
{
    protected $signature = 'productos:fix-images';
    protected $description = 'Fix product images by checking filesystem and updating database';

    public function handle()
    {
        $this->info('Buscando imágenes de productos...');
        
        $productosDir = public_path('assets/Productos');
        if (!File::exists($productosDir)) {
            $this->error("Directorio de productos no encontrado: {$productosDir}");
            return;
        }

        $productos = Producto::all();
        $fixed = 0;

        foreach ($productos as $producto) {
            // Buscar carpeta del producto por nombre
            $folders = File::directories($productosDir);
            $found = false;
            
            foreach ($folders as $folder) {
                $folderName = basename($folder);
                $files = File::files($folder);
                
                foreach ($files as $file) {
                    $fileName = $file->getFilename();
                    
                    // Si es la imagen principal, actualizar la base de datos
                    if (strpos($fileName, 'principal') !== false) {
                        $relativePath = "assets/Productos/" . $folderName . "/" . $fileName;
                        
                        // Verificar si el producto ya tiene esta imagen
                        if (empty($producto->imagen) || $producto->imagen !== $relativePath) {
                            $producto->imagen = $relativePath;
                            $producto->save();
                            $this->info("✓ Actualizado: {$producto->nombre} -> {$relativePath}");
                            $fixed++;
                            $found = true;
                            break;
                        }
                    }
                }
                
                if ($found) break;
            }
        }

        // También procesar imágenes de galería
        foreach ($productos as $producto) {
            if (empty($producto->imagenes)) {
                $folders = File::directories($productosDir);
                
                foreach ($folders as $folder) {
                    $folderName = basename($folder);
                    $files = File::files($folder);
                    $galeryImages = [];
                    
                    foreach ($files as $file) {
                        if (strpos($file->getFilename(), 'galeria') !== false) {
                            $relativePath = "assets/Productos/" . $folderName . "/" . $file->getFilename();
                            $galeryImages[] = $relativePath;
                        }
                    }
                    
                    if (!empty($galeryImages) && (empty($producto->imagenes) || count($galeryImages) > count($producto->imagenes ?? []))) {
                        $producto->imagenes = $galeryImages;
                        $producto->save();
                        $this->info("✓ Galería actualizada: {$producto->nombre}");
                        $fixed++;
                        break;
                    }
                }
            }
        }

        $this->info("\n✓ Proceso completado. {$fixed} productos actualizados.");
    }
}

