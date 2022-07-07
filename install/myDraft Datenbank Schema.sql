-- --------------------------------------------------------
-- Host:                         85.215.101.24
-- Server Version:               10.6.8-MariaDB-1:10.6.8+maria~buster-log - mariadb.org binary distribution
-- Server Betriebssystem:        debian-linux-gnu
-- HeidiSQL Version:             12.0.0.6532
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Exportiere Struktur von Tabelle mydraft.api_googleplus
CREATE TABLE IF NOT EXISTS `api_googleplus` (
  `api_googleplus_id` int(11) NOT NULL AUTO_INCREMENT,
  `access_token` varchar(300) CHARACTER SET utf8mb3 NOT NULL,
  `code` varchar(300) CHARACTER SET utf8mb3 NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `person_id` varchar(200) CHARACTER SET utf8mb3 DEFAULT NULL,
  `name` varchar(200) CHARACTER SET utf8mb3 DEFAULT NULL,
  `geburtstag` varchar(20) CHARACTER SET utf8mb3 DEFAULT NULL,
  `gender` varchar(50) CHARACTER SET utf8mb3 DEFAULT NULL,
  `url` varchar(50) CHARACTER SET utf8mb3 DEFAULT NULL,
  `hasApp` varchar(50) CHARACTER SET utf8mb3 DEFAULT NULL,
  `aboutMe` text CHARACTER SET utf8mb3 DEFAULT NULL,
  `relationshipStatus` varchar(200) CHARACTER SET utf8mb3 DEFAULT NULL,
  `verified` varchar(10) CHARACTER SET utf8mb3 DEFAULT NULL,
  `circledByCount` int(11) DEFAULT NULL,
  `plusOneCount` int(11) DEFAULT NULL,
  `isPlusUser` varchar(10) CHARACTER SET utf8mb3 DEFAULT NULL,
  `objectType` varchar(20) CHARACTER SET utf8mb3 DEFAULT NULL,
  `email` varchar(300) CHARACTER SET utf8mb3 DEFAULT NULL,
  `email_type` varchar(300) CHARACTER SET utf8mb3 DEFAULT NULL,
  `vorname` varchar(300) CHARACTER SET utf8mb3 DEFAULT NULL,
  `nachname` varchar(300) CHARACTER SET utf8mb3 DEFAULT NULL,
  `displayName` varchar(300) CHARACTER SET utf8mb3 DEFAULT NULL,
  `imageurl` varchar(500) CHARACTER SET utf8mb3 DEFAULT NULL,
  `currentLocation` varchar(500) CHARACTER SET utf8mb3 DEFAULT NULL,
  `language` varchar(500) CHARACTER SET utf8mb3 DEFAULT NULL,
  `ageRange_min` varchar(10) CHARACTER SET utf8mb3 DEFAULT NULL,
  `ageRange_max` varchar(10) CHARACTER SET utf8mb3 DEFAULT NULL,
  `nickname` varchar(300) CHARACTER SET utf8mb3 DEFAULT NULL,
  `tagline` text CHARACTER SET utf8mb3 DEFAULT NULL,
  PRIMARY KEY (`api_googleplus_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.api_twitter_history
CREATE TABLE IF NOT EXISTS `api_twitter_history` (
  `api_twitter_history_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message` longtext NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `bSend` enum('Y','N') DEFAULT 'N',
  `Sended_count` int(10) unsigned DEFAULT 0,
  `api_twitter_account_id` int(10) unsigned DEFAULT NULL,
  `sendet_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`api_twitter_history_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2183477 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.benutzer
CREATE TABLE IF NOT EXISTS `benutzer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(254) NOT NULL,
  `erstellt_am` datetime DEFAULT current_timestamp(),
  `domain_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `firma` varchar(255) DEFAULT NULL,
  `vorname` varchar(255) DEFAULT NULL,
  `nachname` varchar(255) DEFAULT NULL,
  `strasse_hnr` varchar(255) DEFAULT NULL,
  `plz` varchar(50) DEFAULT NULL,
  `stadt` varchar(255) DEFAULT NULL,
  `land` varchar(255) DEFAULT NULL,
  `profile_id` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `login_count` int(11) DEFAULT 0,
  `login_error` int(11) DEFAULT 0,
  `telefon` varchar(100) DEFAULT NULL,
  `email_crc` varchar(100) DEFAULT NULL,
  `email_validate` enum('Y','N') DEFAULT 'N',
  `email_freischaltung_datum` datetime DEFAULT NULL,
  `googleid` varchar(300) DEFAULT NULL,
  `bISBlowfish` enum('Y','N') DEFAULT 'N',
  `api_facebook_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.benutzer_gruppe
CREATE TABLE IF NOT EXISTS `benutzer_gruppe` (
  `benutzer_gruppe_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gruppenname_de` varchar(255) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`benutzer_gruppe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.benutzer_module
CREATE TABLE IF NOT EXISTS `benutzer_module` (
  `benutzer_module_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `benutzer_id` int(10) unsigned NOT NULL DEFAULT 0,
  `modul_typ` varchar(255) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`benutzer_module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.domains
CREATE TABLE IF NOT EXISTS `domains` (
  `domain_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `startseite` int(11) DEFAULT 1,
  `warenkorb_id` int(11) DEFAULT 1,
  `zurkasse_id` int(11) DEFAULT 1,
  `agb_id` int(11) DEFAULT 1,
  `widerruf` int(11) DEFAULT 1,
  `bIsShop` enum('Y','N') DEFAULT 'N',
  `logo_pfad` varchar(300) DEFAULT NULL,
  `system_shop_marktplatz_disable` enum('Y','N') DEFAULT 'N',
  `shop_mwst_setting` enum('MwSt_inkl','MwSt_exkl','MwSt_befreit') DEFAULT 'MwSt_inkl',
  `template_folder` varchar(300) DEFAULT 'universelles_rss',
  `portal_abrechnung_gruppierung` int(11) DEFAULT 0,
  `email_freischaltung` enum('Y','N') DEFAULT 'N',
  `email_freischaltung_datum` datetime DEFAULT NULL,
  `google_webmaster` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `meta_page_topic` varchar(100) NOT NULL,
  `meta_revisit_after` varchar(50) NOT NULL DEFAULT '7 days',
  `shipping_id` int(10) unsigned NOT NULL DEFAULT 1,
  `meta_nowfollow` varchar(50) NOT NULL,
  `bIsNews` enum('Y','N') NOT NULL DEFAULT 'Y',
  `bIsRSSPortal` enum('Y','N') NOT NULL DEFAULT 'N',
  `bGlobalCaching` enum('Y','N') NOT NULL DEFAULT 'Y',
  `logo_height` varchar(50) DEFAULT NULL,
  `logo_width` varchar(50) DEFAULT NULL,
  `bIsSSL` enum('Y','N') DEFAULT 'N',
  `bWebShopAnimation` enum('shop_animation_on','shop_animation_off') DEFAULT 'shop_animation_off',
  `hasPortalRSS` enum('Y','N') DEFAULT 'Y',
  `bing_meta` varchar(300) DEFAULT NULL,
  `isRestaurant` enum('Y','N') DEFAULT 'N',
  `isSendRechnung_at_order` enum('Y','N') DEFAULT 'N',
  `enable_new_frame_warenkorb` enum('Y','N') DEFAULT 'N',
  `isOrderAllowed` enum('Y','N') DEFAULT 'Y',
  `email_send_invoice` enum('Y','N') DEFAULT 'Y',
  `domain_crc` varchar(250) DEFAULT NULL,
  `has_product_content_page` enum('Y','N') DEFAULT 'Y',
  `meta_autor` varchar(150) DEFAULT NULL,
  `webseiten_name` varchar(150) DEFAULT NULL,
  `twitter_handle_name` varchar(150) DEFAULT NULL,
  `meta_copyright` varchar(150) DEFAULT NULL,
  `webseite_icon` varchar(500) DEFAULT NULL,
  `webseite_icon_96` varchar(500) DEFAULT NULL,
  `webseite_icon_16` varchar(500) DEFAULT NULL,
  `twitter_icon_alt` varchar(500) DEFAULT NULL,
  `twitter_icon` varchar(500) DEFAULT NULL,
  `og_icon` varchar(500) DEFAULT NULL,
  `google_adsense_id` varchar(250) DEFAULT NULL,
  `og_type` varchar(250) DEFAULT NULL,
  `meta_email` varchar(250) DEFAULT NULL,
  `meta_fb_page_id` varchar(250) DEFAULT NULL,
  `twitter_account_id` varchar(250) DEFAULT NULL,
  `og_image_typ` varchar(250) DEFAULT NULL,
  `og_image_width` varchar(250) DEFAULT NULL,
  `og_image_height` varchar(250) CHARACTER SET utf8mb3 DEFAULT NULL,
  PRIMARY KEY (`domain_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.domain_settings
CREATE TABLE IF NOT EXISTS `domain_settings` (
  `domain_settings_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain_id` int(10) unsigned NOT NULL,
  `name` varchar(250) NOT NULL,
  `value` varchar(500) NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`domain_settings_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.email_vorlage
CREATE TABLE IF NOT EXISTS `email_vorlage` (
  `email_vorlage_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain_id` int(10) unsigned NOT NULL,
  `typ` varchar(300) NOT NULL,
  `content` text DEFAULT NULL,
  `betreff` tinytext DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `standard` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`email_vorlage_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.email_vorlage_settings
CREATE TABLE IF NOT EXISTS `email_vorlage_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vorlagen_id` int(10) unsigned NOT NULL,
  `isLieferserivce` enum('Y','N') NOT NULL DEFAULT 'N',
  `hasPicture` enum('Y','N') NOT NULL DEFAULT 'Y',
  `hasShipping` enum('Y','N') NOT NULL DEFAULT 'Y',
  `Shipping_text` text NOT NULL,
  `isGericht` enum('Y','N') NOT NULL DEFAULT 'N',
  `cart_show_mwst` enum('Y','N') NOT NULL DEFAULT 'Y',
  `cart_show_endsumme_text` varchar(150) NOT NULL DEFAULT 'Endsumme',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.menue
CREATE TABLE IF NOT EXISTS `menue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain_id` int(10) unsigned NOT NULL DEFAULT 0,
  `name_de` varchar(255) NOT NULL,
  `titel_de` varchar(255) NOT NULL,
  `sortierung` int(10) unsigned NOT NULL DEFAULT 0,
  `status_de` varchar(250) NOT NULL,
  `layout` varchar(250) NOT NULL DEFAULT 'left-column',
  `visitors` int(10) unsigned NOT NULL DEFAULT 0,
  `content_type` varchar(50) DEFAULT 'normale_seite',
  `exturl` varchar(500) DEFAULT NULL,
  `target` varchar(250) DEFAULT NULL,
  `bLogin_requiered` enum('Y','N') DEFAULT 'N',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `template_file` varchar(300) DEFAULT 'index.tpl',
  `spalte_links_breite` varchar(30) DEFAULT NULL,
  `spalte_rechts_breite` varchar(30) DEFAULT NULL,
  `spalte_mitte_breite` varchar(30) DEFAULT NULL,
  `showRSS_FeedContent` enum('Y','N') DEFAULT 'Y',
  `youtube_id` varchar(100) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `domain_id` (`domain_id`),
  KEY `status_de` (`status_de`),
  KEY `content_type` (`content_type`),
  KEY `sortierung` (`sortierung`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2352618 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.menue_parent
CREATE TABLE IF NOT EXISTS `menue_parent` (
  `id_menue_parent` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menue_id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_menue_parent`),
  KEY `menue_id` (`menue_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2351613 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.menue_visitors
CREATE TABLE IF NOT EXISTS `menue_visitors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `visitor` varchar(300) NOT NULL DEFAULT '0',
  `page_id` int(10) unsigned NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `login_id` int(11) unsigned DEFAULT NULL,
  `kunden_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_at` (`created_at`),
  KEY `page_id` (`page_id`),
  KEY `visitor` (`visitor`)
) ENGINE=InnoDB AUTO_INCREMENT=17190692 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.module_installiert
CREATE TABLE IF NOT EXISTS `module_installiert` (
  `module_installed_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typ` varchar(255) NOT NULL DEFAULT '0',
  `status_aktiv` enum('Y','N') NOT NULL DEFAULT 'Y',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `name_de` varchar(200) NOT NULL,
  `sortierung` int(10) unsigned NOT NULL,
  `isShop` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`module_installed_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.module_installiert_profiles
CREATE TABLE IF NOT EXISTS `module_installiert_profiles` (
  `module_installiert_profiles_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_installiert_id` int(10) unsigned NOT NULL DEFAULT 0,
  `profile_id` int(11) NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`module_installiert_profiles_id`),
  KEY `module_installiert_id` (`module_installiert_id`),
  KEY `profile_id` (`profile_id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.module_in_menue
CREATE TABLE IF NOT EXISTS `module_in_menue` (
  `id_module` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menue_id` int(10) unsigned NOT NULL,
  `modul_id` int(10) unsigned NOT NULL,
  `container` varchar(255) NOT NULL DEFAULT 'col-main',
  `typ` varchar(255) NOT NULL DEFAULT 'text-html',
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_module`),
  KEY `menue_id` (`menue_id`),
  KEY `modul_id` (`modul_id`),
  KEY `container` (`container`),
  KEY `typ` (`typ`),
  KEY `position` (`position`)
) ENGINE=InnoDB AUTO_INCREMENT=11270825 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_kommentar
CREATE TABLE IF NOT EXISTS `modul_kommentar` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) NOT NULL DEFAULT '0',
  `content_de` longtext DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `last_usr` int(11) unsigned NOT NULL,
  `design` varchar(50) NOT NULL DEFAULT 'box',
  `menue_id` int(10) unsigned DEFAULT NULL,
  `news_cat` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2349466 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_kommentar_content
CREATE TABLE IF NOT EXISTS `modul_kommentar_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) DEFAULT '0',
  `content_de` longtext NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `last_usr` int(11) unsigned DEFAULT NULL,
  `design` varchar(50) DEFAULT 'box',
  `menue_id` int(10) unsigned DEFAULT NULL,
  `cat_id` int(10) unsigned DEFAULT NULL,
  `modul_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menue_id` (`menue_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_kommentar_parent
CREATE TABLE IF NOT EXISTS `modul_kommentar_parent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kommentar_id` int(10) unsigned NOT NULL DEFAULT 0,
  `kommentar_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_kommentar_parent2
CREATE TABLE IF NOT EXISTS `modul_kommentar_parent2` (
  `kommentar_content_parent_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kommentar_content_id` int(10) unsigned NOT NULL DEFAULT 0,
  `kommentar_parent_id` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`kommentar_content_parent_id`),
  KEY `kommentar_content_id` (`kommentar_content_id`),
  KEY `kommentar_parent_id` (`kommentar_parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_kontakt_form
CREATE TABLE IF NOT EXISTS `modul_kontakt_form` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) DEFAULT NULL,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `last_usr` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_menue
CREATE TABLE IF NOT EXISTS `modul_menue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) NOT NULL DEFAULT '0',
  `menue_id` int(10) unsigned NOT NULL DEFAULT 0,
  `typ` varchar(50) NOT NULL DEFAULT 'menue',
  `bAlphabetisch` enum('Y','N') NOT NULL DEFAULT 'Y',
  `last_usr` int(10) unsigned NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=483734 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_menue_shopcategory
CREATE TABLE IF NOT EXISTS `modul_menue_shopcategory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) NOT NULL DEFAULT '0',
  `content_de` longtext DEFAULT NULL,
  `last_usr` int(11) unsigned NOT NULL,
  `design` varchar(50) DEFAULT 'box',
  `menue_id` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `shopste_cat_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_newsletter_add
CREATE TABLE IF NOT EXISTS `modul_newsletter_add` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_de` varchar(255) NOT NULL DEFAULT '0',
  `title_de` varchar(255) NOT NULL DEFAULT '0',
  `sortierung` int(10) unsigned NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `domain_id` int(11) NOT NULL DEFAULT 1,
  `page_id` int(11) unsigned DEFAULT NULL,
  `last_usr` int(11) unsigned DEFAULT NULL,
  `content_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_news_category
CREATE TABLE IF NOT EXISTS `modul_news_category` (
  `news_cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_de` varchar(255) NOT NULL DEFAULT '0',
  `sortierung` int(10) unsigned NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `domain_id` int(11) NOT NULL DEFAULT 0,
  `page_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`news_cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_news_categoryview
CREATE TABLE IF NOT EXISTS `modul_news_categoryview` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) NOT NULL DEFAULT '0',
  `content_de` longtext NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `last_usr` int(11) unsigned NOT NULL,
  `design` varchar(50) NOT NULL DEFAULT 'box',
  `menue_id` int(10) unsigned DEFAULT NULL,
  `news_cat` int(10) unsigned DEFAULT NULL,
  `gui_header_show_category` enum('Y','N') DEFAULT 'Y',
  `gui_header_show_category_link` enum('Y','N') DEFAULT 'Y',
  `gui_header_show_date` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_news_content
CREATE TABLE IF NOT EXISTS `modul_news_content` (
  `news_content_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Bereich` tinyint(4) DEFAULT NULL,
  `Author` varchar(55) DEFAULT NULL,
  `eMail` varchar(30) DEFAULT NULL,
  `Webseite` varchar(250) DEFAULT NULL,
  `AddDatum` varchar(25) DEFAULT NULL,
  `AddTitel` varchar(250) DEFAULT NULL,
  `AddText` text DEFAULT NULL,
  `counter` bigint(20) NOT NULL DEFAULT 0,
  `comment` text DEFAULT NULL,
  `Bewertung_Gesammt` int(10) unsigned NOT NULL DEFAULT 0,
  `Bewertung_anzahl` int(10) unsigned NOT NULL DEFAULT 0,
  `WordCounter` int(10) unsigned NOT NULL DEFAULT 0,
  `ZeilenAnzahl` int(10) unsigned NOT NULL DEFAULT 0,
  `AddClock` time DEFAULT NULL,
  `news_cat` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `domain_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`news_content_id`),
  FULLTEXT KEY `AddText` (`AddText`,`AddTitel`)
) ENGINE=InnoDB AUTO_INCREMENT=2541 DEFAULT CHARSET=utf8mb4 PACK_KEYS=0;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_news_content_add
CREATE TABLE IF NOT EXISTS `modul_news_content_add` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_de` varchar(255) NOT NULL DEFAULT '0',
  `title_de` varchar(255) NOT NULL DEFAULT '0',
  `sortierung` int(10) unsigned NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `domain_id` int(11) NOT NULL DEFAULT 0,
  `page_id` int(11) unsigned DEFAULT NULL,
  `last_usr` int(11) unsigned DEFAULT NULL,
  `content_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_news_content_copy
CREATE TABLE IF NOT EXISTS `modul_news_content_copy` (
  `AddTitel` varchar(250) DEFAULT NULL,
  `AddText` text DEFAULT NULL,
  FULLTEXT KEY `AddText` (`AddText`,`AddTitel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_news_content_view
CREATE TABLE IF NOT EXISTS `modul_news_content_view` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_de` varchar(255) NOT NULL DEFAULT '0',
  `title_de` varchar(255) NOT NULL DEFAULT '0',
  `sortierung` int(10) unsigned NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `domain_id` int(11) NOT NULL DEFAULT 0,
  `page_id` int(11) unsigned DEFAULT NULL,
  `last_usr` int(11) unsigned DEFAULT NULL,
  `content_de` text DEFAULT NULL,
  `news_cat` int(11) unsigned DEFAULT NULL,
  `menue_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1296 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_news_latest
CREATE TABLE IF NOT EXISTS `modul_news_latest` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) NOT NULL DEFAULT '0',
  `content_de` longtext NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `last_usr` int(11) unsigned NOT NULL,
  `design` varchar(50) NOT NULL DEFAULT 'box',
  `menue_id` int(10) unsigned DEFAULT NULL,
  `news_category_id` int(10) unsigned DEFAULT NULL,
  `news_category_show_items_count` int(10) unsigned DEFAULT 1,
  `news_cat_id` int(11) DEFAULT NULL,
  `show_cat_link` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_portal_gebuehrenanzeige
CREATE TABLE IF NOT EXISTS `modul_portal_gebuehrenanzeige` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) CHARACTER SET utf8mb3 NOT NULL DEFAULT '0',
  `content_de` longtext CHARACTER SET utf8mb3 NOT NULL,
  `last_usr` int(11) unsigned NOT NULL,
  `design` varchar(50) CHARACTER SET utf8mb3 NOT NULL DEFAULT 'box',
  `menue_id` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_portal_mitglied_info
CREATE TABLE IF NOT EXISTS `modul_portal_mitglied_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) CHARACTER SET utf8mb3 NOT NULL DEFAULT '0',
  `content_de` longtext CHARACTER SET utf8mb3 NOT NULL,
  `lastchange` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_usr` int(11) unsigned NOT NULL,
  `design` varchar(50) CHARACTER SET utf8mb3 NOT NULL DEFAULT 'box',
  `menue_id` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_portal_ordercentral
CREATE TABLE IF NOT EXISTS `modul_portal_ordercentral` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) CHARACTER SET utf8mb3 DEFAULT NULL,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `last_usr` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_portal_shop_cat_list
CREATE TABLE IF NOT EXISTS `modul_portal_shop_cat_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) CHARACTER SET utf8mb3 DEFAULT NULL,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `last_usr` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `shop_cat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_portal_shop_item_detail
CREATE TABLE IF NOT EXISTS `modul_portal_shop_item_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) CHARACTER SET utf8mb3 DEFAULT NULL,
  `menue_id` int(11) unsigned DEFAULT NULL,
  `last_usr` int(11) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `shop_item_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_portal_shop_product_slider
CREATE TABLE IF NOT EXISTS `modul_portal_shop_product_slider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) CHARACTER SET utf8mb3 NOT NULL DEFAULT '0',
  `content_de` longtext CHARACTER SET utf8mb3 NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `last_usr` int(11) unsigned NOT NULL,
  `design` varchar(50) CHARACTER SET utf8mb3 NOT NULL DEFAULT 'box',
  `menue_id` int(10) unsigned DEFAULT NULL,
  `shop_category` int(10) unsigned DEFAULT NULL,
  `produkt_anzahl` int(10) unsigned DEFAULT 5,
  `zufall` enum('Y','N') CHARACTER SET utf8mb3 DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_portal_umkreis
CREATE TABLE IF NOT EXISTS `modul_portal_umkreis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) CHARACTER SET utf8mb3 DEFAULT NULL,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `last_usr` int(11) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_portal_userlogin
CREATE TABLE IF NOT EXISTS `modul_portal_userlogin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) CHARACTER SET utf8mb3 DEFAULT NULL,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `last_usr` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_register_user
CREATE TABLE IF NOT EXISTS `modul_register_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) NOT NULL DEFAULT '0',
  `content_de` longtext NOT NULL,
  `last_usr` int(11) unsigned NOT NULL,
  `design` varchar(50) NOT NULL DEFAULT 'box',
  `menue_id` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_registrieren_mydraft
CREATE TABLE IF NOT EXISTS `modul_registrieren_mydraft` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) DEFAULT NULL,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `last_usr` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_registrieren_shopste
CREATE TABLE IF NOT EXISTS `modul_registrieren_shopste` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) DEFAULT NULL,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `last_usr` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_rss_category
CREATE TABLE IF NOT EXISTS `modul_rss_category` (
  `news_cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_de` varchar(255) NOT NULL DEFAULT '0',
  `sortierung` int(10) unsigned NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `domain_id` int(11) NOT NULL DEFAULT 0,
  `page_id` int(11) unsigned DEFAULT NULL,
  `isYoutube` enum('Y','N') DEFAULT 'N',
  `twitter_tags` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`news_cat_id`),
  KEY `domain_id` (`domain_id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_rss_categoryview
CREATE TABLE IF NOT EXISTS `modul_rss_categoryview` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(500) NOT NULL DEFAULT '0',
  `content_de` longtext NOT NULL,
  `last_usr` int(11) unsigned NOT NULL,
  `design` varchar(50) NOT NULL DEFAULT 'box',
  `menue_id` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `news_cat` int(10) unsigned DEFAULT NULL,
  `gui_header_show_external_link` enum('Y','N') DEFAULT 'Y',
  `gui_header_show_sources_link` enum('Y','N') DEFAULT 'Y',
  `gui_header_show_date` enum('Y','N') DEFAULT 'Y',
  `gui_header_show_category` enum('Y','N') DEFAULT 'Y',
  `gui_header_show_category_link` enum('Y','N') DEFAULT 'Y',
  `gui_footer_rateing` enum('Y','N') DEFAULT 'Y',
  `gui_content_show_news` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`id`),
  KEY `menue_id` (`menue_id`),
  KEY `news_cat` (`news_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_rss_category_parent
CREATE TABLE IF NOT EXISTS `modul_rss_category_parent` (
  `id_news_category_parent` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `news_cat_id` int(10) unsigned NOT NULL DEFAULT 0,
  `news_cat_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_news_category_parent`),
  KEY `news_cat_id` (`news_cat_id`),
  KEY `news_cat_parent` (`news_cat_parent`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb3;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_rss_content
CREATE TABLE IF NOT EXISTS `modul_rss_content` (
  `news_content_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Bereich` tinyint(4) DEFAULT NULL,
  `Author` varchar(55) DEFAULT NULL,
  `eMail` varchar(30) DEFAULT NULL,
  `Webseite` varchar(750) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `AddDatum` datetime DEFAULT current_timestamp(),
  `AddTitel` varchar(500) DEFAULT NULL,
  `AddText` longtext DEFAULT NULL,
  `counter` bigint(20) DEFAULT 0,
  `comment` text DEFAULT NULL,
  `Bewertung_Gesammt` int(10) unsigned DEFAULT 0,
  `Bewertung_anzahl` int(10) unsigned DEFAULT 0,
  `WordCounter` int(10) unsigned DEFAULT 0,
  `ZeilenAnzahl` int(10) unsigned DEFAULT 0,
  `AddClock` time DEFAULT NULL,
  `news_cat` int(10) unsigned DEFAULT NULL,
  `domain_id` int(10) unsigned DEFAULT 0,
  `page_id` int(10) unsigned DEFAULT 0,
  `rss_quelle_id` int(10) unsigned DEFAULT 0,
  `isYoutube` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`news_content_id`),
  KEY `Webseite` (`Webseite`),
  KEY `page_id` (`page_id`),
  KEY `news_cat` (`news_cat`),
  KEY `Bereich` (`Bereich`),
  KEY `domain_id` (`domain_id`),
  FULLTEXT KEY `AddText` (`AddText`),
  FULLTEXT KEY `AddTitel` (`AddTitel`),
  FULLTEXT KEY `AddTitel_AddText` (`AddTitel`,`AddText`)
) ENGINE=InnoDB AUTO_INCREMENT=1778926 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_rss_content_popular
CREATE TABLE IF NOT EXISTS `modul_rss_content_popular` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) DEFAULT NULL,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `last_usr` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `typ` varchar(50) NOT NULL DEFAULT 'MONTH',
  `limit` varchar(50) NOT NULL DEFAULT '10',
  `bLoadAjax` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_rss_content_quelle_add
CREATE TABLE IF NOT EXISTS `modul_rss_content_quelle_add` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_de` varchar(255) NOT NULL DEFAULT '0',
  `title_de` varchar(255) NOT NULL DEFAULT '0',
  `sortierung` int(10) unsigned NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `domain_id` int(11) NOT NULL DEFAULT 0,
  `page_id` int(11) unsigned DEFAULT NULL,
  `last_usr` int(11) unsigned DEFAULT NULL,
  `content_de` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_rss_content_view
CREATE TABLE IF NOT EXISTS `modul_rss_content_view` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) NOT NULL DEFAULT '0',
  `content_de` longtext DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `last_usr` int(11) unsigned NOT NULL,
  `design` varchar(50) NOT NULL DEFAULT 'box',
  `menue_id` int(10) unsigned DEFAULT NULL,
  `news_cat` int(10) unsigned DEFAULT NULL,
  `gui_header_show_external_link` enum('Y','N') DEFAULT 'Y',
  `gui_header_show_sources_link` enum('Y','N') DEFAULT 'Y',
  `gui_header_show_date` enum('Y','N') DEFAULT 'Y',
  `gui_header_show_category` enum('Y','N') DEFAULT 'Y',
  `gui_header_show_category_link` enum('Y','N') DEFAULT 'Y',
  `gui_footer_rateing` enum('Y','N') DEFAULT 'Y',
  `gui_footer_social_media` enum('Y','N') DEFAULT 'Y',
  `page_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1778686 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_rss_feed_show
CREATE TABLE IF NOT EXISTS `modul_rss_feed_show` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) DEFAULT NULL,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `last_usr` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `rss_quelle` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_rss_quelle
CREATE TABLE IF NOT EXISTS `modul_rss_quelle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) DEFAULT NULL,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `last_usr` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `rss_quelle` varchar(500) NOT NULL,
  `rss_cat` int(10) unsigned NOT NULL,
  `einsender` varchar(250) DEFAULT NULL,
  `enabled` enum('Y','N') NOT NULL DEFAULT 'Y',
  `last_inserted_content_item_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=545 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_shop_cart
CREATE TABLE IF NOT EXISTS `modul_shop_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) CHARACTER SET utf8mb3 DEFAULT NULL,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `last_usr` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_shop_cart_order
CREATE TABLE IF NOT EXISTS `modul_shop_cart_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) CHARACTER SET utf8mb3 DEFAULT NULL,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `last_usr` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_shop_cat_list
CREATE TABLE IF NOT EXISTS `modul_shop_cat_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) CHARACTER SET utf8mb3 DEFAULT NULL,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `last_usr` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `shop_cat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_shop_cat_neuste
CREATE TABLE IF NOT EXISTS `modul_shop_cat_neuste` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) CHARACTER SET utf8mb3 DEFAULT NULL,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `last_usr` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `shop_cat_id` int(11) NOT NULL,
  `item_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_shop_item_detail
CREATE TABLE IF NOT EXISTS `modul_shop_item_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) CHARACTER SET utf8mb3 DEFAULT NULL,
  `menue_id` int(11) unsigned DEFAULT NULL,
  `last_usr` int(11) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `shop_item_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_shop_product_slider
CREATE TABLE IF NOT EXISTS `modul_shop_product_slider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) CHARACTER SET utf8mb3 NOT NULL DEFAULT '0',
  `content_de` longtext CHARACTER SET utf8mb3 NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `last_usr` int(11) unsigned NOT NULL,
  `design` varchar(50) CHARACTER SET utf8mb3 NOT NULL DEFAULT 'box',
  `menue_id` int(10) unsigned DEFAULT NULL,
  `shop_category` int(10) unsigned DEFAULT NULL,
  `produkt_anzahl` int(10) unsigned DEFAULT 5,
  `zufall` enum('Y','N') CHARACTER SET utf8mb3 DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_similar
CREATE TABLE IF NOT EXISTS `modul_similar` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) NOT NULL DEFAULT '0',
  `menue_id` int(10) unsigned NOT NULL DEFAULT 0,
  `typ` varchar(50) NOT NULL DEFAULT 'menue',
  `bAlphabetisch` enum('Y','N') NOT NULL DEFAULT 'Y',
  `last_usr` int(10) unsigned NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `suchwort` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1778665 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_sitemap
CREATE TABLE IF NOT EXISTS `modul_sitemap` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) NOT NULL DEFAULT '0',
  `content_de` longtext NOT NULL,
  `last_usr` int(11) unsigned NOT NULL,
  `design` varchar(50) NOT NULL DEFAULT 'box',
  `menue_id` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `gui_show_rss_content_preview` enum('Y','N') DEFAULT 'Y',
  `gui_show_news_content_preview` enum('Y','N') DEFAULT 'Y',
  `gui_show_shop_content_preview` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_sitemap_shopcategory
CREATE TABLE IF NOT EXISTS `modul_sitemap_shopcategory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) NOT NULL DEFAULT '0',
  `content_de` longtext NOT NULL,
  `last_usr` int(11) unsigned NOT NULL,
  `design` varchar(50) NOT NULL DEFAULT 'box',
  `menue_id` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_stats
CREATE TABLE IF NOT EXISTS `modul_stats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) DEFAULT NULL,
  `menue_id` int(10) unsigned DEFAULT NULL,
  `last_usr` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `typ` varchar(50) NOT NULL DEFAULT 'MONTH',
  `limit` varchar(50) NOT NULL DEFAULT '10',
  `bLoadAjax` enum('Y','N') NOT NULL DEFAULT 'N',
  `bShowRating` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3737057 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.modul_texthtml
CREATE TABLE IF NOT EXISTS `modul_texthtml` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) NOT NULL DEFAULT '0',
  `content_de` longtext NOT NULL,
  `last_usr` int(11) unsigned NOT NULL,
  `design` varchar(50) NOT NULL DEFAULT 'box',
  `menue_id` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=401 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.newsletter
CREATE TABLE IF NOT EXISTS `newsletter` (
  `newsletter_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `crc` varchar(100) NOT NULL,
  `rss_cat` int(11) DEFAULT 0,
  `enabled` enum('Y','N') DEFAULT 'N',
  `domain_id` int(10) unsigned DEFAULT 1,
  `email_user_name` varchar(250) NOT NULL,
  `gesendet_am` datetime DEFAULT NULL,
  `gesendet_anzahl` int(10) unsigned DEFAULT 0,
  PRIMARY KEY (`newsletter_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.portal_abrechnung
CREATE TABLE IF NOT EXISTS `portal_abrechnung` (
  `portal_abrechnung_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `abrechnung_monat` tinyint(3) unsigned NOT NULL,
  `abrechnung_jahr` smallint(5) unsigned NOT NULL,
  `domain_id` int(10) unsigned NOT NULL,
  `endsumme_gebuehr_ueberweisung` decimal(10,4) NOT NULL,
  `endsumme_gebuehr_paypal` decimal(10,4) NOT NULL,
  `in_rechnung_gestellt_am` datetime NOT NULL,
  `bezahlt_am` datetime NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `Umsatz_ohne_versand` decimal(10,4) NOT NULL,
  `bezahlt_mahnung_senden` datetime NOT NULL,
  `mahnung_gesendet` enum('Y','N') CHARACTER SET utf8mb3 NOT NULL DEFAULT 'N',
  `abrechnung_gesendet` enum('Y','N') CHARACTER SET utf8mb3 NOT NULL DEFAULT 'N',
  PRIMARY KEY (`portal_abrechnung_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.portal_abrechnung_gebuehr
CREATE TABLE IF NOT EXISTS `portal_abrechnung_gebuehr` (
  `portal_abrechnung_gebühr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gruppierung` int(10) unsigned NOT NULL,
  `von_preis` decimal(10,2) unsigned NOT NULL,
  `bis_preis` decimal(10,2) unsigned NOT NULL,
  `grundgebuehr` decimal(10,2) unsigned NOT NULL,
  `gebuehr_prozent` decimal(10,2) unsigned NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `gruppierung_name` varchar(200) CHARACTER SET utf8mb3 NOT NULL,
  PRIMARY KEY (`portal_abrechnung_gebühr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.portal_bewertungen
CREATE TABLE IF NOT EXISTS `portal_bewertungen` (
  `id_bewertungen` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_list_id` int(10) unsigned NOT NULL,
  `bewertung_text` varchar(500) CHARACTER SET utf8mb3 NOT NULL,
  `bewertung_rate_value` tinyint(4) NOT NULL,
  `benutzer_id` int(10) unsigned NOT NULL,
  `benutzer_gast_id` int(10) unsigned NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_bewertungen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.seiten_bewertung
CREATE TABLE IF NOT EXISTS `seiten_bewertung` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `seiten_id` int(11) DEFAULT NULL,
  `score` double DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1447 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_attribute
CREATE TABLE IF NOT EXISTS `shop_attribute` (
  `attribute_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_set_id` int(10) unsigned NOT NULL DEFAULT 0,
  `name_de` varchar(255) CHARACTER SET utf8mb3 NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `domain_id` int(11) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_attribute_set
CREATE TABLE IF NOT EXISTS `shop_attribute_set` (
  `shop_attribute_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `set_name_de` varchar(255) CHARACTER SET utf8mb3 NOT NULL DEFAULT 'Kein Name',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `attribute_global` enum('Y','N') CHARACTER SET utf8mb3 DEFAULT 'N',
  `domain_id` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`shop_attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_attribute_value
CREATE TABLE IF NOT EXISTS `shop_attribute_value` (
  `shop_attribute_value_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_attribute_id` int(10) unsigned NOT NULL DEFAULT 0,
  `value_de` varchar(255) CHARACTER SET utf8mb3 NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`shop_attribute_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_category
CREATE TABLE IF NOT EXISTS `shop_category` (
  `shop_cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_de` varchar(255) CHARACTER SET utf8mb3 NOT NULL DEFAULT '0',
  `sortierung` int(10) unsigned NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `domain_id` int(11) NOT NULL DEFAULT 0,
  `page_id` int(11) unsigned DEFAULT NULL,
  `eiso_shop_catid` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`shop_cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_category_parent
CREATE TABLE IF NOT EXISTS `shop_category_parent` (
  `id_shop_category_parent` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_cat_id` int(10) unsigned NOT NULL DEFAULT 0,
  `shop_cat_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_shop_category_parent`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_info
CREATE TABLE IF NOT EXISTS `shop_info` (
  `shop_info_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `firma` varchar(350) DEFAULT NULL,
  `vorname` varchar(130) DEFAULT NULL,
  `nachname` varchar(150) DEFAULT NULL,
  `strasse_hnr` varchar(250) DEFAULT NULL,
  `plz` varchar(250) DEFAULT NULL,
  `stadt` varchar(250) DEFAULT NULL,
  `land` varchar(250) DEFAULT NULL,
  `domain_id` varchar(250) NOT NULL,
  `email_order_copy` varchar(500) DEFAULT NULL,
  `email_order_copy_from_name` varchar(250) DEFAULT NULL,
  `email_shop_main` varchar(500) DEFAULT NULL,
  `email_shop_main_form_name` varchar(250) DEFAULT NULL,
  `shop_name` varchar(250) DEFAULT NULL,
  `shop_mitgliedsname` varchar(250) DEFAULT NULL,
  `telefon` varchar(100) DEFAULT NULL,
  `gewerblich` enum('Y','N') DEFAULT 'N',
  `telefon_rueckruf` enum('Y','N') DEFAULT 'N',
  `teamviewer_support` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`shop_info_id`),
  UNIQUE KEY `domain_id` (`domain_id`)
) ENGINE=InnoDB AUTO_INCREMENT=232 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_invoice
CREATE TABLE IF NOT EXISTS `shop_invoice` (
  `shop_invoice_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ges_order_status` varchar(250) CHARACTER SET utf8mb3 NOT NULL DEFAULT 'bestellt',
  `ges_order_versandkosten` decimal(10,2) NOT NULL,
  `ges_order_endsumme` decimal(10,2) NOT NULL,
  `ges_order_customer_id` int(11) NOT NULL,
  `ges_order_gewicht` decimal(10,2) NOT NULL,
  `ges_order_artikelsumme` decimal(10,2) NOT NULL,
  `ges_order_anzahl` int(10) unsigned NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `domain_id` int(10) unsigned DEFAULT 0,
  `bIsShopste` enum('Y','N') CHARACTER SET utf8mb3 DEFAULT 'N',
  PRIMARY KEY (`shop_invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_item
CREATE TABLE IF NOT EXISTS `shop_item` (
  `shop_item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_de` varchar(255) CHARACTER SET utf8mb3 NOT NULL,
  `preis` decimal(10,2) NOT NULL,
  `shop_cat_id` int(11) unsigned NOT NULL DEFAULT 0,
  `menue_id` int(11) DEFAULT NULL,
  `menge` int(11) DEFAULT 1,
  `beschreibung` text CHARACTER SET utf8mb3 DEFAULT NULL,
  `gewicht` double unsigned DEFAULT 0,
  `parrent_shop_item_id` int(10) unsigned DEFAULT 0,
  `item_number` varchar(255) CHARACTER SET utf8mb3 DEFAULT '0',
  `domain_id` int(10) unsigned DEFAULT 0,
  `status_de` varchar(250) CHARACTER SET utf8mb3 DEFAULT 'verkaufsbereit',
  `system_closed_shop` enum('Y','N') CHARACTER SET utf8mb3 DEFAULT 'N',
  `item_enabled` enum('Y','N') CHARACTER SET utf8mb3 DEFAULT 'Y',
  `item_dauer` tinyint(4) DEFAULT 10,
  `item_mwst` double DEFAULT 19,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `shopste_marktplatz_cat` int(11) unsigned DEFAULT 0,
  `shopste_marktplatz_menue_id` int(11) unsigned DEFAULT 0,
  `lieferzeit` varchar(50) CHARACTER SET utf8mb3 DEFAULT '2 Tage',
  `ean` varchar(50) CHARACTER SET utf8mb3 DEFAULT NULL,
  `versandkosten` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`shop_item_id`),
  KEY `shop_cat_id` (`shop_cat_id`),
  KEY `shopste_marktplatz_cat` (`shopste_marktplatz_cat`),
  FULLTEXT KEY `beschreibung` (`beschreibung`),
  FULLTEXT KEY `name_de` (`name_de`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_item_eigenschaft
CREATE TABLE IF NOT EXISTS `shop_item_eigenschaft` (
  `shop_item_eigenschaft_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop_item` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Shop Artikel ID',
  `eigenschaft_name_de` varchar(255) CHARACTER SET utf8mb3 NOT NULL DEFAULT '0',
  `typ` varchar(255) CHARACTER SET utf8mb3 NOT NULL DEFAULT 'select',
  `sorting` int(10) unsigned DEFAULT 0,
  `shop_attribut_id` int(10) unsigned NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`shop_item_eigenschaft_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_item_eigenschaftwert
CREATE TABLE IF NOT EXISTS `shop_item_eigenschaftwert` (
  `shop_item_eigenschaftwert_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop_item_eigenschaft` int(11) NOT NULL DEFAULT 0,
  `name_de` varchar(255) CHARACTER SET utf8mb3 NOT NULL DEFAULT '0',
  `aufpreis_de` decimal(9,2) NOT NULL DEFAULT 0.00,
  `lagerbestand` int(11) NOT NULL DEFAULT 100,
  `shop_attribut_value_id` int(10) unsigned DEFAULT NULL,
  `id_item_shop` int(10) unsigned DEFAULT NULL COMMENT 'Shop Artikel ID',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`shop_item_eigenschaftwert_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_item_picture
CREATE TABLE IF NOT EXISTS `shop_item_picture` (
  `shop_item_picture_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_item_id` int(10) unsigned DEFAULT 0,
  `picture_url` varchar(350) CHARACTER SET utf8mb3 DEFAULT '0',
  `modus` varchar(50) CHARACTER SET utf8mb3 DEFAULT 'main-picture',
  `picture_nr` tinyint(4) DEFAULT 1,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`shop_item_picture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_order
CREATE TABLE IF NOT EXISTS `shop_order` (
  `shop_order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ges_order_status` varchar(250) CHARACTER SET utf8mb3 NOT NULL DEFAULT 'bestellt',
  `ges_order_versandkosten` decimal(10,2) NOT NULL,
  `ges_order_endsumme` decimal(10,2) NOT NULL,
  `ges_order_customer_id` int(11) NOT NULL,
  `ges_order_gewicht` decimal(10,2) NOT NULL,
  `ges_order_artikelsumme` decimal(10,2) NOT NULL,
  `ges_order_anzahl` int(10) unsigned NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `domain_id` int(10) unsigned DEFAULT 0,
  `bIsShopste` enum('Y','N') CHARACTER SET utf8mb3 DEFAULT 'N',
  `invoice_id` int(11) DEFAULT NULL,
  `created_invoice` datetime DEFAULT NULL,
  `bezahlt_am` datetime DEFAULT NULL,
  `versendet_am` datetime DEFAULT NULL,
  `bewertet_am` datetime DEFAULT NULL,
  `abgeschlossen_am` datetime DEFAULT NULL,
  PRIMARY KEY (`shop_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_order_customer
CREATE TABLE IF NOT EXISTS `shop_order_customer` (
  `shop_order_customer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `anrede` varchar(50) CHARACTER SET utf8mb3 NOT NULL,
  `email` varchar(250) CHARACTER SET utf8mb3 NOT NULL,
  `firma` varchar(250) CHARACTER SET utf8mb3 NOT NULL,
  `vorname` varchar(250) CHARACTER SET utf8mb3 NOT NULL,
  `nachname` varchar(250) CHARACTER SET utf8mb3 NOT NULL,
  `strasse_hnr` varchar(250) CHARACTER SET utf8mb3 NOT NULL,
  `plz` varchar(250) CHARACTER SET utf8mb3 NOT NULL,
  `stadt` varchar(250) CHARACTER SET utf8mb3 NOT NULL,
  `land` varchar(250) CHARACTER SET utf8mb3 NOT NULL,
  `domain_id` int(11) DEFAULT NULL,
  `mitgliedsname` varchar(250) CHARACTER SET utf8mb3 DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`shop_order_customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_order_list
CREATE TABLE IF NOT EXISTS `shop_order_list` (
  `shop_order_list_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop_order` int(10) unsigned NOT NULL DEFAULT 0,
  `shop_item_id` int(10) unsigned NOT NULL DEFAULT 0,
  `order_menge` int(10) unsigned NOT NULL DEFAULT 1,
  `order_status` varchar(150) CHARACTER SET utf8mb3 NOT NULL DEFAULT 'bestellt',
  `eiso_export` enum('Y','N') CHARACTER SET utf8mb3 DEFAULT 'N',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `id_domain` int(11) DEFAULT NULL,
  `name_de` varchar(300) CHARACTER SET utf8mb3 DEFAULT NULL,
  `preis` decimal(10,2) DEFAULT NULL,
  `gewicht` double DEFAULT NULL,
  `id_invoice_no` int(10) unsigned DEFAULT NULL,
  `artikel_gebühr` decimal(10,4) unsigned DEFAULT NULL,
  `created_invoice` datetime DEFAULT NULL,
  `versendet_am` datetime DEFAULT NULL,
  `abgeschlossen_am` datetime DEFAULT NULL,
  `bewertet_am` datetime DEFAULT NULL,
  `bezahlt_am` datetime DEFAULT NULL,
  `bewertung_kauefer` enum('Y','N') CHARACTER SET utf8mb3 DEFAULT 'N',
  `bewertung_verkauefer` enum('Y','N') CHARACTER SET utf8mb3 DEFAULT 'N',
  PRIMARY KEY (`shop_order_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_shippment
CREATE TABLE IF NOT EXISTS `shop_shippment` (
  `shop_shippment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_de` varchar(250) CHARACTER SET utf8mb3 NOT NULL,
  `domain_id` int(10) unsigned DEFAULT 0,
  `mwst` varchar(10) CHARACTER SET utf8mb3 DEFAULT '0',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`shop_shippment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.shop_shippment_detail
CREATE TABLE IF NOT EXISTS `shop_shippment_detail` (
  `shop_shippment_detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_shippment_id` int(10) unsigned NOT NULL DEFAULT 0,
  `gewicht_von` double NOT NULL DEFAULT 0,
  `gewicht_bis` double NOT NULL DEFAULT 0,
  `versandkosten` double NOT NULL DEFAULT 0,
  `domain_id` double DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`shop_shippment_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.statistik
CREATE TABLE IF NOT EXISTS `statistik` (
  `statistik_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL COMMENT 'Name des Links',
  `http_link` text NOT NULL COMMENT 'Nur Link als https',
  `content_created_at` datetime NOT NULL,
  `content_hits` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Zugriffe',
  `content_modul` text NOT NULL COMMENT 'rss_content, news_content,etc',
  `content_bewertung` double NOT NULL DEFAULT 0 COMMENT 'Bewertung ausgerechnet',
  `content_group_typ` text NOT NULL,
  `content_group_by` text NOT NULL,
  `menue_id` int(10) unsigned NOT NULL,
  `menue_typ` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `domain_id` int(10) unsigned DEFAULT 1,
  PRIMARY KEY (`statistik_id`),
  KEY `domain_id` (`domain_id`),
  KEY `menue_id` (`menue_id`)
) ENGINE=InnoDB AUTO_INCREMENT=238510 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.suche_anfragen
CREATE TABLE IF NOT EXISTS `suche_anfragen` (
  `anfrage_id` int(11) NOT NULL AUTO_INCREMENT,
  `suchanfrage` varchar(300) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `treffer` int(11) unsigned NOT NULL DEFAULT 0,
  `suchanzahl` int(11) unsigned DEFAULT 0,
  `freigeschaltet` enum('Y','N') DEFAULT 'N',
  `shop_cat_id` int(11) unsigned DEFAULT NULL,
  `modul_typ` text DEFAULT NULL,
  PRIMARY KEY (`anfrage_id`),
  KEY `suchanfrage` (`suchanfrage`)
) ENGINE=InnoDB AUTO_INCREMENT=1036 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle mydraft.sys_error
CREATE TABLE IF NOT EXISTS `sys_error` (
  `sys_error_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(10) unsigned NOT NULL,
  `error_code` varchar(50) DEFAULT NULL,
  `error_message` varchar(500) DEFAULT NULL,
  `domain_id` int(10) unsigned DEFAULT NULL,
  `request_path` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`sys_error_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3657974 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
