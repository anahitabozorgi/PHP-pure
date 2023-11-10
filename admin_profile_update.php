<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   
   $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
   $update_profile->execute([$name, $email, $admin_id]);

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_size = $_FILES['image']['size'];
   $image_folder = 'uploaded_img/'.$image;

   if(!empty($image)){

      if($image_size > 2000000){
         $message[] = 'image size is too large';
      }else{
         $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $admin_id]);

         if($update_image){
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('uploaded_img/'.$old_image);
            $message[] = 'image has been updated!';
         }
      }

   }

   $old_pass = $_POST['old_pass'];
   $previous_pass = md5($_POST['previous_pass']);
   $previous_pass = filter_var($previous_pass, FILTER_SANITIZE_STRING);
   $new_pass = md5($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = md5($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if(!empty($previous_pass) || !empty($new_pass) || !empty($confirm_pass)){
      if($previous_pass != $old_pass){
         $message[] = 'old password not matched!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'confirm password not matched!';
      }else{
         $update_password = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_password->execute([$confirm_pass, $admin_id]);
         $message[] = 'password has been updated!';
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

   <title>admin profile update</title>


   <style>
      .title{
         text-align: center;
      }

      .update-profile-container{
         display: flex;
         align-items: center;
         justify-content: center;
         padding:2rem;
         min-height: 100vh;
      }
      
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
      img{
      height: 20rem;
      width: 20rem;
      object-fit: cover;
      margin-bottom: .5rem;
      }
      a:link, a:visited{
      text-decoration: none;
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

<h1 class="title"> update <span>admin</span> profile </h1>

<section class="update-profile-container">

   <?php
      $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
      $select_profile->execute([$admin_id]);
      $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
   ?>

   <form action="" method="post" enctype="multipart/form-data">
      <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
      <div class="flex">
         <div class="inputBox">
            <span>username : </span>
            <input type="text" name="name" required class="input-control" placeholder="enter your name" value="<?= $fetch_profile['name']; ?>">
            <span>email : </span>
            <input type="email" name="email" required class="input-control" placeholder="enter your email" value="<?= $fetch_profile['email']; ?>">
            <span>profile pic : </span>
            <input type="hidden" name="old_image" value="<?= $fetch_profile['image']; ?>">
            <input type="file" name="image" class="input-control" accept="image/jpg, image/jpeg, image/png">
         </div>
         <div class="inputBox">
            <input type="hidden" name="old_pass" value="<?= $fetch_profile['password']; ?>">
            <span>old password :</span>
            <input type="password" class="input-control" name="previous_pass" placeholder="enter previous password" >
            <span>new password :</span>
            <input type="password" class="input-control" name="new_pass" placeholder="enter new password" >
            <span>confirm password :</span>
            <input type="password" class="input-control" name="confirm_pass" placeholder="confirm new password" >
         </div>
      </div>
      <div class="flex-btn">
         <input type="submit" value="update profile" name="update" class="button">
         <a href="index.php" class="button">go back</a>
      </div>
   </form>

</section>

</body>
</html>