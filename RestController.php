<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
  //соединение с базой данных при помощи функции mysql_connect()
  //в аргументах функции укажите имя сервера, логин и пароль. 
  mb_internal_encoding("utf-8");
  $db = mysqli_connect("localhost","root","");
  //функция mysql_select_db() выбирает текущую 
  //базу данных с именем "komtet_test"
  mysqli_select_db($db, "CRUDtest");

  function method(){
    return $_SERVER['REQUEST_METHOD'];
  }

  if($_GET["controller"]=="author"){ //если мы выполняем действия над авторами
    $success = false;
    if(method()=="POST"){
      $fName = $_POST["fName"];
      $sName = $_POST["sName"];
      $lName = $_POST["lName"];
      if($fName!="" AND $sName!="" AND $lName!=""){
        $query = "INSERT INTO `authors`(`first name`, `second name`, `last name`) VALUES ('{$fName}', '{$sName}', '{$lName}')";
        $success = mysqli_query($db, $query);
        if($success){
          http_response_code(201);
        } else {
          http_response_code(500);
        }
      } else {
        http_response_code(204);
      }
    }
    if(method()=="GET"){
      if($_GET["author_id"]!="" AND $_GET["author_id"]!=NULL){
        $id = $_GET["author_id"];
        $query = "SELECT * FROM `authors` WHERE `id`='{$id}'";
        $result = mysqli_query($db, $query);
        $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
      } else {
        $query = "SELECT * FROM `authors` WHERE 1";
        $response = mysqli_query($db, $query);
        $results = [];
        while($result = mysqli_fetch_array($response, MYSQLI_ASSOC)){
          $results[] = $result;
        }
        echo json_encode($results, JSON_UNESCAPED_UNICODE);
      }
    }
    if(method()=="PUT"){
      $id = $_GET["author_id"];
      $fName = $_GET["fName"];
      $sName = $_GET["sName"];
      $lName = $_GET["lName"];
      if($fName!="" AND $fName!=NULL){
        $query = "UPDATE `authors` SET `first name`='{$fName}' WHERE `id`='{$id}'";
        $result = mysqli_query($db, $query);
      }
      if($sName!="" AND $sName!=NULL){
        $query = "UPDATE `authors` SET `second name`='{$sName}' WHERE `id`='{$id}'";
        $result = mysqli_query($db, $query);
      }
      if($lName!="" AND $lName!=NULL){
        $query = "UPDATE `authors` SET `last name`='{$lName}' WHERE `id`='{$id}'";
        $result = mysqli_query($db, $query);
      }
    }
    if(method()=="DELETE"){
      $success = false;
      $id = $_GET["author_id"];
      $query = "DELETE FROM `authors` WHERE `id`='{$id}'";
      $success = mysqli_query($db, $query);
      if(!$success){
        http_response_code(500);
        echo mysqli_error($db);
      }
    }
  }
  if($_GET["controller"]=="book"){
    $success = FALSE;
    if(method()=="POST"){
      $ISBN=$_POST["ISBN"];
      $author_id=$_POST["author_id"];
      $title=$_POST["title"];
      if($ISBN!="" AND $author_id!="" AND $title!=""){
        $query = "INSERT INTO `books`(`ISBN`, `author`, `title`) VALUES ('{$ISBN}','{$author_id}','{$title}')";
        $success = mysqli_query($db,$query);
        if($success){
          http_response_code(201);
          echo "Запись создана";
        } else {
          http_response_code(500);
          echo mysqli_error($db);
        }
      } else {
        http_response_code(204);
        echo "Нет всех, либо части данных для создания записи";
      }
    }
    if(method()=="GET"){
      $id = $_GET["book_id"];
      if($id!="" AND $id!=NULL){
        $query = "SELECT * FROM `books` WHERE `id`='{$id}'";
        $response = mysqli_query($db,$query);
        if($response){
          http_response_code(200);
          $result = mysqli_fetch_array($response, MYSQLI_ASSOC);
          echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
          http_response_code(500);
          echo mysqli_error($db);
        }
      } else {
        $query = "SELECT * FROM `books` WHERE 1";
        $response = mysqli_query($db, $query);
        $results = [];
        while($result = mysqli_fetch_array($response, MYSQLI_ASSOC)){
          $results[] = $result;
        }
        echo json_encode($results, JSON_UNESCAPED_UNICODE);
      }
    }
    if(method()=="PUT"){
      $success = TRUE;
      $book_id = $_GET["book_id"];
      $author_id = $_GET["author_id"];
      $ISBN = $_GET["isbn"];
      $title = $_GET["title"];
      if($author_id!="" AND $author_id!=NULL){
        $query = "UPDATE `books` SET `author`='{$author_id}' WHERE `id`='{$book_id}'";
        $success = mysqli_query($db, $query);
        if(!$success){
          http_response_code(500);
          echo mysqli_error($db);
        }
      }
      if($ISBN!="" AND $ISBN!=NULL){
        $query = "UPDATE `books` SET `ISBN`='{$ISBN}' WHERE `id`='{$book_id}'";
        $success = mysqli_query($db, $query);
        if(!$success){
          http_response_code(500);
          echo mysqli_error($db);
        }
      }
      if($title!="" AND $title!=NULL){
        $query = "UPDATE `books` SET `title`='{$title}' WHERE `id`='{$book_id}'";
        $success = mysqli_query($db, $query);
        if(!$success){
          http_response_code(500);
          echo mysqli_error($db);
        }
      }
    }
    if(method()=="DELETE"){
      $book_id=$_GET["book_id"];
      $success = false;
      $query = "DELETE FROM `books` WHERE `id`='{$book_id}'";
      $success = mysqli_query($db, $query);
      if(!$success){
        http_response_code(500);
        echo mysqli_error($db);
      }
    }
  }
  if($_GET["controller"]=="showBooksByAuthor"){
    $success = false;
    if(method()=="GET"){
      $author_id = $_GET["author_id"];
      if($author_id!="" AND $author_id!=NULL){
        $query = "SELECT * FROM `books` WHERE `author` = '{$author_id}'";
        $response = mysqli_query($db, $query);
        if($response){
          $results = [];
          while($result= mysqli_fetch_array($response, MYSQLI_ASSOC)){
            $results[]=$result;
          }
          echo json_encode($results, JSON_UNESCAPED_UNICODE);
        } else {
          http_response_code(500);
          echo mysqli_error($db);
        }
      } else {
        http_response_code(204);
      }
    } else {
      http_response_code(405);
    }
  }
  if($_GET["controller"]=="showBooksByIsbn"){
    $success = false;
    if(method()=="GET"){
      $isbn = $_GET["isbn"];
      if($isbn!="" AND $isbn!=NULL){
        $query = "SELECT * FROM `books` WHERE `ISBN`='{$isbn}'";
        $response = mysqli_query($db,$query);
        if($response){
          $results = [];
          while($result= mysqli_fetch_array($response, MYSQLI_ASSOC)){
            $results[]=$result;
          }
          echo json_encode($results, JSON_UNESCAPED_UNICODE);
        } else {
          http_response_code(500);
          echo mysqli_error($db);
        }
      } else {
        http_response_code(204);
      }
    } else {
      http_response_code(405);
    } 
    }
  mysqli_close($db);
?>