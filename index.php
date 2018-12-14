﻿<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メニュー</title>
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
        function closeWindowNoPrompt() {
            window.open('', '_parent', '');
            window.close();
        }
    </script>
</head>
<body>
    <br>
    <hr class="style1">
    <table>
        <tr>
            <td width = "100%"><b style="color:blue;">Welcome to 案件管理ボータル</b></td>
            <td>    <input type="button" value="閉じる" onClick="
            if(window.confirm('システムを終了します。よろしいですか？')){closeWindowNoPrompt()}"
                       style="background-color:#0362ff;color:white;WIDTH: 100px; HEIGHT: 30px"></td>
        </tr>
    </table>
    <hr class="style1">
    <br>
    <br>
        <font size = "5pt" color = "blue">メニュー</font>
    <hr class="style2">
    <b>使用する機能を選択してください</b>
    <hr width="90%" align="left" style="height: 2px;border: 0;background-color: #c8c8c8">
    <br>
    <input type="button" value="新規登録" onClick="location.href='add.php'" style="background-color:#0362ff;color:white;WIDTH: 150px; HEIGHT: 30px">
    <br>
    <input type="button" value="一覧表示" onClick="location.href='list.php'" style="background-color:#0362ff;color:white;WIDTH: 150px; HEIGHT: 30px">
</body>
</html>