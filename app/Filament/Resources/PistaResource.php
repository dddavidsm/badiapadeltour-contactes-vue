<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PistaResource\Pages;
use App\Filament\Resources\PistaResource\RelationManagers;
use App\Models\Pista;
use App\Models\Complejo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PistaResource extends Resource
{
    protected static ?string $model = Pista::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $recordTitleAttribute = 'nombre';

    protected static ?string $navigationGroup = 'Gestión de Pistas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de la Pista')
                    ->description('Datos básicos de la pista de pádel')
                    ->schema([
                        Forms\Components\Select::make('complejo_id')
                            ->label('Complejo')
                            ->relationship('complejo', 'nombre')
                            ->required()
                            ->preload(),

                        Forms\Components\Select::make('tipo')
                            ->label('Tipo')
                            ->required()
                            ->options([
                                'outdoor' => 'Exterior',
                                'indoor' => 'Interior',
                            ])
                            ->default('outdoor'),

                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: Pista 1'),

                        Forms\Components\TextInput::make('precio_hora')
                            ->label('Precio/Hora')
                            ->required()
                            ->numeric()
                            ->prefix('€')
                            ->minValue(0)
                            ->step(0.01)
                            ->default(20.00),

                        Forms\Components\Toggle::make('disponible')
                            ->label('Disponible')
                            ->default(true)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Imagen')
                    ->collapsed()
                    ->schema([
                        Forms\Components\FileUpload::make('imagen')
                            ->label('Imagen de la Pista')
                            ->image()
                            ->disk('public_assets')
                            ->directory('Pistas')
                            ->dehydrateStateUsing(function ($state, $record) {
                                // Si no hay cambios en la imagen, retornar la imagen existente del registro
                                if (empty($state) && $record && $record->imagen) {
                                    return $record->imagen;
                                }
                                
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
                                return $state;
                            })
                            ->getUploadedFileNameForStorageUsing(function ($file, $get) {
                                $complejoId = $get('complejo_id');
                                if ($complejoId) {
                                    $complejo = \App\Models\Complejo::find($complejoId);
                                    if ($complejo) {
                                        $nombreComplejo = $complejo->nombre;
                                        $nombreComplejo = iconv('UTF-8', 'ASCII//TRANSLIT', $nombreComplejo);
                                        $nombreComplejo = preg_replace('/[^a-zA-Z0-9]/', '', $nombreComplejo);
                                        $nombreComplejo = strtolower($nombreComplejo);
                                    } else {
                                        $nombreComplejo = 'complejo';
                                    }
                                } else {
                                    $nombreComplejo = 'complejo';
                                }
                                $countPistas = \App\Models\Pista::where('complejo_id', $complejoId)->count();
                                $sufijo = $countPistas > 0 ? ($countPistas + 1) : '';
                                $extension = $file->getClientOriginalExtension();
                                return "{$nombreComplejo}_pista{$sufijo}.{$extension}";
                            })
                            ->imageEditor()
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg', 'image/webp']),
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

                Tables\Columns\TextColumn::make('complejo.nombre')
                    ->label('Complejo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tipo')
                    ->label('Tipo')
                    ->colors([
                        'primary' => 'indoor',
                        'success' => 'outdoor',
                    ]),

                Tables\Columns\IconColumn::make('es_dobles')
                    ->label('Dobles')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('precio_hora')
                    ->label('Precio/Hora')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\IconColumn::make('disponible')
                    ->label('Disponible')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('imagen')
                    ->label('Imagen')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-pista.jpg')),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('complejo_id')
                    ->label('Complejo')
                    ->relationship('complejo', 'nombre'),

                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'indoor' => 'Indoor',
                        'outdoor' => 'Outdoor',
                    ]),

                Tables\Filters\TernaryFilter::make('disponible')
                    ->label('Disponible')
                    ->placeholder('Todas las pistas')
                    ->trueLabel('Solo disponibles')
                    ->falseLabel('No disponibles'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPistas::route('/'),
            'create' => Pages\CreatePista::route('/create'),
            'edit' => Pages\EditPista::route('/{record}/edit'),
        ];
    }
}
