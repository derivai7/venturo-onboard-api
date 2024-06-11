<?php

namespace App\Models;

use App\Http\Traits\RecordSignature;
use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @method find(string $id)
 * @method where(string $string, string $saleId)
 * @method create(array $payload)
 */
class SaleDetailModel extends Model implements CrudInterface
{
    use Uuid;
    use HasFactory;
    use SoftDeletes;
    use RecordSignature;

    public $timestamps = true;
    protected $fillable = [
        't_sales_id',
        'm_product_id',
        'm_product_detail_id',
        'total_item',
        'price',
        'discount_nominal',
        'note'
    ];

    protected $casts = [
        'id' => 'string',
    ];

    protected $table = 't_sales_detail';

    public function sale(): BelongsTo
    {
        return $this->belongsTo(SaleModel::class, 't_sales_id', 'id');
    }

    public function product(): HasOne
    {
        return $this->hasOne(ProductModel::class, 'id', 'm_product_id');
    }

    public function detail(): HasOne
    {
        return $this->hasOne(ProductDetailModel::class, 'id', 'm_product_detail_id');
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

        foreach ($filter as $field => $value) {
            if (!empty($value)) {
                $query->where($field, 'LIKE', '%' . $value . '%');
            }
        }

        $sort = $sort ?: 'id DESC';
        $query->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : null;

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
