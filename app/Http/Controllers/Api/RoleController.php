<?php
namespace App\Http\Controllers\Api;

use App\Helpers\User\RoleHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRoles\CreateRequest;
use App\Http\Requests\UserRoles\UpdateRequest;
use App\Http\Resources\UserRoles\UserRolesCollection;
use App\Http\Resources\UserRoles\UserRolesResource;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $roles;

    public function __construct()
    {
        $this->roles = new RoleHelper();
    }

    public function destroy($id)
    {
        $roles = $this->roles->delete($id);

        if (!$roles) {
            return response()->failed(['Mohon maaf data role tidak ditemukan']);
        }

        return response()->success($roles, "Role berhasil dihapus");
    }

    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
        ];
        $roles = $this->roles->getAll($filter, 5, $request->sort ?? '');

        return response()->success(new UserRolesCollection($roles['data']));
    }

    public function show($id)
    {
        $roles = $this->roles->getById($id);

        if (!($roles['status'])) {
            return response()->failed(['Data role tidak ditemukan'], 404);
        }

        return response()->success(new UserRolesResource($roles['data']));
    }

    public function store(CreateRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['id', 'name', 'access']);

        $payload['access'] = json_encode($payload['access']);

        $roles = $this->roles->create($payload);

        if (!$roles['status']) {
            return response()->failed($roles['error']);
        }

        return response()->success(new UserRolesResource($roles['data']), "Role berhasil ditambahkan");
    }

    public function update(UpdateRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['id', 'name', 'access']);
        $roles = $this->roles->update($payload, $payload['id'] ?? 0);

        if (!$roles['status']) {
            return response()->failed($roles['error']);
        }

        return response()->success(new UserRolesResource($roles['data']), "Role berhasil diubah");
    }
}
