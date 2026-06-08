<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('roles')->latest()->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('users.create', ['roles' => Role::orderBy('name')->get()]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'active'   => $request->boolean('active', true),
        ]);

        $user->assignRole($request->role);

        ActivityLog::record('create', "Usuario creado: {$user->name}", $user);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function show(User $user): View
    {
        $this->authorize('view', $user);

        $user->load('roles', 'activityLogs');

        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('users.edit', [
            'user'  => $user,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update(array_filter([
            'name'     => $request->name,
            'email'    => $request->email,
            'active'   => $request->boolean('active'),
            'password' => $request->filled('password') ? bcrypt($request->password) : null,
        ]));

        $user->syncRoles([$request->role]);

        ActivityLog::record('update', "Usuario actualizado: {$user->name}", $user);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado.');
    }
}
