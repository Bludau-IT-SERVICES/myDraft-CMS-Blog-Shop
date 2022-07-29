<?php 
session_start();
header("Content-Type: text/html; charset=UTF-8");

/*function encrypt_decrypt($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = SECRECT_KEY;
    $secret_iv = SECRECT_IV;
    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
*/
function output_value($input) {
    if(isset($input)) {
        return $input;
    } 
    return "";
}

$_POST['txtFirma'] = '';
$_POST['txtVorname'] = '';
$_POST['txtNachname'] = '';
$_POST['txtStrasse'] = '';
$_POST['txtPLZ'] = '';
$_POST['txtOrt'] = '';
$_POST['txtLand'] = '';
$_POST['txtTelefon'] = '';
$_POST['txtRegShopname'] = '';
$_POST['optTelefonRückruf'] = 'N';
$_POST['optTeamviewerSupport'] = 'N';
$strGewerbe = '';
$strCRC = '';

if(empty($_POST['iStep'])) {
    $_POST['iStep'] = 0;
}

$path = dirname(__FILE__);
include_once($path."/../libs/mysqli.php");

if (file_exists($path."/../include/installed.php")) {
    include_once($path."/../include/installed.php");
    DBi::$conn = new mysqli(HOST, USER, PASS, DB);
}

require_once($path."/../include/inc_basic-functions.php");

if(is_array($_POST)) {
    $_POST = mysql_real_escape_array($_POST);
}

if(is_array($_GET)) {
    $_GET  = mysql_real_escape_array($_GET);
}

if(is_array($_SESSION)) {
    $_SESSION  = mysql_real_escape_array($_SESSION);
}

if(is_array($_COOKIE)) {
    $_SESSION  = mysql_real_escape_array($_COOKIE);
}

?>
<html>
    <head>
        <title>myDraft CMS Installer</title>
        <style>
            input:invalid {
                border: 2px dashed red;
            }

            input:valid {
                border: 2px solid black;
            }
        </style>
    </head>
<body>

<?php 
    $path = dirname(__FILE__);

    switch($_POST['iStep']) {
        case '1':

            try {
                DBi::$conn = new mysqli($_POST['txtmySQLServer'], $_POST['txtBenutzername'], $_POST['txtPasswort'], $_POST['txtDB']);

                $strData = '
                <?php
                    define("HOST","'.$_POST['txtmySQLServer'].'");
                    define("USER","'.$_POST['txtBenutzername'].'");
                    define("PASS","'.$_POST['txtPasswort'].'");
                    define("DB","'.$_POST['txtDB'].'");                    
                    define("SECRECT_KEY","'.SECRECT_KEY.'"); 
                    define("SECRECT_IV","'.SECRECT_IV.'"); 
                ?>
                ';
                file_put_contents($path."/../include/installed.php", $strData);
?>
    <h1>myDraft mySQL Datentabellen anlegen</h1>
    <form action="install.php" method="POST" id="frmDB_Connection">
        <input type="hidden" name="iStep" value="2">
        <input type="submit" value="Import Standard Schema" name="btnAbschicken_DBConnection"><br>
    </form>

<?php

            } catch (Exception $e) {
                echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
            }

            break;
        case '2':
            try {
                
                $templine = '';                
                $lines = file('./myDraft Datenbank Schema.sql');
                
                foreach ($lines as $line) {
                    if (substr($line, 0, 2) == '--' || $line == '')
                        continue;
                
                    $templine .= $line;                
                    if (substr(trim($line), -1, 1) == ';') {
                        DBi::$conn->query($templine) or print('Fehler beim Ausführen der Abfrage \'<strong>'.$templine.'\': '. mysqli_error(DBi::$conn).'<br /><br />');                        
                        $templine = '';
                    }

                }
                echo "Alle Tabellen erfolgreich importiert.";
?>

  <h1>myDraft mySQL Adminstrator anlegen</h1>
    <form action="install.php" method="POST" id="frmDB_Connection">
        <input type="hidden" name="iStep" value="3">

        <label for="txtBenutzername">Bitte myDraft Administrator Benutzernamen eingeben:<br/>
        <input type="text" id="txtBenutzername" name="txtBenutzername" value="<?php if(!empty($_POST['txtBenutzername'])) echo $_POST['txtBenutzername']; ?>" required="required"></label><br/>

        <label for="txtPasswort">Bitte myDraft Adminstrator Passwort eingeben:<br/>
        <input type="password" id="txtPasswort" name="txtPasswort" value="<?php if(!empty($_POST['txtPasswort'])) echo $_POST['txtPasswort']; ?>" required="required"></label><br/>

        <label for="txtEmail">Bitte Email Adresse eingeben:<br/>
        <input type="text" id="txtEmail" name="txtEmail" value="<?php if(!empty($_POST['txtEmail'])) echo $_POST['txtEmail']; ?>" required="required"></label><br/>

        <input type="submit" value="Admin Benutzer anlegen" name="btnAbschicken_DBConnection"><br>
    </form>

<?php 
            } catch (Exception $e) {
                echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
            }
            break;
        case '3':

            try {	

				$query = "INSERT INTO domains(name,startseite,email_freischaltung) VALUES('".$_SERVER['SERVER_NAME']."','1','Y')";
				DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$domain_id = mysqli_insert_id(DBi::$conn);

                $query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id) VALUES ('Startseite', 'Startseite', '0', 'sichtbar', 'col2-right-layout','".$domain_id."');";
                $resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
                $iPageID = mysqli_insert_id(DBi::$conn);
			
                $query = "UPDATE domains SET startseite='".$iPageID."'WHERE domain_id='".$domain_id."'";
                $resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

                $query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES ('".$iPageID."','0');";
                $resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

                $query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$iPageID.", 0, 'menue', 'Y');";
                $resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
                $iModulID = mysqli_insert_id(DBi::$conn);

                $query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'menue', 'col-right', '0');";	
                $resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

                $query = "INSERT INTO `modul_texthtml` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Startseite', ".$iPageID.", 0, 'Hier entsteht ein neuer Online Shop.','".date("Y-m-d H:i:s")."');";
                $resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
                $iModulID = mysqli_insert_id(DBi::$conn);

                $query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'texthtml', 'col-main', '0');";	
                $resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

                $hash = encrypt_decrypt('encrypt', $_POST['txtPasswort']);

                $query = "INSERT INTO benutzer(username,password,erstellt_am,domain_id,email,firma,vorname,nachname,strasse_hnr,plz,stadt,land,profile_id,telefon,email_crc,bISBlowfish,email_validate) VALUES('".output_value($_POST['txtBenutzername'])."','".$hash."','".date("Y-m-d H:i:s")."','".$domain_id."','".output_value($_POST['txtEmail'])."','".output_value($_POST['txtFirma'])."','".output_value($_POST['txtVorname'])."','".output_value($_POST['txtNachname'])."','".output_value($_POST['txtStrasse'])."','".output_value($_POST['txtPLZ'])."','".output_value($_POST['txtOrt'])."','".output_value($_POST['txtLand'])."','1','".output_value($_POST['txtTelefon'])."','".$strCRC."','Y','Y')";
				DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$iUserID = mysqli_insert_id(DBi::$conn);

				$query = "INSERT INTO shop_info(firma,vorname,nachname,strasse_hnr,plz,stadt,land,domain_id,shop_name,email_shop_main,created_at,telefon,gewerblich,telefon_rueckruf,teamviewer_support,shop_mitgliedsname) VALUES('".output_value($_POST['txtFirma'])."','".output_value($_POST['txtVorname'])."','".output_value($_POST['txtNachname'])."','".output_value($_POST['txtStrasse'])."','".output_value($_POST['txtPLZ'])."','".output_value($_POST['txtOrt'])."','".output_value($_POST['txtLand'])."','".$domain_id."','".output_value($_POST['txtRegShopname'])."','".output_value($_POST['txtEmail'])."','".date("Y-m-d H:i:s")."','".output_value($_POST['txtTelefon'])."','".$strGewerbe."','".output_value($_POST['optTelefonRückruf'])."','".output_value($_POST['optTeamviewerSupport'])."','".output_value($_POST['txtBenutzername'])."')";
				#echo $query;
				DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

            } catch(Exception $e) {
                echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
            }

?>
            <h1>myDraft fertig Installiert</h1>
            Viel Spaß mit myDraft wünscht Ihnen Bludau IT SERVICES.
<?php
            break;
        default:

?>
    <h1>myDraft Datenbankverbindung (mySQL)</h1>

    <form action="install.php" method="POST" id="frmDB_Connection">
        <label for="txtmySQLServer">Bitte mySQL Server IP eingeben:<br/>
        <input type="text" id="txtmySQLServer" name="txtmySQLServer" value="<?php if(!empty($_POST['txtmySQLServer'])) echo $_POST['txtmySQLServer']; ?>" required="required"></label><br/>

        <label for="txtBenutzername">Bitte mySQL Benutzernamen eingeben:<br/>
        <input type="text" id="txtBenutzername" name="txtBenutzername" value="<?php if(!empty($_POST['txtBenutzername'])) echo $_POST['txtBenutzername']; ?>" required="required"></label><br/>

        <label for="txtPasswort">Bitte mySQL Passwort für Benutzer eingeben:<br/>
        <input type="password" id="txtPasswort" name="txtPasswort" value="<?php if(!empty($_POST['txtPasswort'])) echo $_POST['txtPasswort']; ?>" required="required"></label><br/>

        <label for="txtDB">Bitte mySQL Datenban für Benutzer eingeben:<br/>
        <input type="text" id="txtDB" name="txtDB" value="<?php if(!empty($_POST['txtDB'])) echo $_POST['txtDB']; ?>" required="required"></label><br/>        
        <br/><br/>
        <input type="hidden" name="iStep" value="1">
        <input type="submit" name="btnAbschicken_DBConnection"><br>
    </form>
<?php           break;
    }
    #print_r($_POST);
?>

</body>
</html>