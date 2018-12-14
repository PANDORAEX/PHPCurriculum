<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>変更</title>
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
        textarea{
            width: 800px;
            height: 50px;
            resize: none;
            overflow-y: scroll;
        }
        .mylabel{
            background-color: #0362ff;
            color: white;
            width: 160px;
        }
    </style>
</head>
<body>
    <br>
    <hr class="style1">
    <table>
        <tr>
            <td width = "100%"><b style="color:blue;">Welcome to 案件管理ボータル</b></td>
            <td><input type="button" value="戻る" onClick="location.href='list.php'" style="background-color:#0362ff;color:white;WIDTH: 100px; HEIGHT: 30px"></td>
        </tr>
    </table>
    <hr class="style1">
    <br>
    <br>
    <font size = "5pt" color = "blue"><b>変更</b></font>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>必要事項を入力し登録を行ってください</b>
    <hr class="style2">
    <br>
    <?php
        $conn = "host=localhost dbname=phpproject user=postgres password=postgres123";
        $link = pg_connect($conn);
        if (!$link) {
            die('接続失敗です。'.pg_last_error());
        }
        pg_set_client_encoding("UTF-8");
        $statussql = sprintf("SELECT * FROM statuses ORDER BY status_code ASC ");
        $statusresult = pg_query($statussql);
        if (!$statusresult) {
            die('クエリーが失敗しました。'.pg_last_error());
        }
        $languagesql = sprintf("SELECT * FROM languages ORDER BY language_code ASC ");
        $languageresult = pg_query($languagesql);
        if (!$languageresult) {
            die('クエリーが失敗しました。'.pg_last_error());
        }
        $protypesql = sprintf("SELECT * FROM projecttypes ORDER BY protype_code ASC ");
        $protyperesult = pg_query($protypesql);
        if (!$protyperesult) {
            die('クエリーが失敗しました。'.pg_last_error());
        }
    ?>
    <div style="color: red">
        <?php
            $projectid = filter_input(INPUT_POST, 'project_id');
            if ($projectid != ""){  
                $projectsql = sprintf("SELECT * FROM projects WHERE project_id = '%s' ", $projectid);
                $projectresult = pg_query($projectsql);
                if (!$projectresult) {
                    die('クエリーが失敗しました。'.pg_last_error());
                }
                $projectrows = pg_fetch_array($projectresult, NULL, PGSQL_ASSOC); 
                $startdate = $projectrows['start_date'];
                $projectno = $projectrows['project_no'];
                $projectname = $projectrows['project_name'];
                $protypecode = $projectrows['protype_code'];
                $languagecode = $projectrows['language_code'];
                $summary = $projectrows['summary'];
                $statuscode = $projectrows['status_code'];
                $customer = $projectrows['customer'];
                $charge = $projectrows['charge'];
                $reviewer = $projectrows['reviewer'];
                $releasedate = $projectrows['release_date'];
                if ($releasedate != ""){
                    $release_date = date_create($releasedate); 
                    $releasedate = date_format($release_date, 'Y/m/d');
                }
                $remarks = $projectrows['remarks'];
                pg_close($link);
            } else {         
                $error = FALSE;
                $message = "";
                $projectid = filter_input(INPUT_POST, 'projectid');
                if ($projectid == ""){
                    echo 'URLを直打ちせずに一覧画面から遷移してください。';
                    exit;
                }
                $projectsql = sprintf("SELECT * FROM projects WHERE project_id = '%s' ", $projectid);
                $projectresult = pg_query($projectsql);
                if (!$projectresult) {
                    die('クエリーが失敗しました。'.pg_last_error());
                }
                $projectrows = pg_fetch_array($projectresult, NULL, PGSQL_ASSOC); 
                $startdate = $projectrows['start_date'];
                $projectno = $projectrows['project_no'];
                $projectname = $projectrows['project_name'];
                $protypecode = $projectrows['protype_code'];
                $languagecode = filter_input(INPUT_POST, 'language_code');
                $summary = filter_input(INPUT_POST, 'summary');
                $statuscode = filter_input(INPUT_POST, 'status_code');
                $customer = filter_input(INPUT_POST, 'customer');
                $charge = filter_input(INPUT_POST, 'charge');
                $reviewer = filter_input(INPUT_POST, 'reviewer');
                $releasedate = filter_input(INPUT_POST, 'release_date');
                $remarks = filter_input(INPUT_POST, 'remarks');
                if ($languagecode == ""){
                    $message .= '開発言語を選択してください。<br />';
                    $error = TRUE;
                }
                if ($summary == ""){
                    $message .= '概要を入力してください。<br />';
                    $error = TRUE;
                }
                if ($statuscode == ""){
                    $message .= '状態を選択してください。<br />';
                    $error = TRUE;
                }
                if ($releasedate != ""){
                    if (!preg_match("/^[0-9]{4}\/[0-9]{2}\/[0-9]{2}$/", $releasedate)) {
                        $message .= 'リリース日は yyyy/MM/dd の形式で入力してください。<br />';
                        $error = TRUE;
                    }
                    else{
                        list($Y, $m, $d) = explode('/', $releasedate);
                        if (checkdate($m, $d, $Y) === FALSE) {
                            $message .= 'リリース日は カレンダーに存在しない日付です。<br />';
                            $error = TRUE;
                        }
                    }
                }
                if ($error == FALSE){
                    if ($releasedate == ""){
                        $releasedate = "NULL";
                        $prosql = sprintf("UPDATE projects SET language_code = '%s',summary = '%s',"
                                    . "status_code = '%s',customer = '%s',charge = '%s',"
                                    . "reviewer = '%s',release_date = %s,remarks = '%s' WHERE "
                                    . "project_id = '%s'"
                                    , pg_escape_string($languagecode)
                                    , pg_escape_string($summary), pg_escape_string($statuscode), pg_escape_string($customer)
                                    , pg_escape_string($charge), pg_escape_string($reviewer), pg_escape_string($releasedate)
                                    , pg_escape_string($remarks), $projectid);
                    }
                    else {
                        $prosql = sprintf("UPDATE projects SET language_code = '%s',summary = '%s',"
                                    . "status_code = '%s',customer = '%s',charge = '%s',"
                                    . "reviewer = '%s',release_date = '%s',remarks = '%s' WHERE "
                                    . "project_id = '%s'"
                                    , pg_escape_string($languagecode)
                                    , pg_escape_string($summary), pg_escape_string($statuscode), pg_escape_string($customer)
                                    , pg_escape_string($charge), pg_escape_string($reviewer), pg_escape_string($releasedate)
                                    , pg_escape_string($remarks), $projectid);
                    }
                    $proresult_flag = pg_query($prosql);
                    if (!$proresult_flag) {
                        die('UPDATEクエリーが失敗しました。'.pg_last_error());
                    }
                    pg_close($link);
                    header('Location: list.php');
                }
                else {
                    echo $message;
                    pg_close($link);
                }
            }
        ?>
    </div>
    <form action ="change.php" method="post">
        <input type="hidden" name="projectid" value="<?php echo $projectid ;?>">
    <fieldset style="border: 1px solid #000000; padding: 10px;-webkit-border-radius: 5px;border-color: gainsboro;width: 1100px">
    <legend><b>新規明細登録</b></legend>
        <table style="table-layout: fixed">
            <tr>
                <td class ="mylabel">発生日</td><td><input type="text" name="start_date" id="id_start_date" maxlength="10" value="<?php $start_date = date_create($startdate); echo date_format($start_date, 'Y/m/d'); ?>" disabled="disabled" /></td>
            </tr>
            <tr>
                <td class ="mylabel">案件番号</td>
                <td>
                    <input type="text" name="project_no" id="id_project_no" maxlength="15" value="<?php echo $projectno; ?>" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td class ="mylabel">案件名</td><td><input type="text" name="project_name" id="id_project_name" maxlength="30" value="<?php echo $projectname; ?>" disabled="disabled" /></td>
            </tr>
            <tr>
                <td class ="mylabel">工程区分</td>
                <td>
                    <select name="protype_code" id="id_protype_code" disabled="disabled" >
                        <?php if ($protypecode == ""){ ?>
                        <option value="" selected></option>
                        <?php for ($i = 0 ; $i < pg_num_rows($protyperesult) ; $i++){
                                $protyperows = pg_fetch_array($protyperesult, NULL, PGSQL_ASSOC);
                                ?>
                        <option value="<?php echo $protyperows['protype_code']; ?>"><?php echo $protyperows['protype_name']; ?></option>
                        <?php } 
                        } else { ?>
                            <option value="" ></option>
                            <?php for ($i = 0 ; $i < pg_num_rows($protyperesult) ; $i++){
                                $protyperows = pg_fetch_array($protyperesult, NULL, PGSQL_ASSOC);
                                if ($protypecode == $protyperows['protype_code']){
                                ?>
                            <option value="<?php echo $protyperows['protype_code']; ?>" selected><?php echo $protyperows['protype_name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $protyperows['protype_code']; ?>"><?php echo $protyperows['protype_name']; ?></option>
                        <?php } } }?>
                    </select>
                </td>
            </tr>
            <tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
            <tr>
                <td class ="mylabel">開発言語</td>
                <td>
                    <select name="language_code" id="id_language_code">
                        <?php if ($languagecode == ""){ ?>
                        <option value="" selected></option>
                        <?php for ($i = 0 ; $i < pg_num_rows($languageresult) ; $i++){
                                $languagerows = pg_fetch_array($languageresult, NULL, PGSQL_ASSOC);
                                ?>
                        <option value="<?php echo $languagerows['language_code']; ?>"><?php echo $languagerows['language_name']; ?></option>
                        <?php } 
                        } else { ?>
                            <option value="" ></option>
                            <?php for ($i = 0 ; $i < pg_num_rows($languageresult) ; $i++){
                                $languagerows = pg_fetch_array($languageresult, NULL, PGSQL_ASSOC);
                                if ($languagecode == $languagerows['language_code']){
                                ?>
                            <option value="<?php echo $languagerows['language_code']; ?>" selected><?php echo $languagerows['language_name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $languagerows['language_code']; ?>"><?php echo $languagerows['language_name']; ?></option>
                        <?php } } }?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class ="mylabel">概要</td><td><textarea name="summary" cols="40" rows="10" maxlength="255" id="id_summary"><?php echo $summary; ?></textarea></td>
            </tr>
            <tr>
                <td class ="mylabel">状態</td>
                <td>
                    <select name="status_code" id="id_status_code">
                        <?php if ($statuscode == ""){ ?>
                        <option value="" selected></option>
                        <?php for ($i = 0 ; $i < pg_num_rows($statusresult) ; $i++){
                                $statusrows = pg_fetch_array($statusresult, NULL, PGSQL_ASSOC);
                                ?>
                        <option value="<?php echo $statusrows['status_code']; ?>"><?php echo $statusrows['status_name']; ?></option>
                        <?php } 
                        } else { ?>
                            <option value="" ></option>
                            <?php for ($i = 0 ; $i < pg_num_rows($statusresult) ; $i++){
                                $statusrows = pg_fetch_array($statusresult, NULL, PGSQL_ASSOC);
                                if ($statuscode == $statusrows['status_code']){
                                ?>
                            <option value="<?php echo $statusrows['status_code']; ?>" selected><?php echo $statusrows['status_name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $statusrows['status_code']; ?>"><?php echo $statusrows['status_name']; ?></option>
                        <?php } } }?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class ="mylabel">お客様担当者</td><td><input type="text" name="customer" id="id_customer" maxlength="20" value="<?php echo $customer; ?>" /></td>
            </tr>
            <tr>
                <td class ="mylabel">担当者</td><td><input type="text" name="charge" id="id_charge" maxlength="20" value="<?php echo $charge; ?>" /></td>
            </tr>
            <tr>
                <td class ="mylabel">レビュー者</td><td><input type="text" name="reviewer" id="id_reviewer" maxlength="20" value="<?php echo $reviewer; ?>" /></td>
            </tr>
            <tr>
                <td class ="mylabel">リリース日</td><td><input type="text" name="release_date" id="id_release_date" maxlength="10" value="<?php  echo $releasedate; ?>" /></td>
            </tr>
            <tr>
                <td class ="mylabel">備考</td><td><textarea name="remarks" cols="40" rows="10" maxlength="255" id="id_remarks"><?php echo $remarks; ?></textarea></td>
            </tr>
        </table></fieldset>
    <table><tr></tr></table>
    <table style="width: 100%;height: 40px;background-color:#0362ff;">
        <tr>
            <td align ="center"><input type="submit" value="変更" onClick="" style="WIDTH: 110px; HEIGHT: 30px;"></td>
        </tr>
    </table>
    </form>
    <br>
</body>
</html>