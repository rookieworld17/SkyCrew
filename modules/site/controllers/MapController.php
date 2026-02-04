<?php

namespace modules\site\controllers;

use Craft;
use craft\web\Controller;
use craft\elements\Entry;
use yii\web\Response;

class MapController extends Controller
{
    protected array|bool|int $allowAnonymous = true;

    public function actionCalc(): Response
    {
        $r = Craft::$app->request;

        $droneId  = $r->getRequiredParam('drone');
        $datetime = $r->getRequiredParam('datetime');
        $lat      = (float) $r->getRequiredParam('lat');
        $lng      = (float) $r->getRequiredParam('lng');
        $zonesRaw = $r->getParam('zones');

        $zones = $zonesRaw ? json_decode($zonesRaw, true) : [];

        $drone = Entry::find()
            ->section('drones')
            ->id($droneId)
            ->one();

        $dt = new \DateTime($datetime);
        $date = $dt->format('Y-m-d');
        $targetTs = $dt->getTimestamp();

        $weatherUrl =
            "https://api.open-meteo.com/v1/forecast" .
            "?latitude={$lat}&longitude={$lng}" .
            "&hourly=temperature_2m,wind_speed_10m" .
            "&start_date={$date}" .
            "&end_date={$date}" .
            "&timezone=auto";

        $weather = null;

        try {
            $weather = json_decode(
                file_get_contents($weatherUrl),
                true
            );
        } catch (\Throwable $e) {}

        $temperature = null;
        $windSpeedKmh = null;

        if (!empty($weather['hourly']['time'])) {
            $closestIndex = null;
            $minDiff = PHP_INT_MAX;

            foreach ($weather['hourly']['time'] as $i => $time) {
                $ts = strtotime($time);
                $diff = abs($ts - $targetTs);

                if ($diff < $minDiff) {
                    $minDiff = $diff;
                    $closestIndex = $i;
                }
            }

            if ($closestIndex !== null) {
                $temperature = $weather['hourly']['temperature_2m'][$closestIndex] ?? null;
                $windSpeedKmh = $weather['hourly']['wind_speed_10m'][$closestIndex] ?? null;
            }
        }

        $windSpeed = $windSpeedKmh !== null
            ? round(((float) $windSpeedKmh) / 3.6, 2)
            : null;

        // MAX WIND RESISTANCE BY WEIGHT
        $weight = (float) ($drone->weight ?? 0);

        if ($weight < 250) {
            $maxWindResistance = 7.0;
        } elseif ($weight < 500) {
            $maxWindResistance = 9.0;
        } elseif ($weight < 900) {
            $maxWindResistance = 11.0;
        } else {
            $maxWindResistance = 14.0;
        }

        $windLimit = $maxWindResistance * 0.7;

        // TIME
        $hour = (int) (new \DateTime($datetime))->format('G');

        $N = 40;
        $D = 0;

        if ($hour >= 21 || $hour < 6) {
            $N = 5;
            $D = 1;
        } elseif ($hour >= 18 || $hour < 8) {
            $N = 20;
            $D = 0.5;
        }

        $E = 5;

        $C =
            (($windSpeed / $windLimit) * 40) +
            ((40 - $N) * $D) +
            ((12 - $E) * 3);

        if ($C < 40) {
            $difficulty = 'Einfach';
            $difficultyLevel = 'success';
        } elseif ($C < 80) {
            $difficulty = 'Mittel';
            $difficultyLevel = 'warning';
        } else {
            $difficulty = 'Gefahr';
            $difficultyLevel = 'danger';
        }

        $zoneNames = [];

        foreach ($zones as $zone) {
            if (!isset($zone['id'])) {
                continue;
            }

            $zoneNames[] = $this->normalizeZoneName($zone['id']);
        }

        $zoneNames = array_unique($zoneNames);
        $noFlyZone = !empty($zoneNames);
        $zoneText  = $noFlyZone ? implode(', ', $zoneNames) : null;

        $noFlyWind = $windSpeed !== null && $windSpeed > $windLimit;
        $canFly = !$noFlyWind && !$noFlyZone;

        $html = Craft::$app->getView()->renderTemplate(
            'pages/partials/mapInfo.twig',
            [
                'drone'              => $drone,
                'lat'                => $lat,
                'lng'                => $lng,
                'temperature'        => $temperature,
                'windSpeedKmh'       => $windSpeedKmh,
                'windSpeedMs'        => $windSpeed,
                'maxWindResistance'  => $maxWindResistance,
                'windLimit'          => round($windLimit, 1),
                'difficulty'         => $difficulty,
                'difficultyLevel'    => $difficultyLevel,
                'canFly'             => $canFly,
                'noFlyWind'          => $noFlyWind,
                'noFlyZone'          => $noFlyZone,
                'zoneText'           => $zoneText,
            ]
        );

        return $this->asRaw($html);
    }

    private function normalizeZoneName(string $id): string
    {
        $base = explode('.', $id)[0];

        return ucfirst(str_replace('_', ' ', $base));
    }
}
