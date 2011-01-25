<?php

return new date_handler;

class date_handler {
	var $title = "Date";
	var $icon_class = 'calendar';
	var $has_children = false;
	var $is_child = true;
	var $is_convertable = true;
	var $valid_parents = 'page_handler';

	function start(&$handlers = false) {
		$this->handlers = &$handlers;
	}

	function format($data, $format = 'text') {
		switch ($format) {
			case 'list':
			case 'text':	return $this->get($data['value']);
			case 'html':	return '<div class="date">'.$this->get($data['value']).'</div>';
			case 'field':	$d = $data['value'] ? date('d',$data['value']) : date('d'); $m = $data['value'] ? date('m',$data['value']) : date('m'); $y = $data['value'] ? date('Y',$data['value']) : date('Y');
							$months = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
							$out = '<div class="date_handler"><select class="date_handler day" name="'.$data['name'].'[day]">'; for($x=1; $x<32; $x++) $out .= '<option value="'.$x.'"'.($x == $d ? ' selected="selected"' : '').'>'.$x.'</option>'; $out .= '</select>';
							$out .= '<select class="date_handler month" name="'.$data['name'].'[month]">'; for($x=1; $x<13; $x++) $out .= '<option value="'.$x.'"'.($x == $m ? ' selected="selected"' : '').'>'.$months[$x].'</option>'; $out .= '</select>';
							$out .= '<select class="date_handler year" name="'.$data['name'].'[year]">'; for($x=($y-10); $x<=($y+10); $x++) $out .= '<option value="'.$x.'"'.($x == $y ? ' selected="selected"' : '').'>'.$x.'</option>'; $out .= '</select></div>';
							return $out;
			default:		return $this->get($data['value'],$format);
		}
	}
	
	function get_default($content) {
		return time();
	}

	function set($value,$data = false) {
		return mktime(0,0,0,$value['month'],$value['day'],$value['year']);
	}

	function get($value,$format = false) {
		if (!$format) $format = DATE_FORMAT;
		if (is_numeric($value)) return date($format,$value);
		return $value;
	}

	function import($value) {
		return strtotime($value);
	}

	function export($value) {
		return $this->get($value);
	}
}