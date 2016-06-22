<?php

namespace UberCrawler\Libs;

use UberCrawler\Config\App as App;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

class Helper
{
    /**
    * [$type description].
    *
    * @var string
    */
    public static function printOut($message, $type = 'INFO')
    {
        echo "{$type}::: {$message} \n";
    }

    /**
    * [makedirs description].
    *
    * @param [type]  $dirpath [description]
    * @param int $mode    [description]
    *
    * @return [type]  [description]
    */
    public static function makedirs(
        $dirpath,
        $mode = 0755
    ) {
    
        return is_dir($dirpath) || mkdir($dirpath, $mode, true);
    }

    /**
     * Stores the grabbed HTML pages onto the localdisk
     * for cashing purposes. The stored files are not being
     * used in this project yet. Might introduce this feature
     * in future releases.
     *
     * @param string $data     [description]
     * @param int    $pageNumb [description]
     *
     * @return array Status object with 'success' and 'fileName'
     */
    public static function storeIntoFile(
        $data,
        $pageNumb = 1
    ) {
        if (!is_integer($pageNumb)) {
            throw new GeneralException(
                'Page Number has to be a positive integer.',
                'FATAL'
            );
        }
        // Get the appropriate filename
        $fileName = self::buildStorageFilePath($pageNumb);
        // Build the necessary dirs if they
        // don't exist already
        Helper::makedirs(App::$APP_SETTINGS['data_storage_dir']);
        // Store data into a file
        if (!file_put_contents($fileName, $data)) {
            // Insufficient access possibly
            Helper::printOut("Unable to write content into: {$fileName}");
            // Failed to write content to file
            return [
                'success' => false,
                'fileName' => $fileName
            ];
        }
        // Writing to file is done successfully
        return [
            'success' => true,
            'fileName' => $fileName
        ];
    }

    /**
     * [buildStorageFilePath description].
     *
     * @param [type] $pageNumb [description]
     *
     * @return [type] [description]
     */
    public static function buildStorageFilePath($pageNumb)
    {
        // Build the filename
        $fileName = "data-page_{$pageNumb}.html";

        return implode(
            DIRECTORY_SEPARATOR,
            [App::$APP_SETTINGS['data_storage_dir'],
            $fileName]
        );
    }
}
