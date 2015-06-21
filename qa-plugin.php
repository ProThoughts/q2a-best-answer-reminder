<?php

/*
	Plugin Name: Best Answer Reminder
	Plugin URI: 
	Plugin Description: remind best answer
	Plugin Version: 0.3
	Plugin Date: 2015-06-21
	Plugin Author:
	Plugin Author URI:
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.7
	Plugin Update Check URI: 
*/

if (!defined('QA_VERSION')) {
	header('Location: ../../');
	exit;
}

qa_register_plugin_module(
	'widget',
	'qa-ba-reminder-widget.php',
	'qa_ba_reminder_widget',
	'Best Answer Reminder' 
);

qa_register_plugin_phrases('qa-ba-reminder-lang-*.php', 'qa_ba_reminder_lang');
