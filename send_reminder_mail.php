<?php
if (!defined('QA_VERSION')) {
	require_once dirname(empty($_SERVER['SCRIPT_FILENAME']) ? __FILE__ : $_SERVER['SCRIPT_FILENAME']).'/../../qa-include/qa-base.php';
   require_once QA_INCLUDE_DIR.'app/emails.php';
}

define('GA_TAG', 'utm_medium=mail&utm_source=self&utm_campaign=ba-reminder');
$tmp = getNoBestAnswerQuestion(2);

// TODO bad performance
$noBAQuestions = array();
foreach($tmp as $question){
	$noBAQuestions[$question['userid']][] = $question;
}

foreach($noBAQuestions as $userid => $userQuestions) {
	if(count($userQuestions) > 1){

		$params = array();
	 	$body = '';
		$user = getUserInfo($userid);

		$body = '<p>' . $user[0]['handle'] . 'さん、いつも' . qa_opt('site_title') . 'に質問を投稿してくださりありがとうございます。';
		$body .= '次の質問は2個以上の回答が寄せられていますが、ベストアンサーは選択されていません。';
		$body .= '回答してくれた方のためにも、ベストアンサーを選択してください。' . '</p>';
		$body .= '<br>';

		$body .= '<ul>';
		for($i=0;$i<count($userQuestions);$i++){
			$tmpQ = $userQuestions[$i];
			$title = $tmpQ['title'];
			$url = qa_opt('site_url') . $tmpQ['qid'] . '?' . GA_TAG;

			$body .= '<li><a href="' . $url . '">' . $title . '</a></li>';
		}
		$body .= '</ul>';

		$oneAnswers = getNoBestAnswerQuestionByUser($userid, 1, true);
		if(count($oneAnswers) > 1) {
			$body .= '<p>また、次の質問は1つの回答しか寄せられていませんが、もし回答に満足されているなら、ベストアンサーに選択してください。</p>';
			$body .= '<ul>';
			for($i=0;$i<count($oneAnswers);$i++){
					$tmpQ = $oneAnswers[$i];
					$title = $tmpQ['title'];
					$url = qa_opt('site_url') . $tmpQ['qid'] . '?' . GA_TAG;

					$body .= '<li><a href="' . $url . '">' . $title . '</a></li>';
			}
			$body .= '</ul>';
		}

		$body .= '<p>ベストアンサーは、ログイン状態で自分が行った質問にのみ表示される、回答の左側の大きなチェックマークをクリックすると選択できます。';
		$body .= '詳しくは、<a href="http://38qa.net/%E4%BD%BF-%E3%81%84-%E6%96%B9#best-answer" >こちら</a></p>';
		$body .= '<p>これからもご質問お待ちしております。</p>';

		$params['fromemail'] = qa_opt('from_email');
		$params['fromname'] = qa_opt('site_title');

		$params['subject'] = '【' . qa_opt('site_title') . '】ベストアンサーを選択してください';
//		$params['body'] = $body;
		$params['htmlbody'] = $body;
		$params['toname'] = $user[0]['handle'];
		$params['toemail'] = $user[0]['email'];
		$params['html'] = true;

		sendEmail($params);
	}
}

function getUserInfo($userid) {
	$sql = 'select email,handle from qa_users where userid=' . $userid;
	$result = qa_db_query_sub($sql);
	return qa_db_read_all_assoc($result);
}

function sendEmail($params){
	echo $params['toemail'];
	qa_send_email($params);
}
