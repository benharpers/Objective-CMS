<?php

$providers = require('../includes/includes.php');

$providers->start('/admin/');

$providers->template->display('index.tpl');
