<?php

if (@$_REQUEST['account']['type'] == -1 || @$_REQUEST['terms'] != 1 || strtolower($_REQUEST['verify']) != 'httfh') {
	die('Please select your role, agree to terms and enter the verification code correctly.');
}