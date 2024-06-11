<?php

namespace App\Models;

use App\Http\Traits\RecordSignature;
use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @method find(string $id)
 * @method create(array $payload)
 */
class PromoModel extends Model implements CrudInterface
{
    use Uuid;
    use HasFactory;
    use SoftDeletes;
    use RecordSignature;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'status',
        'expired_in_day',
        'nominal_percentage',
        'nominal_rupiah',
        'term_conditions',
        'photo',
    ];

    protected $table = 'm_promo';

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
        $query = $this->query();

        if (!empty($filter['name'])) {
            $query->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }
        if (!empty($filter['status'])) {
            $query->where('status', $filter['status']);
        }

        $sort = $sort ?: 'id DESC';
        $query->orderByRaw($sort);

        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $query->paginate($itemPerPage)->appends('sort', $sort);
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
