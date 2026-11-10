<?php

namespace App\Listeners;

use App\Models\LoginLog;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Str;

class LogAuthActivity
{
    public function onLogin(Login $event): void
    {
        $this->write('login', $event->user);
    }

    public function onLogout(Logout $event): void
    {
        if ($event->user) {
            $this->write('logout', $event->user);
        }
    }

    public function onFailed(Failed $event): void
    {
        // Only the email is kept. Never store $event->credentials['password'].
        $this->write('failed', $event->user, $event->credentials['email'] ?? null);
    }

    private function write(string $type, $user = null, ?string $email = null): void
    {
        LoginLog::create([
            'user_id'    => $user?->id,
            'email'      => $email ?? $user?->email,
            'role'       => $user?->role,
            'event'      => $type,
            'ip_address' => request()->ip(),
            'user_agent' => Str::limit((string) request()->userAgent(), 250, ''),
        ]);
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class  => 'onLogin',
            Logout::class => 'onLogout',
            Failed::class => 'onFailed',
        ];
    }
}