<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Check hash tag and bad word filter</title>
	<link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="./check-hashtag-and-bad-word-filter.js">
</head>
<body id="check-hashtag-and-bad-word-filter">
	<ul>
		<li v-for="post in listPostsNoHashTag">
			{{post.id}} - {{post.message}}
		</li>
	</ul>
</body>
</html>
<script src="/vendor/jquery/jquery.js"></script>
<script src="/vendor/jquery.cookie/jquery.cookie.js"></script>
<script src="/vendor/vue/vue.min.js"></script>
<script src="/vendor/socola.dai.ca/js/fb.js"></script>
<script src="./check-hashtag-and-bad-word-filter.js"></script>