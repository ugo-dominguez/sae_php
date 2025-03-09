<?php
namespace App\Models;

use App\Config\Requests;
use App\Config\Utils;

class Restaurant {
    public int $id;
    public string $name;
    public ?string $address;
    public ?string $website;
    public ?string $phone;
    public bool $accessible;
    public bool $delivery;
    public ?array $schedule;

    public static array $days = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su', 'PH'];
    
    public function __construct(array $data) {
        $this->id = (int) $data['idRestau'];
        $this->name = $data['nameR'];
        $this->city = $data['city'] ?? null;
        $this->website = $data['website'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->lat = $data['latitude'] ?? null;
        $this->lng = $data['longitude'] ?? null;
        $this->accessible = (bool) $data['accessibl'];
        $this->delivery = (bool) $data['delivery'];
        $this->schedule = !empty($data['schedule']) ? self::mapSchedule($data['schedule']) : null;
    }

    public function getAddress() {
        return $this->city;
    }

    public function getCurrentDayTime() {
        date_default_timezone_set('Europe/Paris');

        return [
            'day' => substr(date('D'), 0, 2),
            'time' => date('H:i')
        ];
    }

    public function isCurrentlyOpen() {
        ['day' => $currentDay, 'time' => $currentTime] = $this->getCurrentDayTime();
        
        if (isset($this->schedule[$currentDay])) {
            foreach ($this->schedule[$currentDay] as $timeRange) {
                list($openTime, $closeTime) = explode('-', $timeRange);
                
                $currentTimestamp = strtotime($currentTime);
                $openTimestamp = strtotime($openTime);
                $closeTimestamp = strtotime($closeTime);
                
                if ($closeTimestamp < $openTimestamp) {
                    $closeTimestamp = strtotime('+1 day', $closeTimestamp);
                }
                
                if ($currentTimestamp >= $openTimestamp && $currentTimestamp <= $closeTimestamp) {
                    return true;
                }
            }
        }
        return false;
    }

    public function whenWillClose(): string {
        ['day' => $currentDay, 'time' => $currentTime] = $this->getCurrentDayTime();

        if (isset($this->schedule[$currentDay])) {
            foreach ($this->schedule[$currentDay] as $timeRange) {
                list($openTime, $closeTime) = explode('-', $timeRange);
                if ($currentTime >= $openTime && $currentTime <= $closeTime) {
                    return $closeTime;
                }
            }
        }
        return 'N/A';
    }

    public function whenWillOpen(): string {
        ['day' => $currentDay, 'time' => $currentTime] = $this->getCurrentDayTime();
    
        if (isset($this->schedule[$currentDay])) {
            foreach ($this->schedule[$currentDay] as $slot) {
                [$start, $end] = explode('-', $slot);
    
                if ($currentTime < $start) {
                    return $start;
                }
            }
        }

        $currentIndex = array_search($currentDay, self::$days);
        $nextDay = self::$days[($currentIndex + 1) % 7];
    
        if (isset($this->schedule[$nextDay])) {
            return $this->schedule[$nextDay][0] ? explode('-', $this->schedule[$nextDay][0])[0] : '';
        }
        return 'N/A'; 
    }

    public static function getDaysInRange($startDay, $endDay) {
        $startIdx = array_search($startDay, self::$days);
        $endIdx = array_search($endDay, self::$days);
        return array_slice(self::$days, $startIdx, $endIdx - $startIdx + 1);
    }

    public static function mapSchedule(string $schedule) {
        $parts = array_map('trim', explode(';', $schedule));
        $mappedSchedule = [];
        
        foreach ($parts as $part) {
            if (empty($part)) {
                continue;
            }

            $exploded = explode(' ', $part, 2);
            $days = $exploded[0] ?? '';
            $hours = $exploded[1] ?? '';
            $daysList = explode(',', $days);
            $timeRanges = !empty($hours) ? explode(',', $hours) : [];
            
            foreach ($daysList as $day) {
                if (strpos($day, '-') !== false) {
                    list($startDay, $endDay) = explode('-', $day);
                    $daysRange = self::getDaysInRange($startDay, $endDay);
                    foreach ($daysRange as $singleDay) {
                        $mappedSchedule[$singleDay] = $timeRanges;
                    }
                } else {
                    $mappedSchedule[$day] = $timeRanges;
                }
            }
        }
        return $mappedSchedule;
    }

    public function getNote() {
        $reviews = Requests::getReviewsForRestaurant($this->id);
        if (count($reviews) === 0) {
            return 0;
        }
        
        $total = 0;
        foreach ($reviews as $review) {
            $total += $review->note;
        }

        return $total / count($reviews);
    }

    public function getStars() {
        return Utils::getStars($this->getNote());
    }

    public function getReviewCount() {
        return count(Requests::getReviewsForRestaurant($this->id));
    }
}