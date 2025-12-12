<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Attendance;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendancePage extends Page
{
    protected static string $view = 'filament.pages.attendance-page';
    protected static ?string $navigationLabel = 'My Attendance';
    protected static ?string $navigationIcon = 'heroicon-o-finger-print';
    protected static ?string $navigationGroup = 'User Management';

    protected function getHeaderActions(): array
    {
        if (!Auth::user()->hasRole('cashier')) return [];

        $today = date('Y-m-d');
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('clock_in', $today)
            ->first();

        $actions = [];

        // CLOCK IN
        if (!$attendance) {
            $actions[] = Action::make('clock_in')
                ->label('Clock In')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $latitude = request()->input('latitude', null);
                    $longitude = request()->input('longitude', null);

                    $shift = Carbon::now()->hour < 15 ? 'Shift 1' : 'Shift 2';

                    Attendance::create([
                        'user_id' => Auth::id(),
                        'clock_in' => now(),
                        'shift' => $shift,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ]);

                    $this->notify('success', 'Clock In successful!');
                });
        }

        // CLOCK OUT
        elseif ($attendance && !$attendance->clock_out) {
            $actions[] = Action::make('clock_out')
                ->label('Clock Out')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () use ($attendance) {
                    $latitude = request()->input('latitude', null);
                    $longitude = request()->input('longitude', null);

                    $attendance->update([
                        'clock_out' => now(),
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ]);

                    $this->notify('success', 'Clock Out successful!');
                });
        }

        return $actions;
    }
}
