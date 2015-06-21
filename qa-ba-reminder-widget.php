<?php
class qa_ba_reminder_widget {

	function allow_template($template) {
		return true;
	}

	function allow_region($region) {
		return true;
	}
	
	function output_widget($region, $place, $themeobject, $template, $request, $qa_content) {
		$userid = qa_get_logged_in_userid();
		if(!isset($userid)){
			return;
		}

		$minAnswerCount = qa_opt('q2a_ba_reminder_answer_count');
		$list = $this->getNoBestAnswerQuestion($userid, $minAnswerCount);
		if(count($list) === 0){
			return;
		}

		$title = qa_lang_html('qa_ba_reminder_lang/please_select_ba');
		$desc = qa_lang_html('qa_ba_reminder_lang/module_desc');
		$desc = strtr($desc, array(
			'^1' => $minAnswerCount,
		));

		echo '<h2>' . $title . '</h2>';
		echo '<p>' . $desc . '</p>';

		echo '<ul>';
		foreach($list as $question) {
			echo '<li><a href="' . qa_opt('site_url') . $question['qid'] . '" >'. $question['title'] . '</a></li>';
		}
		echo '</ul>';
		
	}

	function getNoBestAnswerQuestion($userid, $minAnswerCount) {


		$sql = "select t1.postid as qid, t1.title, count(t1.postid) as answer_num " . 
			"from qa_posts t1  join qa_posts t2 on t1.postid = t2.parentid ". 
			"where t1.userid = " . $userid . " and t1.type = 'Q' and t1.selchildid is null and t2.type='A' group by t1.postid having answer_num >=" . $minAnswerCount;
		$result = qa_db_query_sub($sql); 
		return qa_db_read_all_assoc($result);
	}

}
