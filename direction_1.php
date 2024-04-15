<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="css/style1.css?v=<?php  echo time();?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body>
<div class="container">
    <div class="form-box box">
        <form action="registration.php" method="POST">
        <center><a href="login_pre_final.php" class="btn">Pre-Final Year</a></center>
        
        <br>
        <br>
        <center><a href="login_final.php" class="btn">Final Year</a></center>

        </form>
    </div>
</div>


  <script>
    const toggle = document.querySelector(".toggle"),
      input = document.querySelector(".password");
    toggle.addEventListener("click", () => {
      if (input.type === "password") {
        input.type = "text";
        toggle.classList.replace("fa-eye-slash", "fa-eye");
      } else {
        input.type = "password";
      }
    })
  </script>
</body>

</html>