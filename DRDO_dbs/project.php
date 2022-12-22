<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Intern Project</title>
    <style>
        body{
            font-family: sans-serif;
            background-color:#d7e0d9;
        }
        .searchit{
            width:70%;
            border-radius:5px;
            height:60px;
            margin-top:20px;
            margin-bottom:15px;
            font-size:25px;
            padding-left:10px;
            text-align:center;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            background-color:#e1e5e6;
            border:1px solid black;
        }
        .searchit:hover{
            background-color:white;
            color:black;
            border:1px solid black;
        }
        .form2
        {
            text-align:center;
        }
        .searchtext{
            font-size:25px;
            font-weight:bold;
            font-family: sans-serif;
        }
        .searchbtn{
            width:100px;
            height:40px;
            font-weight:bold;
            font-size:20px;
            background-color:white;
            color:black;
            border:1px solid black;
            border-radius:4px;

        }
        .searchbtn:hover{
            cursor:pointer;
            background-color:black;
            color:white;
        }
        /* list */
        .list{
            font-size:20px;
            margin-top:30px;
            width:70%;
            margin-left:150px;
        }
        a{
            color: blue;
            text-decoration:none;
        }
        table, td, tr, th{
            border:1px solid black;
            border-collapse: collapse;
        }
        .records{
            font-size:20px;
            margin-top:30px;
            margin-left:140px;
            font-weight:bold;
            font-family: sans-serif;
        }
        .categories{
            margin-left:120px;
        }
        /* upload */
        .form1{
            text-align: center;
            font-size:20px;
            margin-left:25%;
            margin-right:25%;
            font-family: sans-serif;
            border:1px solid black;
            padding:10px;
            background-color: #e1e5e6;
        }
        .upload{
            font-weight:bold;
            margin-top:5px;
        }
        .project
        {
            display: flex;
        }
        .form3{
            margin-right:30px;
            margin-top:30px;
        }
        .selected
        {
            margin-top:32px;
        }
        .section2{
            display:block;
            float: left;

        }
        .section3
        {
            float:right;
            width:100%;
        }
        .section1
        {
            display:flex;
        }
    </style>
</head>
<body>
    <?php
       $dbh= new PDO("mysql:host=localhost;dbname=abc", "root", "");
       $userprofile=$_SESSION['user_name'];
       if($userprofile==true)
       {

       }
       else{
            header("Location: login.php");
       }
       if(isset($_POST['btn'])){
        if($_FILES['myfile']['error'] == 0){
            $name=$_FILES['myfile']['name'];
            $type=$_FILES['myfile']['type'];
            $data=file_get_contents($_FILES['myfile']['tmp_name']);
            $project=$_POST['project'];
            $category=strtoupper($_POST['category']);
            if($category!="")
            {
                $stmt=$dbh->prepare("insert into table1 values('',?,?,?,?,?)");
                $stmt->bindParam(1,$name);
                $stmt->bindParam(2,$type);
                $stmt->bindParam(3,$data);
                $stmt->bindParam(4,$category);
                $stmt->bindParam(5,$project);
                if($stmt->execute())
                {
                    echo '<script>alert("File has been uploaded to the database")</script>';
                }
                else
                {
                    echo '<script>alert("Size Limit Exceeded")</script>';
                }
            }
            else
            {
                echo '<script>alert("Enter the category")</script>';
            }
        }
       }
    ?>
    
    <form class="form1" method="post" enctype="multipart/form-data">
        <p class="upload">UPLOAD FILE HERE</p>
        <input type="file" name="myfile" />
        <input type="text" name="category" placeholder="Category" required/>
        <input type="text" name="project" placeholder="Project" required/>
        <button name="btn">Upload</button>   
        
    </form>
    <p class="uploaded"></p>
    <br/>
    
    <form class="form2" method="post">
        <label class="searchtext">SEARCH HERE</label>
        <br/>
        <input class="searchit" placeholder="Enter the file name" type="text" name="search"/>
        <br/>
        <input class="searchbtn" type="submit" name="submit"/>
    </form method="post">
<div class="project">
    <p class="records">Projects:</p>
    <form class="form3"  method="get">
        <select name="color" id="color" class="categories">
            <?php
            $stat=$dbh->prepare("select DISTINCT project from table1 order by project asc");
            $stat->execute();
            while($row=$stat->fetch()){
            echo "<option value=".$row['project'].">".$row['project']."</option>";
            }
            ?>
        </select>
        <button type="submit" name="select">Select</button>
    </form>
    <?php
        $color=isset($_GET['color'])?$_GET['color'] : "";
    ?>
    <p class="selected">
        <?php
            echo $color;    
        ?>
        : is selected click on SUBMIT button
    </p>
</div>

<div class="section1">
    <div class="section2">
    <p class="records">Sub Categories:</p>
    <ol class="categories">
        <?php
        $color=isset($_GET['color'])?$_GET['color'] : "";
        $stat=$dbh->prepare("select DISTINCT category from table1 where project='$color' order by category asc");
        $stat->execute();
        while($row=$stat->fetch()){
        echo "<li>".$row['category']."</li>";
        }
        ?>
    </ol>
    </div>
    <div class="section3">
    <table class="list">
    <tr><th>Category</th><th>File Name</th><th>Operation</th></tr>
    <?php
        if(isset($_POST["submit"])){
            $color=isset($_GET['color'])?$_GET['color'] : "";
            $str=$_POST["search"];
            $stat=$dbh->prepare("select * from table1 WHERE (project='$color') AND (name like '%$str%' or category like '%$str%')");
            $stat->execute();
            while($row=$stat->fetch()){
                echo "<tr> <th>".$row['category']."</th> <td><a target='_blank' href='view.php?id=".$row['id']."'>".$row['name']."</a> </td> <td><a target='_blank' href='delete.php?id=".$row['id']."'>Delete</a></td></tr>";
            }
        }
    ?>
    </table>
    </div>
</div>
</body>
</html>