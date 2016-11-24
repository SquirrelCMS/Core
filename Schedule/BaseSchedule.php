<?php
/**
 * Created by PhpStorm.
 * User: mrubk
 * Date: 21/10/2016
 * Time: 5:20 CH
 */

namespace Modules\Core\Schedule;


use Illuminate\Console\Scheduling\Schedule;
use Modules\Core\Schedule\Contracts\ScheduleContract;

abstract class BaseSchedule implements ScheduleContract
{
    /**
     * @var \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected $schedule;

    public function __construct(Schedule &$schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * @param $schedule
     * @return $this
     */
    public function setSchedule(&$schedule)
    {
        $this->schedule = $schedule;
        return $this;
    }
}