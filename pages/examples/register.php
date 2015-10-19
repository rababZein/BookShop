<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Book Shop | Registration Page</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="../../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="../../plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <?php
    session_start();
    if(isset($_SESSION['userName'])){
      header("Location: ../layout/collapsed-sidebar.php");
    }
    require '../../ormProject.php';
    require '../../validation.php';
    $connection=ORM::getInstance();
    $connection->setTable('user');
    if($_POST){
      
        $validation = new Validation();
        $userName=$_POST['userName'];
        $Password=$_POST['Password'];
        $ConfirmPassword=$_POST['ConfirmPassword'];
        $Email=$_POST['Email'];
    
        $rules=array(
          'userName'=>'required',
          'Email'=>'email|required',
          'Password'=>'required',
          'ConfirmPassword'=>'required',
    
        
          );
          $checkPassword=$validation->confirmPassword($Password,$ConfirmPassword);
        if($validation->validate($_POST,$rules)==true and $checkPassword==true){
          $data=array(
            'userName'=>$userName,
            'password'=>md5($Password),
            'email'=>$Email,
        
            );
          
          $insert=$connection->insert($data);
          if($insert==1){
            $data = array('userName' => $userName, );
            $result =  $connection->selectRow(  "*" , $data );
            $_SESSION['userId'] = $result['userId'];
            $_SESSION['userName'] = $userName;
            $_SESSION['admin'] = $result['admin'];
            header("Location: ../layout/collapsed-sidebar.php");
          }else{
            
            $insertError="user name or email are already used!";
         
            
          }

        }
      
    }
    ?>
  


  <body class="register-page">
    <div class="register-box">
      <div class="register-logo">
        <a href="../../index2.html"><b>Book</b>Shoop</a>
      </div>
    <?php if (! empty($validation->errors)) { ?>
         <?php if (count($validation->errors) > 0) { ?>
                <div class="alert alert-danger">
                    <strong>ooh </strong>validError<br><br>
                    <ul>
                       <?php foreach ($validation->errors as $key => $error )  { 
                          echo "<li>".$error."</li>";
                        } ?>
                    </ul>
                </div>
          <?php } 

          }?>

       <?php if (! empty($insertError)) { ?>
         
                <div class="alert alert-danger">
                    <strong>ooh </strong>Input Error<br><br>
                    <ul>
                       <?php 
                          echo "<li>".$insertError."</li>";
                       ?>
                    </ul>
                </div>
          <?php  

          }?>



      <div class="register-box-body">
        <p class="login-box-msg">Register a new membership</p>
        <form action="#" method="post">
        <!--  <form action="../../index.html" method="post"> -->
          <div class="form-group has-feedback">
            <input type="text" name="userName" class="form-control" placeholder="Full name" value="<?php if(!empty($_POST['userName'])){echo $_POST['userName'];}?>" />
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" name="Email" class="form-control" placeholder="Email" value="<?php if(!empty($_POST['Email'])){echo $_POST['Email'];}?>"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="Password" class="form-control" placeholder="Password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="ConfirmPassword" class="form-control" placeholder="Retype password"/>
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div>
          <div class="row">
           
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
            </div><!-- /.col -->
          </div>
        </form>        

        

        <a href="login.php" class="text-center">I already have a membership</a>
      </div><!-- /.form-box -->
    </div><!-- /.register-box -->

    <!-- jQuery 2.1.3 -->
    <script src="../../plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="../../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="../../plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>