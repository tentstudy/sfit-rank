<?php
    session_start();
    require_once '../db/connect.php';
    $listMembers = $db->getListMembers();
    // echo "<pre>";
    // print_r($listMembers);
    // exit();
    /*chỉ admim mới có quyền vào đây*/
    // $admin = array(
    //     '100006487845973',
    //     '100006907028797'
    // );
    // if(empty($_SESSION['id']) || !in_array($_SESSION['id'], $admin)){
    //     header('Location: ./');
    // }
date_default_timezone_set("Asia/Ho_Chi_Minh");
// $listMembers = array(
// "100005841970365" => "Tien Phan",
// "100008461985652" => "Nguyễn Minh Đức",
// "100006417369234" => "Thái Lee",
// "100014423655999" => "Trang Thu Phạm",
// "100016873235010" => "Ngọc Nguyễn",
// "100013442134842" => "Thu Hòa",
// "100005165048913" => "Thịnh BÒng",
// "100010228659729" => "Tính Nguyễn",
// "100004556018500" => "Phiêu Linh",
// "100005970276134" => "Nhật Nguyễn",
// "100013478444111" => "Nguyễn Tiến Quân",
// "100005582502835" => "Minh Đức",
// "100015374538383" => "Lê Vân",
// "100005346512784" => "Phạm Thùy Linh",
// "100003859809381" => "Long Chelsea",
// "100004812499286" => "Nhẫn Trần",
// "100006194651938" => "Xuan Anh Nguyen",
// "100006472931102" => "Hoàng Liên",
// "100004212182900" => "Quân Lương",
// "100006907028797" => "The Tien Nguyen",
// "100005344208992" => "Phờ Oong",
// "100004237694043" => "Hồ Nguyệt",
// "100004385950450" => "Nguyễn Thành",
// "100003758271944" => "Phạm Việt Thi",
// "100006487845973" => "Nguyễn Đăng Dũng",
// "100010728160751" => "Tâm Bồ"
// );
$exercises = $_REQUEST['exercises'] ?? 1;

?>
<!DOCTYPE html>
<html lang="vn">
    <head>
        <meta charset="utf-8">
        <title>Danh sách file</title>
        <link rel="stylesheet" type="text/css" href="bootstrap-3.1.1-dist/css/bootstrap.min.css">
        <style type="text/css">
        .media-left img{
        width: 40px;
        height: 40px;
        border-radius: 50%;
        }
        .media-body.pull-left{
        margin-top: 10px;
        }
        .media.clearfix{
        margin-top: 0;
        padding:5px;
        }
        .media.clearfix:hover,
        .media.clearfix.active{
        cursor: pointer;
        background: #eee;
        }
        iframe{
            width: 100%;
            height: calc(100vh - 100px);
        }
        .fullscreen {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: white;
        }
        #btn-fullscreen {
        width: 28px;
        height: 28px;
        position: fixed;
        right: 10px;
        bottom: 10px;
        z-index: 10;
        cursor: pointer;
        }
        #btn-fullscreen:hover {
        width: 32px;
        height: 32px;
        right: 8px;
        bottom: 8px;
        transition: 0.2s;
        }
        </style>
    </head>
    <body>
        <div class="col-md-9 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12">
            <img id="btn-fullscreen"src="images/icon-fullscreen.png" alt="" />
            <h1 class="text-center">XXXXXXXXXXXXXXXX</h1>
            <div class="row">
                <div class="col-md-4 col-xs-2 ">
                    <!--                     <select id="bai" class="form-control">
                        <option value="1">Bai 1</option>
                        <option value="2">Bai 2</option>
                        <option value="3">Bai 3</option>
                    </select>
                    <br /> -->
                    <div class="applicants">
                        <a href="http://www.google.com" target="myframe">Link Text</a> 
                        <ul class="media-list">
                            <?php foreach ($listMembers as $index => $member): ?>
                            <?php
                                // print_r($member);
                                // continue;
                                $id = $member['user_id'];
                                $name = $member['user_name'];
                                $listFile = glob("./storage/{$exercises}_{$id}_*");
                                // if(!sizeof($listFile)){
                                //     continue;
                                // }
                            ?>
                            <li class="media clearfix active" id-user="1">
                                <div class="media-left pull-left">
                                    <a href="#">
                                        <img class="media-object" src="<?php echo "https://graph.facebook.com/{$id}/picture?type=large&redirect=true&width=40&height=40" ?>" alt="...">
                                    </a>
                                </div>
                                <div class="media-body pull-left">
                                    <h4 class="media-heading"><?php echo $name ?></h4>
                                    <?php
                                        foreach ($listFile as $path) {
                                            $time = explode('.', explode('_', $path)[2])[0];
                                            $time = date("d/m/Y H:i:s", $time);
                                            echo "<a href=\"{$path}\" target=\"myframe\" title=\"\">Bài {$exercises} {$time}</a><br>";
                                        }
                                    ?>
                                </div>
                            </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                </div>
                <div class="col-md-8 col-xs-10">
                    <iframe id="myframe" name="myframe">
                    <p>Trình duyệt của bạn không hỗ trợ iframe.</p>
                    </iframe>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript">
        full_screen = () => {
            const sEnabled = "webkitFullscreenEnabled";
            const sIsFull = "webkitIsFullScreen";
            const sResquestFull = "webkitRequestFullScreen";
            if(sEnabled in document)
            {
            if( ! document[sIsFull])
            {
            const html = $('html')[0];
            if(sResquestFull in html)
            {
            html.webkitRequestFullScreen();
            }
            } else {
            document.webkitExitFullscreen()
            }
            }
        }
        $(document).ready(function () {
        // $('.media.clearfix').click(function() {
        // $('.media.clearfix').removeClass('active');
        // $(this).addClass('active');
        // var id = $(this).attr('id-user');
        // var bai = $('#bai').val();
        // console.log(bai);
        // $.ajax({
        // url: "bainop.php",
        // method: "get",
        // data: {
        // id : id,
        // bai : bai
        // },
        // dataType: "json",
        // success: function(respone) {
        // console.log(respone);
        // $('#myframe').contents().find('html').html(respone.data);
        // }
        // });
        // });
            $('#btn-fullscreen').on('click', () => {
                full_screen();
                $('#myframe').toggleClass('fullscreen');
            });
        });
        </script>
    </body>
</html>