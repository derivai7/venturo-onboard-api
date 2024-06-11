<?php

namespace App\Models;

use App\Http\Traits\RecordSignature;
use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method find(string $id)
 * @method create(array $payload)
 * @method get()
 */
class VoucherModel extends Model implements CrudInterface
{
    use Uuid;
    use HasFactory;
    use SoftDeletes;
    use RecordSignature;

    public $timestamps = true;
    protected $fillable = [
        'm_customer_id',
        'm_promo_id',
        'start_time',
        'end_time',
        'total_voucher',
        'nominal_rupiah',
        'photo',
        'description'
    ];

    protected $table = 'm_voucher';

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
        $query = $this->query();

        if (!empty($filter['m_customer_id']) && is_array($filter['m_customer_id'])) {
            $query->whereIn('m_customer_id', $filter['m_customer_id']);
        }

        if (!empty($filter['start_time']) && !empty($filter['end_time'])) {
            $query->where(function ($query) use ($filter) {
                $query->where(function ($query) use ($filter) {
                    $query->where('start_time', '>=', $filter['start_time'])
                        ->where('start_time', '<=', $filter['end_time']);
                })->orWhere(function ($query) use ($filter) {
                    $query->where('end_time', '>=', $filter['start_time'])
                        ->where('end_time', '<=', $filter['end_time']);
                });
            })->orWhere(function ($query) use ($filter) {
                $query->where('start_time', '<=', $filter['start_time'])
                    ->where('end_time', '>=', $filter['end_time']);
            });
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

    public function useVoucher($id)
    {
        $voucher = $this->find($id);

        if ($voucher && $voucher->total_voucher > 0) {
            $voucher->total_voucher -= 1;
            return $voucher->save();
        }

        return false;
    }
}
