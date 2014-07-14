<?php
namespace msf\models;

class InterestRate {
    /**
     * Model name used by data source
     */
    const MODEL_NAME = 'interest_rate';

    /**
     * Valid type values
     */
    const SOURCE_URL = 'http://www.snaprates.com';

    /**
     * Raw HTML content retrieved from source.
     * @var string
     */
    private $_sourceContent;

    public function __construct($sourceUrl) {
        $this->_sourceContent = str_replace(
                array("\n", "\r", "\t"),
                '',
                file_get_contents($sourceUrl)
        );
        if (empty($this->_sourceContent)) {
            throw new \RuntimeException("{$sourceUrl} cannot be opened");
        }
    } // end __construct

    /**
     * Parses the source content and extracts the relevant information.
     * @return array Keyed array with treasury and swap rates
     *  array('treasury' => array(
     *          < timestamp>, < 7 year rate>, < 10 year rate> ),
     *        'swap'  => array(
     *           <timestamp>, < 7 year rate>, < 10 year rate> )
     *       )
     */
    public function parse() {
        $data = array();
        $data['treasury'] = $this->_parseSection('<h2>U.S. Treasuries</h2>');
        $data['swap'] = $this->_parseSection('<h2>Interest Rate Swaps</h2>');
        return $data;
    }

    /**
     * Extracts the meaningful content from the HTML block initiated with
     * the given header
     * @param string $header
     * @return array (last updated timestamp, 7-year rate, 10-year rate)
     */
    public function _parseSection($header) {
        // Start of block is denoted by the header passed in
        $start = stripos($this->_sourceContent, $header);
        // End of block should be the closest footer tag
        $end = stripos($this->_sourceContent, '<footer>', $start);

        // Get section to work with
        $section = substr($this->_sourceContent, $start, $end - $start);

        // Extract timestamp for last updates
        $match = array();
        preg_match('/<div class="aging" title="(.*?)">/', $section, $match);
        if (count($match) === 2) {
            $timestamp = date('Y-m-d g:i A', strtotime($match[1]));
        }
        else {
            $timestamp = 'Not available';
        }

        // Extract 7 and 10 year rates
        preg_match(
            '/<td class="type first">7 Year<\/td><td class="rate">(.*?)%<\/td>/',
            $section,
            $match
        );
        $rate7Year = empty($match[1]) ? 0.0 : $match[1];

        // Extract 7 and 10 year rates
        preg_match(
            '/<td class="type first">10 Year<\/td><td class="rate">(.*?)%<\/td>/',
            $section,
            $match
        );
        $rate10Year = empty($match[1]) ? 0.0 : $match[1];

        return array($timestamp, $rate7Year, $rate10Year);
    }


} // end InterestRate
