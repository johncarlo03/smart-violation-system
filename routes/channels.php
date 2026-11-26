<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('office.{office}', function ($user, $office) {
    $allowedOffices = [
        7 => 'dean',
        6 => 'chairman',
        3 => 'sao',
    ];

    return ($allowedOffices[(int) $user->role] ?? null) === $office;
});