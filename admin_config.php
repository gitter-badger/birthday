<?php
/*
   +---------------------------------------------------------------+
   |        Birthday Menu for e107 v2xx - by Father Barry G4HDU
   |
   |        This module for the e107 2+ website system
   |        Copyright Barry Keal 2004-2015
   |
   |        Released under the terms and conditions of the
   |        GNU General Public License (http://gnu.org).
   |
   +---------------------------------------------------------------+
*/
// ***************************************************************
// *
// *		Plugin		:	Birthday Menu (e107 v2)
// *
// ***************************************************************
	require_once("../../class2.php");
if (!getperms("P")) { header("location:".e_BASE."index.php"); exit; }


class plugin_birthday_admin extends e_admin_dispatcher
{
	/**
	 * Format: 'MODE' => array('controller' =>'CONTROLLER_CLASS'[, 'index' => 'list', 'path' => 'CONTROLLER SCRIPT PATH', 'ui' => 'UI CLASS NAME child of e_admin_ui', 'uipath' => 'UI SCRIPT PATH']);
	 * Note - default mode/action is autodetected in this order:
	 * - $defaultMode/$defaultAction (owned by dispatcher - see below)
	 * - $adminMenu (first key if admin menu array is not empty)
	 * - $modes (first key == mode, corresponding 'index' key == action)
	 * @var array
	 */
	protected $modes = array(
		'main'		=> array('controller' => 'plugin_birthday_admin_ui',
		'path' 		=> null,
		'ui' 		=> 'plugin_birthday_admin_form_ui', 'uipath' => null)
	);

	/* Both are optional
	   protected $defaultMode = null;
	   protected $defaultAction = null;
	*/

	/**
	 * Format: 'MODE/ACTION' => array('caption' => 'Menu link title'[, 'url' => '{e_PLUGIN}blank/admin_config.php', 'perm' => '0']);
	 * Additionally, any valid e107::getNav()->admin() key-value pair could be added to the above array
	 * @var array
	 */
	protected $adminMenu = array(
		'main/prefs' 		=> array('caption'=> 'Settings', 'perm' => '0')
	);

	/**
	 * Optional, mode/action aliases, related with 'selected' menu CSS class
	 * Format: 'MODE/ACTION' => 'MODE ALIAS/ACTION ALIAS';
	 * This will mark active main/list menu item, when current page is main/edit
	 * @var array
	 */
	protected $adminMenuAliases = array(
		'main/edit'	=> 'main/list'
	);

	/**
	 * Navigation menu title
	 * @var string
	 */
	protected $menuTitle = BIRTHDAY_ADMIN_A20;
}



class plugin_birthday_admin_ui extends e_admin_ui
{
	// required
	protected $pluginTitle = BIRTHDAY_ADMIN_A20; #"e107 blank";

	/**
	 * plugin name or 'core'
	 * IMPORTANT: should be 'core' for non-plugin areas because this
	 * value defines what CONFIG will be used. However, I think this should be changed
	 * very soon (awaiting discussion with Cam)
	 * Maybe we need something like $prefs['core'], $prefs['blank'] ... multiple getConfig support?
	 *
	 * @var string
	 */
	protected $pluginName = 'birthday';

	/**
	 * DB Table, table alias is supported
	 * Example: 'r.blank'
	 * @var string
	 */
#	protected $table = "blank";

	/**
	 * If present this array will be used to build your list query
	 * You can link fileds from $field array with 'table' parameter, which should equal to a key (table) from this array
	 * 'leftField', 'rightField' and 'fields' attributes here are required, the rest is optional
	 * Table alias is supported
	 * Note:
	 * - 'leftTable' could contain only table alias
	 * - 'leftField' and 'rightField' shouldn't contain table aliases, they will be auto-added
	 * - 'whereJoin' and 'where' should contain table aliases e.g. 'whereJoin' => 'AND u.user_ban=0'
	 *
	 * @var array [optional] table_name => array join parameters
	 */
#	protected $tableJoin = array(
		//'u.user' => array('leftField' => 'comment_author_id', 'rightField' => 'user_id', 'fields' => '*'/*, 'leftTable' => '', 'joinType' => 'LEFT JOIN', 'whereJoin' => '', 'where' => ''*/)
#	);

	/**
	 * This is only needed if you need to JOIN tables AND don't wanna use $tableJoin
	 * Write your list query without any Order or Limit.
	 *
	 * @var string [optional]
	 */
#	protected $listQry = "";
	//

	// optional - required only in case of e.g. tables JOIN. This also could be done with custom model (set it in init())
	//protected $editQry = "SELECT * FROM #blank WHERE blank_id = {ID}";

	// required - if no custom model is set in init() (primary id)
#	protected $pid = "blank_id";

	// optional
#	protected $perPage = 20;

	// default - true - TODO - move to displaySettings
#	protected $batchDelete = true;

	// UNDER CONSTRUCTION
#	protected $displaySettings = array();

	// UNDER CONSTRUCTION
#	protected $disallowPages = array('main/create', 'main/prefs');

	//TODO change the blank_url type back to URL before blank.
	// required
	/**
	 * (use this as starting point for wiki documentation)
	 * $fields format  (string) $field_name => (array) $attributes
	 *
	 * $field_name format:
	 * 	'table_alias_or_name.field_name.field_alias' (if JOIN support is needed) OR just 'field_name'
	 * NOTE: Keep in mind the count of exploded data can be 1 or 3!!! This means if you wanna give alias
	 * on main table field you can't omit the table (first key), alternative is just '.' e.g. '.field_name.field_alias'
	 *
	 * $attributes format:
	 * 	- title (string) Human readable field title, constant name will be accpeted as well (multi-language support
	 *
	 *  - type (string) null (means system), number, text, dropdown, url, image, icon, datestamp, userclass, userclasses, user[_name|_loginname|_login|_customtitle|_email],
	 *    boolean, method, ip
	 *  	full/most recent reference list - e_form::renderTableRow(), e_form::renderElement(), e_admin_form_ui::renderBatchFilter()
	 *  	for list of possible read/writeParms per type see below
	 *
	 *  - data (string) Data type, one of the following: int, integer, string, str, float, bool, boolean, model, null
	 *    Default is 'str'
	 *    Used only if $dataFields is not set
	 *  	full/most recent reference list - e_admin_model::sanitize(), db::_getFieldValue()
	 *  - dataPath (string) - xpath like path to the model/posted value. Example: 'dataPath' => 'prefix/mykey' will result in $_POST['prefix']['mykey']
	 *  - primary (boolean) primary field (obsolete, $pid is now used)
	 *
	 *  - help (string) edit/create table - inline help, constant name will be accpeted as well, optional
	 *  - note (string) edit/create table - text shown below the field title (left column), constant name will be accpeted as well, optional
	 *
	 *  - validate (boolean|string) any of accepted validation types (see e_validator::$_required_rules), true == 'required'
	 *  - rule (string) condition for chosen above validation type (see e_validator::$_required_rules), not required for all types
	 *  - error (string) Human readable error message (validation failure), constant name will be accepted as well, optional
	 *
	 *  - batch (boolean) list table - add current field to batch actions, in use only for boolean, dropdown, datestamp, userclass, method field types
	 *    NOTE: batch may accept string values in the future...
	 *  	full/most recent reference type list - e_admin_form_ui::renderBatchFilter()
	 *
	 *  - filter (boolean) list table - add current field to filter actions, rest is same as batch
	 *
	 *  - forced (boolean) list table - forced fields are always shown in list table
	 *  - nolist (boolean) list table - don't show in column choice list
	 *  - noedit (boolean) edit table - don't show in edit mode
	 *
	 *  - width (string) list table - width e.g '10%', 'auto'
	 *  - thclass (string) list table header - th element class
	 *  - class (string) list table body - td element additional class
	 *
	 *  - readParms (mixed) parameters used by core routine for showing values of current field. Structure on this attribute
	 *    depends on the current field type (see below). readParams are used mainly by list page
	 *
	 *  - writeParms (mixed) parameters used by core routine for showing control element(s) of current field.
	 *    Structure on this attribute depends on the current field type (see below).
	 *    writeParams are used mainly by edit page, filter (list page), batch (list page)
	 *
	 * $attributes['type']->$attributes['read/writeParams'] pairs:
	 *
	 * - null -> read: n/a
	 * 		  -> write: n/a
	 *
	 * - dropdown -> read: 'pre', 'post', array in format posted_html_name => value
	 * 			  -> write: 'pre', 'post', array in format as required by e_form::selectbox()
	 *
	 * - user -> read: [optional] 'link' => true - create link to user profile, 'idField' => 'author_id' - tells to renderValue() where to search for user id (used when 'link' is true and current field is NOT ID field)
	 * 				   'nameField' => 'comment_author_name' - tells to renderValue() where to search for user name (used when 'link' is true and current field is ID field)
	 * 		  -> write: [optional] 'nameField' => 'comment_author_name' the name of a 'user_name' field; 'currentInit' - use currrent user if no data provided; 'current' - use always current user(editor); '__options' e_form::userpickup() options
	 *
	 * - number -> read: (array) [optional] 'point' => '.', [optional] 'sep' => ' ', [optional] 'decimals' => 2, [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY'
	 * 			-> write: (array) [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY', [optional] 'maxlength' => 50, [optional] '__options' => array(...) see e_form class description for __options format
	 *
	 * - ip		-> read: n/a
	 * 			-> write: [optional] element options array (see e_form class description for __options format)
	 *
	 * - text -> read: (array) [optional] 'htmltruncate' => 100, [optional] 'truncate' => 100, [optional] 'pre' => '', [optional] 'post' => ' px'
	 * 		  -> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 255), [optional] '__options' => array(...) see e_form class description for __options format
	 *
	 * - textarea 	-> read: (array) 'noparse' => '1' default 0 (disable toHTML text parsing), [optional] 'bb' => '1' (parse bbcode) default 0,
	 * 								[optional] 'parse' => '' modifiers passed to e_parse::toHTML() e.g. 'BODY', [optional] 'htmltruncate' => 100,
	 * 								[optional] 'truncate' => 100, [optional] 'expand' => '[more]' title for expand link, empty - no expand
	 * 		  		-> write: (array) [optional] 'rows' => '' default 15, [optional] 'cols' => '' default 40, [optional] '__options' => array(...) see e_form class description for __options format
	 * 								[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
	 *
	 * - bbarea -> read: same as textarea type
	 * 		  	-> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 0),
	 * 				[optional] 'size' => [optional] - medium, small, large - default is medium,
	 * 				[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
	 *
	 * - image -> read: [optional] 'title' => 'SOME_LAN' (default - LAN_PREVIEW), [optional] 'pre' => '{e_PLUGIN}myplug/images/',
	 * 				'thumb' => 1 (true) or number width in pixels, 'thumb_urlraw' => 1|0 if true, it's a 'raw' url (no sc path constants),
	 * 				'thumb_aw' => if 'thumb' is 1|true, this is used for Adaptive thumb width
	 * 		   -> write: (array) [optional] 'label' => '', [optional] '__options' => array(...) see e_form::imagepicker() for allowed options
	 *
	 * - icon  -> read: [optional] 'class' => 'S16', [optional] 'pre' => '{e_PLUGIN}myplug/images/'
	 * 		   -> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
	 *
	 * - datestamp  -> read: [optional] 'mask' => 'long'|'short'|strftime() string, default is 'short'
	 * 		   		-> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
	 *
	 * - url	-> read: [optional] 'pre' => '{ePLUGIN}myplug/'|'http://somedomain.com/', 'truncate' => 50 default - no truncate, NOTE:
	 * 			-> write:
	 *
	 * - method -> read: optional, passed to given method (the field name)
	 * 			-> write: optional, passed to given method (the field name)
	 *
	 * - hidden -> read: 'show' => 1|0 - show hidden value, 'empty' => 'something' - what to be shown if value is empty (only id 'show' is 1)
	 * 			-> write: same as readParms
	 *
	 * - upload -> read: n/a
	 * 			-> write: Under construction
	 *
	 * Special attribute types:
	 * - method (string) field name should be method from the current e_admin_form_ui class (or its extension).
	 * 		Example call: field_name($value, $render_action, $parms) where $value is current value,
	 * 		$render_action is on of the following: read|write|batch|filter, parms are currently used paramateres ( value of read/writeParms attribute).
	 * 		Return type expected (by render action):
	 * 			- read: list table - formatted value only
	 * 			- write: edit table - form element (control)
	 * 			- batch: either array('title1' => 'value1', 'title2' => 'value2', ..) or array('singleOption' => '<option value="somethig">Title</option>') or rendered option group (string '<optgroup><option>...</option></optgroup>'
	 * 			- filter: same as batch
	 * @var array
	 */
#	protected  $fields = array(
#		'checkboxes'				=> array('title'=> '', 					'type' => null,			'data' => null,			'width'=>'5%', 		'thclass' =>'center', 'forced'=> TRUE,  'class'=>'center', 'toggle' => 'e-multiselect'),
#		'blank_id'					=> array('title'=> LAN_ID, 					'type' => 'number',		'data' => 'int',		'width'=>'5%',		'thclass' => '',  'class'=>'center',	'forced'=> TRUE, 'primary'=>TRUE/*, 'noedit'=>TRUE*/), //Primary ID is not editable
#		'blank_icon'				=> array('title'=> LAN_ICON, 			'type' => 'icon',		'data' => 'str',		'width'=>'5%',		'thclass' => '',	'forced'=> TRUE, 'primary'=>TRUE/*, 'noedit'=>TRUE*/), //Primary ID is not editable
#		'blank_type'	   			=> array('title'=> LAN_TYPE, 				'type' => 'method', 	'data' => 'str',		'width'=>'auto',	'thclass' => '', 'batch' => TRUE, 'filter'=>TRUE),
#		'blank_folder' 				=> array('title'=> 'Folder', 			'type' => 'text', 		'data' => 'str',		'width' => 'auto',	'thclass' => ''),
#		'blank_name' 				=> array('title'=> 'Name', 				'type' => 'text', 		'data' => 'str',		'width' => 'auto',	'thclass' => ''),
#		'blank_version' 			=> array('title'=> 'Version',			'type' => 'number', 		'data' => 'str',		'width' => 'auto',	'thclass' => ''),
#		'blank_author' 				=> array('title'=> LAN_AUTHOR,			'type' => 'text', 		'data' => 'str',		'width' => 'auto',	'thclass' => 'left'),
#		'blank_authorURL' 			=> array('title'=> "Url", 				'type' => 'url', 		'data' => 'str',		'width' => 'auto',	'thclass' => 'left'),
#		'blank_date' 				=> array('title'=> LAN_DATE, 			'type' => 'datestamp', 	'data' => 'int',		'width' => 'auto',	'thclass' => '', 'readParms' => 'long', 'writeParms' => 'type=datetime'),
#		'blank_compatibility' 		=> array('title'=> 'Compatible',			'type' => 'text', 		'data' => 'str',		'width' => '10%',	'thclass' => 'center' ),
#		'blank_url' 				=> array('title'=> LAN_URL,		'type' => 'file', 		'data' => 'str',		'width' => '20%',	'thclass' => 'center',	'batch' => TRUE, 'filter'=>TRUE, 'parms' => 'truncate=30', 'validate' => false, 'help' => 'Enter blank URL here', 'error' => 'please, ener valid URL'),
#		'test_list_1'				=> array('title'=> 'test 1',			'type' => 'boolean', 		'data' => 'int',		'width' => '5%',	'thclass' => 'center',	'batch' => TRUE, 'filter'=>TRUE, 'noedit' => true),
#		'options' 					=> array('title'=> LAN_OPTIONS, 		'type' => null, 		'data' => null,			'width' => '10%',	'thclass' => 'center last', 'class' => 'center last', 'forced'=>TRUE)
#	);

	//required - default column user prefs
#	protected $fieldpref = array('checkboxes', 'blank_id', 'blank_type', 'blank_url', 'blank_compatibility', 'options');

	// FORMAT field_name=>type - optional if fields 'data' attribute is set or if custom model is set in init()
	/*protected $dataFields = array();*/

	// optional, could be also set directly from $fields array with attributes 'validate' => true|'rule_name', 'rule' => 'condition_name', 'error' => 'Validation Error message'
	/*protected  $validationRules = array(
	   'blank_url' => array('required', '', 'blank URL', 'Help text', 'not valid error message')
	   );*/

	// optional, if $pluginName == 'core', core prefs will be used, else e107::getPluginConfig($pluginName);
	protected $prefs = array(
		'birthday_numdue'				    => array('title'=> "Date Format", 'tab'=>0,'type'=>'dropdown','writeParms'=>array(
				'1'=>'1',
				'2'=>'2',
				'3'=>'3',
				'4'=>'4',
				'5'=>'5',
				'6'=>'6',
				'7'=>'7',
				'8'=>'8',
				'9'=>'9',
				'10'=>'10')),
		'birthday_dformat'				    => array('title'=> "Date Format", 'tab'=>0,'type'=>'dropdown',
			'writeParms'=>array(
				'0'=>'d M',
				'1'=>'d M Y',
				'2'=>'M d',
				'3'=>'M d Y',
				'4'=>'Y M d',
				'5'=>'d mmm Y',
				'6'=>'d MMM Y',
				'7'=>'mmm d Y',
				'8'=>'MMM d Y',
				'9'=>'dth mmm Y',
				'10'=>'dth MMM Y',

				'11'=>'mmm dth Y',
				'12'=>'MMM dth Y',
				'13'=>'d mmm ',
				'14'=>'d MMM ',
				'15'=>'mmm d ',
				'16'=>'MMM d ',
				'17'=>'dth mmm ',
				'18'=>'dth MMM ',
				'19'=>'mmm dth ',
				'20'=>'MMM dth ') ),
		'showAge' 			=> array('title'=> 'Show members age', 'type' => 'boolean', 'data' => 'integer'),
		'linkUser' 			=> array('title'=> 'Show link to member', 'type' => 'boolean', 'data' => 'integer'),

		'sendEmail' 		=> array('title'=> 'Send Email', 'type' => 'boolean', 'data' => 'integer'),

		'birthday_emailfrom' 		=> array('title'=> 'Email sent from (name)', 'type' => 'text', 'data' => 'string', 'validate' => 'regex', 'rule' => '#^[\w]+$#i', 'help' => 'allowed characters are a-zA-Z and underscore'),
		'birthday_emailaddr' 		=> array('title'=> 'Email sent from (address)', 'type' => 'email', 'data' => 'string', 'help' => 'Send email as this email address'),
		'birthday_lastemail' 		=> array('title'=> 'Last email time', 'type' => 'text', 'data' => 'string', 'validate' => 'regex', 'rule' => '#^[\w]+$#i', 'help' => 'allowed characters are a-zA-Z and underscore'),
		'birthday_sendpm' 			=> array('title'=> 'Send PM', 'type' => 'boolean', 'data' => 'integer'),
		'birthday_pmfrom' 			=> array('title'=> 'Send PM from', 'type' => 'user', 'data' => 'string',  'help' => 'allowed characters are a-zA-Z and underscore'),
		'birthday_showclass' 		=> array('title'=> "Menu visible to class", 'tab'=>1, 'type'=>'userclass', 'writeParms'=>'default=254&classlist=public,member,main,admin,classes,no-excludes' ),
		'birthday_includeclass' 	=> array('title'=> "Show members of class", 'tab'=>1, 'type'=>'userclass', 'writeParms'=>'default=255&classlist=main,admin,classes,no-excludes' ),
		'birthday_excludeclass' 	=> array('title'=> "Hide members of class", 'tab'=>1, 'type'=>'userclass', 'writeParms'=>'default=255&classlist=main,admin,classes,no-excludes' ),
		'birthday_usecss' 			=> array('title'=> 'Use CSS in email', 'type' => 'boolean', 'data' => 'integer'),
		'showAvatar' 			=> array('title'=> 'Show avatar', 'type' => 'boolean', 'data' => 'integer'),
		'birthday_avwidth'	   		=> array('title'=> 'Avatar Width', 'type'=>'number', 'data' => 'string', 'min'=>'1','max'=>'10','required'=>'required','help' => 'Width of avatar (px) 0 no avatar'),

	);
#	<pref name="birthday_numdue">3</pref>
#	<pref name="birthday_dformat">1</pref>
#	<pref name="sendEmail">0</pref>
#	<pref name="showAge">1</pref>
#	<pref name="birthday_subject">Happy Birthday</pref>
#	<pref name="birthday_emailfrom">sysop</pref>
#	<pref name="birthday_emailaddr">you@example.com</pref>
#	<pref name="birthday_greeting">Hi {NAME} Happy Birthday to you</pref>
#	<pref name="birthday_lastemail">0</pref>
#	<pref name="birthday_showclass">0</pref>
#	<pref name="birthday_pmfrom">0</pref>
#	<pref name="birthday_usecss">0</pref>
#	<pref name="birthday_avwidth">25</pref>
	// optional
	public function init()
	{
	}


	public function customPage()
	{
		$ns = e107::getRender();
		$text = "Hello World!";
		$ns->tablerender("Hello",$text);

	}
}

class plugin_birthday_admin_form_ui extends e_admin_form_ui
{

	function blank_type($curVal,$mode) // not really necessary since we can use 'dropdown' - but just an example of a custom function.
	{
		$frm = e107::getForm();

		$types = array('type_1'=>"Type 1", 'type_2' => 'Type 2');

		if($mode == 'read')
		{
			return vartrue($types[$curVal]).' (custom!)';
		}

		if($mode == 'batch') // Custom Batch List for blank_type
		{
			return $types;
		}

		if($mode == 'filter') // Custom Filter List for blank_type
		{
			return $types;
		}

		return $frm->select('blank_type', $types, $curVal);
	}

}


/*
   * After initialization we'll be able to call dispatcher via e107::getAdminUI()
   * so this is the first we should do on admin page.
   * Global instance variable is not needed.
   * NOTE: class is auto-loaded - see class2.php __autoload()
*/
/* $dispatcher = */

new plugin_birthday_admin();

/*
   * Uncomment the below only if you disable the auto observing above
   * Example: $dispatcher = new plugin_blank_admin(null, null, false);
*/
//$dispatcher->runObservers(true);

require_once(e_ADMIN."auth.php");

/*
   * Send page content
*/
e107::getAdminUI()->runPage();

require_once(e_ADMIN."footer.php");

/* OBSOLETE - see admin_shortcodes::sc_admin_menu()
   function admin_config_adminmenu()
   {
   //global $rp;
   //$rp->show_options();
   e107::getRegistry('admin/blank_dispatcher')->renderMenu();
   }
*/

/* OBSOLETE - done within header.php
   function headerjs() // needed for the checkboxes - how can we remove the need to duplicate this code?
   {
   return e107::getAdminUI()->getHeader();
   }
*/












/*

require_once("../../class2.php");
if (!defined('e107_INIT'))
{
    exit;
}
if (!getperms("P"))
{
    header("location:" . e_BASE . "index.php");
    exit;
}
include_lan(e_PLUGIN . "birthday/languages/" . e_LANGUAGE . "_birthday_mnu.php");

require_once(e_ADMIN . "auth.php");
if (!defined("ADMIN_WIDTH"))
{
    define(ADMIN_WIDTH, "width:100%;");
}
require_once(e_HANDLER . "userclass_class.php");

$caption = BIRTHDAY_LAN_2;
if (e_QUERY == "update")
{
    $pref['birthday_dformat'] = intval($_POST['birthday_dformat']);
    $pref['sendEmail'] = $tp->toDB($_POST['sendEmail']);
    $pref['showAge'] = $tp->toDB($_POST['showAge']);
    $pref['birthday_showdate'] = intval($_POST['birthday_showdate']);
    $pref['birthday_numdue'] = $tp->toDB($_POST['birthday_numdue']);
    $pref['birthday_emailfrom'] = $tp->toDB($_POST['birthday_emailfrom']);
    $pref['birthday_greeting'] = $tp->toDB($_POST['birthday_greeting']);
    $pref['birthday_subject'] = $tp->toDB($_POST['birthday_subject']);
    $pref['birthday_emailaddr'] = $tp->toDB($_POST['birthday_emailaddr']);
    $pref['birthday_showclass'] = $tp->toDB($_POST['birthday_showclass']);
    $pref['birthday_avwidth'] = intval($_POST['birthday_avwidth']);
    $pref['birthday_pmfrom'] = intval($_POST['birthday_pmfrom']);
    $pref['birthday_usepm'] = intval($_POST['birthday_usepm']);
    $pref['birthday_usecss'] = intval($_POST['birthday_usecss']);
    $pref['birthday_include'] = intval($_POST['birthday_include']);
    $pref['birthday_exclude'] = intval($_POST['birthday_exclude']);
    $pref['showAvatar'] = intval($_POST['showAvatar']);
    $pref['birthday_demographic'] = intval($_POST['birthday_demographic']);
    save_prefs();
     $e107cache->clear("nq_bdaymenu");
    $birthday_msg =  BIRTHDAY_ADMIN_A5;
}

$text .= "
<form method='post' action='" . e_SELF . "?update' id='confbday'>
	<table style='" . ADMIN_WIDTH . "' class='fborder' >";
$text .= "
		<tr>
			<td class='fcaption' colspan='2'>" . BIRTHDAY_ADMIN_A1 . "</td>
		</tr>
		<tr>
			<td class='forumheader2' colspan='2'><b>" . $birthday_msg . "</b>&nbsp;</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A2 . "</td>
			<td style='width:70%' class='forumheader3'>
				<input type = 'text' class = 'tbox' name = 'birthday_numdue' value = '" . $tp->toFORM($pref['birthday_numdue']) . "' />
			</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A12 . "</td>
			<td style='width:70%' class='forumheader3'>
				<input type = 'checkbox' class = 'tbox' name = 'showAge' value = '1' ".($pref['showAge'] == "1"?" checked='checked' ":"")." />
			</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A51 . "</td>
			<td style='width:70%' class='forumheader3'>
				<input type = 'checkbox' class = 'tbox' name = 'showAvatar' value = '1' ".($pref['showAvatar'] == "1"?" checked='checked' ":"")." />
			</td>
		</tr>

		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A52 . "</td>
			<td style='width:70%' class='forumheader3'>
				<input type = 'text' size='8' class = 'tbox' name = 'birthday_avwidth' value = '" . $tp->toFORM($pref['birthday_avwidth']) . "' /> px
			</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A64 . "</td>
			<td style='width:70%' class='forumheader3'>" . r_userclass("birthday_demographic", $pref['birthday_demographic'], "off", 'public,nobody,member,admin, classes') . "</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A50 . "</td>
			<td style='width:70%' class='forumheader3'>
				<input type = 'checkbox' class = 'tbox' name = 'birthday_showdate' value = '1' ".($pref['birthday_showdate'] == "1"? " checked='checked' ":"")." />
			</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A4 . "</td>
			<td style='width:70%' class='forumheader3'>
				<select name='birthday_dformat' class='tbox'>
					<option value='0' ".($pref['birthday_dformat']==0?"selected='selected'":"")." >d M</option>
					<option value='1' ".($pref['birthday_dformat']==1?"selected='selected'":"")." >d M Y</option>
					<option value='2' ".($pref['birthday_dformat']==2?"selected='selected'":"")." >M d</option>
					<option value='3' ".($pref['birthday_dformat']==3?"selected='selected'":"")." >M d Y</option>
					<option value='4' ".($pref['birthday_dformat']==4?"selected='selected'":"")." >Y M d</option>

					<option value='5' ".($pref['birthday_dformat']==5?"selected='selected'":"")." >d mmm Y</option>
					<option value='6' ".($pref['birthday_dformat']==6?"selected='selected'":"")." >d MMM Y</option>
					<option value='7' ".($pref['birthday_dformat']==7?"selected='selected'":"")." >mmm d Y</option>
					<option value='8' ".($pref['birthday_dformat']==8?"selected='selected'":"")." >MMM d Y</option>

					<option value='9' ".($pref['birthday_dformat']==9?"selected='selected'":"")." >dth mmm Y</option>
					<option value='10' ".($pref['birthday_dformat']==10?"selected='selected'":"")." >dth MMM Y</option>
					<option value='11' ".($pref['birthday_dformat']==11?"selected='selected'":"")." >mmm dth Y</option>
					<option value='12' ".($pref['birthday_dformat']==12?"selected='selected'":"")." >MMM dth Y</option>

					<option value='13' ".($pref['birthday_dformat']==13?"selected='selected'":"")." >d mmm </option>
					<option value='14' ".($pref['birthday_dformat']==14?"selected='selected'":"")." >d MMM </option>
					<option value='15' ".($pref['birthday_dformat']==15?"selected='selected'":"")." >mmm d </option>
					<option value='16' ".($pref['birthday_dformat']==16?"selected='selected'":"")." >MMM d </option>

					<option value='17' ".($pref['birthday_dformat']==17?"selected='selected'":"")." >dth mmm </option>
					<option value='18' ".($pref['birthday_dformat']==18?"selected='selected'":"")." >dth MMM </option>
					<option value='19' ".($pref['birthday_dformat']==19?"selected='selected'":"")." >mmm dth </option>
					<option value='20' ".($pref['birthday_dformat']==20?"selected='selected'":"")." >MMM dth </option>
		 		</select>
			</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A8 . "</td>
			<td style='width:70%' class='forumheader3'>
				<input type = 'checkbox' class = 'tbox' name = 'sendEmail' value = '1' ".($pref['sendEmail'] == "1"?" checked='checked' ":"")." />
			</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A7 . "</td>
			<td style='width:70%' class='forumheader3'>
				<input type = 'text' size='30' class = 'tbox' name = 'birthday_emailfrom' value = '" . $tp->toFORM($pref['birthday_emailfrom']) . "' />
			</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A10 . "</td>
			<td style='width:70%' class='forumheader3'>
				<input type = 'text' size='30' class = 'tbox' name = 'birthday_subject' value = '" . $tp->toFORM($pref['birthday_subject']) . "' />
			</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A11 . "</td>
			<td style='width:70%' class='forumheader3'>
				<input type = 'text' size='30' class = 'tbox' name = 'birthday_emailaddr' value = '" . $tp->toFORM($pref['birthday_emailaddr']) . "' />
			</td>
		</tr>
		<tr>
			<td  style='width:30%;vertical-align:top;' class='forumheader3'>" . BIRTHDAY_ADMIN_A9 . "</td>
			<td style='width:70%' class='forumheader3'>
				<textarea class = 'tbox' name = 'birthday_greeting' rows='6' cols='70' >" . $tp->toFORM($pref['birthday_greeting']) . "</textarea>
			</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A35 . "</td>
			<td style='width:70%' class='forumheader3'>" . r_userclass("birthday_showclass", $pref['birthday_showclass'], "off", 'public,nobody,member,admin, classes') . "</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A41 . "</td>
			<td style='width:70%' class='forumheader3'>" . r_userclass("birthday_exclude", $pref['birthday_exclude'], "off", "nobody,admin,classes") . "</td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A40 . "</td>
			<td style='width:70%' class='forumheader3'><input type='checkbox' class='tbox' value='1' name='birthday_usecss' " . ($pref['birthday_usecss'] > 0?"checked='checked'":"") . " /></td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A38 . "</td>
			<td style='width:70%' class='forumheader3'><input type='checkbox' class='tbox' value='1' name='birthday_usepm' " . ($pref['birthday_usepm'] > 0?"checked='checked'":"") . " /></td>
		</tr>
		<tr>
			<td style='width:30%' class='forumheader3'>" . BIRTHDAY_ADMIN_A39 . "</td>
			<td style='width:70%' class='forumheader3'>
				<select class='tbox' name='birthday_pmfrom'>";
// Sort out admin/main admin class in selection
if ($sql->db_Select("user", "user_id,user_name", "nowhere", false))
{
    while ($autoassign_row = $sql->db_Fetch())
    {
        extract($autoassign_row);
        $text .= "<option value='$user_id' " . ($user_id == $pref['birthday_pmfrom']?"selected='selected'":"") . ">" . $tp->toFORM($user_name) . "</option>";
    } // while
}
else
{
    $text .= "<option value='0' >Select admin class and save first</option>";
}
// Submit button
$text .= "
				</select>
			</td>
		</tr>
		<tr>
			<td class='forumheader2' colspan='2' style='text-align:left;'><input type='submit' name='update' value='" . BIRTHDAY_ADMIN_A3 . "' class='button' /></td>
		</tr>
		<tr>
			<td class='fcaption' colspan='2'>&nbsp;</td>
		</tr>
	</table>
</form>";
$ns->tablerender($caption, $text);
require_once(e_ADMIN . "footer.php");
*/