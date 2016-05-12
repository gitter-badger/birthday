<?php

class bdayGraph{
	private $prefs = '';
    function __construct(){
    	$this->prefs = e107::getPlugPref('birthday', '', true);
    	$this->cache = new ecache;
    	$this->sql = e107::getDb();
    	$this->tp = e107::getParser();
    	$this->frm = e107::getForm();
    	$this->ns = e107::getRender();
    #	$this->sc = new birthday_shortcodes;
    #	$this->template = new bdayTemplate();
    }
	function cron(){

	}
}
?>