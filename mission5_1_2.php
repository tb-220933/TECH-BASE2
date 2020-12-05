<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission5-1</title>
</head>
<body>
<?php
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, 
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
    //テーブル作成        
    $sql = "CREATE TABLE IF NOT EXISTS MIS05"
	    ." ("
	    ."id INT AUTO_INCREMENT PRIMARY KEY,"
	    ."name char(32),"
	    ."comment TEXT,"
	    ."date TEXT,"
	    ."pass TEXT"
	    .");";
    $stmt = $pdo->query($sql);
	
    //テーブルがつくられているかデバッグ確認
    /*
    $sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";
	*/

	//テーブルの構成要素確認
	/*
    $sql = 'SHOW CREATE TABLE MIS05';
        $result = $pdo -> query($sql);
        foreach($result as $row){
            echo $row[1];
        }
        echo "<hr>";
    */
    
    //作成したテーブルに、insertを行ってデータを入力する
    if(!empty($_POST["name"]) && !empty($_POST["comment"])
            && $_POST["submit"] == true
                && empty($_POST["editnumber"])
                    && $_POST["pass"] == pass){
        $sql = $pdo -> prepare("INSERT INTO 
                        MIS05 (name, comment, date, pass) 
                            VALUES (:name, :comment, :date, :pass)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $date = date("Y/m/d H:i:s");
        $pass = $_POST["pass"];
        $sql -> execute();
    }
    
    //削除機能
    if($_POST["delpass"] == pass){
        $id = $_POST["deleteNO"];
        $sql = 'delete from MIS05 where id=:id';
        $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	    $stmt->execute();
    }
	//編集機能
	//編集対象番号が空じゃないとき
    if(!empty($_POST["editNO"]) && $_POST["edpass"] == pass){
    $sql = 'SELECT * FROM MIS05';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        if($_POST["editNO"] == $row['id']){
        //編集対象フォームを取得
	    $ednum = $row['id'];
	    $edname = $row['name'];
	    $edcomment = $row['comment'];
    }}}
    
    $editnumber = $_POST["editnumber"];
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    if(!empty($editnumber) 
        && !empty($name) && !empty($comment) 
            && $_POST["pass"] == pass){
    $id = $editnumber;
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y:m:d H:i:s");
    $sql = 'UPDATE MIS05 
                SET name=:name,comment=:comment,
                    date=:date WHERE id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':date', $date, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
    }

?>
<form action=""method="post">
    <input type="text" name="name" 
            value="<?php if(!empty($edname)){
                echo $edname ;} ?>" placeholder="名前"><br>
    <input type="text" name="comment" 
            value="<?php if(!empty($edcomment)){
                echo $edcomment ;} ?>" placeholder="コメント"><br>
    <input type="text" name="pass" placeholder="パスワード">
    <input type="hidden" name="editnumber"
            value="<?php if(!empty($ednum)){
                echo $ednum ;} ?>">
    <input type="submit" name="submit"><br>
    <input type="text" name="deleteNO" 
            placeholder="削除対象番号"><br>
    <input type="text" name="delpass" placeholder="パスワード">            
    <input type="submit" name="delete" value="削除"><br>
    <input type="text" name="editNO" 
            placeholder="編集対象番号"><br>
    <input type="text" name="edpass" placeholder="パスワード">
    <input type="submit" name="edit" value="編集"><br>
    
</form>
<?php
    //入力したデータをselectによって表示する
    $sql = 'SELECT * FROM MIS05';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    
    foreach ($results as $row){
	    //$rowの中にはテーブルのカラム名が入る
	    echo $row['id'].',';
	    echo $row['name'].',';
	    echo $row['comment'].',';
	    echo $row['date'].'<br>';
	
    echo "<hr>";
    }

?>
</body>
</html>