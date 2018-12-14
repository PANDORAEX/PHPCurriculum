<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
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
    </style>
    <script type="text/javascript">
        function reset_blank(){
            document.getElementById("id_userid").innerText = "";
            document.getElementById("id_password").innerText = "";
        }
    </script>
</head>
<body>
    <br>
    <hr class="style1">
    <br>
    <b>Welcome to 案件管理ボータル</b>
    <br>
    <br>
    <hr class="style1">
    <br>
    <br>
    <h2>ログイン</h2>
    <hr class="style2">
    <br>
    ユーザー名とパスワードを入力してください
    <hr width="90%" align="left" style="height: 2px;border: 0;background-color: #c8c8c8">
    <div style="color: red">
    <?php
        $userid = "";
        $password = "";
        $message = "";
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == "POST"){            
            $error = FALSE;
            $userid = filter_input(INPUT_POST, 'userid');
            $password = filter_input(INPUT_POST, 'password');
            if ($userid == ""){
                $message = 'ユーザ名を入力してください。<br />';
                $error = TRUE;
            }
            elseif(mb_strlen($userid) != 8){
                $message = 'ユーザ名は8桁で入力してください。<br />';
                $error = TRUE;
            } 
            elseif (!preg_match("/^[0-9a-zA-Z]+$/", $userid)) {
                $message = 'ユーザ名は半角英数字で入力してください。<br />';
                $error = TRUE;
            }
            if ($password == ""){
                $message .= 'パスワードを入力してください。<br />';
                $error = TRUE;
            }
            if ($error == FALSE){
                $conn = "host=localhost dbname=phpproject user=postgres password=postgres123";
                $link = pg_connect($conn);
                if (!$link) {
                    die('接続失敗です。'.pg_last_error());
                }
                pg_set_client_encoding("UTF-8");
                $sql = sprintf("SELECT password FROM users where userid='%s'", pg_escape_string($userid));
                $result = pg_query($sql);
                if (!$result) {
                    die('クエリーが失敗しました。'.pg_last_error());
                }
                $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
                if ($rows == ""){
                    echo 'ユーザ名またはパスワードに誤りがあります。';
                }
                elseif ($rows['password'] == $password)
                {
                    $close_flag = pg_close($link);
                    header('Location: index.php');
                }
                else {
                    echo 'ユーザ名またはパスワードに誤りがあります。';
                }
                $close_flag = pg_close($link);
            }
            else {
                echo $message;
            }
        }
    ?>
        
    </div>
    <br>
    <form  method="post" action="login.php">
        <table border = "0">
            <tr><td>ユーザ名：</td><td><input type="text" maxlength="8" name="userid" id="id_userid" size="15" value="<?php echo $userid; ?>" /></td></tr>
            <tr><td>パスワード:</td><td><input type="password" name="password" id="id_password" /></td></tr>
            <tr>
                <td></td>
                <td><button type="submit" name="login" style="width: 80px"><b>ログイン</b></button>
                    <input type="button" name ="clear" style="width: 80px;font-weight: bold" value = "クリア" onclick="reset_blank();"></td>
            </tr>
        </table>
    </form>
</body>
</html>