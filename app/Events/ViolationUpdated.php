<?php

namespace App\Events;

use App\Models\Violation;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ViolationUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Violation $violation,
        public string $office,
        public string $action = 'updated'
    ) {
        $this->violation->loadMissing(['student', 'offense']);
    }

    public function broadcastOn(): array
    {
        if ($this->office === 'sao') {
            return [
                new PrivateChannel('office.sao'),
            ];
        }

        return [
            new PrivateChannel(
                'office.' . $this->office .
                '.department.' . $this->violation->department_id
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ViolationUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->violation->id,
            'status' => $this->violation->status,
            'action' => $this->action,
            'student' => $this->violation->student?->name,
            'offense' => $this->violation->offense?->name,
            'message' => match ($this->action) {
                'reported' => 'A new violation has been reported.',
                'forwarded' => 'A violation has been forwarded to your office.',
                default => 'A violation has been updated.',
            },
        ];
    }
}
