<?php
/**
 * HtmlSpecialChars filter
 * 
 * @author pekkis
 * @package  Emerald_Common_Filter
 */
class Emerald_Common_Filter_HtmlSpecialChars implements Zend_Filter_Interface
{
    /**
     * Character set. Corresponds to third htmlspecialchars() argument
     *
     * @var string
     */
    protected $_charset = 'UTF-8';

    /**
     * Double encode. Corresponds to second htmlspecialchars() argument
     *
     * @var boolean
     */
    protected $_doubleEncode = true;

    /**
     * Quote style. Corresponds to second htmlspecialchars() argument
     *
     * @var integer
     */
    protected $_quoteStyle = ENT_COMPAT;

    /**
     * Constructor
     *
     * @param  array $options
     * @return void
     */
    public function __construct(array $options = array())
    {
        if (!empty($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Set options
     *
     * @param  array                           $options
     * @return Emerald_Common_Filter_HtmlSpecialChars
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }

    /**
     * Returns the charset option
     *
     * @return string
     */
    public function getCharset()
    {
        return $this->_charset;
    }

    /**
     * Sets the charset option
     *
     * @param  string                          $charset
     * @return Emerald_Common_Filter_HtmlSpecialChars
     */
    public function setCharset($charset)
    {
        $this->_charset = $charset;

        return $this;
    }

    /**
     * Returns the doubleEncode option
     *
     * @return boolean
     */
    public function getDoubleEncode()
    {
        return $this->_doubleEncode;
    }

    /**
     * Sets the doubleEncode option
     *
     * @param  boolean                         $doubleEncode
     * @return Emerald_Common_Filter_HtmlSpecialChars
     */
    public function setDoubleEncode($doubleEncode)
    {
        $this->_doubleEncode = $doubleEncode;

        return $this;
    }

    /**
     * Returns the quoteStyle option
     *
     * @return integer
     */
    public function getQuoteStyle()
    {
        return $this->_quoteStyle;
    }

    /**
     * Sets the quoteStyle option
     *
     * @param  integer                         $quoteStyle
     * @return Emerald_Common_Filter_HtmlSpecialChars
     */
    public function setQuoteStyle($quoteStyle)
    {
        $this->_quoteStyle = $quoteStyle;

        return $this;
    }

    /**
     * Defined by Zend_Filter_Interface
     *
     * Convert special characters to HTML entities
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        return htmlspecialchars((string) $value, $this->_quoteStyle, $this->_charset, $this->_doubleEncode);
    }
}
