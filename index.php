<?php

$providers = require('includes/includes.php');

$providers->start('/');

$providers->template->display('index.tpl');
