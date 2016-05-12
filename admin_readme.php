<?php
/*
+---------------------------------------------------------------+
|        Birthday Menu for e107 v7xx - by Father Barry
|
|        This module for the e107 .7+ website system
|        Copyright Barry Keal 2004-2008
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
|
+---------------------------------------------------------------+
*/
require_once("../../class2.php");
if (!defined('e107_INIT'))
{
    exit;
}
if (!getperms("P"))
{
    header("location:" . e_HTTP . "index.php");
    exit;
}
include_lan(e_PLUGIN . "birthday/languages/readme/" . e_LANGUAGE . ".php");
require_once("plugin.php");
require_once(e_ADMIN . "auth.php");
if (!defined('ADMIN_WIDTH'))
{
    define(ADMIN_WIDTH, "width:100%;");
}
$welcome_text = "
<table class='fborder' style='" . ADMIN_WIDTH . "'>
	<tr>
		<td class='fcaption' colspan='2'>" . BIRTHDAY_RR01 . "</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:15%;' >" . BIRTHDAY_RR02 . "</td>
		<td class='forumheader3'>" .$eplug_name . "&nbsp;</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:15%;' >" . BIRTHDAY_RR04 . "</td>
		<td class='forumheader3'>" . $eplug_author . "</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:15%;' >" . BIRTHDAY_RR06 . "</td>
		<td class='forumheader3'>" .$eplug_version . "&nbsp;</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:15%;' >" . BIRTHDAY_RR08 . "</td>
		<td class='forumheader3'>" . BIRTHDAY_RR09 . "</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:15%;' >" . BIRTHDAY_RR10 . "</td>
		<td class='forumheader3'>" . BIRTHDAY_RR11 . "</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:15%;' >" . BIRTHDAY_RR12 . "</td>
		<td class='forumheader3'>" . BIRTHDAY_RR13 . "</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:15%;' >" . BIRTHDAY_RR14 . "</td>
		<td class='forumheader3'>" . BIRTHDAY_RR15 . "</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:15%;' >" . BIRTHDAY_RR16 . "</td>
		<td class='forumheader3'>" . BIRTHDAY_RR17 . "</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:15%;' >" . BIRTHDAY_RR25 . "</td>
		<td class='forumheader3'><span style='color:#ff4444;'>" . BIRTHDAY_RR24 . "</span></td>
	</tr>
	<tr>
		<td class='forumheader3' colspan='2'>
		<strong>" . BIRTHDAY_RR18 . "</strong><br /><br />" . BIRTHDAY_RR19 . "<br /><br />
		<strong>" . BIRTHDAY_RR20 . "</strong><br /><br />" . BIRTHDAY_RR21 . "<br /><br />
		<strong>" . BIRTHDAY_RR22 . "</strong><br /><br />" . BIRTHDAY_RR23 . "
		</td>
	</tr>
	<tr>
		<td class='fcaption' colspan='2'>&nbsp;</td>
	</tr>
</table>";
// readme;
$ns->tablerender(BIRTHDAY_RR01, $welcome_text);
require_once(e_ADMIN . "footer.php");

?>