<?php

namespace App\Notifications;

use App\Models\Violation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ViolationAssigned extends Notification
{
    use Queueable;

    public function __construct(
        public Violation $violation,
        public string $action = 'reported'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $this->violation->loadMissing(['student', 'offense']);

        return [
            'violation_id' => $this->violation->id,
            'student' => $this->violation->student?->name,
            'offense' => $this->violation->offense?->name,
            'status' => $this->violation->status,
            'action' => $this->action,
            'message' => match ($this->action) {
                'reported' => 'A new violation has been reported.',
                'forwarded' => 'A violation has been forwarded to your office.',
                default => 'A violation has been updated.',
            },
        ];
    }
}
