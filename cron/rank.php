<pre>
<?php
	$totalTime = strtotime("now");
	require_once '../db/connect.php';
	class Member
	{
		public $rank = '';
		public $id;
		public $name;
		public $post = 0;
		public $comments = array('in' => 0, 'out' => 0);
		public $reactions = array('in' => 0, 'out' => 0);
		public $score = 0;
		function __construct($id, $name)
		{
			$this->id = $id;
			$this->name = $name;
		}
		function commentIn() {
			$this->comments['in']++;
		}
		function commentOut() {
			$this->comments['out']++;
		}
		function reactionIn() {
			$this->reactions['in']++;
		}
		function reactionOut() {
			$this->reactions['out']++;
		}
		function postOut() {
			$this->post++;
		}
		function calculateScore() {
			$this->score = ($this->reactions['in'] + $this->reactions['out']) * 1
			 + ($this->comments['in'] + $this->comments['out']) * 3
			 + $this->post * 5;
			return $this;
		}
	}
	date_default_timezone_set("Asia/Ho_Chi_Minh");
	$time = strtotime("-30 days");
// 	$token       = 'EAACW5Fg5N2IBAATz86ZB0ZB1fWI3CZByluZCMK3bVilKuOeplZAnQZBPZCtA9vrBMPMyQI88RwieSvfZBJltLOGazhOu3ZB5UNIiU886FeTSEGwh9gFPQPZCe5zsPBWrvCWA3GIh44fRFcjoMpQTmJHtgX4bWMwlytPCckrAJs3AN6KE9UQiiIv9ZCiXeVWjwh77Yj1ZCsqWvKRG9H3JgF5nPb7h';
// 	$endPoint    = 'https://graph.facebook.com';
// 	$queryMember = 'members';
	$groupId     = '1796364763915932';

// 	$listMembers = array();
// 	$data = file_get_contents("{$endPoint}/{$groupId}?field={$queryMember}&access_token={$token}");
// 	// echo($data);
// 	echo("{$endPoint}/{$groupId}?field={$queryMember}&access_token={$token}");
// 
	require_once 'FB.php';
	$fb = new FB('./');
	$fb->setAccess_token('EAACW5Fg5N2IBAATz86ZB0ZB1fWI3CZByluZCMK3bVilKuOeplZAnQZBPZCtA9vrBMPMyQI88RwieSvfZBJltLOGazhOu3ZB5UNIiU886FeTSEGwh9gFPQPZCe5zsPBWrvCWA3GIh44fRFcjoMpQTmJHtgX4bWMwlytPCckrAJs3AN6KE9UQiiIv9ZCiXeVWjwh77Yj1ZCsqWvKRG9H3JgF5nPb7h');
	$data = $fb->graph_all($groupId, 'members.limit(500){id,name}', 'members', 'v2.3');
	foreach ($data as $member) {
		$listMembers["_{$member->id}"] = new Member($member->id, $member->name);
	}
	// echo(json_encode($listMembers, JSON_PRETTY_PRINT));
	/*lấy danh sách bài viết*/
	$listPosts = $fb->graph_all($groupId, "feed.since({$time}).limit(200){id,from}", 'feed', 'v2.3');
	foreach ($listPosts as $post) {
		/* cộng điểm cho thằng đăng bài*/
		$listMembers["_{$post->from->id}"]->postOut();
		/* lấy danh sách bình luận*/
		$listComments = $fb->graph_all($post->id, "comments.limit(1000).since({$time}){comments.limit(0).summary(true),from{id}}", 'comments', 'v2.3');
		/* cộng điểm bình luận cho thằng đăng bài và thằng bình luận*/
		foreach ($listComments as $comment) {
			$listMembers["_{$post->from->id}"]->commentIn();
			if(empty($listMembers["_{$comment->from->id}"])){
				$listMembers["_{$comment->from->id}"] = new Member($comment->from->id, $comment->from->id);
			}
			$listMembers["_{$comment->from->id}"]->commentOut();
			/* lấy danh sách những thẳng trả lời bình luận*/
			if ($comment->comments->summary->total_count) {
			}
		}
		/* cộng điểm cho thằng reac và thằng đăng bài*/
		$listReactions = $fb->graph_all($post->id, "reactions.limit(1000).since({$time}){id}", 'reactions', 'v2.6');
		foreach ($listReactions as $reaction) {
			$listMembers["_{$post->from->id}"]->reactionIn();
			$listMembers["_{$reaction->id}"]->reactionOut();
		}
	}
	/*JSON_PRETTY_PRINT*/
	/* tính điểm cho từng thằng*/
	$tg = array();
	foreach ($listMembers as $member) {
		$member->calculateScore();
		$tg[] = $member;
	}
	$listMembers = $tg;
	$jsonString = json_encode(array(
		'members' => $listMembers)
	);
	$totalTime = strtotime("now") - $totalTime;
	echo("hết: {$totalTime}");
	// $data = $db->getInsightGroup('1796364763915932');
	// file_put_contents('zz.txt', json_decode($data)->json);
	// $get = file_get_contents("zz.txt");
	// print_r(json_decode($get));
	// echo(json_encode($listPosts, JSON_PRETTY_PRINT));
	$db->saveInsightGroup($groupId, strtotime("now"), base64_encode($jsonString));
	// print_r(json_decode($data));
?>