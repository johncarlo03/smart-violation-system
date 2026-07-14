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
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'id_number' => 'required|string|unique:users,id_number',
            'password' => 'required|confirmed|min:8',
            'course_id' => 'required|string',
            'department_id' => 'required|string',
            'year_level' => 'required|string',
            'rfid_number' => 'required|string|unique:users,rfid_number',
            'role' => 'required|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

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

        $user->name = $request->name;
        $user->email = $request->email;
        $user->id_number = $request->id_number;
        $user->password = $request->password;
        $user->rfid_number = $request->rfid_number;
        $user->role = $request->role;

        $user->save();

        return redirect()->route('superadmin.users')
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
