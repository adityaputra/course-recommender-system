<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Administrator Login</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url();?>public/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url();?>public/css/signin.css" rel="stylesheet">

  </head>

  <body>

    <div class="container">

      <form class="form-signin" role="form" action="<?php echo base_url();?>index.php/admin/log/in" method="post">
        <h2 class="form-signin-heading">Administrator Login</h2>
        <label for="inputUsername" class="sr-only">Username</label>
        <input type="text" id="inputUsername" name="inputUsername" class="form-control" placeholder="Username" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
<!--         <div class="checkbox"> -->
<!--           <label> -->
<!--             <input type="checkbox" value="remember-me"> Remember me -->
<!--           </label> -->
<!--         </div> -->
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

    </div> <!-- /container -->

  </body>
</html>
