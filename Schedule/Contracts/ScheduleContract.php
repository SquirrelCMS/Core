<?php
/**
 * Created by Squirrel Group.
 * User: mr.ubkey@gmail.com
 * Date: 21/10/2016
 * Time: 5:18 CH
 */

namespace Modules\Core\Schedule\Contracts;


interface ScheduleContract
{
    /**
     * @param $schedule
     * @return mixed
     */
    public function setSchedule(&$schedule);

    /**
     * @return mixed
     */
    public function register();
}