<?php require_once '../check-login.php'; ?>
<?php
	date_default_timezone_set("Asia/Ho_Chi_Minh");
?>
<!DOCTYPE html>
<html lang="vi-VN">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Document</title>
		<?php require_once '../layout/css.php'; ?>
		<style>
			#choose-file {
				display: none;
			}
			.col-right .list-group {
				max-height: calc(100vh - 90px);
				overflow-x: overlay;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<h1 class="text-center">Nộp bài (<?php echo $userName ?? $_SESSION['name']; ?>)</h1>
			</div>
			<div class="col-md-7 col-xs-8">
				<form action="save-code.php" method="POST" role="form" id="socola" enctype="multipart/form-data">
					<div class="form-group">
						Đề bài: <br>
						Tạo <b>1</b> file html giới thiệu bản thân có ảnh cá nhân
						<!-- <label for="">Chọn bài</label> -->
						<!-- 						<select name="exercises" class="form-control">
							<option value="exercises1">Bai 1</option>
							<option value="exercises2">Bai 2</option>
							<option value="exercises3">Bai 3</option>
							<option value="exercises4">Bai 4</option>
							<option value="exercises5">Bai 5</option>
						</select> -->
						<input type="text" name="exercises" value="1" hidden="true">
					</div>
					<div class="form-group">
						<label for="choose-file">
							<a type="button" class="btn btn-default">
								<span class="glyphicon glyphicon-folder-open"></span>
								Chọn file
							</a>
						</label>
						<!--  accept=".html,.php,.zip,.rar" -->
						<input type="file" name="fileToUpload" id="choose-file" />
						<span id="choosed-file">Không có file nào được chọn</span>
					</div>
					<div class="form-group">
						<button class="btn btn-primary" name="submit" type="submit" id="submit">Nộp bài</button>
					</div>
				</form>
			</div>
			<div class="col-md-5 col-xs-8 col-right">
				<ul class="list-group">
					
					<?php
						$listFile = glob("storage/*{$userId}*");
					?>
					<?php foreach ($listFile as $file): ?>
					<?php $file = explode('_', str_replace(array('storage/','.html'), '', $file)) ?>
					<li class="list-group-item clearfix">
						<div class="pull-left">
							<h5>Bài <?php echo $file[0] ?></h5>
							<small><?php echo date("d/m/Y H:i:s", $file[2]) ?></small>
						</div>
						<!-- <a href="#" target="_blank" class="btn btn-primary pull-right">Tải xuống</a> -->
					</li>
					<?php endforeach ?>
				</ul>
			</div>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="/js/nop-bai.js"></script>
	</body>
</html>