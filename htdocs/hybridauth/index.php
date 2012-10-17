<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html 
*/

// ------------------------------------------------------------------------
//	HybridAuth End Point
// ------------------------------------------------------------------------
$_SERVER['DEVELOP'] = strpos($_SERVER['HTTP_HOST'], 'dev.') !== false;

$incPath = get_include_path();
$rootPath = realpath(dirname(__FILE__).'/../../');
set_include_path($rootPath.':'.$incPath);

require_once("Hybrid/Auth.php");
require_once("Hybrid/Endpoint.php");

Hybrid_Endpoint::process();
