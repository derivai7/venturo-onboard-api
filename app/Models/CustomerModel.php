<?php

namespace App\Models;

use App\Http\Traits\RecordSignature;
use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method find(string $id)
 * @method create(array $payload)
 * @method get()
 */
class CustomerModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    use RecordSignature;

    public $timestamps = true;

    protected $table = 'm_customer';

    protected $attributes = [
        'is_verified' => 0,
    ];

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'date_of_birth',
        'photo',
        'is_verified',
    ];

    protected $casts = [
        'id' => 'string',
        'is_verified' => 'boolean',
    ];

    public function discount(): HasMany
    {
        return $this->hasMany(DiscountModel::class, 'm_customer_id', 'id');
    }

    public function voucher(): HasMany
    {
        return $this->hasMany(VoucherModel::class, 'm_customer_id', 'id');
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
        $query = $this->query();

        if (!empty($filter['id'])) {
            $customersIdArray = explode(',', $filter['id']);
            $query->whereIn('id', $customersIdArray);
        }

        if (!empty($filter['name'])) {
            $query->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        if (isset($filter['is_verified']) && $filter['is_verified'] !== '') {
            $query->where('is_verified', '=', $filter['is_verified']);
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
