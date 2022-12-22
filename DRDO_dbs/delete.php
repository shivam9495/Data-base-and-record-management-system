<?php
    if(isset($_POST['confirm'])){
        if($_POST['confirm']=='Yes')
        {
            $dbh= new PDO("mysql:host=localhost;dbname=abc", "root", "");
            $id=isset($_GET['id'])?$_GET['id'] : "";
            $stat=$dbh->prepare("delete from table1 where id=?");
            $stat->bindParam(1,$id);
            if($stat->execute())
            {
                echo "<script>window.close();</script>";
            }
            else{
                echo "Not Deleted";
            }
        }
        else
        {
            echo "<script>window.close();</script>";
        }
    }
    
?>
<h3>Do you really want to delete</h3>
<form method="post">
<input type="submit" name="confirm" value="Yes"><br/><br/>
<input type="submit" name="confirm" value="No"><br/>
</form>