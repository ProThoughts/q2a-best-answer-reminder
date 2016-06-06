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

// admin options
qa_register_plugin_module('module', 'q2a-ba-reminder-admin.php', 'q2a_ba_reminder_admin', 'q2a Best Answer Reminder Admin');

function getNoBestAnswerQuestion($minAnswerCount, $equal = false){
	$sql = "select t1.userid as userid, t1.postid as qid, t1.title as title, count(t1.postid) as answer_num  from qa_posts t1  join qa_posts t2 on t1.postid = t2.parentid  where t1.userid is not null and t1.type = 'Q' and t1.selchildid is null and t2.type='A' group by t1.postid ";
	$sql .= "having answer_num ";
	if($equal) {
		$sql .= "=";
	} else {
		$sql .= ">=";
	}
	$sql .= $minAnswerCount;
	$sql .= " order by t1.userid;";
	$result = qa_db_query_sub($sql);
	return qa_db_read_all_assoc($result);
}

function getNoBestAnswerQuestionByUser($userid, $minAnswerCount, $equal = false) {

	$sql = "select t1.postid as qid, t1.title, count(t1.postid) as answer_num ";
	$sql .= "from qa_posts t1  join qa_posts t2 on t1.postid = t2.parentid ";
	$sql .= "where t1.userid = " . $userid;
	$sql .= " and t1.type = 'Q' and t1.selchildid is null and t2.type='A' group by t1.postid ";
	$sql .= " having answer_num ";
	if($equal) {
		$sql .= "=";
	} else {
		$sql .= ">=";
	}
	$sql .= $minAnswerCount;

	$result = qa_db_query_sub($sql);
	return qa_db_read_all_assoc($result);
}
