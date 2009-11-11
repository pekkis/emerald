<?php
/**
 * Abstract class for extension
 */
require_once 'Zend/View/Helper/FormElement.php';


/**
 * Zend button was poo.
 *
 */
class Emerald_View_Helper_FormButton extends Zend_Emerald_View_Helper_FormElement
{
    public function formButton($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, id, value, attribs, options, listsep, disable

        // build the element
        if ($disable) {
            // disabled. no hidden value because it can't be clicked.
            $xhtml = '[' . $this->view->escape($value) . ']';
        } else {

            // enabled
            $xhtml = '<button'
                   . ' name="' . $this->view->escape($name) . '"'
                   . ' id="' . $this->view->escape($id) . '"';
            // add attributes and close
            $xhtml .= $this->_htmlAttribs($attribs) . '>';
        }

        if (! empty($value)) {
        	$xhtml .= $this->view->escape($value);
        }
        $xhtml .= '</button>';
        
        
        return $xhtml;
    }
}