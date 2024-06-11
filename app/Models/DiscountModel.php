<?php

namespace App\Models;

use App\Http\Traits\RecordSignature;
use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method find(string $id)
 * @method create(array $payload)
 * @method count()
 * @method selectRaw(string $string)
 */
class DiscountModel extends Model implements CrudInterface
{
    use Uuid;
    use HasFactory;
    use RecordSignature;

    public $timestamps = true;

    protected $fillable = [
        'm_customer_id',
        'm_promo_id',
    ];

    protected $table = 'm_discount';

    protected $casts = [
        'id' => 'string',
    ];

    public function customer(): HasOne
    {
        return $this->hasOne(CustomerModel::class, 'id', 'm_customer_id');
    }

    public function promo(): HasOne
    {
        return $this->hasOne(PromoModel::class, 'id', 'm_promo_id');
    }

    public function drop(string $id)
    {
        return $this->find($id)->delete();
    }

    public function edit(array $payload, string $id)
    {
        return $this->find($id)->update($payload);
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): LengthAwarePaginator
    {
        $user = $this->query();

        if (!empty($filter['m_customer_id']) && is_array($filter['m_customer_id'])) {
            $user->whereIn('m_customer_id', $filter['m_customer_id']);
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

    public function getTotalDiscountsByPromoIds()
    {
        return $this->selectRaw('m_promo_id, COUNT(*) as total')
            ->groupBy('m_promo_id')
            ->get();
    }
}
