'use strict';
console.log('ngu');
var fb;
var app = {
	groupId: '677222392439615',
	message: `Bổ sung hashtag nhé \n https://sfit-utc.tentstudy.xyz/hashtags.html`,
	listHashTags: [
		'#sfit_news',
		'#sfit_question',
		'#sfit_discussion',
		'#sfit_share',
		'#sfit_relax	Ảnh',
		'#sfit_suggest',
		'#sfit_job'
	],
	listPostsNohashTag: [],
	query: {
		getListPosts: 'feed.since(-3 minutes).limit(200){id,from,message}',
		comment: ''
	},
	start: function() {
		fb = new FB('./');
		fb.setToken('EAACW5Fg5N2IBAP3RlQh6vMgkdWGcoqJxZCJtNTNMyS5lVzGZClYLwe00rjXR8ixSfTGsZClZAtLXbWv0cMsxjpAsMH4noSOx6E2DDFGQXx91Jxp1KoXK4RIR0CgolTzGn8dxHMFcuAntZAPZBcJDkRTyFmbJxbSMm3QotPgJnXCZBfI49QiCZCojlOBrgt3A7N8ZD');
		app.getListPosts();
	},
	getListPosts: function() {
		fb.graph(app.groupId, app.query.getListPosts, function(listPosts) {
			if (!listPosts.length) {
				return;
			}
			listPosts.forEach(function(post) {
				// console.log(post.message);
				let hasHashTag = false;
				app.listHashTags.forEach(function(hashTag) {
					if(post.message.indexOf(hashTag) !== -1){
						hasHashTag = true;
						return;
					}
				});
				if(!hasHashTag){
					app.remindHashTag(post.id);
				}
			});
		}, Null, 'v2.3');
	},
	remindHashTag: function(postId) {
		/*kiểm tra xem đã từng nhắc hay chưa*/
		$.getJSON('../API/check-remind-hashtag.php', {postId}, function(res) {
			console.log(res);
			if(res.check == 1){
				return;
			}
			fb.comment(postId, app.message);
			console.log(postId);
		});
	}
};

function Null() {

}
app.start();