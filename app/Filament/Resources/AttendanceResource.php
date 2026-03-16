<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Attendance;
use Filament\Tables\Table;
use Filament\Tables\Actions;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\AttendanceResource\Pages;

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

            ->headerActions([
    Tables\Actions\Action::make('downloadReport')
        ->label('Download Report')
        ->icon('heroicon-o-document-arrow-down')
        ->color('success')
        ->form([
            Select::make('report_type')
                ->options([
                    'daily' => 'Daily',
                    'monthly' => 'Monthly',
                    'custom' => 'Custom',
                ])
                ->required()
                ->reactive(),

            DatePicker::make('date')
                ->visible(fn($get) => $get('report_type') === 'daily'),

            Select::make('month')
                ->options([
                    1 => 'January', 2 => 'February', 3 => 'March',
                    4 => 'April', 5 => 'May', 6 => 'June',
                    7 => 'July', 8 => 'August', 9 => 'September',
                    10 => 'October', 11 => 'November', 12 => 'December',
                ])
                ->visible(fn($get) => $get('report_type') === 'monthly'),

            Select::make('year')
                ->options([
                    2024 => '2024',
                    2025 => '2025',
                ])
                ->visible(fn($get) => $get('report_type') === 'monthly'),

            DatePicker::make('start_date')
                ->visible(fn($get) => $get('report_type') === 'custom'),

            DatePicker::make('end_date')
                ->visible(fn($get) => $get('report_type') === 'custom'),

            Select::make('format')
                ->options([
                    'pdf' => 'PDF',
                    'excel' => 'Excel',
                ])
                ->default('pdf'),
        ])
        ->action(fn(array $data) =>
            redirect()->route('attendance.report.download', $data)
        ),
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

    // Role-based permissions untuk UserResource
    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole('admin');
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole('admin');
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole('admin');
    }
}
