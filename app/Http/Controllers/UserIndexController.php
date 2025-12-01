<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserIndexController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $search = trim((string) $request->input('q', ''));

        $query = \App\Models\User::query()
            ->where('is_admin', true)
            ->with('student')     // so we can show “Register as Student” if needed
            ->orderBy('name');

        if ($search !== '') {
            $like = '%'.$search.'%';
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like);
            });
        }

        $users = $query->paginate(15)->withQueryString();

        // view no longer needs $role
        return view('admin.utilities.users.index', compact('users', 'search'));
    }

    public function create()
    {
        abort_unless(auth()->user()?->is_admin, 403);
        return view('admin.utilities.users.create');
    }

    /** Handle create-user submit. Always admin; optional Student profile. */
    public function store(Request $request)
    {
        // trim email so validation doesn’t fail on spaces
        $request->merge(['email' => trim((string) $request->input('email'))]);

        // use DNS check only in prod (shared hosts often block it)
        $emailRule = app()->environment('production')
            ? 'required|email:rfc,dns|unique:users,email'
            : 'required|email:rfc|unique:users,email';

        $data = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => $emailRule,
            'password'     => 'nullable|string|min:8',
            'make_student' => 'sometimes|boolean',
        ]);

        try {
            return DB::transaction(function () use ($data) {
                Log::info('Admin user creation requested', [
                    'email' => $data['email'],
                    'first_name' => $data['first_name'],
                    'last_name'  => $data['last_name'],
                ]);

                $user = User::create([
                    'name'      => $data['first_name'].' '.$data['last_name'],
                    'email'     => $data['email'],
                    'password'  => isset($data['password']) && $data['password'] !== ''
                        ? bcrypt($data['password'])
                        : bcrypt(Str::random(16)),
                    'is_admin'  => true,
                    'is_active' => 1,
                ]);

                Log::info('Admin user created', [
                    'user_id'  => $user->id,
                    'email'    => $user->email,
                    'is_admin' => $user->is_admin,
                ]);

                // Create Student profile if requested
                if (!empty($data['make_student'])) {
                    $student = Student::firstOrCreate(
                        ['user_id' => $user->id],
                        [
                            'first_name' => $data['first_name'],
                            'last_name'  => $data['last_name'],
                            'email'      => $data['email'],
                            'is_active'  => true,
                            'is_lead'    => false,
                        ]
                    );

                    Log::info('Student profile created for admin user', [
                        'user_id'    => $user->id,
                        'student_id' => $student->id,
                    ]);

                    // If you use a roles pivot, also attach the "student" role
                    if (method_exists($user, 'roles')) {
                        $studentRole = Role::where('slug', 'student')->first();
                        if ($studentRole && ! $user->roles()->where('roles.id', $studentRole->id)->exists()) {
                            $user->roles()->attach($studentRole->id);
                            Log::info('Student role attached to user', [
                                'user_id' => $user->id,
                                'role_id' => $studentRole->id,
                            ]);
                        }
                    }
                }

                return redirect()
                    ->route('admin.utilities.user.index')
                    ->with('success', 'Admin user created successfully.');
            });
        } catch (\Throwable $e) {
            Log::error('Failed to create admin user', [
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withErrors('Failed to create user: '.$e->getMessage())
                ->withInput();
        }
    }



}
