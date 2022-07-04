<?php 
/**
 * Mydraft Anwendung
 *
 */
class Mydraft {
  // smarty template Objekt
  var $tpl = null;
  // Fehler 
  var $error = null;

  /**
  * class Konstruktor
  */
  function __construct() {
	  
    // instantiate the template object
    $this->tpl = new MYDRAFT_Smarty;

  }

// Normale Webseite anzeigen
function displayCMSPage($seite,$cache_id,$bNoCache) {
    
    # Fehler ans Template hängen
    $this->tpl->assign('error', $this->error);

    # KEIN CACHING 
    # z.B. Warenkorb
    if($bNoCache == true) {
      #$this->setClearCacheId($seite,$cache_id);
      //bug: $this->caching = false;
       $this->tpl->caching = 0;
    }

    ##################################
    # >> Caching Typen
    ##################################
    if ($seite == 'normale_seite') {
      $this->cache_lifetime = 86400; // 1 Tag
    } else if ($seite == 'rss_kategorie') {
      $this->cache_lifetime = 900; // 15 Minuten
    } else if ($seite == 'rss_content') {
      $this->cache_lifetime = 2592000; // 30 Tage
    } elseif ($seite == 'suchen') {
      $this->cache_lifetime = 900; // 15 Minuten
    }

    $this->tpl->display($seite,$cache_id);
}

function setClearCacheId($seite,$cache_id) {
    $this->clear_cache($seite,$cache_id);
    #print_r($this)."ABV";
    return true;
}  	
function setTemplate_clean_cache() {

  // nur Cache von 'rss_kategorie.tpl' löschen
  $this->clear_cache('rss_kategorie.tpl');

  #print_r($this)."ABV";
  return true;

}
}
?>