<?php
namespace App\Models;

class Restaurant {
    public int $id;
    public string $name;
    public ?string $address;
    public ?string $schedule;
    public ?string $website;
    public ?string $phone;
    public bool $accessible;
    public bool $delivery;

    public static array $days = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su', 'PH'];
    
    public function __construct(array $data) {
        $this->id = (int) $data['idRestau'];
        $this->name = $data['nameR'];
        $this->city = $data['city'] ?? null;
        $this->schedule = $data['schedule'] ?? null;
        $this->website = $data['website'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->lat = $data['latitude'] ?? null;
        $this->lng = $data['longitude'] ?? null;
        $this->accessible = (bool) $data['accessibl'];
        $this->delivery = (bool) $data['delivery'];
    }

    public function getAddress() {
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$this->lat&lon=$this->lng";
    
        $opts = [
            "http" => [
                "header" => "User-Agent: MyPHPApp"
            ]
        ];
        $context = stream_context_create($opts);
        $response = file_get_contents($url, false, $context);
        $data = json_decode($response, true);

        $houseNumber = $data['address']['house_number'] ?? '';
        $road = $data['address']['road'] ?? $data['address']['square'] ?? '';
        $city = $data['address']['city'];
        $formatted_address = trim("$houseNumber $road, $city");
        return $formatted_address ?? "Address not found";
    }

    public function parseSchedule() {
        $parts = array_map('trim', explode(';', $this->schedule));
        $schedule = [];
        
        foreach ($parts as $part) {
            if (empty($part)) {
                continue;
            }

            list($days, $hours) = explode(' ', $part, 2);
            $daysList = explode(',', $days);
            $timeRanges = explode(',', $hours);
            
            foreach ($daysList as $day) {
                if (strpos($day, '-') !== false) {
                    list($startDay, $endDay) = explode('-', $day);
                    $daysRange = $this->getDaysInRange($startDay, $endDay);
                    foreach ($daysRange as $singleDay) {
                        $schedule[$singleDay] = $timeRanges;
                    }
                } else {
                    $schedule[$day] = $timeRanges;
                }
            }
        }
        return $schedule;
    }
    
    public function getDaysInRange($startDay, $endDay) {
        $startIdx = array_search($startDay, self::$days);
        $endIdx = array_search($endDay, self::$days);
        return array_slice(self::$days, $startIdx, $endIdx - $startIdx + 1);
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
        $mapSchedule = $this->parseSchedule();
    
        if (isset($mapSchedule[$currentDay])) {
            foreach ($mapSchedule[$currentDay] as $timeRange) {
                list($openTime, $closeTime) = explode('-', $timeRange);
                if ($currentTime >= $openTime && $currentTime <= $closeTime) {
                    return true;
                }
            }
        }
        return false;
    }

    public function whenWillClose(): string {
        ['day' => $currentDay, 'time' => $currentTime] = $this->getCurrentDayTime();
        $mapSchedule = $this->parseSchedule();

        if (isset($mapSchedule[$currentDay])) {
            foreach ($mapSchedule[$currentDay] as $timeRange) {
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
        $mapSchedule = $this->parseSchedule();
    
        if (isset($mapSchedule[$currentDay])) {
            foreach ($mapSchedule[$currentDay] as $slot) {
                [$start, $end] = explode('-', $slot);
    
                if ($currentTime < $start) {
                    return $start;
                }
            }
        }

        $currentIndex = array_search($currentDay, self::$days);
        $nextDay = self::$days[($currentIndex + 1) % 7];
    
        if (isset($mapSchedule[$nextDay])) {
            return $mapSchedule[$nextDay][0] ? explode('-', $mapSchedule[$nextDay][0])[0] : null;
        }
        return 'N/A'; 
    }
}