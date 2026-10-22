<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use App\Models\{Course, Department, Offense, Penalty, User, Violation};
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'event' => 'nullable|in:created,updated,deleted,restored',
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);

        $audits = Audit::with([
            'user',
            'auditable' => fn(MorphTo $morph) => $morph->morphWith([
                Violation::class => ['student', 'offense'],
            ]),
        ])
            ->when($request->filled('event'), fn($q) => $q->where('event', $request->event))
            ->when($request->filled('type'), fn($q) => $q->where('auditable_type', $request->type))
            ->when($request->filled('user'), fn($q) => $q->where('user_id', $request->user))
            ->when($request->filled('from'), fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->latest()->latest('id')
            ->paginate(25)
            ->withQueryString();

        // field => [model, column to show]
        $lookups = [
            'student_id' => [User::class, 'name'],
            'cso_id' => [User::class, 'name'],
            'reviewed_by' => [User::class, 'name'],
            'department_id' => [Department::class, 'name'],
            'course_id' => [Course::class, 'name'],
            'offense_id' => [Offense::class, 'name'],
            'penalty_id' => [Penalty::class, 'penalty_description'],
        ];

        $labels = [];
        foreach ($lookups as $field => [$model, $column]) {
            $ids = $audits->getCollection()
                ->flatMap(fn($a) => [$a->old_values[$field] ?? null, $a->new_values[$field] ?? null])
                ->filter()->unique();

            if ($ids->isNotEmpty()) {
                $labels[$field] = $model::whereIn('id', $ids)->pluck($column, 'id');
            }
        }

        $users = User::whereIn('id', Audit::query()->whereNotNull('user_id')->select('user_id'))
            ->orderBy('name')->get(['id', 'name']);
        $types = Audit::query()->select('auditable_type')->distinct()->pluck('auditable_type');
        $events = ['created', 'updated', 'deleted', 'restored'];

        return view('superadmin.audits', compact('audits', 'users', 'types', 'events', 'labels'));
    }
}