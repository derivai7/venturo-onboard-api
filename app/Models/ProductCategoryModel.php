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
 * @method create(array $payload)
 * @method find(int $id)
 * @method where(string $string, string $string1, $index)
 * @method static max(string $string)
 */
class ProductCategoryModel extends Model implements CrudInterface
{
    use Uuid;
    use HasFactory;
    use SoftDeletes; // Use SoftDeletes library
    use RecordSignature;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'index'
    ];

    protected $casts = [
        'id' => 'string',
    ];

    protected $table = 'm_product_category';

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

        $sort = $sort ?: 'm_product_category.index ASC';
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
