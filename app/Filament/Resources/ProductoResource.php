<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\Pages;
use App\Filament\Resources\ProductoResource\RelationManagers;
use App\Models\Tienda\Producto;
use App\Models\Tienda\Categoria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationGroup = 'Tienda';
    
    protected static ?string $modelLabel = 'Producto';
    
    protected static ?string $pluralModelLabel = 'Productos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Básica')
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre del Producto')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('categoria_id')
                                    ->label('Categoría')
                                    ->relationship('categoria', 'nombre')
                                    ->required()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nombre')
                                            ->label('Nombre de la Categoría')
                                            ->required()
                                            ->maxLength(100),
                                    ])
                                    ->createOptionModalHeading('Crear Nueva Categoría'),
                                
                                Forms\Components\TextInput::make('precio')
                                    ->label('Precio')
                                    ->required()
                                    ->numeric()
                                    ->prefix('€')
                                    ->minValue(0)
                                    ->step(0.01),
                            ]),
                        
                        Forms\Components\TextInput::make('stock')
                            ->label('Stock Disponible')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Imágenes')
                    ->collapsed()
                    ->schema([
                        Forms\Components\FileUpload::make('imagen')
                            ->label('Imagen Principal')
                            ->image()
                            ->disk('public_assets')
                            ->directory(function ($get, $record) {
                                if ($record && $record->id) {
                                    return "productos/{$record->id}";
                                }
                                $nombre = $get('nombre');
                                if ($nombre) {
                                    $nombreLimpio = iconv('UTF-8', 'ASCII//TRANSLIT', $nombre);
                                    $nombreLimpio = preg_replace('/[^a-zA-Z0-9]/', '', $nombreLimpio);
                                    return "productos/new_{$nombreLimpio}_" . time();
                                }
                                return "productos/new_" . time();
                            })
                            ->preserveFilenames()
                            ->dehydrateStateUsing(function ($state, $record) {
                                // Si no hay cambios en la imagen, retornar la imagen existente
                                if (empty($state) && $record && $record->imagen) {
                                    return $record->imagen;
                                }
                                if (empty($state)) {
                                    return null;
                                }
                                if (is_string($state)) {
                                    return str_starts_with($state, 'assets/')
                                        ? $state
                                        : 'assets/' . ltrim($state, '/');
                                }
                                if (is_array($state)) {
                                    $first = reset($state);
                                    if (is_string($first)) {
                                        return str_starts_with($first, 'assets/')
                                            ? $first
                                            : 'assets/' . ltrim($first, '/');
                                    }
                                }
                                return null;
                            })
                            ->getUploadedFileNameForStorageUsing(function ($file, $get, $record) {
                                $nombre = $get('nombre');
                                if (!$nombre) {
                                    $nombre = 'Producto';
                                }
                                $nombreLimpio = iconv('UTF-8', 'ASCII//TRANSLIT', $nombre);
                                $nombreLimpio = preg_replace('/[^a-zA-Z0-9]/', '', $nombreLimpio);
                                $extension = $file->getClientOriginalExtension();
                                return "{$nombreLimpio}_principal.{$extension}";
                            })
                            ->imageEditor()
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg', 'image/webp'])
                            ->columnSpanFull(),
                        
                        Forms\Components\FileUpload::make('imagenes')
                            ->label('Galería (máx. 5 imágenes)')
                            ->image()
                            ->multiple()
                            ->disk('public_assets')
                            ->directory(function ($get, $record) {
                                if ($record && $record->id) {
                                    return "productos/{$record->id}";
                                }
                                $nombre = $get('nombre');
                                if ($nombre) {
                                    $nombreLimpio = iconv('UTF-8', 'ASCII//TRANSLIT', $nombre);
                                    $nombreLimpio = preg_replace('/[^a-zA-Z0-9]/', '', $nombreLimpio);
                                    return "productos/new_{$nombreLimpio}_" . time();
                                }
                                return "productos/new_" . time();
                            })
                            ->preserveFilenames()
                            ->dehydrateStateUsing(function ($state, $record) {
                                // Si no hay cambios en las imágenes, retornar las existentes
                                if (empty($state) && $record && $record->imagenes) {
                                    return $record->imagenes;
                                }
                                if (empty($state)) {
                                    return null;
                                }
                                if (!is_array($state)) {
                                    $state = [$state];
                                }
                                $paths = [];
                                foreach ($state as $item) {
                                    if (is_string($item) && $item !== '') {
                                        $paths[] = str_starts_with($item, 'assets/')
                                            ? $item
                                            : 'assets/' . ltrim($item, '/');
                                    }
                                }
                                return !empty($paths) ? $paths : null;
                            })
                            ->getUploadedFileNameForStorageUsing(function ($file, $get, $record) {
                                static $counter = 1;
                                $nombre = $get('nombre');
                                if (!$nombre) {
                                    $nombre = 'Producto';
                                }
                                $nombreLimpio = iconv('UTF-8', 'ASCII//TRANSLIT', $nombre);
                                $nombreLimpio = preg_replace('/[^a-zA-Z0-9]/', '', $nombreLimpio);
                                $extension = $file->getClientOriginalExtension();
                                $filename = "{$nombreLimpio}_galeria_{$counter}.{$extension}";
                                $counter++;
                                return $filename;
                            })
                            ->imageEditor()
                            ->maxSize(5120)
                            ->maxFiles(5)
                            ->reorderable()
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg', 'image/webp'])
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Características')
                    ->schema([
                        Forms\Components\Toggle::make('destacado')
                            ->label('Producto Destacado'),
                        
                        Forms\Components\Toggle::make('novedad')
                            ->label('Novedad'),
                        
                        Forms\Components\TextInput::make('orden')
                            ->numeric()
                            ->default(0)
                            ->helperText('Orden de visualización (menor número = mayor prioridad)'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('imagen')
                    ->label('Imagen')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),
                
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                
                Tables\Columns\TextColumn::make('categoria.nombre')
                    ->label('Categoría')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('precio')
                    ->money('EUR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')),
                
                Tables\Columns\IconColumn::make('destacado')
                    ->boolean()
                    ->label('Destacado'),
                
                Tables\Columns\IconColumn::make('novedad')
                    ->boolean()
                    ->label('Novedad'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categoria')
                    ->relationship('categoria', 'nombre')
                    ->label('Categoría'),
                
                Tables\Filters\TernaryFilter::make('destacado')
                    ->label('Destacado'),
                
                Tables\Filters\TernaryFilter::make('novedad')
                    ->label('Novedad'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('orden', 'asc')
            ->paginated(false);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit' => Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}
