<?php

return new address_handler;

class address_handler {
	var $title = "Address";
	var $icon_class = 'user_card';
	var $has_children = false;
	var $is_child = true;
	var $is_convertable = true;
	var $valid_parents = 'page_handler';
	var $defaults = array('company'=>'Company Name','first_name'=>'First Name','last_name'=>'Last Name','street'=>'Street Address','city'=>'City','state'=>'ST','zip'=>'Zip');
	var $formats = array('text'=>"{company}\r\n{first_name} {last_name}\r\n{street}\r\n{city}, {state} {zip}",
						 'list'=>'{company}, {first_name} {last_name}, {street}, {city}, {state} {zip}',
						 'html'=>'{company}<br />{first_name} {last_name}<br />{street}<br />{city}, {state} {zip}',
						 'field'=>'<div class="address_handler"><input type="text" class="company" title="Company Name" size="20" name="{name}[company]" value="{company}" /><br /><input type="text" class="first_name" title="Contact First Name" size="10" name="{name}[first_name]" value="{first_name}" /><input type="text" class="last_name" title="Contact Last Name" size="10" name="{name}[last_name]" value="{last_name}" /><br /><input type="text" class="street" title="Street Address" size="20" name="{name}[street]" value="{street}" /><br /><input type="text" class="city" title="City" size="10" name="{name}[city]" value="{city}" /><input type="text" class="state" title="State (Abbr.)" size="3" name="{name}[state]" value="{state}" /><input type="text" class="zip" title="Zip Code" size="7" name="{name}[zip]" value="{zip}" /></div>');

	function start(&$handlers = false) {
		$this->handlers = &$handlers;
	}

	function format($data, $format = 'text') {;
		if (!$values = $this->get($data['value'])) {
			$values = $this->defaults;
		}
		foreach ($values as $k=>$v) $data[$k] = $v;
		return str_replace(preg_replace('/(.+)/','{\1}',array_keys($data)),
						   array_values($data),
						   @$this->formats[$this->formats[$format] ? $format : 'text']);
	}
	
	function get_default($content) {
		return serialize($this->defaults);
	}

	function set($value,$data = false) {
		return serialize($value);
	}

	function get($value) {
		return unserialize($value);
	}

	function import($value) {
		$values = explode(', ',$value);
		$keys = array_keys($this->defaults);
		$output = array();
		foreach ($keys as $k=>$v) $output[$v] = $values[$k];
		return serialize($output);
	}

	function export($value) {
		return implode(', ',$value);
	}
}