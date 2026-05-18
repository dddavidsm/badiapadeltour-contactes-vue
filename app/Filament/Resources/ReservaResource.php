<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservaResource\Pages;
use App\Filament\Resources\ReservaResource\RelationManagers;
use App\Models\Reserva;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReservaResource extends Resource
{
    protected static ?string $model = Reserva::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de la Reserva')
                    ->description('Detalles de la reserva')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Usuario')
                            ->relationship('user', 'email')
                            ->required(),

                        Forms\Components\Select::make('pista_id')
                            ->label('Pista')
                            ->relationship('pista', 'nombre')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->nombre . ' - ' . ucfirst($record->tipo))
                            ->required(),

                        Forms\Components\DatePicker::make('fecha_reserva')
                            ->label('Fecha')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->closeOnDateSelection(),

                        Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'pendiente' => 'Pendiente',
                                'confirmada' => 'Confirmada',
                                'completada' => 'Completada',
                                'cancelada' => 'Cancelada',
                            ])
                            ->required()
                            ->default('pendiente'),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('hora_inicio')
                                    ->label('Hora Inicio (HH:MM)')
                                    ->required()
                                    ->placeholder('08:00')
                                    ->mask('99:99')
                                    ->rule('regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'),

                                Forms\Components\TextInput::make('hora_fin')
                                    ->label('Hora Fin (HH:MM)')
                                    ->required()
                                    ->placeholder('09:00')
                                    ->mask('99:99')
                                    ->rule('regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'),
                            ]),

                        Forms\Components\TextInput::make('precio_total')
                            ->label('Precio Total')
                            ->numeric()
                            ->required()
                            ->prefix('€')
                            ->step(0.01)
                            ->minValue(0),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.apellidos')
                    ->label('Apellidos')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.dni')
                    ->label('DNI')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.telefono')
                    ->label('Teléfono')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pista.nombre')
                    ->label('Pista')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_reserva')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('hora_inicio')
                    ->label('Hora Inicio')
                    ->time()
                    ->sortable(),

                Tables\Columns\TextColumn::make('hora_fin')
                    ->label('Hora Fin')
                    ->time()
                    ->sortable(),

                Tables\Columns\TextColumn::make('precio_total')
                    ->label('Precio')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'confirmada',
                        'info' => 'completada',
                        'danger' => 'cancelada',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'confirmada' => 'Confirmada',
                        'completada' => 'Completada',
                        'cancelada' => 'Cancelada',
                    ]),

                Tables\Filters\SelectFilter::make('pista_id')
                    ->label('Pista')
                    ->relationship('pista', 'nombre'),

                Tables\Filters\Filter::make('fecha_reserva')
                    ->form([
                        Forms\Components\DatePicker::make('fecha_desde')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('fecha_hasta')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['fecha_desde'],
                                fn (Builder $q) => $q->whereDate('fecha_reserva', '>=', $data['fecha_desde'])
                            )
                            ->when(
                                $data['fecha_hasta'],
                                fn (Builder $q) => $q->whereDate('fecha_reserva', '<=', $data['fecha_hasta'])
                            );
                    }),
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
            'index' => Pages\ListReservas::route('/'),
            'create' => Pages\CreateReserva::route('/create'),
            'edit' => Pages\EditReserva::route('/{record}/edit'),
        ];
    }
}
