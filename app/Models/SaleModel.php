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
use Illuminate\Support\Facades\DB;

/**
 * @method find(string $id)
 * @method create(array $payload)
 * @method orderBy(string $string, string $string1)
 * @method get()
 * @method static where(string $string, string $string1, string $string2)
 */
class SaleModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    use RecordSignature;

    public $timestamps = true;

    protected $fillable = [
        'no_receipt',
        'subtotal',
        'total_payment',
        'm_customer_id',
        'm_voucher_id',
        'voucher_nominal',
        'm_discount_id',
        'date',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    protected $table = 't_sales';

    public function customer(): HasOne
    {
        return $this->hasOne(CustomerModel::class, 'id', 'm_customer_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(SaleDetailModel::class, 't_sales_id', 'id');
    }

    public function voucher(): HasOne
    {
        return $this->hasOne(VoucherModel::class, 'id', 'm_voucher_id');
    }

    public function discount(): HasOne
    {
        return $this->hasOne(DiscountModel::class, 'id', 'm_discount_id');
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

        $sort = $sort ?: 'id DESC';
        $query->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $query->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(string $id)
    {
        return $this->find($id);
    }

    public function getNewest()
    {
        return $this->orderBy('created_at', 'desc')->first();
    }

    public function getLastSaleByDate($date)
    {
        return $this->where('no_receipt', 'LIKE', "%/KWT/$date")
            ->orderBy('date', 'desc')
            ->first();
    }

    public function getSalePromo(array $filter, int $itemPerPage = 0, string $sort = ''): LengthAwarePaginator
    {
        $sale = $this->query()->with(['voucher', 'discount', 'customer', 'voucher.promo', 'discount.promo']);

        $startDate = $filter['start_date'];
        $endDate = $filter['end_date'];
        $customer = $filter['customer_id'];
        $promo = $filter['promo_id'];

        if (!empty($startDate) && !empty($endDate)) {
            $sale->whereRaw('date >= "' . $startDate . ' 00:00:01" and date <= "' . $endDate . ' 23:59:59"');
        }

        if (!empty($customer)) {
            $sale->whereIn('m_customer_id', $customer);
        }

        if (!empty($promo)) {
            $sale->where(function ($query) use ($promo) {
                $query->whereHas('voucher', function ($query) use ($promo) {
                    $query->where('m_promo_id', $promo);
                })->orWhereHas('discount', function ($query) use ($promo) {
                    $query->where('m_promo_id', $promo);
                });
            });
        } else {
            $sale->where(function ($query) {
                $query->whereNotNull('m_voucher_id')->orWhereNotNull('m_discount_id');
            });
        }

        $sort = $sort ?: 'id DESC';
        $sale->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $sale->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getSaleTransaction(array $filter, int $itemPerPage = 0, string $sort = ''): LengthAwarePaginator
    {
        $sale = $this->query()->with([
            'voucher',
            'discount',
            'details',
            'details.product',
            'details.detail',
            'customer',
            'voucher.promo',
            'discount.promo'
        ]);

        $startDate = $filter['start_date'];
        $endDate = $filter['end_date'];
        $customer = $filter['customer_id'];
        $menu = $filter['menu_id'];

        if (!empty($startDate) && !empty($endDate)) {
            $sale->whereRaw('date >= "' . $startDate . ' 00:00:01" and date <= "' . $endDate . ' 23:59:59"');
        }

        if (!empty($customer)) {
            $sale->whereIn('m_customer_id', $customer);
        }

        if (!empty($menu)) {
            $sale->whereHas('details', function ($query) use ($menu) {
                $query->whereIn('m_product_id', $menu);
            });
        }

        $sort = $sort ?: 'id DESC';
        $sale->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $sale->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getSaleByCategory($startDate, $endDate, $category = '')
    {
        $sale = $this->query()->with([
            'details.product' => function ($query) use ($category) {
                if (!empty($category)) {
                    $query->where('m_product_category_id', $category);
                }
            },
            'details',
            'details.product.category'
        ]);

        if (!empty($startDate) && !empty($endDate)) {
            $sale->whereRaw('date >= "' . $startDate . ' 00:00:01" and date <= "' . $endDate . ' 23:59:59"');
        }

        return $sale->orderByDesc('date')->get();
    }

    public function getSaleByCustomers($startDate, $endDate, $customer)
    {
        $sale = $this->query()->with('customer');

        if (!empty($customer)) {
            $sale->whereIn('m_customer_id', $customer);
        }

        if (!empty($startDate) && !empty($endDate)) {
            $sale->whereRaw('date >= "' . $startDate . ' 00:00:01" and date <= "' . $endDate . ' 23:59:59"');
        }

        return $sale->orderByDesc('date')->get();
    }

    public function getSaleDetailCustomer($customerId, $date)
    {
        return $this->query()->with(['customer', 'details'])
            ->where('m_customer_id', $customerId)
            ->whereDate('date', '=', $date)
            ->orderBy('no_receipt')
            ->get();
    }

    public function getTotalSaleByPeriod(string $startDate, string $endDate): int
    {
        $total = $this->query()
            ->select(DB::raw('sum(total_payment) as total_sale'))
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereRaw('date >= "' . $startDate . ' 00:00:01" and date <= "' . $endDate . ' 23:59:59"');
            })
            ->first()
            ->toArray();

        return $total['total_sale'] ?? 0;
    }

    public function getListYear(): array
    {
        $years = $this->query()
            ->select(DB::raw('Distinct(year(date)) as year'))
            ->get()
            ->toArray();

        return array_map(function ($year) {
            return $year['year'];
        }, $years);
    }

    public function getTotalPerYear($year)
    {
        $total = $this->query()
            ->select(DB::raw('SUM(total_payment) as total_sale'))
            ->where(function ($query) use ($year) {
                $query->where(DB::raw('year(date)'), '=', $year);
            })
            ->first()
            ->toArray();

        return $total['total_sale'] ?? 0;
    }

    public function getTotalPerMonth($month, $year)
    {
        $total = $this->query()
            ->select(DB::raw('SUM(total_payment) as total_sale'))
            ->where(function ($query) use ($month, $year) {
                $query->whereMonth('date', '=', $month)
                    ->whereYear('date', '=', $year);
            })
            ->first()
            ->toArray();

        return $total['total_sale'] ?? 0;
    }

    public function getTotalPerDates($dates)
    {
        $salesData = $this->query()
            ->select(DB::raw('DATE(date) as saleDate'), DB::raw('SUM(total_payment) as totalSale'))
            ->whereRaw('date >= "' . $dates['startDate'] . ' 00:00:01" and date <= "' . $dates['endDate'] . ' 23:59:59"')
            ->groupBy('saleDate')
            ->orderBy('saleDate', 'ASC')
            ->get();

        return $salesData ?? [];
    }

}
