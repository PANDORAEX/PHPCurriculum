<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>一覧</title>
    <style>
        hr.style1 {
            background-image: linear-gradient(90deg, blue, transparent);
            border: 0;
            height: 3px;
        }
        hr.style2{
            height: 3px;
            border: 0;
            background-color: blue;
        }
        @media print {
            .hidden-print {
            display: none;
            }
        .content-print{
            top:0 !IMPORTANT;
            left:0 !IMPORTANT;
            width:172mm !IMPORTANT;
            height:251mm !IMPORTANT;
            }
        }
        textarea{
            width: 97%;
            height: 50px;
            resize: none;
            overflow-y: scroll;
        }
        .mylabel{
            background-color: #0362ff;
            color: white;

        }
    </style>
</head>
<body>
    <br>
    <hr class="style1">
    <table>
        <tr>
            <td width = "100%"><b style="color:blue;">Welcome to 案件管理ボータル</b></td>
            <td><input type="button" value="戻る" onClick="location.href='index.php'" style="background-color:#0362ff;color:white;WIDTH: 100px; HEIGHT: 30px"></td>
        </tr>
    </table>
    <hr class="style1">
    <br>
    <br>
    <font size = "5pt" color = "blue"><b>一覧</b></font>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>登録済の明細一覧です</b>
    <hr class="style2">
    明細を削除する場合は＜削除＞ボタンをクリックしてください
    <hr width="80%" align="left" style="height: 2px;border: 0;background-color:black">
    <br>
    <?php 
        $button=filter_input(INPUT_POST,"button");
        if($button == "削除") {
            $conn = "host=localhost dbname=phpproject user=postgres password=postgres123";
            $link = pg_connect($conn);
            if (!$link) {
                die('接続失敗です。'.pg_last_error());
            }
            pg_set_client_encoding("UTF-8");
            $project_id = filter_input(INPUT_POST, 'project_id');
            $projectsql = sprintf("DELETE FROM projects WHERE project_id = '%s' ", $project_id);
            $projectresult = pg_query($projectsql);
            if (!$projectresult) {
                die('クエリーが失敗しました。'.pg_last_error());
            }
            pg_close($link);
            header("Refresh:0");
        }
    ?>
    <?php 
        $conn = "host=localhost dbname=phpproject user=postgres password=postgres123";
        $link = pg_connect($conn);
        if (!$link) {
            die('接続失敗です。'.pg_last_error());
        }
        pg_set_client_encoding("UTF-8");
        $countsql = sprintf("SELECT COUNT(*) FROM projects");
        $countresult = pg_query($countsql);
        if (!$countresult) {
            die('クエリーが失敗しました。'.pg_last_error());
        }
        $countrows = pg_fetch_array($countresult, NULL, PGSQL_ASSOC);
        if ($countrows['count'] == 0){ ?>
            <div style="color: red;font-weight: bold;">
        <?php
            echo 'レコードが登録されていません。'; ?>
            </div> 
        <?php }
        else { 
            $projectsql = sprintf("SELECT * FROM projects ORDER BY project_id ASC ");
        $projectresult = pg_query($projectsql);
        if (!$projectresult) {
            die('クエリーが失敗しました。'.pg_last_error());
        }
        ?>
        <table width="100%" border="1" style="text-align: center">
            <tr>
                <td class ="mylabel" rowspan="2" width="5%">No.</td><td class ="mylabel" width="12%">発生日</td><td class ="mylabel" width="12%">案件番号</td>
                <td class ="mylabel" width="12%">案件名</td><td  class ="mylabel" width="12%">工程区分</td><td class ="mylabel" width="12%">状態</td><td class ="mylabel" width="30%">概要</td><td class ="mylabel" width="5%">削除</td>
            </tr>
            <tr>
                <td class ="mylabel">リリース日</td><td class ="mylabel">開発言語</td><td class ="mylabel">お客様担当者</td><td class ="mylabel">担当者</td>
                <td class ="mylabel">レビュー者</td><td class ="mylabel">備考</td><td class ="mylabel">変更</td>
            </tr>
            <?php for ($i = 0 ; $i < pg_num_rows($projectresult) ; $i++) {
                $projectrows = pg_fetch_array($projectresult, NULL, PGSQL_ASSOC); 
                $start_date = date_create($projectrows['start_date']); 
                $startdate = date_format($start_date, 'Y年m月d日'); 
                $releasedate = "";
                if ($projectrows['release_date'] != ""){
                    $release_date = date_create($projectrows['release_date']); 
                    $releasedate = date_format($release_date, 'Y年m月d日'); 
                }
                    ?>
                <form action ="list.php" method="post">
                    <input type="hidden" value="<?php echo $projectrows['project_id']; ?>" name="project_id">
            <tr>
                <td rowspan="2"><?php echo sprintf('%04d', $projectrows['project_id']); ?></td><td><?php echo $startdate; ?></td><td><?php echo $projectrows['project_no']; ?></td>
                <td><?php echo $projectrows['project_name']; ?></td><td><?php $protypesql = sprintf("SELECT protype_name FROM projecttypes WHERE protype_code = '%s'", $projectrows['protype_code']);
                    $protyperesult = pg_query($protypesql); 
                    $protyperows = pg_fetch_array($protyperesult, NULL, PGSQL_ASSOC); 
                    echo $protyperows['protype_name']; ?></td>
                <td><?php $statussql = sprintf("SELECT status_name FROM statuses WHERE status_code = '%s'", $projectrows['status_code']);
                    $statusresult = pg_query($statussql); 
                    $statusrows = pg_fetch_array($statusresult, NULL, PGSQL_ASSOC); 
                    echo $statusrows['status_name']; ?></td><td><textarea><?php echo $projectrows['summary']; ?></textarea></td>
                <td><input type="submit" value="削除" class="hidden-print" name="button" onclick="if(!window.confirm('削除します。よろしいですか？')){
                                                                                return false;
	                                                                    }"></td>
            </tr></form>
            <form action="change.php" method="post"><tr>
                    <input type="hidden" value="<?php echo $projectrows['project_id']; ?>" name="project_id">
                <td><?php echo $releasedate; ?></td><td><?php $languagesql = sprintf("SELECT language_name FROM languages WHERE language_code = '%s'", $projectrows['language_code']);
                    $languageresult = pg_query($languagesql); 
                    $languagerows = pg_fetch_array($languageresult, NULL, PGSQL_ASSOC); 
                    echo $languagerows['language_name']; ?></td><td><?php echo $projectrows['customer']; ?></td><td><?php echo $projectrows['charge']; ?></td>
                <td><?php echo $projectrows['reviewer']; ?></td><td><textarea><?php echo $projectrows['remarks']; ?></textarea></td>
                <td><input type="submit" value="変更" class="hidden-print" name="button"></td>
                </form>
        </tr></form>
            <?php } ?>
        </table>
        <?php }
        $close_flag = pg_close($link);
        ?>
    <br>

</body>
</html>