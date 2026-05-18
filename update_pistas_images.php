use App\Models\Pista;

echo "\n=== Listado de pistas y sus imágenes ===\n";
$pistas = Pista::all(['id', 'nombre', 'imagen']);
foreach($pistas as $pista) {
    echo "ID: {$pista->id} | Nombre: {$pista->nombre} | Imagen: {$pista->imagen}\n";
}

echo "\n=== Actualizando rutas de imágenes ===\n";
$updated = Pista::where('imagen', 'NOT LIKE', 'assets/Pistas/%')
    ->where('imagen', 'IS NOT', null)
    ->get();

foreach($updated as $pista) {
    $oldPath = $pista->imagen;
    // Si la imagen no tiene el prefijo correcto, agregarlo
    if (!str_starts_with($oldPath, 'assets/Pistas/')) {
        $newPath = 'assets/Pistas/' . basename($oldPath);
        $pista->imagen = $newPath;
        $pista->save();
        echo "✓ Actualizado: {$oldPath} → {$newPath}\n";
    }
}

echo "\n=== Verificando cambios ===\n";
$pistas = Pista::all(['id', 'nombre', 'imagen']);
foreach($pistas as $pista) {
    echo "ID: {$pista->id} | Nombre: {$pista->nombre} | Imagen: {$pista->imagen}\n";
}
