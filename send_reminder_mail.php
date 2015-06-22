<?php
if (!defined('QA_VERSION')) { 
	require_once dirname(empty($_SERVER['SCRIPT_FILENAME']) ? __FILE__ : $_SERVER['SCRIPT_FILENAME']).'/../../qa-include/qa-base.php';
   require_once QA_INCLUDE_DIR.'app/emails.php';
}

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

		$body = $user[0]['handle'] . 'さん、いつも' . qa_opt('site_title') . 'に質問を投稿してくださりありがとうございます。';
		$body .= '次の質問は2個以上の回答が寄せられていますが、ベストアンサーは選択されていません。'; 
		$body .= '回答してくれた方のためにも、ベストアンサーを選択してください。' . "\n";
		$body .= "\n";
		for($i=0;$i<count($userQuestions);$i++){
			$tmpQ = $userQuestions[$i];
  
			$body .= "・" . $tmpQ['title'] . "\n";
			$body .= qa_opt('site_url') . $tmpQ['qid']. "\n";
			$body .= "\n";
		}

		$body .= 'ベストアンサーは、回答の左側の大きなチェックマークをクリックすると選択できます。' . "\n";
		$body .= 'これからもご質問お待ちしております。';

		$params['fromemail'] = qa_opt('from_email');
		$params['fromname'] = qa_opt('site_title');

		$params['subject'] = '【' . qa_opt('site_title') . '】ベストアンサーを選択してください';
		$params['body'] = $body;
		$params['toname'] = $user[0]['handle'];
		$params['toemail'] = $user[0]['email'];
		$params['html'] = false;

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
