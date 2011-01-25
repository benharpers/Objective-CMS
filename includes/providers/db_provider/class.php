<?php

class db_provider {
	var $title = "Database";
	var $icon_class = 'database';
	var $db = false;
	var $prefix = false;
	var $qid = 0;
	var $queries = array();
	var $queries_raw = array();
	var $insert_ids = array();
	
	function start(&$providers = false) {
		$this->providers = &$providers;
		if (@$this->providers->setup->mysql['prefix'] && $this->providers->setup->mysql['prefix'] != "" && $this->providers->setup->mysql['prefix'] != "none")
			$this->prefix = $this->providers->setup->mysql['prefix'];
		if ($this->db = mysql_connect($this->providers->setup->mysql['host'], $this->providers->setup->mysql['user'], $this->providers->setup->mysql['password']))
			return mysql_select_db($this->providers->setup->mysql['db'], $this->db);
		return false;
	}

	function query($query_raw) {
		$query = @mysql_query($query_raw, $this->db);
		if (mysql_errno($this->db)) {
			trigger_error(mysql_error($this->db)." in query &quot;$query_raw&quot;");
		} elseif ($query) {
			$this->qid++;
			$this->queries[$this->qid] = $query;
			$this->queries_raw[$this->qid] = $query_raw;
			if (substr(strtolower($query_raw),0,6) == 'insert') {
				$this->insert_ids[$this->qid] = @mysql_insert_id();
			}
			return $this->qid;
		}
		return false;
	}
	
	function get_query($qid) {
		if (@$this->queries[$qid]) return $this->queries[$qid];
		return false;
	}

	function fetch($qid) {
		if (@$this->queries[$qid]) return db_unescape(mysql_fetch_assoc($this->queries[$qid]));
		return false;
	}
	
	function seek($qid,$num = 0) {
		if (@$this->queries[$qid]) return @mysql_data_seek($this->queries[$qid],$num);
		return false;
	}

	function num_rows($qid) {
		if (@$this->queries[$qid]) return mysql_num_rows($this->queries[$qid]);
		return false;
	}

	function get_insert_id($qid) {
		if (@$this->insert_ids[$qid]) return $this->insert_ids[$qid];
		return false;
	}

	// $db->select(array('table1 t1','table2 t2'=>array('t2.field1'=>'t1.field1','t2.field2'=>'t1.field2')),array('t1.field1','t2.field2','t2.field3'),array('t1.field3'=>'test','t1.field1'=>'blah'));
	function select($table, $what = false, $where = false, $order = false, $group = false, $limit = false, $offset = false) {
		return 'select '.($what ? db_array_to_string($what) : '*').' from '.(is_array($table) ? db_array_join($table,$this->prefix) : $this->prefix.$table).(($where && $w = db_array_to_params($where)) ? " where $w" : '').($group ? ' group by '.db_array_to_string($group) : '').($order ? ' order by '.db_array_to_string($order) : '').($limit ? " limit $limit" : '').($offset ? " offset $offset" : '');
	}

	// $db->insert('table',array('field1'=>'test','field2'=>66));
	function insert($table, $values = false) {
		return $this->action_into('insert', $table, $values);
	}
	
	function replace($table, $values = false) {
		return $this->action_into('replace', $table, $values);
	}
	
	function action_into($action, $table, $values = false) {
		return "$action into ".$this->prefix."$table (".db_array_to_string(array_keys($values)).') values ('.db_array_to_list($values).')';
	}

	// $db->update('table',array('field1'=>66,'field2'=>'test'),array('field1'=>'test','field2'=>66));
	function update($table, $set, $where = false) {
		return "update ".$this->prefix."$table set ".db_array_to_params($set, ',').(($where && $w = db_array_to_params($where)) ? " where $w" : '');
	}

	// $db->delete('table',array('field1'=>'test','field2'=>66));
	function delete($table, $where = false) {
		return "delete from ".$this->prefix."$table".(($where && $w = db_array_to_params($where)) ? " where $w" : '');
	}
}

function db_array_to_list($array, $delimiter = ', ') {
	if ($array) {
		$return = array();
		foreach ((array) $array as $value) $return[] = db_quote_var($value);
		return implode($delimiter, $return);
	}
	return false;
}

function db_array_to_params($array, $delimiter = ' and ') {
	if (@is_array($array)) {
		$return = array();
		foreach ($array as $key=>$value) $return[] = is_numeric($key) ? $value : ("`$key` = ".db_quote_var($value));
		return implode($delimiter, $return);
	}
	return false;
}

function db_quote_var($var) {
	switch (gettype($var)) {
		case 'NULL':
		case 'boolean':		return $var ? 1 : '0';
		case 'double':
		case 'integer':		return $var;
		case 'object':
		case 'array':		return "'".db_escape(serialize($var))."'";
	}
	return "'".db_escape($var)."'";
}

function db_escape($var) {
	if (is_array($var)) foreach ($var as $k=>$v) $var[$k] = db_escape($v);
	else return str_replace(array("'",'\\\\','"'),array("%%APOS%%",'%%BS%%',"%%QUOT%%"),stripslashes($var));
	return $var;
}

function db_unescape($var) {
	if (is_array($var)) foreach ($var as $k=>$v) $var[$k] = db_unescape($v);
	else return str_replace(array("%%APOS%%",'%%BS%%',"%%QUOT%%"),array("'",'\\\\','"'),stripslashes($var));
	return $var;
}

function db_array_to_string($array, $delimiter = ', ') {
	return implode($delimiter, (array) $array);
}

function db_array_join($tables, $prefix = '') {
	$return = array();
	foreach ($tables as $table=>$relations) {
		if (is_array($relations)) {
			$relations_array = array();
			foreach ($relations as $field=>$relation) $relations_array[] = "$field = $relation";
			$return[] = "$prefix$table on ".implode(' and ',$relations_array);
		} else $return[] = "$prefix$relations";
	}
	return implode(' join ',$return);
}