<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use App\Models\Attendance;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    // Time rules for late and early leave
    protected $lateThreshold = '08:30:00'; // Considered late after 8:30 AM
    protected $earlyLeaveThreshold = '17:00:00'; // Minimum leave time 5:00 PM

    protected function getHeaderActions(): array
    {
        $today = now()->toDateString();

        // Check today's attendance
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('clock_in', $today)
            ->first();

        $actions = [];

        // Clock In button
        if (!$attendance) {
            $actions[] = Actions\Action::make('clock_in')
                ->label('Clock In')
                ->color('success')
                ->icon('heroicon-o-clock')
                ->button()
                ->action(function () {
                    $this->clockIn();
                });
        }
        // Clock Out button
        elseif ($attendance && !$attendance->clock_out) {
            $actions[] = Actions\Action::make('clock_out')
                ->label('Clock Out')
                ->color('danger')
                ->icon('heroicon-o-clock')
                ->button()
                ->action(function () {
                    $this->clockOut();
                });
        }
        // If already clocked out, show info
        else {
            $actions[] = Actions\Action::make('already_clocked_out')
                ->label('You have already checked in today')
                ->color('gray')
                ->icon('heroicon-o-check-badge')
                ->button()
                ->disabled();
        }

        return $actions;
    }

    public function clockIn()
    {
        try {
            $today = now()->toDateString();
            $userId = Auth::id();

            // Check if already clocked in today
            $existingAttendance = Attendance::where('user_id', $userId)
                ->whereDate('clock_in', $today)
                ->first();

            if ($existingAttendance) {
                Notification::make()
                    ->title('You have already checked in today')
                    ->warning()
                    ->send();
                return;
            }

            // Determine status based on time
            $currentTime = now();
            $status = 'present';

            // Check if late (after 8:30 AM)
            if ($currentTime->format('H:i:s') > $this->lateThreshold) {
                $status = 'late';
            }

            // Create clock in record
            Attendance::create([
                'user_id' => $userId,
                'clock_in' => $currentTime,
                'shift' => now()->hour < 15 ? 'shift 1' : 'shift 2',
                'status' => $status
            ]);

            $message = $status === 'late'
                ? 'You are late! ' . $currentTime->format('H:i:s')
                : 'Clock in successful! ' . $currentTime->format('H:i:s');

            Notification::make()
                ->title($status === 'late' ? 'Clock In (Late)' : 'Clock In Success!')
                ->body($message)
                ->color($status === 'late' ? 'warning' : 'success')
                ->send();

            // Refresh page to update buttons
            $this->redirect(self::getUrl());

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function clockOut()
    {
        try {
            $today = now()->toDateString();
            $userId = Auth::id();

            // Find attendance for clock out
            $attendance = Attendance::where('user_id', $userId)
                ->whereDate('clock_in', $today)
                ->whereNull('clock_out')
                ->first();

            if (!$attendance) {
                Notification::make()
                    ->title('Unable to Clock Out')
                    ->body('No clock in record found or already clocked out')
                    ->warning()
                    ->send();
                return;
            }

            $currentTime = now();
            $status = $attendance->status;

            // Check if leaving early (before 5:00 PM)
            if ($currentTime->format('H:i:s') < $this->earlyLeaveThreshold && $status === 'present') {
                $status = 'early_leave';
            }

            // Update clock out
            $attendance->update([
                'clock_out' => $currentTime,
                'status' => $status
            ]);

            $message = match ($status) {
                'early_leave' => 'Early leave! ' . $currentTime->format('H:i:s'),
                'late' => 'Clock out successful! (You were late coming in)',
                default => 'Clock out successful! ' . $currentTime->format('H:i:s')
            };

            Notification::make()
                ->title($status === 'early_leave' ? 'Clock Out (Early Leave)' : 'Clock Out Success!')
                ->body($message)
                ->color($status === 'early_leave' ? 'warning' : 'success')
                ->send();

            // Refresh page to update buttons
            $this->redirect(self::getUrl());

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}