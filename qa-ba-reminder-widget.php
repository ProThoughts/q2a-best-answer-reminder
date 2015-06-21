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
		$list = $this->getNoBestAnswerQuestion($userid);
		if(count($list) === 0){
			return;
		}

		echo '<h2>ベストアンサーを選択してください</h2>';
		echo '<p>以下の質問には、複数の回答が寄せられていますが、まだベストアンサーが選択されていません。</p>';
		echo '<ul>';
		foreach($list as $question) {
			echo '<li><a href="' . qa_opt('site_url') . $question['qid'] . '" >'. $question['title'] . '</a></li>';
		}
		echo '</ul>';
		
	}

	function getNoBestAnswerQuestion($userid) {


		$sql = "select t1.postid as qid, t1.title, count(t1.postid) as answer_num " . 
			"from qa_posts t1  join qa_posts t2 on t1.postid = t2.parentid ". 
			"where t1.userid = " . $userid . " and t1.type = 'Q' and t1.selchildid is null and t2.type='A' group by t1.postid having answer_num >1";
		$result = qa_db_query_sub($sql); 
		return qa_db_read_all_assoc($result);
	}

}
