<?php
namespace App\Models;

use App\Http\Traits\RecordSignature;
use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @method find(string $id)
 * @method create(array $payload)
 * @method getKey()
 * @property $id
 * @property $email
 * @property $updated_security
 */
class UserModel extends Authenticatable implements CrudInterface, JWTSubject
{
    use Uuid;
    use HasFactory;
    use SoftDeletes; // Use SoftDeletes library
    use RecordSignature;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'user_roles_id',
        'phone_number',
    ];

    protected $table = 'user_auth';

    protected $casts = [
        'id' => 'string',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'user' => [
                'id' => $this->id,
                'email' => $this->email,
                'updated_security' => $this->updated_security
            ]
        ];
    }

    public function isHasRole($permissionName): bool
    {
        $tokenPermission = explode('|', $permissionName);
        $userPrivilege = json_decode($this->role->access ?? '{}', TRUE);

        foreach($tokenPermission as $val) {
            $permission = explode('.', $val);
            $feature = $permission[0] ?? '-';
            $activity = $permission[1] ?? '-';

            if(isset($userPrivilege[$feature][$activity]) && $userPrivilege[$feature][$activity] == true) {
                return true;
            }
        }

        return false;
    }

    public function role(): HasOne
    {
        return $this->hasOne(RoleModel::class, 'id', 'user_roles_id');
    }

    public function drop(string $id)
    {
        return $this->find($id)->delete();
    }

    public function edit(array $payload, string $id)
    {
        return $this->find($id)->update($payload);
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): LengthAwarePaginator
    {
        $user = $this->query();

        if (!empty($filter['name'])) {
            $user->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        if (!empty($filter['email'])) {
            $user->where('email', 'LIKE', '%' . $filter['email'] . '%');
        }

        $sort = $sort ?: 'id DESC';
        $user->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $user->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(string $id)
    {
        return $this->find($id);
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }
}
