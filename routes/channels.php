<?php

use Illuminate\Support\Facades\Broadcast;

// SAO channel (no department required)
Broadcast::channel('office.sao', function ($user) {
    return (int) $user->role === 3;
});

// Chairman & Dean channel (department-scoped)
Broadcast::channel('office.{office}.department.{departmentId}', function ($user, string $office, int $departmentId) {
    $expectedRole = match ($office) {
        'chairman' => 6,
        'dean' => 7,
        default => null,
    };

    return $expectedRole !== null
        && (int) $user->role === $expectedRole
        && (int) $user->department_id === $departmentId;
});

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});