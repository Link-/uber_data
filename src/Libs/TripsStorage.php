<?php

namespace UberCrawler\Libs;

use UberCrawler\Config\App as App;

class TripsStorage
{
    /**
     * [TripCollectiontoCSV description].
     *
     * @param TripCollection $tripCol [description]
     */
    public static function TripCollectiontoCSV(TripsCollection $tripCol)
    {
        date_default_timezone_set(App::$APP_SETTINGS['timezone']);
        // Get current timestamp
        $timeStamp = time();
        // File: /tmp/uber-parsed/1464880984.csv
        $fullFilePath = implode(
            DIRECTORY_SEPARATOR,
            [App::$APP_SETTINGS['parsed_data_dir'],
            time().'.csv',
            ]
        );

        Helper::makedirs(App::$APP_SETTINGS['parsed_data_dir']);

        $fileHandle = fopen($fullFilePath, 'w');

        if ($fileHandle) {
            foreach ($tripCol as $trip) {
                fputcsv($fileHandle, $trip->toArray());
            }

            fclose($fileHandle);
        }

        return $fullFilePath;
    }
}
