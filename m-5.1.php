<!doctype html>
<?php
    // DB接続設定
    $dsn = 'mysql:dbname=データベース名;host=localhost';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    $sql = "CREATE TABLE IF NOT EXISTS tbprac"//テーブル作成
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name CHAR(32),"
    . "comment TEXT,"
    . "date DATETIME,"//日時のカラムを作成
    . "pass CHAR(20)"//パスワードのカラムを作成
    .");";
    $stmt = $pdo->query($sql);
    
    //データの入力
    if(!empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["pass"])){
        $date=date("Y/m/d H:i:s");
        $name = $_POST["name"];
        $comment = $_POST["str"];
        $pass=$_POST["pass"];
        $sql = "INSERT INTO tbprac (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->execute();
    }
    //データの削除
    elseif(!empty($_POST["delnum"]) && !empty($_POST["delpass"])){
        $delpass=$_POST["delpass"];
        $sql = 'SELECT * FROM tbprac';//パスワードを照合させるためにデータを抽出
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //パスワードを照合
            if($row["pass"]==$delpass){
                $id = $_POST["delnum"];//削除する投稿番号
                $sql = 'delete from tbprac where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
        
    }
    //データの編集
    elseif(!empty($_POST["edtnum"]) && !empty($_POST["edtname"]) && !empty($_POST["edtstr"]) && !empty($_POST["edtpass"])){
        $edtpass=$_POST["edtpass"];
        $sql = 'SELECT * FROM tbprac';//パスワードを照合させるためにデータを抽出
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //パスワードを照合
            if($row["pass"]==$edtpass){
                $date=date("Y/m/d H:i:s");
                $id = $_POST["edtnum"]; //変更する投稿番号
                $name = $_POST["edtname"];
                $comment = $_POST["edtstr"];
                $sql = 'UPDATE tbprac SET name=:name,comment=:comment,date=:date WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }
?>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>m-5.1</title>
    </head>
    <body>
        <form action="" method="post">
            <label>
                名前：
                <input type="text" name="name" placeholder="名前を入力">
            </label>
            <br>
            <label>
                コメント：
                <input type="text" name="str" placeholder="コメントを入力">
            </label>
            <br>
            <label>
                パスワード:
                <input type="password" name="pass" placeholder="パスワードを入力">
            </label>
            <br>
            <input type="submit" name="submit">
        </form>
        <hr>
        <form action="" method="post">
            <label>
                削除番号：
                <input type="number" name="delnum" placeholder="数字を入力">
            </label>
            <br>
            <label>
                パスワード:
                <input type="password" name="delpass" placeholder="パスワードを入力">
            </label>
            <br>
            <button>削除</button>
        </form>
        <hr>
        <form action="" method="post">
            <label>
                編集番号：
                <input type="number" name="edtnum" placeholder="数字を入力">
            </label>
            <br>
            <label>
                名前：
                <input type="text" name="edtname" placeholder="名前を入力">
            </label>
            <br>
            <label>
                コメント：
                <input type="text" name="edtstr" placeholder="コメントを入力">
            </label>
            <br>
            <label>
                パスワード:
                <input type="password" name="edtpass" placeholder="パスワードを入力">
            </label>
            <br>
            <button>編集</button>
        </form>
        
        <?php
        
        $sql = 'SELECT * FROM tbprac';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date']."<br>";
            echo "<hr>";
        }
        ?>
    </body>
</html>        