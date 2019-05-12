<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['default_controller'] = "home";
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$prefix = 'application/controllers';
#Security > Login
include $prefix.'/Security/Admin/Config/route.php';
#Dashboard
include $prefix.'/Admin/Dashboard/Config/route.php';
#Todo
include $prefix.'/Admin/Todo/Config/route.php';
