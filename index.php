<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
</head>
<body>
    <?php
      if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(empty($_POST["username"]) || empty($_POST["phone"]) || empty($_POST["email"]) || empty($_POST["subject"]) || empty($_POST["msg"])){
            header("location:index.php?empty=1");
        }
        else{
            $username=validate($_POST["username"]);
            $phone=validate($_POST["phone"]);
            $email=validate($_POST["email"]);
            $subject=validate($_POST["subject"]);
            $msg=validate($_POST["msg"]);

            $id=0;
            $conn=mysqli_connect("localhost","root","","php_interview");
            $stmt=$conn->prepare("SELECT MAX(id) as sn FROM contact_form");
            $stmt->execute();
            $result=$stmt->get_result();
            if($r=$result->fetch_assoc()){
                $id=$r["sn"];
            }
            $id++;

            $contact_id="";
            $a=array();
            for($i='A';$i<='Z';$i++){
                array_push($a,$i);
                if($i=='Z'){
                    break;
                }
            }
            for($i='a';$i<='z';$i++){
                array_push($a,$i);
                if($i=='z'){
                    break;
                }
            }
            for($i=0;$i<=9;$i++){
                array_push($a,$i);
            }
            $b=array_rand($a,6);
            for($i=0;$i<sizeof($b);$i++){
                $contact_id=$contact_id.$a[$b[$i]];
            }
            $contact_id=$contact_id."_".$id;
            $ip=$_SERVER['REMOTE_ADDR'];

            $stmt=$conn->prepare("INSERT INTO contact_form (id,contact_id,username,phone,email,subject,message,ip) VALUES(?,?,?,?,?,?,?,?)");
            $stmt->bind_param("isssssss",$id,$contact_id,$username,$phone,$email,$subject,$msg,$ip);
            $stmt->execute();
            header("location:index.php?success=1");

        }
      }
      function validate($data){
        $data=trim($data);
        $data=stripslashes($data);
        $data=htmlspecialchars($data);
        return $data;
      }
    ?>
 <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>">
 FullName:<input type="text" name="username" class="form-control"><br>
 Phone No:<input type="number" name="phone" class="form-control"><br>
 Email:<input type="email" name="email" class="form-control"><br>
 Subject:<input type="text" name="subject" class="form-control"><br>
 Messgae:<textarea name="msg" class="form-control"></textarea><br>
 <input type="submit" value="Submit">
</form>
</body>
</html>