<?php
namespace App\Models;

use App\Http\Traits\RecordSignature;
use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method find(string $id)
 * @method create(array $payload)
 * @method where(string $string, string $name)
 */
class RoleModel extends Model implements CrudInterface
{
    use Uuid;
    use HasFactory;
    use SoftDeletes; // Use SoftDeletes library
    use RecordSignature;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'access',
    ];

    protected $table = 'user_roles';

    protected $casts = [
        'id' => 'string',
    ];

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
        $roles = $this->query();

        if (!empty($filter['name'])) {
            $roles->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        $sort = $sort ?: 'id DESC';
        $roles->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $roles->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(string $id)
    {
        return $this->find($id);
    }

    public function getIdByName(string $name)
    {
        $user = $this->where('name', $name)->first();

        if ($user) {
            return $user->id;
        }

        return null;
    }


    public function store(array $payload)
    {
        return $this->create($payload);
    }
}
