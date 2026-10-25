<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Users;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;


class UsersCreationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::all();
        $courses = Course::all()->groupBy('department_id');
        $users = User::latest()->paginate(10);

        return view('superadmin.users', compact('departments', 'courses', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'id_number' => 'required|string|unique:users,id_number',
            'password' => 'required|confirmed|min:8',
            'role' => ['required', Rule::in(['1', '2', '3', '4', '5'])],
            'department_id' => ['nullable', 'required_if:role,1,5', 'exists:departments,id'],
            'course_id' => ['nullable', 'required_if:role,1', 'exists:courses,id'],
            'year_level' => ['nullable', 'required_if:role,1', Rule::in(['1', '2', '3', '4'])],
            'rfid_number' => 'nullable|string|unique:users,rfid_number',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validated['role'] !== '1') {
            $validated['course_id'] = null;
            $validated['year_level'] = null;
        }
        if (!in_array($validated['role'], ['1', '5'], true)) {
            $validated['department_id'] = null;
        }

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request
                ->file('profile_photo')
                ->store('profile-photos', 'public');
        }
        User::create($validated);

        return redirect()->back()->with('success', 'User Registered Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'string',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'id_number' => [
                'required',
                'string',
                Rule::unique('users', 'id_number')->ignore($user->id),
            ],
            'rfid_number' => [
                'nullable',
                Rule::unique('users', 'rfid_number')->ignore($user->id),
            ],
            'role' => 'required|string',

            'department_id' => ['nullable', 'required_if:role,1,5', 'exists:departments,id'],
            'course_id'     => ['nullable', 'required_if:role,1', 'exists:courses,id'],
            'year_level'    => ['nullable', 'required_if:role,1', Rule::in(['1', '2', '3', '4'])],

            'password' => 'nullable|confirmed|min:8',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->id_number = $validated['id_number'];
        $user->rfid_number = $validated['rfid_number'];
        $user->role = $validated['role'];

        $user->department_id = $validated['department_id'] ?? null;
        $user->course_id = $validated['course_id'] ?? null;
        $user->year_level = $validated['year_level'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->back()
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Restrict profile suicide if a user tries to erase themselves
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own profile.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted from system records.');
    }
}
