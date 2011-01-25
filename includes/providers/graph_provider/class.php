<?php

class graph_provider {
	var $title = "Graphs";
	var $icon_class = 'graphs';

	function start(&$providers = false) {
		$this->providers = &$providers;

		$this->vars = array('start_day' => date('d',strtotime('-3 months')),
						    'start_month' => date('m',strtotime('-3 months')),
						    'start_year' => date('Y',strtotime('-3 months')),
						    'end_day' => date('d',strtotime('1 days')),
						    'end_month' => date('m',strtotime('1 days')),
						    'end_year' => date('Y',strtotime('1 days')));

		$months = array(1=>array('id'=>1,'text'=>'January'), 2=>array('id'=>2,'text'=>'February'),
						3=>array('id'=>3,'text'=>'March'), 4=>array('id'=>4,'text'=>'April'),
						5=>array('id'=>5,'text'=>'May'), 6=>array('id'=>6,'text'=>'June'),
						7=>array('id'=>7,'text'=>'July'), 8=>array('id'=>8,'text'=>'August'),
						9=>array('id'=>9,'text'=>'September'), 10=>array('id'=>10,'text'=>'October'),
						11=>array('id'=>11,'text'=>'November'), 12=>array('id'=>12,'text'=>'December'));
		$days = $years = array(); $y=date('Y')-9;
		for ($x=0; $x<=10; $x++) $years[$x+$y] = array('id'=>($x+$y),'text'=>($x+$y));
		for ($x=1; $x<=31; $x++) $days[$x] = array('id'=>$x,'text'=>($x.(substr($x,-1)==1 && $x != 11 ? 'st' : (substr($x,-1)==2 && $x != 12 ? 'nd' : (substr($x,-1)==3 && $x != 13 ? 'rd' : 'th')))));

		return true;
	}
}

class graph_day {
	var $code = 'day';
	var $graph_type = 'line';
	var $title = 'Days';

	function show(&$class) {
		$class->scales['day'] = array('id'=>'day','text'=>'Day');
	}

	function select_records(&$class) {
		$date = mktime(0,0,0,$class->vars['start_month'],$class->vars['start_day'],$class->vars['start_year'])-(60*60*24);
		$end = mktime(0,0,0,$class->vars['end_month'],$class->vars['end_day'],$class->vars['end_year'])+(60*60*24);
		while ($date <= $end) {
			$class->data[date('m/d',$date)] = '0';
			$date += 60*60*24;
		}
		$class->select[] = 'date_format(o.date_added,\'%m/%d\') as label';
		$class->select[] = 'date_format(o.date_added, \'%Y.%m.%d\') as id';
		$class->select[] = 'date_format(o.date_added,\'%M %D, %Y\') as title';
	}
}

class group_month {
	var $code = 'month';
	var $graph_type = 'line';
	var $title = 'Months';

	function show(&$class) {
		$class->scales['month'] = array('id'=>'month','text'=>'Month');
	}

	function select_records(&$class) {
		$date = mktime(0,0,0,$class->vars['start_month'],$class->vars['start_day'],$class->vars['start_year'])-(60*60*24*31);
		$end = mktime(0,0,0,$class->vars['end_month'],$class->vars['end_day'],$class->vars['end_year'])+(60*60*24*31);
		while ($date <= $end) {
			$class->data[strftime('%m/%y',$date)] = '0';
			$date += 60*60*24*31;
		}
		$class->select[] = 'date_format(o.date_added,\'%m/%y\') as label';
		$class->select[] = 'date_format(o.date_added, \'%Y.%m\') as id';
		$class->select[] = 'date_format(o.date_added,\'%M %Y\') as title';
	}
}

class group_year {
	var $code = 'year';
	var $graph_type = 'line';
	var $title = 'Years';

	function show(&$class) {
		$class->scales['year'] = array('id'=>'year','text'=>'Year');
	}

	function select_records(&$class) {
		$date = mktime(0,0,0,$class->vars['start_month'],$class->vars['start_day'],$class->vars['start_year'])-(60*60*24*365);
		$end = mktime(0,0,0,$class->vars['end_month'],$class->vars['end_day'],$class->vars['end_year'])+(60*60*24*365);
		while ($date <= $end) {
			$class->data[strftime('%Y',$date)] = '0';
			$date += 60*60*24*365;
		}
		$class->select[] = 'date_format(o.date_added,\'%Y\') as label';
		$class->select[] = 'date_format(o.date_added, \'%Y\') as id';
		$class->select[] = 'date_format(o.date_added,\'%Y\') as title';
	}
}

class group_state {
	var $code = 'state';
	var $graph_type = 'pie';
	var $title = 'States';

	function show(&$class) {
		$class->headers = array('id'=>'zone','text'=>'State');
	}

	function select_records(&$class) {
		$class->select[] = 'state as label';
		$class->select[] = 'state_id as id';
		$class->select[] = "state as title";
	}
}