<?php

class Calendar
{

    static function get_days_names()
    {
        return ORM::forTable('day_names')
            ->findArray();
    }

    public static function get_month_names()
    {
        return ORM::forTable('month_names')
            ->findArray();
    }

    public static function get_current_day()
    {
        return strftime("%d", time());
    }

     public static function get_current_hour()
    {
        return date('H');
    }

     public static function get_current_min()
    {
        return date('i');
    }

    public static function get_current_month()
    {
        return strftime("%m", time());
    }

    public static function get_current_year()
    {
        return strftime("%Y", time());
    }

    public static function quantity_days_in_month($selected_month = false, $selected_year = false)
    {
        if (!$selected_year) $selected_year = self::get_current_year();
        if (!$selected_month) $selected_month = self::get_current_month();
        return cal_days_in_month(CAL_GREGORIAN, $selected_month, $selected_year);
    }

    public static function get_day_no_in_week($day = false, $selected_month = false, $selected_year = false)
    {
        if (!$selected_year) $selected_year = self::get_current_year();
        if (!$selected_month) $selected_month = self::get_current_month();
        if (!$day) $day = self::get_current_day();
        return strftime("%u", strtotime("$selected_year-$selected_month-$day"));
    }

    public static function get_calendar_page($selected_month = false, $selected_year = false)
    {
        if (!$selected_month) {
            $selected_month = Calendar::get_current_month();
        }
        if (!$selected_year) {
            $selected_year = Calendar::get_current_year();
        }
        $last_month = $selected_month - 1;
        $next_month = $selected_month + 1;
        $temp_year_l_month = $selected_year;
        $temp_year_n_month = $selected_year;
        if ($last_month == 0) {
            $last_month = 12;
            $temp_year_l_month = $temp_year_l_month - 1;
        }
        if ($next_month == 13) {
            $next_month = 1;
            $temp_year_n_month = $temp_year_n_month + 1;
        }

        $quantity_days_in_selected_month = self::quantity_days_in_month($selected_month, $selected_year);
        $quantity_days_in_last_month = self::quantity_days_in_month($last_month, $temp_year_l_month);
        $quantity_days_in_next_month = self::quantity_days_in_month($next_month, $temp_year_n_month);
        $name_first_day_in_month = self::get_day_no_in_week(1, $selected_month, $selected_year);
        $name_last_day_in_month = self::get_day_no_in_week($quantity_days_in_selected_month, $selected_month, $selected_year);

        $page = [];

        //получить часть предыдущего месяца на странице
        if ($name_first_day_in_month == 1) {
            $first_month_on_calendar = $selected_month;
            $first_day_on_calendar = 1;
            $quantity_days_in_last_month_on_calendar = 0;
        } else {
            $first_month_on_calendar = $selected_month - 1;
            $first_day_on_calendar = $quantity_days_in_last_month - ($name_first_day_in_month - 1) + 1;
            $quantity_days_in_last_month_on_calendar = $quantity_days_in_last_month - $first_day_on_calendar;
            for ($i = 0; $i <= $quantity_days_in_last_month_on_calendar; $i++) {
                $page[] = ['day' => $first_day_on_calendar + $i, 'month' => $last_month, 'year' => $temp_year_l_month];
            }

        }

        //получить выбраный месяц
        for ($i=1;$i<=$quantity_days_in_selected_month;$i++){
            $page[] = ['day' =>  $i, 'month' => $selected_month, 'year' => $selected_year];
        }


        //получить часть следующего месяца на странице
        if ($name_last_day_in_month <= 7) {
            for ($i = 1; $name_last_day_in_month+$i <= 7; $i++) {
                $page[] = ['day' =>  $i, 'month' => $next_month, 'year' => $temp_year_n_month];
            }
        }
        return $page;
    }

}