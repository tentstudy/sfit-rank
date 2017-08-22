'use strict';
var app = new Vue({
	el: '#check-hashtag-and-bad-word-filter',
	data: {
		groupId: '1892710067642670',
		interval: '',
		query: {},
		hashTag: ['#socola'],
		listPostsNoHashTag: []
	},
	methods: {
		start: function() {
			app.resetData(30);
			app.getListPosts();
		},
		resetData: function(interval) {
			app.interval = interval;
			let since = `-${interval} days`;
			app.query = {
                getListPosts: `feed.since(${since}).limit(200){id,from,message}`,
                listCommentsInPost: `comments.limit(1000).since(${since}){comments.limit(0).summary(true),from{id}}`,
                listCommentsInComment: '',
                listReactionInPost: `reactions.limit(1000).since(${since}){id}`
            };
		},
		getListPosts: function() {
			fb.graph(app.groupId, app.query.getListPosts, function(listPosts) {
				if (!listPosts.length){
					return;
				}
				listPosts.forEach(function(post) {
					app.listPostsNoHashTag.push(post);
				});
			}, function() {
				
			}, 'v2.3');
		},
		checkHashTag: function(message) {
			
		}
	}
});
var fb;
$(function() {
	fb = new FB('./');
	fb.setToken('EAACW5Fg5N2IBAP3RlQh6vMgkdWGcoqJxZCJtNTNMyS5lVzGZClYLwe00rjXR8ixSfTGsZClZAtLXbWv0cMsxjpAsMH4noSOx6E2DDFGQXx91Jxp1KoXK4RIR0CgolTzGn8dxHMFcuAntZAPZBcJDkRTyFmbJxbSMm3QotPgJnXCZBfI49QiCZCojlOBrgt3A7N8ZD');
	app.start();
});