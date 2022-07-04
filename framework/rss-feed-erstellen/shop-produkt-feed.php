<?php 
class rss_feed { 
 

    /* Variables: */ 
    protected $mime; 
    protected $charset; 
    protected $title; 
    protected $link; 
    protected $description; 
    protected $language; 
    protected $copyright; 
    protected $managingEditor; 
    protected $webMaster; 
    protected $pubDate; 
    protected $lastBuildDate; 
    protected $category; 
    protected $generator; 
    protected $docs; 
    protected $ttl; 
    protected $textInput; 
    protected $domain; 
    protected $starttime; 
    protected $itemid=0; 
    protected $caching=FALSE; 
    protected $cachefile; 
    protected $cachetime; 
    protected $items=array(); 
    /* Constructor, sends header an sets required variables: */ 
    public function  __construct($title, $link, $description, $charset="UTF-8", $starttime=null) { 
        if (stristr($_SERVER["HTTP_ACCEPT"],"application/rss+xml")) 
            $this->mime="application/rss+xml"; 
        elseif (stristr($_SERVER["HTTP_ACCEPT"],"application/xml")) 
            $this->mime="application/xml"; 
        else 
            $this->mime="text/xml"; 
        $this->charset=$charset; 
        header("content-type: ".$this->mime."; charset=".$this->charset); 
        $this->title=$title; 
        $this->link=$link; 
        $this->description=$description; 
        if($starttime === null and $starttime !== false) 
            $this->starttime=microtime(true); 
        elseif($starttime !== FALSE) 
            $this->starttime=floatval($starttime); 
    } 
    /* Activates ($activated=true) or deactivates ($activated=false) the caching, 
     * cached data will be ouputted to the file $cachefile, 
     * the file will de refreshed after $cachetime seconds: */ 
    public function caching($cachefile, $activate=true, $cachetime=900) { 
        $this->caching=TRUE; 
        $this->cachefile=$cachefile; 
        $this->cachetime=$cachetime; 
    } 
    /* Sets the language of the feed: */ 
    public function set_language($language) { 
        $this->language=$language; 
    } 
    /* Sets the copyright of the feed: */ 
    public function set_copyright($copyright) { 
        $this->copyright=$copyright; 
    } 
    /* Sets the managing editor of the feed: */ 
    public function set_managingEditor($email, $name) { 
        $this->managingEditor=array("email" => $email, "name" => $name); 
    } 
    /* Sets the webmaster: */ 
    public function set_webMaster($email, $name) { 
        $this->webMaster=array("email" => $email, "name" => $name); 
    } 
    /* Sets the publication date of the feed: */ 
    public function set_pubDate($timestamp) { 
        $this->pubDate=date("r", $timestamp); 
    } 
    /* Sets the last build date of the feed: */ 
    public function set_lastBuildDate($timestamp) { 
        $this->lastBuildDate=date("r", $timestamp); 
    } 
    /* Sets the category of the feed: */ 
    public function set_category($category) { 
        $this->category=(array)explode(",", $category); 
        ; 
    } 
    /* Sets the documentation of the feed version: */ 
    public function set_docs($docs) { 
        $this->docs=$docs; 
    } 
    /* Sets the time to life of the feed: */ 
    public function set_ttl($minutes) { 
        $this->ttl=$minutes; 
    } 
    /* Sets the image (logo) of the feed: */ 
    public function set_image($url) { 
        $this->image=$url; 
    } 
    /* Adds a text input box the feed: */ 
    public function set_textInput($title, $description, $name, $link) { 
        $this->textInput=array("title" => $title, "description" => $description, "name" => $name, "link" => $link); 
    } 
    /* Adds an item to the feed, returns the item ID (required by the following functions): */ 
    public function add_item($title, $description, $link=NULL, $html=FALSE) { 
        $this->itemid++; 
        if($html !== FALSE) 
            $description=htmlentities($description, ENT_QUOTES, $this->charset, TRUE); 
        $this->items[$this->itemid]=array("title" => $title, "description" => $description, "name" => $name, "link" => $link); 
        return $this->itemid; 
    } 
    /* Sets the author of the feed item: */ 
    public function item_set_author($email, $name, $item=NULL) { 
        if($item !== NULL and intval($item) > 0) 
            $item=intval($item); 
        else 
            $item=$this->itemid; 
        $this->items[$item]["author"]=array("email" => $email, "name" => $name); 
    } 
    /* Sets the category of the feed item: */ 
    public function item_set_category($category, $item=NULL) { 
        if($item !== NULL and intval($item) > 0) 
            $item=intval($item); 
        else 
            $item=$this->itemid; 
        $this->items[$item]["category"]=(array)explode(",", $category); 
    } 
    /* Sets the comment site of the feed item: */ 
    public function item_set_comments($url, $item=NULL) { 
        if($item !== NULL and intval($item) > 0) 
            $item=intval($item); 
        else 
            $item=$this->itemid; 
        $this->items[$item]["comments"]=$url; 
    } 
    /* Adds an attachment to the feed item: */ 
    public function item_add_enclosure($url, $length, $type, $item=NULL) { 
        if($item !== NULL and intval($item) > 0) 
            $item=intval($item); 
        else 
            $item=$this->itemid; 
        $this->items[$item]["enclosure"][]=array("url" => $url, "length" => $length, "type" => $type); 
    } 
    /* Sets an unique ID to the feed item: */ 
    public function item_set_guid($guid, $isPermaLink="true", $item=NULL) { 
        if($item !== NULL and intval($item) > 0) 
            $item=intval($item); 
        else 
            $item=$this->itemid; 
        if(strval($isPermaLink) != "true") 
            $isPermaLink = "false"; 
        $this->items[$item]["guid"]=array("guid" => $guid, "isPermaLink"  => strval($isPermaLink)); 
    } 
    /* Sets the publication date of the feed item: */ 
    public function item_set_pubDate($timestamp, $item=NULL) { 
        if($item !== NULL and intval($item) > 0) 
            $item=intval($item); 
        else 
            $item=$this->itemid; 
        $this->items[$item]["pubDate"]=date("r", $timestamp); 
    } 
    /* Sets the source of the feed item: */ 
    public function item_set_source($url, $name, $item=NULL) { 
        if($item !== NULL and intval($item) > 0) 
            $item=intval($item); 
        else 
            $item=$this->itemid; 
        $this->items[$item]["source"]=array("url" => $url, "name" => $name); 
    } 
    /* Outputs all item of this feed */ 
    private function item_out() { 
        $out=""; 
        foreach ($this->items AS $item) { 
            $out .= "<item> 
<title>".$item["title"]."</title> 
<description>".$item["description"]."</description>\r\n"; 
            if(isset ($item["link"])) 
                $out .= "<link>".$item["link"]."</link>\r\n"; 
            if(isset ($item["author"])) 
                $out .= "<author>".$item["author"]["email"]." (".$item["author"]["name"].")</author>\r\n"; 
            if(isset ($item["category"])) 
                $out .= "<category>".implode("</category>\r\n<category>", $item["category"])."</category>\r\n"; 
            if(isset ($item["comments"])) 
                $out .= "<comments>".$item["comments"]."</comments>\r\n"; 
            if(isset ($item["guid"])) 
                $out .= "<guid isPermaLink=\"".$item["guid"]["isPermaLink"]."\">".$item["guid"]["guid"]."</guid>\r\n"; 
            if(isset ($item["pubDate"])) 
                $out .= "<pubDate>".$item["pubDate"]."</pubDate>\r\n"; 
            if(isset ($item["source"])) 
                $out .= "<source url=\"".$item["source"]["url"]."\">".$item["source"]["name"]."</source>\r\n"; 
            if(isset ($item["enclosure"])) { 
                foreach ($item["enclosure"] AS $enclosure) 
                    $out .= "<enclosure url=\"".$enclosure["url"]."\" length=\"".$enclosure["length"]."\" type=\"".$enclosure["type"]."\" />\r\n"; 
            } 
            $out .= "</item>\r\n"; 
        } 
        return $out; 
    } 
    /* outputs the feed: */ 
    public function output() { 
        if($this->caching === TRUE and filemtime($this->cachefile) + $this->cachetime > time()) { 
            $handle=fopen($this->cachefile, "wb"); 
            $out=fread($handle, filesize($this->cachefile)); 
            fclose($handle); 
            if($out !== FALSE) { 
                echo $out; 
                return TRUE; 
            } 
        } 
        $out="<?xml version=\"1.0\" ?> 
<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\"> 
<channel> 
<title>".$this->title."</title> 
<link>".$this->link."</link> 
<description>".$this->description."</description>\r\n"; 
        if(strlen($this->language) > 0) 
            $out .= "<language>".$this->language."</language>\r\n"; 
        if(strlen($this->copyright) > 0) 
            $out .= "<copyright>".$this->copyright."</copyright>\r\n"; 
        if(count($this->managingEditor) > 0) 
            $out .= "<managingEditor>".$this->managingEditor["email"]." (".$this->managingEditor["name"].")</managingEditor>\r\n"; 
        if(count($this->webMaster) > 0) 
            $out .= "<webMaster>".$this->webMaster["email"]." (".$this->webMaster["name"].")</webMaster>\r\n"; 
        if(strlen($this->pubDate) > 0) 
            $out .= "<pubDate>".$this->pubDate."</pubDate>\r\n"; 
        if(strlen($this->lastBuildDate) > 0) 
            $out .= "<lastBuildDate>".$this->lastBuildDate."</lastBuildDate>\r\n"; 
        if(count($this->category) > 0) 
            $out .= "<category>".implode("</category>\r\n<category>", $this->category)."</category>\r\n"; 
        if(strlen($this->docs) > 0) 
            $out .= "<docs>".$this->docs."</docs>\r\n"; 
        if(strlen($this->ttl) > 0) 
            $out .= "<ttl>".$this->ttl."</ttl>\r\n"; 
        if(strlen($this->image) > 0) 
            $out .= "<image> 
<url>".$this->image."</url> 
<title>".$this->title."</title> 
<link>".$this->link."</link> 
</image>\r\n"; 
        if(count($this->textInput) > 0) 
            $out .= "<textInput> 
<title>".$this->textInput["title"]."</title> 
<description>".$this->textInput["description"]."</description> 
<name>".$this->textInput["name"]."</name> 
<link>".$this->textInput["link"]."</link> 
</textInput>\r\n"; 
        $out .= "<atom:link href=\"http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]."\" rel=\"self\" type=\"application/rss+xml\" />\r\n"; 
        $out .= $this->item_out(); 
        $out .= "</channel> 
</rss>"; 
        if($starttime !== FALSE) 
            $out .= "\r\n<!-- Generated in ".number_format(round((microtime(TRUE)-$this->starttime), 2), 2, ",", ".")."ms -->"; 
        if($this->caching === TRUE and filemtime($this->cachefile) + $this->cachetime < time()) { 
            $handle=fopen($this->cachefile, "wb"); 
            fwrite($handle, $out); 
            fclose($handle); 
        } 
        echo $out; 
    } 
} 

?>
<?php 
@session_start();
	$_SESSION['page_id'] = $_GET['page_id'];
	$_SESSION['language'] = 'de';
	$_SESSION['domain_name'] = "http://".$_SERVER['HTTP_HOST'];
	// Sprachauswahl
	$_SESSION['domainLanguage'] = 'de';
$path = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once($path.'/include/inc_config-data.php');
include_once($path.'/include/inc_basic-functions.php');

// erstelle neuen feed 
#exit;
$rss = new rss_feed("Shopste RSS Feed", "http://shopste.com/rss-feed/rss.xml", "Marktplatz für Händler und Einsteiger. Ermöglicht das erstellen von Online Shops als Service. Einfach nur registrieren und loslegen."); 
// Query 
$query = "SELECT * FROM shop_item WHERE menge > 0 ORDER BY updated_at DESC LIMIT 0,50";
$resTextHTML =  mysql_query($query) or die(mysql_error());

while($strTEXTHTML = mysql_fetch_assoc($resTextHTML)) {
	$query = "SELECT * FROM menue JOIN domains ON menue.domain_id = domains.domain_id WHERE menue.id=".$strTEXTHTML['menue_id']." AND hasPortalRSS='Y'";
	$resDomain = mysql_query($query) or die(mysql_error());
	$strDomain = mysql_fetch_assoc($resDomain);
	if(!empty($strDomain['name'])) {
		$strURL = 'http://'.$strDomain['name'].'/'.getPathUrl('de',$strTEXTHTML['menue_id']); 
		#echo $strURL;
		#exit;
		$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$strTEXTHTML['shop_item_id']."'";
		$resPictures = mysql_query($query) or die(mysql_error());		
		$strPic = mysql_fetch_assoc($resPictures);
		
		$strTEXTHTML['beschreibung'] = '<img src="http://'.$_SERVER['SERVER_NAME'].''.str_replace('/orginal/','/kategorie/',$strPic['picture_url']).'"/>'.$strTEXTHTML['beschreibung'];
		$rss->add_item(htmlspecialchars($strTEXTHTML['name_de']), htmlspecialchars($strTEXTHTML['beschreibung']),$strURL);  
		#$d1=new DateTime($strTEXTHTML['lastchange']);
		#$rss->item_set_pubDate($d1->getTimestamp());
		
		$date=$strTEXTHTML['updated_at']; //Date example
		
		list($day, $month, $year, $hour, $minute) = split('[- :]', $date); 

		//The variables should be arranged according to your date format and so the separators
		#print_r($strTEXTHTML);
		#exit;
		#echo $hour.' '.$minute.' - '.$month.' '.$day.' '.$year;
		#exit;
		$timestamp = mktime($hour, $minute, 0, $month, $year, $day);
		#echo $timestamp;
		#exit;
		$rss->item_set_pubDate($timestamp);
	}

 
	#$rss->set_image($strPic['picture_url']);
}

if(CORE_PIWIK_ACTIVE == 'YES') {
    require_once "../piwik//vendor/matomo/matomo-php-tracker/PiwikTracker.php";
	PiwikTracker::$URL = 'https://freie-welt.eu/framework/piwik/';
	$t = new PiwikTracker( $idSite = 1 );
	$t->setTokenAuth(CORE_PIWIK_API_KEY);
	$t->doTrackPageView('Shopste neuste Produkte');
	$t->setIp($SERVER['REMOTEADDR']);
	if(isset($SERVER['HTTPREFERER']))
	$t->setUrl($SERVER['HTTPREFERER']);	
}

// Füge neuen Eintrag hinzu 
// Gebe Feed aus 
$rss->output(); 
mysqli_close(DBi::$conn); 
?>