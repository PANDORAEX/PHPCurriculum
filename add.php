<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規登録</title>
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
    <script type="text/javascript">
        function reset_blank(){
            document.getElementById("id_start_date").innerText = "";
            document.getElementById("id_project_no").innerText = "";
            document.getElementById("id_project_name").innerText = "";
            document.getElementById("id_protype_code").options[0].selected = true;
            document.getElementById("id_language_code").options[0].selected = true;
            document.getElementById("id_summary").innerText = "";
            document.getElementById("id_status_code").options[0].selected = true;
            document.getElementById("id_customer").innerText = "";
            document.getElementById("id_charge").innerText = "";
            document.getElementById("id_reviewer").innerText = "";
            document.getElementById("id_release_date").innerText = "";
            document.getElementById("id_remarks").innerText = "";
        }
    </script>
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
    <font size = "5pt" color = "blue"><b>新規登録</b></font>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>必要事項を入力し登録を行ってください</b>
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

        $close_flag = pg_close($link);
    ?>
    <div style="color: red">
        <?php
            $startdate = "";
            $projectno = "";
            $projectname = "";
            $protypecode = "";
            $languagecode = "";
            $summary = "";
            $statuscode = "";
            $customer = "";
            $charge = "";
            $reviewer = "";
            $releasedate = "";
            $remarks = "";
            
            if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == "POST"){            
                $error = FALSE;
                $message = "";
                $startdate = filter_input(INPUT_POST, 'start_date');
                $projectno = filter_input(INPUT_POST, 'project_no');
                $projectname = filter_input(INPUT_POST, 'project_name');
                $protypecode = filter_input(INPUT_POST, 'protype_code');
                $languagecode = filter_input(INPUT_POST, 'language_code');
                $summary = filter_input(INPUT_POST, 'summary');
                $statuscode = filter_input(INPUT_POST, 'status_code');
                $customer = filter_input(INPUT_POST, 'customer');
                $charge = filter_input(INPUT_POST, 'charge');
                $reviewer = filter_input(INPUT_POST, 'reviewer');
                $releasedate = filter_input(INPUT_POST, 'release_date');
                $remarks = filter_input(INPUT_POST, 'remarks');
                if ($startdate == ""){
                    $message = '発生日を入力してください。<br />';
                    $error = TRUE;
                }
                elseif (!preg_match("/^[0-9]{4}\/[0-9]{2}\/[0-9]{2}$/", $startdate)) {
                    $message = '発生日は yyyy/MM/dd の形式で入力してください。<br />';
                    $error = TRUE;
                }
                else{
                    list($Y, $m, $d) = explode('/', $startdate);
                    if (checkdate($m, $d, $Y) === FALSE) {
                        $message = '発生日は カレンダーに存在しない日付です。<br />';
                        $error = TRUE;
                    }
                }
                if ($projectno == ""){
                    $message .= '案件番号を入力してください。<br />';
                    $error = TRUE;
                }
                if ($projectname == ""){
                    $message .= '案件名を入力してください。<br />';
                    $error = TRUE;
                }
                if ($protypecode == ""){
                    $message .= '工程区分を選択してください。<br />';
                    $error = TRUE;
                }
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
                    $conn = "host=localhost dbname=phpproject user=postgres password=postgres123";
                    $link = pg_connect($conn);
                    if (!$link) {
                        die('接続失敗です。'.pg_last_error());
                    }
                    pg_set_client_encoding("UTF-8");
                    $sequencesql = sprintf("SELECT number FROM projectsequence where name='projectid'");
                    $sequenceresult = pg_query($sequencesql);
                    if (!$sequenceresult) {
                        die('クエリーが失敗しました。'.pg_last_error());
                    }
                    $sequencerows = pg_fetch_array($sequenceresult, NULL, PGSQL_ASSOC);
                    $projectid = $sequencerows['number'];
                    if ($projectid >= 10000){
                        echo '登録可能な一連番号が無いため、登録を行えません。';
                    }
                    else{
                        $sequpdsql = sprintf("UPDATE projectsequence SET number = '%s' WHERE name = 'projectid'", $projectid + 1);
                        $sequpdflag = pg_query($sequpdsql);
                        if (!$sequpdflag) {
                            die('UPDATEクエリーが失敗しました。'.pg_last_error());
                        }
                        if ($releasedate == ""){
                            $releasedate = "NULL";
                            $prosql = sprintf("INSERT INTO projects (project_id, start_date,project_no"
                                . ",project_name,protype_code,language_code,summary,status_code,"
                                . "customer,charge,reviewer,release_date,remarks) VALUES "
                                . "(%s, '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',%s,'%s')"
                                ,$projectid,pg_escape_string($startdate),pg_escape_string($projectno)
                                ,pg_escape_string($projectname), pg_escape_string($protypecode), pg_escape_string($languagecode)
                                , pg_escape_string($summary), pg_escape_string($statuscode), pg_escape_string($customer)
                                , pg_escape_string($charge), pg_escape_string($reviewer), pg_escape_string($releasedate)
                                , pg_escape_string($remarks));
                        }
                        else{
                            $prosql = sprintf("INSERT INTO projects (project_id, start_date,project_no"
                                    . ",project_name,protype_code,language_code,summary,status_code,"
                                    . "customer,charge,reviewer,release_date,remarks) VALUES "
                                    . "(%s, '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')"
                                    ,$projectid,pg_escape_string($startdate),pg_escape_string($projectno)
                                    ,pg_escape_string($projectname), pg_escape_string($protypecode), pg_escape_string($languagecode)
                                    , pg_escape_string($summary), pg_escape_string($statuscode), pg_escape_string($customer)
                                    , pg_escape_string($charge), pg_escape_string($reviewer), pg_escape_string($releasedate)
                                    , pg_escape_string($remarks));
                        }
                        $proresult_flag = pg_query($prosql);
                        if (!$proresult_flag) {
                            die('INSERTクエリーが失敗しました。'.pg_last_error());
                        }
                        pg_close($link);
                        header('Location: add.php');
                    }
                    pg_close($link);
                    
                }
                else {
                    echo $message;
                }
            }
        ?>
    </div>
    <form action ="add.php" method="post">
    <fieldset style="border: 1px solid #000000; padding: 10px;-webkit-border-radius: 5px;border-color: gainsboro;width: 1100px">
    <legend><b>新規明細登録</b></legend>
        <table style="table-layout: fixed">
            <tr>
                <td class ="mylabel">発生日</td><td><input type="text" name="start_date" id="id_start_date" maxlength="10" value="<?php echo $startdate; ?>" /></td>
            </tr>
            <tr>
                <td class ="mylabel">案件番号</td>
                <td>
                    <input type="text" name="project_no" id="id_project_no" maxlength="15" value="<?php echo $projectno; ?>" />
                </td>
            </tr>
            <tr>
                <td class ="mylabel">案件名</td><td><input type="text" name="project_name" id="id_project_name" maxlength="30" value="<?php echo $projectname; ?>" /></td>
            </tr>
            <tr>
                <td class ="mylabel">工程区分</td>
                <td>
                    <select name="protype_code" id="id_protype_code">
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
                <td class ="mylabel">リリース日</td><td><input type="text" name="release_date" id="id_release_date" maxlength="10" value="<?php echo $releasedate; ?>" /></td>
            </tr>
            <tr>
                <td class ="mylabel">備考</td><td><textarea name="remarks" cols="40" rows="10" maxlength="255" id="id_remarks"><?php echo $remarks; ?></textarea></td>
            </tr>
        </table></fieldset>
    <table><tr></tr></table>
    <table style="width: 100%;height: 40px;background-color:#0362ff;">
        <tr>
            <td align ="right"><input type="submit" value="登録" style="WIDTH: 110px; HEIGHT: 30px;"></td>
            <td><input type="button" value="リセット" style="WIDTH: 110px; HEIGHT: 30px" onclick="reset_blank()"></td>
        </tr>
    </table>
    </form>
    <br>
</body>
</html>