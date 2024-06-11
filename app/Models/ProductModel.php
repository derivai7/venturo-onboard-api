<?php

namespace App\Models;

use App\Http\Traits\RecordSignature;
use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method find(string $id)
 * @method create(array $payload)
 * @method orderBy(string $string, string $string1)
 * @method get()
 */
class ProductModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use RecordSignature;

    use Uuid;

    public $timestamps = true;

    protected $fillable = [
        'm_product_category_id',
        'name',
        'price',
        'description',
        'photo',
        'is_available'
    ];

    protected $casts = [
        'id' => 'string',
    ];

    protected $table = 'm_product';

    public function category(): HasOne
    {
        return $this->hasOne(ProductCategoryModel::class, 'id', 'm_product_category_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ProductDetailModel::class, 'm_product_id', 'id')->orderBy('description');
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

        if (!empty($filter['name'])) {
            $user->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        if (!empty($filter['m_product_category_id'])) {
            $user->where('m_product_category_id', '=', $filter['m_product_category_id']);
        }

        if ($filter['is_available'] != '') {
            $user->where('is_available', '=', $filter['is_available']);
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

    public function getNewest()
    {
        return $this->orderBy('created_at', 'desc')->first();
    }
}
