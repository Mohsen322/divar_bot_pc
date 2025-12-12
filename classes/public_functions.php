<?php
// تبدیل به تاریخ شمسی
require_once("jdf.php");

function gregorianToJalaliDate($gregorian_date){
	list($year, $month, $day) = explode("-", $gregorian_date);
	list($j_year, $j_month, $j_day) = gregorian_to_jalali($year, $month, $day);
	
	return $j_year.'/'.$j_month.'/'.$j_day;
}


	function getRegionAlias($region){
		if($region == 'turkey') return 'ترکیه';
		elseif($region == 'usa') return 'آمریکا';
		elseif($region == 'japan') return 'ژاپن';
		elseif($region == 'ukrain') return 'اوکراین';
		elseif($region == 'all') return 'ریجن آل';
	}

function human_readable_time_difference($target_datetime_str, $format_str) {
    // تبدیل تاریخ و زمان مورد نظر به شی DateTime
    $target_datetime = new DateTime($target_datetime_str);

    // محاسبه تفاوت زمانی
    $now = new DateTime();
    $time_difference = $now->diff($target_datetime);

    // استخراج تعداد روزها، ساعت‌ها، دقیقه‌ها و ثانیه‌ها
    $days = $time_difference->days;
    $hours = $time_difference->h;
    $minutes = $time_difference->i;
    $seconds = $time_difference->s;

    // ایجاد یک رشته قابل خواندن برای نمایش
    $result = "";

    if ($format_str == "d h") {
        if ($days > 0) {
            $result .= "$days روز";
            if ($hours > 0) {
                $result .= " و $hours ساعت";
            }
        } elseif ($hours > 0) {
            $result .= "$hours ساعت";
        } else {
            $result = "لحظاتی";
        }
        $result .= " پیش.";
    } elseif ($format_str == "d h i") {
        $parts = [];
        if ($days > 0) {
            $parts[] = "$days روز";
        }
        if ($hours > 0) {
            $parts[] = "$hours ساعت";
        }
        if ($minutes > 0) {
            $parts[] = "$minutes دقیقه";
        }
        if (empty($parts)) {
            $result = "لحظاتی";
        } else {
            $result = implode(" و ", $parts);
        }
        $result .= " پیش.";
    } elseif ($format_str == "d h i s") {
        $parts = [];
        if ($days > 0) {
            $parts[] = "$days روز";
        }
        if ($hours > 0) {
            $parts[] = "$hours ساعت";
        }
        if ($minutes > 0) {
            $parts[] = "$minutes دقیقه";
        }
        if ($seconds > 0) {
            $parts[] = "$seconds ثانیه";
        }
        if (empty($parts)) {
            $result = "لحظاتی";
        } else {
            $result = implode(" و ", $parts);
        }
        $result .= " پیش.";
    } else {
        $result = "فرمت معتبر نیست.";
    }

    return $result;
}
?>

