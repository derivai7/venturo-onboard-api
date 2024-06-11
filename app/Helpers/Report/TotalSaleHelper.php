<?php

namespace App\Helpers\Report;

use App\Models\SaleModel;
use DateTime;
use Exception;

class TotalSaleHelper
{
    private $sale;

    public function __construct()
    {
        $this->sale = new SaleModel();
    }

    private function getTotalToday(): int
    {
        return $this->sale->getTotalSaleByPeriod(
            (string) date('Y-m-d'),
            (string) date('Y-m-d'));
    }

    private function getTotalYesterday(): int
    {
        $date = new DateTime();
        $date->modify('-1 day');

        return $this->sale->getTotalSaleByPeriod(
            $date->format('Y-m-d'),
            $date->format('Y-m-d'));
    }

    private function getTotalThisMonth(): int
    {
        $startDate = new DateTime();
        $start     = $startDate->modify('first day of this month')->format('Y-m-d');

        $endDate   = new DateTime();
        $end       = $endDate->modify('last day of this month')->format('Y-m-d');

        return $this->sale->getTotalSaleByPeriod($start, $end);
    }

    private function getTotalLastMonth(): int
    {
        $startDate = new DateTime();
        $start     = $startDate->modify('first day of last month')->format('Y-m-d');

        $endDate   = new DateTime();
        $end       = $endDate->modify('last day of last month')->format('Y-m-d');

        return $this->sale->getTotalSaleByPeriod($start, $end);
    }

    public function getTotalInPeriod(): array
    {
        return [
            'status'  => true,
            'data'    => [
                'today'      => $this->getTotalToday(),
                'yesterday'  => $this->getTotalYesterday(),
                'this_month' => $this->getTotalThisMonth(),
                'last_month' => $this->getTotalLastMonth(),
            ]
        ];
    }

    public function getTotalPerYears(): array
    {
        $years   = $this->sale->getListYear();
        sort($years);

        $diagram = [];
        foreach ($years as $year) {
            $total                    = $this->sale->getTotalPerYear($year);
            $diagram['label'][]       = (string) $year;
            $diagram['data'][]        = $total;
        }

        return [
            'status' => true,
            'data'   => $diagram
        ];
    }

    public function getTotalPerMonths($year): array
    {
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March',
            4 => 'April', 5 => 'May', 6 => 'June',
            7 => 'July', 8 => 'August', 9 => 'September',
            10 => 'October', 11 => 'November', 12 => 'December',
        ];

        $diagram = [];
        foreach ($months as $month => $name) {
            $total                    = $this->sale->getTotalPerMonth($month, $year);
            $diagram['label'][]       = $name;
            $diagram['data'][]        = $total;
        }

        return [
            'status' => true,
            'data'   => $diagram
        ];
    }

    /**
     * @throws Exception
     */
    public function getTotalPerCustomDate($startDate = null, $endDate = null): array
    {
        $dates = ['startDate' => $startDate, 'endDate' => $endDate];

        $listSales = $this->sale->getTotalPerDates($dates);
        $diagram = [];

        foreach ($listSales as $sale) {
            $isSameYear = $this->isSameYear($startDate, $endDate);

            $diagram['label'][] = $this->formatSaleDate($isSameYear, $sale['saleDate']);
            $diagram['data'][] = $sale['totalSale'];
        }

        return [
            'status' => true,
            'data'   => $diagram
        ];
    }

    /**
     * @throws Exception
     */
    private function isSameYear($startDate, $endDate): bool
    {
        $startDateTime = new DateTime($startDate);
        $endDateTime = new DateTime($endDate);

        return $startDateTime->format('Y') === $endDateTime->format('Y');
    }

    /**
     * @throws Exception
     */
    private function formatSaleDate($isSameYear, $date): string
    {
        $dateTime = new DateTime($date);
        $dateFormat = $isSameYear ? 'd M' : 'd M Y';

        return $dateTime->format($dateFormat);
    }
}
