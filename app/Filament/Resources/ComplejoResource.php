<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplejoResource\Pages;
use App\Filament\Resources\ComplejoResource\RelationManagers;
use App\Models\Complejo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComplejoResource extends Resource
{
    protected static ?string $model = Complejo::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Complejo')
                    ->description('Datos básicos del complejo de pádel')
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre del Complejo')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: Badia Padel Center')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(4)
                            ->placeholder('Descripción detallada del complejo')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('direccion')
                            ->label('Dirección')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: Calle Principal 123')
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('hora_apertura')
                                    ->label('Hora Apertura (HH:MM)')
                                    ->required()
                                    ->placeholder('08:00')
                                    ->mask('99:99')
                                    ->default('08:00')
                                    ->rule('regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'),

                                Forms\Components\TextInput::make('hora_cierre')
                                    ->label('Hora Cierre (HH:MM)')
                                    ->required()
                                    ->placeholder('23:00')
                                    ->mask('99:99')
                                    ->default('23:00')
                                    ->rule('regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'),
                            ]),

                        Forms\Components\Toggle::make('activo')
                            ->label('Complejo Activo')
                            ->default(true)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Imagen')
                    ->schema([
                        Forms\Components\FileUpload::make('imagen')
                            ->label('Imagen del Complejo')
                            ->image()
                            ->disk('public_assets')
                            ->directory('complejos')
                            ->dehydrateStateUsing(function ($state) {
                                // Normalize possible array structures from FileUpload (associative keyed by uuid)
                                if (is_array($state)) {
                                    $first = array_values($state)[0] ?? null;
                                    if (is_array($first)) {
                                        $first = array_values($first)[0] ?? null;
                                    }
                                    $state = $first;
                                }

                                if (!is_string($state) || $state === '') {
                                    return null;
                                }

                                return str_starts_with($state, 'assets/')
                                    ? $state
                                    : 'assets/' . ltrim($state, '/');
                            })
                            ->getUploadedFileNameForStorageUsing(function ($file, $get) {
                                $nombre = $get('nombre');
                                if (!$nombre) {
                                    $nombre = 'complejo';
                                }
                                // Normalizar caracteres especiales y acentos
                                $nombreLimpio = iconv('UTF-8', 'ASCII//TRANSLIT', $nombre);
                                $nombreLimpio = preg_replace('/[^a-zA-Z0-9]/', '', $nombreLimpio);
                                $nombreLimpio = strtolower($nombreLimpio);
                                $extension = $file->getClientOriginalExtension();
                                return "complejo_{$nombreLimpio}.{$extension}";
                            })
                            ->preserveFilenames()
                            ->imageEditor()
                            ->maxSize(5120)
                            ->helperText('Imagen representativa del complejo. Se guardará como: complejo_(nombre).ext'),
                    ]),
            ]);
        
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('direccion')
                    ->label('Dirección')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('pistas_count')
                    ->label('Pistas')
                    ->counts('pistas')
                    ->sortable(),

                Tables\Columns\TextColumn::make('hora_apertura')
                    ->label('Apertura')
                    ->time('H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('hora_cierre')
                    ->label('Cierre')
                    ->time('H:i')
                    ->sortable(),

                Tables\Columns\IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('activo')
                    ->label('Estado')
                    ->queries(
                        true: fn (Builder $query) => $query->where('activo', true),
                        false: fn (Builder $query) => $query->where('activo', false),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([])
            ->paginated(false);
    }

    public static function getRelations(): array
    {
        return [
            // Relación con pistas
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComplejos::route('/'),
            'create' => Pages\CreateComplejo::route('/create'),
            'edit' => Pages\EditComplejo::route('/{record}/edit'),
        ];
    }
}
