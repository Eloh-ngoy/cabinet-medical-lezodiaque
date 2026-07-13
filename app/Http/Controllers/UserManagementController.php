<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->can('manage users')) {
            abort(403, 'Vous n\'avez pas la permission de gérer les utilisateurs.');
        }

        $search = $request->get('search');
        $users = User::with('roles')
            ->when($search, function ($query) use ($search) {
                return $query->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $roles = Role::all();

        return view('users.index', compact('users', 'roles', 'search'));
    }

    public function create()
    {
        if (!Auth::user()->can('manage users')) {
            abort(403, 'Vous n\'avez pas la permission de gérer les utilisateurs.');
        }

        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('manage users')) {
            abort(403, 'Vous n\'avez pas la permission de gérer les utilisateurs.');
        }

        $validated = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'full_name' => 'required|string|max:255',
            'matricule' => 'nullable|string|max:50|unique:users,matricule',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'full_name' => $validated['full_name'],
            'matricule' => $validated['matricule'] ?? null,
            'password' => Hash::make($validated['password']),
            'must_change_password' => true,
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        if (!Auth::user()->can('manage users')) {
            abort(403, 'Vous n\'avez pas la permission de gérer les utilisateurs.');
        }

        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if (!Auth::user()->can('manage users')) {
            abort(403, 'Vous n\'avez pas la permission de gérer les utilisateurs.');
        }

        $validated = $request->validate([
            'username' => 'required|string|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'full_name' => 'required|string|max:255',
            'matricule' => 'nullable|string|max:50|unique:users,matricule,' . $user->id,
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'full_name' => $validated['full_name'],
            'matricule' => $validated['matricule'] ?? null,
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        if (!Auth::user()->can('manage users')) {
            abort(403, 'Vous n\'avez pas la permission de gérer les utilisateurs.');
        }

        if ($user->username === 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Impossible de supprimer l\'administrateur principal.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}