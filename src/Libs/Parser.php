<?php

namespace UberCrawler\Libs;

use UberCrawler\Config\App as App;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

class Parser
{
    /**
     * [$_currentPage description].
     *
     * @var [type]
     */
    protected $_currentPage;

    /**
     * [$_nextPage description].
     *
     * @var [type]
     */
    protected $_nextPage;

    /**
     * [$_rawHTMLData description].
     *
     * @var [type]
     */
    protected $_rawHTMLData;

    /**
     * [$_DomDocument description].
     *
     * @var [type]
     */
    protected $_DomDocument;

    /**
     * [$_DomXPath description].
     *
     * @var [type]
     */
    protected $_DomXPath;

    /**
     * [$_tripsCollection description].
     *
     * @var [type]
     */
    protected $_tripsCollection;

    /**
     * [__construct description].
     *
     * @param string $html [description]
     */
    public function __construct($html = '')
    {
        $this->_DomDocument = new \DOMDocument();

        if (!empty($html)) {
            $this->loadHTML($html);
        }

        // Get an instance of TripsCollection Object
        $this->_tripsCollection = new TripsCollection();
    }

    /**
     * [getDomXPath description].
     *
     * @codeCoverageIgnore
     *
     * @return [type] [description]
     */
    public function getDomXPath()
    {
        return $this->_DomXPath;
    }

    /**
     * [getDomDocument description].
     *
     * @codeCoverageIgnore
     *
     * @return [type] [description]
     */
    public function getDomDocument()
    {
        return $this->_DomDocument;
    }

    /**
     * Returns the instance of TripsCollection.
     *
     * @return [type] [description]
     */
    public function getTripsCollection()
    {
        return $this->_tripsCollection;
    }

    /**
     * Returns the loaded Raw HTML Data.
     *
     * @codeCoverageIgnore
     *
     * @return [type] [description]
     */
    public function getRawHTMLData()
    {
        return $this->_rawHTMLData;
    }

    /**
     * Loads the HTML into the DOMDocument
     * then calls the parseDataTable()
     * to generate the TripDetails and add
     * them to the TripsCollection.
     *
     * @param string $html [description]
     *
     * @return [type] [description]
     */
    public function parsePage($html = '')
    {
        if (empty($html)) {
            return false;
        }

        $this->loadHTML($html);

        return $this->parseDataTable();
    }

    /**
     * [loadHTML description].
     *
     * @param [type] $html [description]
     *
     * @return [type] [description]
     */
    public function loadHTML($html)
    {
        if (empty($html)) {
            throw new GeneralException(
                'Cannot parse empty HTML Document!',
                'FATAL'
            );
        }

        $this->_rawHTMLData = $html;
        $loadedHTML = $this->_DomDocument->loadHTML($html);
        $this->_DomXPath = new \DomXPath($this->_DomDocument);

        // True of the HTML has been loaded
        // and a new instance of DomXPath was successfully
        // created
        return (boolean) ($loadedHTML & ($this->_DomXPath instanceof \DomXPath));
    }

    /**
     * Retrieve the value of the "href" attribute of the pagination
     * "next" element.
     *
     * @return string GET query string ?page=n
     */
    public function getNextPage()
    {

        // Find the element for the next element in the pagination
        $nodes = $this->_DomXPath->query("//*[contains(concat(' ',".
                                         " normalize-space(@class), ' '), '".
                                         " pagination__next ')]");

        if (count($nodes) > 0) {
            foreach ($nodes as $elmnt) {
                // Return the first match
                return $elmnt->attributes['href']->value;
            }
        }

        return false;
    }

    /**
     * Parses the Data Table from the Uber Page. Do not call this
     * method if _DomXPath has not been initialized.
     *
     * It will add the parsed TripDetails into the _tripsCollection
     * instance and will always return True or False depending
     * on whether the _tripsCollection is Empty or Not.
     *
     * @return bool True or False depending on TripCollection emptiness
     */
    public function parseDataTable()
    {
        if (!$this->_DomXPath) {
            throw new GeneralException(
                'Cannot Parse the data table as _DomXPath has not '.
                'been initialized yet!',
                'FATAL'
            );
        }

        // Verify that the data table exists
        $dataTable = $this->_DomXPath->query('//*[@id="trips-table"]/tbody');
        // If data table was not found
        // return 'true' meaning that the TripsCollection is empty!
        if ($dataTable->length < 1) {
            return true;
        }

        // This will return the number childNodes within the tbody
        // it will refer to the number of <tr> elements within the
        // table i.e. the number of trips
        $pageTripCount = $this->_DomXPath
                              ->query('//*[@id="trips-table"]/tbody/tr')
                              ->length;

        /**
         * This is the list of all XPath queries for all the needed elements
         * in the retrieved HTML
         * Each trip has 2 <tr> elements in the <table> and each
         * page contains 20 trips meaning we have 40 <tr> elements
         * per page
         * {:TRIP_N:} is used as a placeholder and will be replaced
         * by the position of the trip in the HTML DomTree
         * 'F' replaces {:TRIP_N:} with a value 'n'
         * 'N' replaces {:TRIP_N:} with a value 'n+1'
         */
        $xpathMap = [
                'tripID'
                => ['//*[@id="trips-table"]/tbody/tr[{:TRIP_N:}]/@data-target', 'F'],
                'pickupDate'
                => ['//*[@id="trips-table"]/tbody/tr[{:TRIP_N:}]/td[2]/text()','F'],
                'driverName'
                => ['//*[@id="trips-table"]/tbody/tr[{:TRIP_N:}]/td[3]/text()', 'F'],
                'tripStatus'
                => ['//*[@id="trips-table"]/tbody/tr[{:TRIP_N:}]/td[4]/div/text()', 'F'],
                'fareValue'
                => ['//*[@id="trips-table"]/tbody/tr[{:TRIP_N:}]/td[4]/text()', 'F'],
                'carType'
                => ['//*[@id="trips-table"]/tbody/tr[{:TRIP_N:}]/td[5]/text()', 'F'],
                'city'
                => ['//*[@id="trips-table"]/tbody/tr[{:TRIP_N:}]/td[6]/text()', 'F'],
                'pickupTime'
                => ['//*[@id="trips-table"]/tbody/tr[{:TRIP_N:}]/td/div/div/div/div[2]/div/div[1]/div[3]/p/text()', 'N'],
                'pickupAddress'
                => ['//*[@id="trips-table"]/tbody/tr[{:TRIP_N:}]/td/div/div/div/div[2]/div/div[1]/div[3]/h6/text()', 'N'],
                'dropoffTime'
                => ['//*[@id="trips-table"]/tbody/tr[{:TRIP_N:}]/td/div/div/div/div[2]/div/div[2]/div[2]/p/text()', 'N'],
                'dropoffAddress'
                => ['//*[@id="trips-table"]/tbody/tr[{:TRIP_N:}]/td/div/div/div/div[2]/div/div[2]/div[2]/h6/text()', 'N']
        ];
        
        /**
         * TODO: This is really bad --
         * Needs major rework
         */
        for ($tripN = 1; $tripN < $pageTripCount; $tripN += 2) {
            // Create a new TripDetails Object
            $trip = new TripDetails();
            // Set the trip details
            foreach ($xpathMap as $key => $element) {
                // either tripN or tripN + 1
                $trIndex = ($element[1] == 'F') ? $tripN : $tripN + 1;
                $xpath = str_replace("{:TRIP_N:}", $trIndex, $element[0]);
                // Query the DOM
                $nodeList = $this->_DomXPath->query($xpath);
                // Get the text
                if ($nodeList->length > 0) {
                    // Fill the data in TripDetails
                    switch ($key) {
                        case 'tripID':
                            // This is an attribute so we capture the
                            // value here
                            $value = $nodeList[0]->value;
                            $trip->setTripId($value);
                            break;
                        case 'pickupDate':
                            // This is an element, we capture wholeText
                            $nodeText = $nodeList[0]->wholeText;
                            $trip->setPickupDate($nodeText);
                            break;
                        case 'driverName':
                            $nodeText = $nodeList[0]->wholeText;
                            $trip->setDriverName($nodeText);
                            break;
                        case 'tripStatus':
                            $nodeText = $nodeList[0]->wholeText;
                            $trip->setFareValue($nodeText);
                            break;
                        case 'fareValue':
                            $nodeText = $nodeList[0]->wholeText;
                            // Remove Unicode Characters
                            $nodeText = preg_replace(
                                "/^[\pZ\pC]+|[\pZ\pC]+$/u",
                                '',
                                $nodeText
                            );
                            $trip->setFareValue($nodeText);
                            break;
                        case 'carType':
                            $nodeText = $nodeList[0]->wholeText;
                            $trip->setCarType($nodeText);
                            break;
                        case 'city':
                            $nodeText = $nodeList[0]->wholeText;
                            $trip->setCity($nodeText);
                            break;
                        case 'pickupTime':
                            $nodeText = $nodeList[0]->wholeText;
                            $trip->getTripRoute()->setOriginPickupDateTime(
                                $nodeText
                            );
                            break;
                        case 'dropoffTime':
                            $nodeText = $nodeList[0]->wholeText;
                            $trip->getTripRoute()->setDestDropoffDateTime(
                                $nodeText
                            );
                            break;
                        case 'pickupAddress':
                            $nodeText = $nodeList[0]->wholeText;
                            $trip->getTripRoute()->setPickupStreetAddress(
                                $nodeText
                            );
                            break;
                        case 'dropoffAddress':
                            $nodeText = $nodeList[0]->wholeText;
                            $trip->getTripRoute()->setDropoffStreetAddress(
                                $nodeText
                            );
                            break;
                    }
                }
            }
            // Add the trip to the TripsCollection
            $this->_tripsCollection->addTrip($trip);
        }

        return $this->_tripsCollection->isEmpty();
    }
}
