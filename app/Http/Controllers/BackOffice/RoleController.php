<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\ActivityLog;
use App\Models\Module;
use App\Models\Role;
use App\Models\RoleModulePermission;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Display role cards + global permission matrix.
     */
    public function index(): View
    {
        $roles   = Role::with('permissions')->get();
        $modules = Module::orderBy('sort_order')->get();

        // Build permission matrix: matrix[role_id][module_id] = RoleModulePermission
        $matrix = [];
        foreach ($roles as $role) {
            $matrix[$role->id] = $role->permissions->keyBy('module_id');
        }

        return view('pages.backoffice.roles.index', [
            'roles'     => $roles,
            'modules'   => $modules,
            'matrix'    => $matrix,
            'pageTitle' => 'Role & Hak Akses',
        ]);
    }

    /**
     * Store a newly created role with permissions.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['display_name', 'slug', 'description', 'icon', 'color', 'is_system']);
        $data['icon']  = $data['icon'] ?? 'fa-solid fa-shield-halved';
        $data['color'] = $data['color'] ?? 'gray';

        $role = Role::create($data);

        // Sync permissions
        $this->syncPermissions($role, $request->input('permissions', []));

        /** @var User $actor */
        $actor = $request->user();

        ActivityLog::record(
            $actor,
            'create_role',
            "Menambahkan role baru: {$role->display_name}",
            ['role_id' => $role->id]
        );

        return redirect()
            ->route('admin.roles.index')
            ->with('success', "Role \"{$role->display_name}\" berhasil ditambahkan.");
    }

    /**
     * Return role + permissions as JSON (for edit modal via fetch).
     */
    public function show(Role $role): JsonResponse
    {
        $role->load('permissions');

        $permissions = [];
        foreach ($role->permissions as $perm) {
            $permissions[$perm->module_id] = [
                'can_view'   => $perm->can_view,
                'can_create' => $perm->can_create,
                'can_edit'   => $perm->can_edit,
                'can_delete' => $perm->can_delete,
            ];
        }

        return response()->json([
            'id'           => $role->id,
            'slug'         => $role->slug,
            'display_name' => $role->display_name,
            'description'  => $role->description,
            'icon'         => $role->icon,
            'color'        => $role->color,
            'is_system'    => $role->is_system,
            'user_count'   => $role->userCount(),
            'permissions'  => $permissions,
        ]);
    }

    /**
     * Update the specified role and its permissions.
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $data = $request->safe()->only(['display_name', 'description', 'icon', 'color']);
        $role->update(array_filter($data, fn ($v) => $v !== null));

        // Sync permissions
        $this->syncPermissions($role, $request->input('permissions', []));

        /** @var User $actor */
        $actor = $request->user();

        ActivityLog::record(
            $actor,
            'update_role',
            "Mengubah role: {$role->display_name}",
            ['role_id' => $role->id]
        );

        return redirect()
            ->route('admin.roles.index')
            ->with('success', "Role \"{$role->display_name}\" berhasil diperbarui.");
    }

    /**
     * Delete the specified role (non-system only, no assigned users).
     */
    public function destroy(Request $request, Role $role): RedirectResponse
    {
        if ($role->is_system) {
            return back()->with('error', 'Role sistem tidak dapat dihapus.');
        }

        if ($role->userCount() > 0) {
            return back()->with('error', "Role \"{$role->display_name}\" masih memiliki {$role->userCount()} user aktif. Pindahkan user terlebih dahulu.");
        }

        $name = $role->display_name;

        /** @var User $actor */
        $actor = $request->user();

        ActivityLog::record(
            $actor,
            'delete_role',
            "Menghapus role: {$name}",
            ['role_id' => $role->id]
        );

        $role->permissions()->delete();
        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', "Role \"{$name}\" berhasil dihapus.");
    }

    /**
     * Sync the permission matrix for a role.
     *
     * @param  array<int, array{can_view?: bool, can_create?: bool, can_edit?: bool, can_delete?: bool}>  $permissions
     */
    private function syncPermissions(Role $role, array $permissions): void
    {
        $moduleIds = Module::pluck('id')->toArray();

        foreach ($moduleIds as $moduleId) {
            $perm = $permissions[$moduleId] ?? [];

            RoleModulePermission::updateOrCreate(
                ['role_id' => $role->id, 'module_id' => $moduleId],
                [
                    'can_view'   => (bool) ($perm['can_view'] ?? false),
                    'can_create' => (bool) ($perm['can_create'] ?? false),
                    'can_edit'   => (bool) ($perm['can_edit'] ?? false),
                    'can_delete' => (bool) ($perm['can_delete'] ?? false),
                ]
            );
        }
    }
}
