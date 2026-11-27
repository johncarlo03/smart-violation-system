<?php

use Illuminate\Support\Facades\Broadcast;

// Chairman and Dean: only their own department.
Broadcast::channel(
    'office.{office}.department.{department}',
    function ($user, $office, $department) {
        $allowedOffices = [
            7 => 'dean',
            6 => 'chairman',
        ];

        return ($allowedOffices[(int) $user->role] ?? null) === $office
            && (int) $user->department_id === (int) $department;
    }
);

// SAO: all departments.
Broadcast::channel('office.sao', function ($user) {
    return (int) $user->role === 3;
});