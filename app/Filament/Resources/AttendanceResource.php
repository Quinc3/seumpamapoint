<?php

namespace App\Filament\Resources;

use App\Models\Attendance;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\AttendanceResource\Pages;
use Illuminate\Database\Eloquent\Builder;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;
    protected static ?string $navigationIcon = 'heroicon-o-finger-print';
    protected static ?string $navigationGroup = 'Users Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user_id')
                ->relationship('user', 'name')
                ->required()
                ->disabled(fn() => !Auth::user()->hasRole('admin')),

            TextInput::make('shift')
                ->required()
                ->default(now()->hour < 15 ? 'shift 1' : 'shift 2'),

            TextInput::make('clock_in')
                ->label('Clock In Time')
                ->disabled(),

            TextInput::make('clock_out')
                ->label('Clock Out Time')
                ->disabled(),

            Select::make('status')
                ->options([
                    'present' => 'On Time',
                    'late' => 'Late Arrival',
                    'early_leave' => 'Early Leave',
                    'absent' => 'Absent',
                ])
                ->visible(fn() => Auth::user()->hasRole('admin')),

            TextInput::make('latitude')->label('Latitude')->visible(false),
            TextInput::make('longitude')->label('Longitude')->visible(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.roles.name')
                    ->label('Role')
                    ->badge()
                    ->formatStateUsing(fn($state) => is_array($state) ? implode(', ', $state) : $state)
                    ->color(fn($state) => match ($state) {
                        'admin' => 'warning',
                        'cashier' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('shift')
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'shift 1' => 'blue',
                        'shift 2' => 'purple',
                        default => 'gray',
                    }),

                TextColumn::make('clock_in')
                    ->label('Clock In')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->description(fn(Attendance $record) => $record->clock_in ? $record->clock_in->format('H:i:s') : ''),

                TextColumn::make('clock_out')
                    ->label('Clock Out')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->description(fn(Attendance $record) => $record->clock_out ? $record->clock_out->format('H:i:s') : ''),

                // Status column with late/early leave indicators
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'present' => 'On Time',
                        'late' => 'Late Arrival',
                        'early_leave' => 'Early Leave',
                        'absent' => 'Absent',
                        default => $state,
                    })
                    ->color(fn($state) => match ($state) {
                        'present' => 'success',
                        'late' => 'warning',
                        'early_leave' => 'danger',
                        'absent' => 'gray',
                        default => 'gray',
                    })
                    ->icon(fn($state) => match ($state) {
                        'present' => 'heroicon-o-check-circle',
                        'late' => 'heroicon-o-clock',
                        'early_leave' => 'heroicon-o-arrow-left',
                        'absent' => 'heroicon-o-x-circle',
                        default => null,
                    }),

                // Work duration column
                TextColumn::make('work_duration')
                    ->label('Work Duration')
                    ->getStateUsing(function (Attendance $record) {
                        if (!$record->clock_in || !$record->clock_out) {
                            return '-';
                        }

                        $duration = $record->clock_in->diff($record->clock_out);
                        return $duration->format('%hh %im');
                    })
                    ->badge()
                    ->color(
                        fn($state) =>
                        str_contains($state, '0h') ? 'gray' : 'success'
                    ),

                TextColumn::make('latitude')
                    ->label('Lat')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('longitude')
                    ->label('Long')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
            ])
            ->filters([
                // Add status filter
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'present' => 'On Time',
                        'late' => 'Late Arrival',
                        'early_leave' => 'Early Leave',
                        'absent' => 'Absent',
                    ]),
                Tables\Filters\SelectFilter::make('shift')
                    ->options([
                        'shift 1' => 'Shift 1',
                        'shift 2' => 'Shift 2',
                    ]),
                Tables\Filters\Filter::make('today')
                    ->label('Today')
                    ->query(fn($query) => $query->whereDate('clock_in', today())),
                Tables\Filters\Filter::make('late_employees')
                    ->label('Late Employees')
                    ->query(fn($query) => $query->where('status', 'late')),
                Tables\Filters\Filter::make('early_leavers')
                    ->label('Early Leavers')
                    ->query(fn($query) => $query->where('status', 'early_leave')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (!Auth::user()->hasRole('admin')) {
            $query->where('user_id', Auth::id());
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
        ];
    }
}
