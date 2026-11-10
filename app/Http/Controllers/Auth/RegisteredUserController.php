<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     * (Departments and courses are supplied by a view composer, see AppServiceProvider.)
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'id_number'     => ['required', 'string', 'max:255', 'unique:' . User::class],
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'department_id' => ['required', 'exists:departments,id'],
            // The course must exist AND belong to the chosen department
            'course_id'     => [
                'required',
                Rule::exists('courses', 'id')->where('department_id', $request->department_id),
            ],
            'year_level'    => ['required', Rule::in(['1', '2', '3', '4'])],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = new User([
            'name'          => $request->name,
            'id_number'     => $request->id_number,
            'email'         => $request->email,
            'department_id' => $request->department_id,
            'course_id'     => $request->course_id,
            'year_level'    => $request->year_level,
            'password'      => Hash::make($request->password),
        ]);

        // Public sign-up is always a student. Never take the role from the request.
        $user->role = 1;
        $user->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}