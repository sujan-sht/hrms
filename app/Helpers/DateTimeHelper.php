<?php

namespace App\Helpers;

use DateTime;
use Carbon\Carbon;

class DateTimeHelper
{
    /**
     *
     */

    // public static function getTimeDiff($startTime, $endTime)
    // {
    //     $workingHour = '';
    //     $time1 = Carbon::parse((string)$startTime);
    //     $time2 = Carbon::parse((string)$endTime);
    //     if (!empty($startTime) && !empty($endTime)) {
    //         $workingHour = $time1->diffInMinutes($time2, false);
    //         $workingHour = round($workingHour / 60, 1);
    //     }

    //     return $workingHour;
    // }

    public static function getTimeDiff($startTime, $endTime)
    {
        $workingHour = 0;

        // Validate that times are not empty
        if (!empty($startTime) && !empty($endTime)) {
            try {
                $time1 = Carbon::parse($startTime);
                $time2 = Carbon::parse($endTime);

                // Check if start time is greater than end time (next day scenario)
                if ($time1->greaterThan($time2)) {
                    // If start time is greater, assume end time is on the next day
                    $time2->addDay();  // Add a day to the end time
                }

                // Calculate the difference in minutes
                $workingHour = $time1->diffInMinutes($time2, false);

                // Ensure the result is not negative and convert minutes to hours
                $workingHour = $workingHour < 0 ? 0 : round($workingHour / 60, 1);
            } catch (\Exception $e) {
                // Handle exception if parsing fails
                $workingHour = 0;
            }
        }

        return $workingHour;
    }

    /**
     *
     */
    public static function DateDiff($startDate, $endDate)
    {
        $workingDays = 0;

        $date1 = Carbon::parse($startDate);
        $date2 = Carbon::parse($endDate);

        if (!empty($date2) && !empty($date1)) {
            $workingDays = $date1->diffInDays($date2, false);
            $workingDays = round($workingDays / 30, 0);
        }

        return $workingDays;
    }

    /**
     *
     */
    public static function DateDiffInDay($startDate, $endDate)
    {
        $workingDays = 0;

        $date1 = Carbon::parse($startDate);
        $date2 = Carbon::parse($endDate);

        if (!empty($date2) && !empty($date1)) {
            $workingDays = $date1->diffInDays($date2, false);
            // $workingDays = round($workingDays/30, 0);
        }

        return $workingDays;
    }

    /**
     *
     */
    public static function getMonthDiff($startDate, $endDate)
    {
        $diff = 0;

        $date1 = Carbon::parse($startDate);
        $date2 = Carbon::parse($endDate);

        if (!empty($date2) && !empty($date1)) {
            $diff = $date1->diffInMonths($date2, false);
        }

        return $diff;
    }

    public static function DateDiffInYearMonthDay($startDate, $endDate)
    {
        $date1 = Carbon::parse($startDate);
        $date2 = Carbon::parse($endDate);

        if (!empty($date2) && !empty($date1)) {
            $time = $date1->diff($date2);
        }
        $return = '';

        if ($time->y > 0) {
            $year = $time->y == 1 ? 'Year' : 'Years';
            $return = sprintf("%02d", $time->y) . ' ' . $year . ' ';
        }

        if ($time->m > 0) {
            $month = $time->m == 1 ? 'Month' : 'Months';
            $return .= sprintf("%02d", $time->m) . ' ' . $month . ' ';
        }

        if ($time->d > 0) {
            $day = $time->d == 1 ? 'Day' : 'Days';
            $return .= sprintf("%02d", $time->d) . ' ' . $day . ' ';
        }

        return $return;
    }
}
