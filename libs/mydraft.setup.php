<?php

require(MYDRAFT_DIR . 'libs/mydraft.lib.php');
require(SMARTY_DIR . 'Smarty.class.php');

// smarty Konfiguration
class MYDRAFT_Smarty extends Smarty {
    function __construct() {
      parent::__construct();
	
      $this->setTemplateDir(MYDRAFT_DIR . 'templates'.$_SESSION['template_folder']);
      $this->setCompileDir(MYDRAFT_DIR . 'templates_c');
      $this->setConfigDir(MYDRAFT_DIR . 'configs');
      $this->setCacheDir(MYDRAFT_DIR . 'cache');
	  $this->setPluginsDir(MYDRAFT_DIR .'plugins');
	  $this->setCacheLifetime(360);
	  $this->caching = 0;
    }
 

}
      
?>