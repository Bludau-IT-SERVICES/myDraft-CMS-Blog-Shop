<?php
include_once("include/inc_config-data.php");

$query ="SELECT COUNT(news_content_id) AS anzahl,page_id FROM modul_rss_content GROUP BY Webseite ORDER BY anzahl DESC";

$resArticleSelect = DBi::$conn->query($query) or die('ERR00001:'.$query.mysqli_error(DBi::$conn));

while($strArticleData = mysqli_fetch_assoc($resArticleSelect)) {

    if($strArticleData["anzahl"] > 1) {

        # Zählen wieviele Doppelte Webseiten
        $query ="SELECT * FROM modul_rss_content WHERE page_id='".$strArticleData["page_id"]."'";
        $resArticleSelect_webseite_row_data = DBi::$conn->query($query) or die('ERR00001:'.$query.mysqli_error(DBi::$conn));        
        $iAnzahl_row = 0;
        while($strArticleSelect_webseite_row_data = mysqli_fetch_assoc($resArticleSelect_webseite_row_data)) {
            # Zählen wieviele Doppelte Webseiten
            $query ="SELECT count(*) as anzahl FROM modul_rss_content WHERE Webseite='".$strArticleSelect_webseite_row_data['Webseite']."'";
            $resArticleSelect_webseite_count = DBi::$conn->query($query) or die('ERR00001:'.$query.mysqli_error(DBi::$conn));        
            $iAnzahl_row = 0;
            while($strArticleData_webseite_count = mysqli_fetch_assoc($resArticleSelect_webseite_count)) {
                $iAnzahl_row = $strArticleData_webseite_count['anzahl'];
            }
            
            $query ="SELECT * FROM modul_rss_content WHERE Webseite='".$strArticleSelect_webseite_row_data['Webseite']."'";
            $resArticleSelect_webseite = DBi::$conn->query($query) or die('ERR00001:'.$query.mysqli_error(DBi::$conn));        
            $iCount = 1;
            while($strArticleData_webseite = mysqli_fetch_assoc($resArticleSelect_webseite)) {

                if(($iCount +1) != $iAnzahl_row) {
                    ###############################
                    # ALLE SEITEN CORE_LÖSCHEN
                    ###############################
                    $query = "SELECT * FROM module_in_menue WHERE menue_id='".$strArticleData_webseite['page_id']."'";
                    $resModule = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
                    while($strModule = mysqli_fetch_assoc($resModule)) {
                        # MODULE AUS EIGENER MODULTABELLE LÖSCHEN
                        $query = "DELETE FROM modul_".$strModule['typ']." WHERE id='".$strModule['modul_id']."'";
                        DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));			
                    }

                    $query = "DELETE FROM module_in_menue WHERE menue_id='".$strArticleData_webseite['page_id']."'";    
                    DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));		

                    $query = "DELETE FROM menue_parent WHERE menue_id='".$strArticleData_webseite['page_id']."'";
                    DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));		
                    
                    $query = "DELETE FROM menue WHERE id='".$strArticleData_webseite['page_id']."'";
                    DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));	
                    
                    $query = "DELETE FROM modul_rss_content WHERE news_content_id='".$strArticleData_webseite["news_content_id"]."'";
                    DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));	
                }
                $iCount++;
            }
        }
    }
}

?>