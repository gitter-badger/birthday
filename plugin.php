<?php
// ***************************************************************
// *
// *		Plugin		:	Birthday Menu (e107 v7+)
// *
// *		Author		:	Barry Keal (c) 2003-2007
// *
// *		Web site	:	www.keal.me.uk
// *
// *		Description	:	Install plugin
// *
// *		Version		:	1.16
// *
// *		Date		:	27 Nov 2003
// *		Revisions	:	 6 May 2005 added email greeting
// *					:	 4 Sep 2006 Fixed language file isssue in admin
// *					:				Changed method of storing prefs
// *					:	27 Nov 2006 Don't display year if age turned off
// *					:	23 April 2007 PM user option added
// *					:	12 June 2009 Added avatar support
// *
// ***************************************************************
/*
+---------------------------------------------------------------+
|        Birthday Menu for e107 v7xx - by Father Barry
|
|        This module for the e107 .7+ website system
|        Copyright Barry Keal 2004-2009
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
|
+---------------------------------------------------------------+
*/
include_lan(e_PLUGIN . 'birthday/languages/' . e_LANGUAGE . '_birthday_mnu.php');
// Plugin info -------------------------------------------------------------------------------------------------------
$eplug_name = 'Birthday Menu';
$eplug_version = '1.18';
$eplug_author = 'Father Barry';
$eplug_url = 'http://www.keal.me.uk';
$eplug_email = '';
$eplug_description = BIRTHDAY_P04;
$eplug_compatible = 'e107v7';
$eplug_readme = 'admin_readme.php'; // leave blank if no readme file
$eplug_compliant = true;
// Name of the plugin's folder -------------------------------------------------------------------------------------
$eplug_folder = 'birthday';
// Name of menu item for plugin ----------------------------------------------------------------------------------
$eplug_menu_name = 'Birthday Menu';
// Name of the admin configuration file --------------------------------------------------------------------------
$eplug_conffile = 'admin_config.php';
// Icon image and caption text ------------------------------------------------------------------------------------
$eplug_icon = $eplug_folder . '/images/birthday_32.png';
$eplug_icon_small = $eplug_folder . '/images/birthday_16.png';
$eplug_caption = BIRTHDAY_P03;
// List of preferences -----------------------------------------------------------------------------------------------
$eplug_prefs = array(
	"birthday_numdue" => 3,
    "birthday_dformat" => 1,
    "sendEmail" => 0,
    "showAge" => 1,
    "birthday_subject" => "Happy Birthday",
    "birthday_emailfrom" => "sysop",
    "birthday_emailaddr" => "you@example.com",
    "birthday_greeting" => "Hi {NAME} Happy Birthday to you",
    "birthday_lastemail" => 0,
    "birthday_showclass" => 0,
    "birthday_pmfrom" => 1,
    "birthday_usepm" => 0,
    "birthday_usecss" => 0,
    "showAvatar" => 0,
    "birthday_avwidth" => 25,

    );
// List of table names -----------------------------------------------------------------------------------------------
$eplug_table_names = '';
// List of sql requests to create tables -----------------------------------------------------------------------------
$eplug_tables = "";
// Create a link in main menu (yes=TRUE, no=FALSE) -------------------------------------------------------------
$eplug_link = false;
$eplug_link_name = '';
$eplug_link_url = '';
// Text to display after plugin successfully installed ------------------------------------------------------------------
$eplug_done = BIRTHDAY_P01;
// upgrading ... //
$upgrade_add_prefs = '';

$upgrade_remove_prefs = '';

$upgrade_alter_tables = '';

$eplug_upgrade_done = BIRTHDAY_P02;

?>