<?php
/**
 * Class Housesimply
 *
 * This class serves as the service and Db model layer.
 *
 * @Author Nathan Daly
 */
class Housesimply {


    /**
     * @var mysqli
     */
    private $mysqli;

    /**
     * @var string
     */
    private $tableName = 'hs_url_lookup';

    /**
     * @var string
     */
    public $exceptionMsg = '';

    /**
     * @var string
     */
    public $bigUrl;

    /**
     * @var string
     */
    public $littleUrl;


    /**
     * Constructor
     *
     * @param $db
     */
    function __construct($url, $reverse = false) {
        // Make sure URL is not empty
        if (empty($url)) {
            return false;
        }

        // Instantiate mysqli class
        $this->mysqli = new mysqli(
            'HOST',
            'USERNAME',
            'PASSWORD',
            'DATABASE'
        );

        /* check connection */
        if (mysqli_connect_errno()) {
            $this->exceptionMsg = 'Failed to connect to MySQLi database';
            return false;
        }

        // Save URL to class property
        if ($reverse === false) {
            $this->bigUrl = $url;
        } else {
            $this->littleUrl = $url;
        }
    }

    /**
     * Main method that will do the conversion and
     * mediate between the private class methods.
     *
     * @return bool
     */
    public function minifyUrl()
    {
        // Make sure class property is set
        if(!isset($this->bigUrl)) {
            $this->exceptionMsg = 'URL is missing from class property';
            return false;
        }

        // Assign little URL
        $this->littleUrl = $this->encodeUrl($this->bigUrl);

        // Check if the URL already exists and if it does
        // then overwrite littleUrl with database entry
        if (!$this->checkUrlExists()) {

            // If there is no previous entry then save
            if (!$this->saveUrl()) {
                return false;
            }

        }

        return true;
    }

    /**
     * Helper method to produce a encrypted
     * string from the URL.
     *
     * @param $string
     * @return string
     */
    private function encodeUrl($string)
    {
        $key = 'housesimple-test-4321';

        if (extension_loaded('mcrypt') === true) {
            $string = base64_encode(mcrypt_encrypt(MCRYPT_BLOWFISH, substr($key, 0, mcrypt_get_key_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB)), trim($string), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND)));
            $string = substr($string, 0, 3);
            $rand = substr(md5(microtime()),rand(0,26),3);
            $string = $string.$rand;
            return $string;
        }

        $this->exceptionMsg = 'Apache module mcrypt must be loaded in order to encode strings';
        return false;
    }

    /**
     * Method to save the URL data.
     *
     * @return bool
     */
    private function saveUrl()
    {
        if (isset($this->bigUrl) && isset($this->littleUrl)) {
            $bigUrl = $this->mysqli->real_escape_string($this->bigUrl);
            $littleUrl = $this->mysqli->real_escape_string($this->littleUrl);

            $sql  = 'INSERT INTO `'.$this->tableName.'` (
                    `big_url`,
                    `small_url`,
                    `ip_address`
                    )
                    VALUES
                        ("'.$bigUrl.'", "'.$littleUrl.'", "'.$_SERVER['REMOTE_ADDR'].'")';

            if ($this->mysqli->query($sql)) {
                return true;
            }

            $this->exceptionMsg = 'Failed to insert into '.$this->tableName;
            return false;
        }

        $this->exceptionMsg = 'Both big URL and little URL need to be set';
        return false;
    }

    /**
     * Method to save the URL data.
     *
     * @return bool
     */
    public function checkUrlExists()
    {
        if (isset($this->littleUrl)) {

            $sql  = 'SELECT
                        COUNT(*) AS "count",
                        big_url,
	                    small_url
                    FROM
                        `'.$this->tableName.'` hul
                    WHERE
                        hul.small_url = "'.$this->littleUrl.'"';

            $result = $this->mysqli->query($sql)->fetch_object();

            if ($result->count > 0) {
                $this->littleUrl = $result->small_url;
                $this->bigUrl = $result->big_url;
                return true;
            }

            return false;
        }

        $this->exceptionMsg = 'Both big URL and little URL need to be set';
        return false;
    }
} 