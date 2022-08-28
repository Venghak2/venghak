<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "m4";

    $conn = mysqli_connect($host, $user, $pass, $db) or die("Error connecting database");

    // 0-delete
    // 1-add
    // 2-edit
    // 3-update
    $stid = "";
    $stname = "";
    $stsex = "M";
    $stscore = "";
    $stimage = "";
    $newname = "Add";

    if(isset($_GET['action']))
    {
        $action = $_GET['action'];
        switch($action){
            case "0":
                $stid = $_GET['stid'];
                $stimage = $_GET['file'];
                $sql = "delete from student where stid= $stid";
                unlink("image/$stimage");
                mysqli_query($conn,$sql) or die("Error in deleting recode");
                break;
            case "1":
                $stname = $_POST['txtname'];
                $stsex = $_POST['sdosex'];
                $stscore = $_POST['txtscore'];
                $stimage = "noname.jpg";
                    if(!empty($_FILES['txtfile'] && $_FILES['txtfile']['size'])>0 )
                    {
                        $stimage = "pic_". date("Y-m-d_H-i-s-v").".jpg";
                        move_uploaded_file($_FILES['txtfile']['tmp_name'], "image/$stimage");
                    }


                $sql = "INSERT INTO student (stname,stsex,stscore,stimage)  VALUES('$stname', '$stsex', $stscore, '$stimage')";
                mysqli_query($conn,$sql) or die("Error in inserting recode!");
                break;
            case "2":
                $stid = $_GET['stid'];
                $sql = "select * from student  where stid=$stid";
                $result = mysqli_query($conn, $sql) or die("could not selecting a recode");
                $row = mysqli_fetch_array($result);
                $stname = $row['stname'];
                $stsex = $row['stsex'];
                $stscore = $row['stscore'];
                $stimage = $row['stimage'];
                $newname = "Update";
                break;
            case "3":
                $stid = $_GET['stid'];
                $stname = $_POST['txtname'];
                $stsex = $_POST['sdosex'];
                $stscore = $_POST['txtscore'];
                $stimage = $_POST['stimage'];

                if(!empty($_FILES['txtfile'] && $_FILES['txtfile']['size'])>0 )
                    {
                        $newstimage = "pic_". date("Y-m-d_H-i-s-v").".jpg";
                        move_uploaded_file($_FILES['txtfile']['tmp_name'], "image/$newstimage");
                        unlink("image/$stimage");
                        $stimage = $newstimage;
                    }
                $sql = "update student set stname='$stname', stsex='$stsex', stscore='$stscore', stimage='$stimage' 
                        where stid=$stid";
                mysqli_query($conn,$sql) or die("Error in inserting recode!");
                break;
        }

    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        h1,form,table {
            margin-left: 20%;
        }
    </style>
</head>
<body>

    <!-- select form database -->
        <?php 
            $sql = "select * from student order by stid asc";
            $result = mysqli_query($conn, $sql) or die("Error not conneting database");
        ?>
    <!-- end select form database -->

    <!-- form -->
        <h1>Information Students</h1>
        <form action="student.php?<?=($newname=='Add')?'action=1':'action=3&stid='.$stid?> " method ="post" enctype="multipart/form-data">
            Name: <input type="text" name ="txtname" value ="<?=$stname?>" ><br>
            Sex: <br>
            <input type="radio" name ="sdosex" value="M" style="margin-left:30px"<?=($stsex=="M")?"checked":""?> >Male<br>
            <input type="radio" name ="sdosex" value="F" style="margin-left:30px"<?=($stsex=="F")?"checked":""?> >Female<br><br>
            Score: <input type="text" name ="txtscore" value ="<?=$stscore?>" ><br><br>
            Image: <input type="file" name ="txtfile" value ="<?=$stimage?>" ><br>
            <input type="hidden" name="stimage" value="<?=$stimage?>">
            <p><?=$stimage?></p><br>
            <input type="submit" value ="<?=$newname?>"> 
            
        <?php
            if($newname=="Add")
            {
        ?>
            <input type="reset" value ="Clare">
        <?php
            }
            else
            {
        ?>
            <input type="button" value ="Cancel" onclick="window.location.href= 'student.php' ">
        <?php
            }
        ?>

        </form>
    <!-- end form -->
    <br><br><br>
    <!-- table -->
        <table border="1px" width="60%">
            <tr>
                <th>ID</th>
                <th>Name Student</th>
                <th>Sex</th>
                <th>Score</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
           
            <?php
                while($row = mysqli_fetch_array($result))
                {
            ?>
                    <tr>
                        <td><?=$row['stid']?></td>
                        <td><?=$row['stname']?></td>
                        <td><?=$row['stsex']?></td>
                        <td><?=$row['stscore']?></td>
                        <td><img src="image/<?=$row['stimage']?>" width="50px"></td>
                        <td>
                            <a href="student.php?action=2&stid=<?=$row['stid']?>">Edit</a>
                            <a href="student.php?action=0&stid=<?=$row['stid']?>&stimage=<?=$row['stimage']?>">Delete</a>
                        </td>
                        
                    </tr>
            <?php
                }
            ?>
           
        </table>
    <!-- end table -->
</body>
</html>
<?php 
    mysqli_close($conn);
?>