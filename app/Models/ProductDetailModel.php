<?php

namespace App\Models;

use App\Http\Traits\RecordSignature;
use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method find(string $id)
 * @method create(array $payload)
 * @method where(string $string, string $productId)
 */
class ProductDetailModel extends Model implements CrudInterface
{
    use Uuid;
    use HasFactory;
    use SoftDeletes; // Use SoftDeletes library
    use RecordSignature;

    public $timestamps = true;
    protected $fillable = [
        'type',
        'description',
        'price',
        'm_product_id'
    ];

    protected $casts = [
        'id' => 'string',
    ];

    protected $table = 'm_product_detail';

    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class, 'm_product_id', 'id');
    }

    public function drop(string $id)
    {
        return $this->find($id)->delete();
    }

    public function dropByProductId(string $productId)
    {
        return $this->where('m_product_id', $productId)->delete();
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
