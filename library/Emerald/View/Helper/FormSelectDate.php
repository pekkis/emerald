<?php
class Emerald_View_Helper_FormSelectDate extends Zend_View_Helper_FormElement 
{
	
	
	public function formSelectDate($name, Zend_Date $value = null, Zend_Date $min = null, Zend_Date $max = null, $attribs = null)
	{
		if(!$value)
			$value = new Zend_Date();
		
		if(!$min)
			$min = new Zend_Date('1999-01-01', Zend_Date::ISO_8601);

		if(!$max)
			$max = new Zend_Date('2030-01-01', Zend_Date::ISO_8601);
			
			
		$info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, id, value, attribs, options, listsep, disable
       
        
        
        $days = range(1, 31, 1);
       	$xhtml = '<select'
                   . ' name="' . $this->view->escape($name) . '[day]"'
                   . ' id="' . $this->view->escape($id) . '"';
            // add attributes and close
            $xhtml .= $this->_htmlAttribs($attribs) . '>';
			foreach($days as $day) {
				$day = (strlen($day) > 1) ? $day : '0' . $day;
				$xhtml .= "<option value=\"{$day}\"";
				if($value->toValue(Zend_Date::DAY) == $day) $xhtml .= ' selected="selected" ';				
				$xhtml .= ">{$day}</option>";
			}
        $xhtml .= '</select>.';
        
        $months = range(1, 12, 1);
       	$xhtml .= '<select'
                   . ' name="' . $this->view->escape($name) . '[month]"'
                   . ' id="' . $this->view->escape($id) . '"';
            // add attributes and close
            $xhtml .= $this->_htmlAttribs($attribs) . '>';
			foreach($months as $month) {
				$month = (strlen($month) > 1) ? $month : '0' . $month;			
				$xhtml .= "<option value=\"{$month}\"";
				if($value->toValue(Zend_Date::MONTH) == $month) $xhtml .= ' selected="selected" ';				
				$xhtml .= ">{$month}</option>";
			}
        $xhtml .= '</select>.';
        
        $years = range($min->toValue(Zend_Date::YEAR), $max->toValue(Zend_Date::YEAR), 1);
       	$xhtml .= '<select'
                   . ' name="' . $this->view->escape($name) . '[year]"'
                   . ' id="' . $this->view->escape($id) . '"';
            // add attributes and close
            $xhtml .= $this->_htmlAttribs($attribs) . '>';
			foreach($years as $year) {
				$xhtml .= "<option value=\"{$year}\"";
				if($value->toValue(Zend_Date::YEAR) == $year) $xhtml .= ' selected="selected" ';				
				$xhtml .= ">{$year}</option>";
			}
        $xhtml .= '</select>&nbsp;';

        
        $hours = range(0, 23, 1);
       	$xhtml .= '<select'
                   . ' name="' . $this->view->escape($name) . '[hour]"'
                   . ' id="' . $this->view->escape($id) . '"';
            // add attributes and close
            $xhtml .= $this->_htmlAttribs($attribs) . '>';
			foreach($hours as $hour) {
				$hour = (strlen($hour) > 1) ? $hour : '0' . $hour;				
				$xhtml .= "<option value=\"{$hour}\"";
				if($value->toValue(Zend_Date::HOUR) == $hour) $xhtml .= ' selected="selected" ';				
				$xhtml .= ">{$hour}</option>";
			}
        $xhtml .= '</select>:';
        
        
        $minutes = range(0, 59, 1);
       	$xhtml .= '<select'
                   . ' name="' . $this->view->escape($name) . '[minute]"'
                   . ' id="' . $this->view->escape($id) . '"';
            // add attributes and close
            $xhtml .= $this->_htmlAttribs($attribs) . '>';
			foreach($minutes as $minute) {
				$minute = (strlen($minute) > 1) ? $minute : '0' . $minute;				
				$xhtml .= "<option value=\"{$minute}\"";
				if($value->toValue(Zend_Date::MINUTE) == $minute) $xhtml .= ' selected="selected" ';				
				$xhtml .= ">{$minute}</option>";
			}
        $xhtml .= '</select>';
        
        
 
		return $xhtml;		
	}
	
	
	
	
}