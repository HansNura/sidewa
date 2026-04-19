<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display user listing with search, filter and pagination.
     */
    public function index(Request $request): View
    {
        $users = User::query()
            ->search($request->input('search'))
            ->filterRole($request->input('role'))
            ->filterStatus($request->input('status'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.backoffice.users.index', [
            'users'       => $users,
            'roles'       => User::ROLES,
            'pageTitle'   => 'Manajemen User',
            'filters'     => $request->only(['search', 'role', 'status']),
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $user = User::create($data);

        /** @var User $actor */
        $actor = $request->user();

        ActivityLog::record(
            $actor,
            'create_user',
            "Menambahkan user baru: {$user->name} ({$user->roleName()})",
            ['target_user_id' => $user->id]
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User \"{$user->name}\" berhasil ditambahkan.");
    }

    /**
     * Return user detail as JSON (for the detail drawer via fetch).
     */
    public function show(User $user): JsonResponse
    {
        $user->load(['activityLogs' => fn ($q) => $q->latest()->limit(10)]);

        return response()->json([
            'id'              => $user->id,
            'name'            => $user->name,
            'email'           => $user->email,
            'nik'             => $user->nik,
            'role'            => $user->role,
            'role_name'       => $user->roleName(),
            'role_badge'      => $user->roleBadgeClasses(),
            'is_active'       => $user->is_active,
            'status_label'    => $user->statusLabel(),
            'avatar_url'      => $user->avatarUrl(),
            'last_login_at'   => $user->last_login_at?->format('d M Y, H:i') . ' WIB',
            'last_login_ip'   => $user->last_login_ip,
            'permissions'     => $user->rolePermissions(),
            'created_at'      => $user->created_at->format('d M Y'),
            'activity_logs'   => $user->activityLogs->map(fn ($log) => [
                'action'      => $log->action,
                'description' => $log->description,
                'time'        => $log->created_at->diffForHumans(),
                'created_at'  => $log->created_at->format('d M Y, H:i'),
            ]),
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', $user->is_active);

        // Only update password if provided
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        /** @var User $actor */
        $actor = $request->user();

        ActivityLog::record(
            $actor,
            'update_user',
            "Mengubah data user: {$user->name}",
            ['target_user_id' => $user->id]
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Data user \"{$user->name}\" berhasil diperbarui.");
    }

    /**
     * Delete the specified user.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        // Prevent self-deletion
        if ($user->id === $actor->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $name = $user->name;

        ActivityLog::record(
            $actor,
            'delete_user',
            "Menghapus user: {$name}",
            ['target_user_id' => $user->id]
        );

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User \"{$name}\" berhasil dihapus.");
    }

    /**
     * Handle bulk actions: deactivate or delete multiple users.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action'   => ['required', 'in:deactivate,delete'],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        /** @var User $actor */
        $actor = $request->user();

        $ids = collect($request->input('user_ids'))
            ->reject(fn ($id) => (int) $id === $actor->id); // Exclude self

        if ($ids->isEmpty()) {
            return back()->with('error', 'Tidak ada user yang valid untuk diproses.');
        }

        $action = $request->input('action');

        if ($action === 'deactivate') {
            User::whereIn('id', $ids)->update(['is_active' => false]);
            $msg = $ids->count() . ' user berhasil dinonaktifkan.';
        } else {
            User::whereIn('id', $ids)->delete();
            $msg = $ids->count() . ' user berhasil dihapus.';
        }

        ActivityLog::record(
            $actor,
            "bulk_{$action}",
            $msg,
            ['target_user_ids' => $ids->values()->toArray()]
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', $msg);
    }

    /**
     * Reset a user's password to a random string.
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $newPassword = Str::random(10);

        $user->update(['password' => $newPassword]);

        /** @var User $actor */
        $actor = $request->user();

        ActivityLog::record(
            $actor,
            'reset_password',
            "Mereset password user: {$user->name}",
            ['target_user_id' => $user->id]
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Password user \"{$user->name}\" telah direset menjadi: {$newPassword}")
            ->with('reset_password', $newPassword);
    }
}
