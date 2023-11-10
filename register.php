<?php

include 'config.php';

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = md5($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_size = $_FILES['image']['size'];
   $image_folder = 'uploaded_img/'.$image;

   $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select->execute([$email]);

   if($select->rowCount() > 0){
      $message[] = 'user already exist!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }elseif($image_size > 2000000){
         $message[] = 'image size is too large!';
      }else{
         $insert = $conn->prepare("INSERT INTO `users`(name, email, password, image) VALUES(?,?,?,?)");
         $insert->execute([$name, $email, $cpass, $image]);
         if($insert){
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'registered succesfully!';
            header('location:login.php');
         }
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>


   <style>

      
      .input-control {
         transition: border .1s linear 0s, box-shadow .1s, width .25s, color .2s;
         border: 1px solid #ced4da;
         box-shadow: 0 1px 3px rgb(50 50 93 / 10%), 0 1px 0 rgb(0 0 0 / 2%);
         display: block;
         padding: .375rem .75rem;
         font-size: .969rem;
         font-weight: 400;
         line-height: 1.6;
         color: #212529;
         background-color: #FFF;
         background-clip: padding-box;
         appearance: none;
         border-radius: .12rem;
         margin-bottom: 1rem;
      }
      .input-control:focus {
         border-color: #9fd4fc;
         box-shadow: 0 0 0 4px rgb(0 149 255 / 15%);
         background-color: #fffffa;
         outline: 0;
      }
      
      .button {
         color: #FFFFFF;
         background: black;
         border: 0;
         padding: .5rem;
         border-radius: .12rem;
      }
   </style>

</head>
<body>



<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>
   
<section class="form-container">

<div class="box-outer">
    <form action="" method="post" enctype="multipart/form-data">
      <h3>registeration form</h3>
      <input type="text" required placeholder="enter your username" class="input-control" name="name">
      <input type="email" required placeholder="enter your email" class="input-control" name="email">
      <input type="password" required placeholder="enter your password" class="input-control" name="pass">
      <input type="password" required placeholder="confirm your password" class="input-control" name="cpass">
      <input type="file" name="image" required class="box" accept="image/jpg, image/png, image/jpeg">
      <p>already have an account? <a href="login.php">login now</a></p>
      <input type="submit" value="register now" class="button" name="submit">
    </form>
    
</div>

</section>

</body>
</html>