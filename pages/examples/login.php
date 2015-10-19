<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Books Shop | Log in</title>
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
			$rules=array(
				'Email'=>'email|required',
				'Password'=>'required',	
			);
			if($validation->validate($_POST,$rules)==true){
       
				$Email=$_POST['Email'];
				$Password=$_POST['Password'];
				$data=array(
					
					'password'=>md5($Password),
					'email'=>$Email,
					
				);
				$result =  $connection->selectRow(  "*" , $data );
				if($result){
          // session_start();
					$_SESSION['userId'] = $result['userId'];
					$_SESSION['userName'] = $result['userName'];
					$id=$result['userId'];
				  $_SESSION['admin'] = $result['admin'];
	
					header("Location: ../layout/collapsed-sidebar.php");

				}else{
					$loginError= '<p class="error">'."your email or password is not correct".'</p>';
					
					foreach($connection->errorsInDataBase as $error){
						$dataBaseError= '<li  class="error">' .$error . '</li>';
					}
				}
				
				
			}
			else{
		
				foreach($validation->errors as $error){
					$validationError[]= $error;
          
				}
			}
			
		}
	?>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="../../index2.html"><b>Book</b>Shop</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <!-- <form action="../../index2.html" method="post" > -->
        <form action="#" method="POST" >


        <?php

            if (!empty($loginError)) {

        ?>
             <div class="form-group has-error">
              <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> Input with error : <?php echo $loginError; ?></label>
               </div>

               <?php }?>     


        
          

            <?php
                if(!empty($validationError)){
                  foreach ($validationError as $error){
                    $pieces = explode(" ", $error);
                    if($pieces[0]=="Email"){
                    ?>
                    <div class="form-group has-error">
                      <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> Input with error : <?php echo $error; ?></label>
                    
                    <?php

                    }
                  }
                }
           ?>



           <div class="form-group has-feedback">

            <input type="email" name="Email" class="form-control" placeholder="Email" id="inputError" />
                
            </div>
                <?php
                    if(!empty($validationError)){
                        foreach ($validationError as $error){
                          $pieces = explode(" ", $error);

                     if($pieces[0]=="Email"){
                    echo "</div>";

                    }}}?>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>



            <?php
                if(!empty($validationError)){
                  foreach ($validationError as $error){
                    $pieces = explode(" ", $error);
                    if($pieces[0]=="Password"){
                    ?>
                    <div class="form-group has-error">
                      <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> Input with error : <?php echo $error; ?></label>
                    
                    <?php

                    }
                  }
                }
           ?>
          
          <div class="form-group has-feedback">
            <input type="password" name="Password" class="form-control" placeholder="Password"  />
            <?php
                    if(!empty($validationError)){
                        foreach ($validationError as $error){
                          $pieces = explode(" ", $error);

                     if($pieces[0]=="Password"){
                    echo "</div>";

                    }}}?>

            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>

      
        <a href="register.php" class="text-center">Register a new membership</a>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

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