<?php

// Can't do a thing without the configuration
define('ROOT', dirname(__FILE__));
require_once(ROOT.'/config/config.inc.php');

// Required for IE, otherwise Content-disposition is ignored
if(ini_get('zlib.output_compression')){
  ini_set('zlib.output_compression', 'Off');
}

// Force the download
header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: private',false);
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename="'.$_POST['name'].'.wrl";' );
header('Content-Transfer-Encoding: binary');
//readfile($fileName);
//exit();

require_once(ROOT.'/templates/file_vrml.php');

exit();
?>