<?php
namespace App\Helpers;

use DateInterval;
use DatePeriod;
use DateTime;
use Exception;

class Pivot
{
    protected $dates;
    protected $endDate;
    protected $startDate;
    protected $totalPerDate;

    /**
     * @throws Exception
     */
    protected function getPeriod(): array
    {
        $begin = new DateTime($this->startDate);
        $end = new DateTime($this->endDate);
        $end = $end->modify('+1 day');

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            $date = $dt->format('Y-m-d');
            $dates[$date] = [
                'date_transaction' => $date,
                'total_sale' => 0,
            ];

            $this->setDefaultTotal($date);
            $this->setSelectedDate($date);
        }

        return $dates ?? [];
    }

    private function setDefaultTotal(string $date)
    {
        $this->totalPerDate[$date] = 0;
    }

    private function setSelectedDate(string $date)
    {
        $this->dates[] = $date;
    }
}
