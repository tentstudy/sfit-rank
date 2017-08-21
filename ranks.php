<?php
require_once __DIR__ . '/db/connect.php';
$groupId = empty($_GET['groupID']) ? '1796364763915932' : $_GET['groupID'];
$listName = array(
    '1796364763915932' => 'SFIT - UTC',
    '677222392439615' => 'SFIT - Giao lưu học hỏi'
);
$data = $db->getInsightGroup($groupId);
// echo('<pre>');
// print_r($data);
// $decoded = $json;
// print_r($decoded);
$listMembers = $data['json']->members;
// exit();
// print_r($listMembers);
$update_time = $data['update_time'];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta property="fb:app_id" content="1878792532378003" />
    <meta property="og:site_name" content="sfit-utc.tentstudy.xyz" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo $listName[$groupId]; ?> Ranking" />
    <meta property="og:description" content="<?php echo $listName[$groupId]; ?> Ranking - By The Tien Nguyen" />
    <!-- <meta property="og:url" content="https://sfit-utc.tentstudy.xyz/rank.html" />
    <meta property="og:image" content="https://tentstudy.xyz/images/banner_share_fb_short_url.png" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="300" />
    <meta property="og:image:height" content="300" /> -->
    <meta property="og:locale" content="vi_VN" />
    <title><?php echo $listName[$groupId]; ?> Ranking</title>
    <link rel="shortcut icon" type="image/png" href="https://tentstudy.xyz/images/icons/favicon.png" />
    <script src="/js/pace.min.js"></script>
    <link href="/css/pace-theme-minimal.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="/css/style.css">
  </head>
  <body>
    <div class="table-users">
      <div class="header">
        <p><?php echo $listName[$groupId]; ?> Ranking</p>
        <p class="update-time">Updated at: <?php echo date('d/m/Y H:i A', $update_time); ?></p>
      </div>
      <table cellspacing="0">
        <thead>
          <tr>
            <th>Rank</th>
            <th>Name</th>
            <th>Points</th>
            
          </tr>
        </thead>
        <tbody>
          <?php foreach ($listMembers as $member): ?>
          <tr>
            <td><img class="rank-img" src="<?php echo $member->rank; ?>" alt="rank img" /></td>
            <td>
              <a href="https://facebook.com/<?php echo $member->id; ?>" target="_blank">
                <img class="avatar-fb" src="https://graph.facebook.com/<?php echo $member->id ?>/picture?type=large&redirect=true&width=40&height=40" alt=""><span> <?php echo $member->name ?></span>
              </a>
            </td>
            <td><?php echo $member->score ?></td>
            
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
    <p class="text-center">By <a href="https://www.facebook.com/SocolaDaiCa1997" target="_blank">The Tien Nguyen</a> - <a href="https://facebook.com/TentStudy" target="_blank">TentStudy</a></p>
  </body>
</html>