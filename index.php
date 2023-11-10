<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];
$admin_id = $_SESSION['admin_id'];

if(!isset($user_id) && !isset($admin_id)){
   ?>
   <!DOCTYPE html>
   <html>
   <head>
   <style>
   ul {
   list-style-type: none;
   margin: 0;
   padding: 0;
   overflow: hidden;
   border: 1px solid #e7e7e7;
   background-color: #f3f3f3;
   }

   li {
   float: left;
   }

   li a {
   display: block;
   color: #666;
   text-align: center;
   padding: 14px 16px;
   text-decoration: none;
   }

   li a:hover:not(.active) {
   background-color: #ddd;
   }

   li a.active {
   color: white;
   background-color: #04AA6D;
   }
   </style>
   </head>
   <body>

      <ul>
         <li><a href="register.php">Register</a></li>
         <li><a href="login.php">Login</a></li>
      </ul>

   </body>
   </html>
   <?php
}

elseif(isset($admin_id)){
   ?>
   <!DOCTYPE html>
   <html lang="en">
   <head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>admin page</title>

   <style>
   .profile-container{
   display: flex;
   align-items: center;
   justify-content: center;
   padding:2rem;
   min-height: 100vh;
}

.profile-container .profile{
   width: 40rem;
   border-radius: .5rem;
   background-color: var(--black);
   padding:2rem;
   text-align: center;
}

.profile-container .profile img{
   height: 20rem;
   width: 20rem;
   object-fit: cover;
   margin-bottom: .5rem;
}

.profile-container .profile h3{
   font-size: 2.5rem;
   padding:.5rem 0;
   color:var(--white);
}
a:link, a:visited{
  background-color: black;
  color: white;
  padding: 14px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
}
</style>

</head>
<body>


<section class="profile-container">

   <?php
      $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
      $select_profile->execute([$admin_id]);
      $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
   ?>

   <div class="profile">
      <h1 class="title"> <span>admin</span> profile page </h1>
      <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
      <h3><?= $fetch_profile['name']; ?></h3>
      <h5><?= $fetch_profile['email']; ?></h5>
      <a href="admin_profile_update.php" class="btn">update profile</a>
      <a href="logout.php" class="delete-btn">logout</a>
      <div class="flex-btn">
      </div>
   </div>

</section>

</body>
</html>
   <?php
}

else{
   
   ?> 
   <!DOCTYPE html>
   <html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <title>user page</title>


   <style>
      .profile-container{
      display: flex;
      align-items: center;
      justify-content: center;
      padding:2rem;
      min-height: 100vh;
   }

   .profile-container .profile{
      width: 40rem;
      border-radius: .5rem;
      background-color: var(--black);
      padding:2rem;
      text-align: center;
   }

   .profile-container .profile img{
      height: 20rem;
      width: 20rem;
      object-fit: cover;
      margin-bottom: .5rem;
   }

   .profile-container .profile h3{
      font-size: 2.5rem;
      padding:.5rem 0;
      color:var(--white);
   }
   a:link, a:visited{
   background-color: black;
   color: white;
   padding: 14px 25px;
   text-align: center;
   text-decoration: none;
   display: inline-block;
   }
   </style>

   </head>
   <body>



   <section class="profile-container">

      <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>

      <div class="profile">
         <h1 class="title"> user profile page </h1>
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <h5><?= $fetch_profile['email']; ?></h5>
         <a href="logout.php" class="button">logout</a>
         <div class="flex-btn">
         </div>
      </div>

   </section>

   </body>
   </html>
   <?php
}
?>
