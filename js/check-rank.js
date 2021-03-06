'use strict';
var fb;
var icon = {
    loading: 'fa fa-spinner fa-spin',
    endload: 'fa fa-check-circle text-success'
};
var postDontCare = [
    '677222392439615_802643839897469'
];
var rank = {
    score: [],
    setScore: function(minScore) {
        let jumb = 10;
        this.score = [];
        this.score.unshift(0); /* unrank*/
        this.score.unshift(minScore); /* đồng 5*/
        /*đồng 4 3 2 1*/
        for (let i = 4; i >= 1; i--) {
            jumb += 1;
            minScore += jumb;
            rank.score.unshift(minScore);
        }
        for (let i = 1; i <= 4; i++) {
            for (let j = 1; j <= 5; j++) {
                jumb += i;
                minScore += jumb;
                rank.score.unshift(minScore);
            }
        }
        /* cao thủ thách đấu + = 5*/
        jumb += 5;
        minScore += jumb;
        rank.score.unshift(minScore);
        jumb += 5;
        minScore += jumb;
        rank.score.unshift(minScore);
    }
};

var listGroups = new Vue({
    el: '#list-groups',
    data: {
        groups: []
    },
    methods: {
        getListGroups: function() {
            fb.graph('me', 'groups.limit(100){name,icon}', function(res) {
                listGroups.groups = res;
            }, Null, 'v2.3');
        }
    }
});

function User(id, name) {
    this.rank = 27;
    this.id = id;
    this.name = name;
    this.posts = 0;
    this.comments = { in: 0, out: 0 };
    this.reactions = { in: 0, out: 0 };
    this.score = '?';
    this.getData = function() {
        let rank = this.rank;
        let id = this.id;
        let name = this.name;
        let posts = this.posts;
        let comments = this.comments;
        let reactions = this.reactions;
        let score = this.score;
        return { rank, id, name, posts, comments, reactions, score };
    };
    this.commentIn = function() {
        this.comments.in++;
    };
    this.commentOut = function() {
        statistics.comments.total++;
        this.comments.out++;
    };
    this.reactionIn = function() {
        this.reactions.in++;
    };
    this.reactionOut = function() {
        statistics.reactions.total++;
        this.reactions.out++;
    };
    this.postOut = function() {
        this.posts++;
    };
    this.calculateScore = function() {
        this.score = (this.reactions.in + this.reactions.out) * 1 +
            (this.comments.in + this.comments.out) * 3 +
            this.posts * 5;
        return this;
    };
    /* get thôi khỏi set*/
    this.getPost = function() {
    	return this.post;
    };
    this.getCommentOut = function() {
    	return this.comments.out;
    };
    this.getCommentsIn = function() {
    	return this.comments.in;
    };
    this.getReactionsOut = function() {
    	return this.reactions.out;
    };
    this.getReactionsIn = function() {
    	return this.reactions.in;
    };
    return this;
}

function Null() {}
var statistics = new Vue({
    el: '#statistics',
    data: {
        starting: 0,
        interval: '',
        since: 0,
        intervalView: '',
        idGroup: '',
        post: { total: '?', hasScanComments: '?', hasScanReaction: '?' },
        comments: { total: '?', mustCheckRep: 0, hasCheckRep: 0, hasCheckReac: 0 },
        reactions: { total: '?' },
        members: { total: '?', real: '?', list: [], key: {}, active: '?' },
        end: { comments: false, reactions: false, post: false },
        query: {},
        icon: {
            members: '',
            postHasScanComments: '',
            postHasScanReaction: '',
            post: '',
            comments: '',
            reactions: ''
        },
        listPostsDontCare: [],
        score: {post: 5, comment: 3, like: 1, love: 1, haha: 1, wow: 1, sad: 1, angry: 1},
        sort: { post: -1, commnetOut: -1, commentIn: -1, reactionOut: -1, reactionIn: -1, score: 1}
    },
    methods: {
        resetData: function() {
            this.post = { total: 0, hasScanComments: 0, hasScanReaction: 0 };
            this.comments = { total: 0, mustCheckRep: 0, hasCheckRep: 0, hasCheckReac: 0};
            this.reactions = { total: 0 };
            this.members = { total: 0, real: 0, list: [], key: {}, active: '?' };
            this.end = { comments: false, reactions: false, post: false };
            this.sort = { post: -1, commentOut: -1, commentIn: -1, reactionOut: -1, reactionIn: -1, score: 1};
            let since = this.interval === 1 ? `-1 day` : `-${this.interval} days`;
            // let since = (Math.ceil(new Date().getTime()/1000) - this.interval*24*3600);
            this.intervalView = `trong ${this.interval} ngày gần đây`;
            this.query = {
                totalMenbers: 'members.limit(0).summary(true)',
                listMembers: 'members.limit(500)',
                listPosts: `feed.since(${since}).limit(200){id,from,created_time}`,
                listCommentsInPost: `comments.limit(1000).since(${since}){comments.limit(0).summary(true),from{id}}`,
                listCommentsInComment: '',
                listReactionInPost: `reactions.limit(1000).since(${since}){id}`
            };
            this.since *= 1000;
        },
        start: function() {
            if (this.starting) { return; }
            this.starting = true;
            $('#list-members').addClass('disabled');
            statistics.resetData();
            statistics.idGroup = $("#list-groups option:selected").attr('data-id-group');
            // statistics.idGroup = '1796364763915932'; /* clb */
            // statistics.idGroup = '677222392439615'; /* sfit community */
            statistics.getTotalMenbers();
            statistics.getListMembers();
        },
        getTotalMenbers: function() {
            this.members.total = fb.graphA(this.idGroup, this.query.totalMenbers, 'v2.6').members.summary.total_count;
        },
        addMember: function(memberId, memberName) {
            if (typeof statistics.members.key[`_${memberId}`] === 'undefined') {
                statistics.members.key[`_${memberId}`] = statistics.members.real++;
                statistics.members.list.push(new User(memberId, memberName));
            }
            return statistics.members.key[`_${memberId}`];
        },
        getListMembers: function() {
            statistics.icon.members = icon.loading;
            fb.graph(this.idGroup, this.query.listMembers, function(res) {
                res.forEach(function(member) {
                    statistics.addMember(member.id, member.name);
                });
            }, statistics.endGetListMembers, 'v2.3');
        },
        endGetListMembers: function() {
            statistics.icon.members = icon.endload;
            statistics.getListPost();
        },
        getListPost: function() {
            statistics.icon.postHasScanComments = icon.loading;
            statistics.icon.postHasScanReaction = icon.loading;
            statistics.icon.post = icon.loading;
            statistics.icon.comments = icon.loading;
            statistics.icon.reactions = icon.loading;
            fb.graph(statistics.idGroup, statistics.query.listPosts, function(res) {
                if (!res) {
                    return;
                }
                res.forEach(function(post) {
                    if (postDontCare.indexOf(post.id) !== -1) {
                        return;
                    }
                    console.log(post.created_time);
                    statistics.post.total++;
                    let ownPost = statistics.addMember(post.from.id, post.from.id);
                    statistics.members.list[ownPost].postOut();
                    statistics.getListReactionInPost(post.id, ownPost);
                    statistics.getListCommentsInPost(post.id, ownPost);
                });
            }, statistics.endGetListPost, 'v2.3');
        },
        getListReactionInPost: function(postId, ownPost) {
            fb.graph(postId, statistics.query.listReactionInPost, function(listReactions) {
                listReactions.forEach(function(reaction) {
                    if (statistics.members.key[`_${reaction.id}`]) {
                        let ownReac = statistics.addMember(reaction.id, reaction.name);
                        // cộng điểm cho thằng like 
                        statistics.members.list[ownReac].reactionOut();
                        // cộng điểm cho thằng đăng bài
                        if (ownReac !== ownPost) {
                            statistics.members.list[ownPost].reactionIn();
                        }
                    }
                });
            }, statistics.endGetListReactionInPost, 'v2.6');
            statistics.checkEnd();
        },
        getListCommentsInPost: function(postId, ownPost) {
            fb.graph(postId, statistics.query.listCommentsInPost, function(listComments) {
                if (!listComments.length) {
                    return;
                }
                statistics.comments.mustCheckRep += listComments.length;
                listComments.forEach(function(comment) {
                	let ownCmt = statistics.addMember(comment.from.id, comment.from.id);
                	// cộng điểm đã bình luận cho thằng bình luận
                	statistics.members.list[ownCmt].commentOut();
                	// cộng điểm được bình luận cho thằng đăng bài
                	if (ownCmt !== ownPost) {
                		statistics.members.list[ownPost].commentIn();
                	}
                	//lấy danh sách những người là trả lời bình luận của họ
                	if (comment.comments.summary.total_count) {
                		statistics.getCommentsInComment(comment.id, ownPost, ownCmt);
                	} else {
                		statistics.comments.hasCheckRep++;
                	}
                	statistics.getListReactionsInComment(comment.id, ownCmt);
                });
            }, statistics.endGetListCommentsInPost, 'v2.3');
            statistics.checkEnd();
        },
        getCommentsInComment: function(commentId, ownPost, ownCmt) {
        	fb.graph(commentId, 'comments.limit(1000){from{id}}', function(listComments) {
	            listComments.forEach(function(comment) {
	                let ownRep = statistics.addMember(comment.from.id);
	                // cộng điểm cho thằng trả lời bình luận
	                statistics.members.list[ownRep].commentOut();
	                // cộng điểm cho thằng được trả lời bình luận
	                if (ownRep !== ownCmt) {
	                    statistics.members.list[ownCmt].commentIn();
	                }
	                /* cộng điểm cho thằng đăng bài*/
	                if (ownRep !== ownPost) {
	                    statistics.members.list[ownPost].commentIn();
	                }
	                statistics.getListReactionsInComment(commentId, ownCmt);
	            });  	
            }, statistics.endGetCommentsInComment, 'v2.3');
            statistics.checkEnd();
        },
        getListReactionsInComment: function(commentId, ownCmt) {
        	// console.log(commentId);
        	fb.graph(commentId, statistics.query.listReactionInPost, function(listReactions) {
        		if (!listReactions.length) {
        			return;
        		}
        		listReactions.forEach(function(reaction) {
        			// cộng điểm cho thằng reac
        			let ownReac = statistics.addMember(reaction.id, reaction.id);
        			statistics.members.list[ownReac].reactionOut();
        			/* cộng điểm cho thằng bình luận*/
        			statistics.members.list[ownCmt].reactionIn();
        		});
        	}, statistics.endGetListReactionsInComment, 'v2.6');
        	statistics.checkEnd();
        },
        /* end */
        endGetListReactionsInComment: function() {
        	statistics.comments.hasCheckReac++;
        	statistics.checkEnd();
        },
        endGetListPost: function() {
            statistics.end.post = true;
            statistics.icon.post = icon.endload;
            statistics.checkEnd();
        },
        endGetListReactionInPost: function() {
            statistics.post.hasScanReaction++;
            statistics.checkEnd();
        },
        endGetListCommentsInPost: function() {
            statistics.post.hasScanComments++;
            statistics.checkEnd();
        },
        endGetCommentsInComment: function() {
            statistics.comments.hasCheckRep++;
            statistics.checkEnd();
        },
        /* đã test cứng k cần sửa nhiều*/
        saveJSON: function() {
            let members = [];
            statistics.members.list.forEach(function(member) {
                members.push(member.getData());
            });
            let data = {
                g: statistics.idGroup,
                d: JSON.stringify({
                	total: {
                		posts: statistics.post.total,
                		reactions: statistics.reactions.total,
                		comments: statistics.comments.total,
                		activeMembers: statistics.members.active,
                		members: statistics.members.total
                	},
                	members: members
                })
            };
            $.post('save-json.php', data, function(res) {
                console.log(res);
            });
        },
        checkEnd: function() {
        	// let endAll = true;
            if (!statistics.end.post) {
                return;
            }
            if (statistics.post.total === statistics.post.hasScanReaction) {
                statistics.icon.postHasScanReaction = icon.endload;
                statistics.end.reactions = true;
            }
            if (statistics.post.total === statistics.post.hasScanComments) {
                statistics.end.comments = true;
            }
            if (statistics.end.reactions && statistics.end.comments &&
                statistics.comments.mustCheckRep === statistics.comments.hasCheckRep &&
				statistics.comments.hasCheckReac === statistics.comments.total) {
                statistics.icon.postHasScanComments = icon.endload;
                statistics.icon.comments = icon.endload;
                statistics.icon.reactions = icon.endload;
                statistics.endAll();
                statistics.saveJSON();
            }
        },
        endAll: function() {
            let length = statistics.members.list.length;
            for (var i = 0; i < length; i++) {
                statistics.members.list[i].calculateScore();
            }
            /* sắp xếp rank */
            statistics.members.list.sort(function(a, b) {
                /* thứ tự ưu tiên score, post, comment*/
                if(b.score - a.score !== 0){
                    return b.score - a.score;    
                }
                if(b.posts - a.posts){
                    return b.posts - a.posts;
                }
                if(b.comments - a.comments){
                    return b.comments - a.comments;
                }
                return 0;
            });
            /* thêm biểu tượng rank tương ứng*/
            /* lấy điểm thằng nát nhất != 0*/
            let indexMinScore = statistics.members.list.length - 1;
            statistics.members.active = statistics.members.real;
            while (indexMinScore && !statistics.members.list[indexMinScore].score) {
                indexMinScore--;
                statistics.members.active--;
            }
            let minScore = statistics.members.list[indexMinScore].score;
            rank.setScore(minScore);
            let currentRank = 0;
            /* set rank cho từng thằng*/
            statistics.members.list.forEach(function(member, index) {
                while (member.score < rank.score[currentRank]) {
                    currentRank++;
                }
                statistics.members.list[index].rank = currentRank;
            });
            /* giáng cấp của thách đầu ngoài top 5 xuống cao thủ*/
            statistics.members.list.forEach(function(member, index) {
                if(member.rank !== 0){
                    return;
                }
                if(index > 4){
                    statistics.members.list[index].rank = 1;
                }
            });
            statistics.starting = false;
            $('#list-members').removeClass("disabled");
        },
        /* sort*/
        sortPost: function() {
        	this.members.list.sort(function(a, b) { /* giản dần*/
    			return (b.post - a.post)/* * x*/;
    		});
        },
        sortCommentOut: function() {
        	this.members.list.sort(function(a, b) { /* giản dần*/
    			return (b.comments.out - a.comments.out)/* * x*/;
    		});
        },
        sortCommentIn: function() {
        	this.members.list.sort(function(a, b) { /* giản dần*/
    			return (b.comments.in - a.comments.in)/* * x*/;
    		});
        },
        sortReactionOut: function() {
        	this.members.list.sort(function(a, b) { /* giản dần*/
    			return (b.reactions.out - a.reactions.out)/* * x*/;
    		});
        },
        sortReactionIn: function() {
        	this.members.list.sort(function(a, b) { /* giản dần*/
    			return (b.reactions.in - a.reactions.in)/* * x*/;
    		});
        },
        sortScore: function() {
        	this.members.list.sort(function(a, b) { /* giản dần*/
    			return (b.score - a.score)/* * x*/;
    		});
        },
    }
});
$(function construct() { /* khởi tạo các giá trị ban đầu*/
    fb = new FB('../../');
    fb.setToken($.cookie('token'));
    fb.checkLiveToken();
    listGroups.getListGroups();
    // statistics.start();
});
$(function() {
    $.getJSON('../API/get-list-post-dont-care.php', function(listPostsDontCare) {
        statistics.listPostsDontCare = listPostsDontCare;
    });
});