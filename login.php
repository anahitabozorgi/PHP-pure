<?php

include 'config.php';

session_start();

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select->execute([$email, $pass]);
   $row = $select->fetch(PDO::FETCH_ASSOC);

   if($select->rowCount() > 0){

      if($row['roll'] == 'admin'){

         $_SESSION['admin_id'] = $row['id'];
         header('location:index.php');

      }elseif($row['roll'] == 'user'){

         $_SESSION['user_id'] = $row['id'];
         header('location:index.php');

      }else{
         $message[] = 'no user found!';
      }
      
   }else{
      $message[] = 'incorrect email or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

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

   <form action="" method="post" enctype="multipart/form-data">
      <h3>login now</h3>
      <input type="email" required placeholder="enter your email" class="input-control" name="email">
      <input type="password" required placeholder="enter your password" class="input-control" name="pass">
      <p>don't have an account? <a href="register.php">register now</a></p>
      <input type="submit" value="login now" class="button" name="submit">
   </form>

</section>

</body>
</html>