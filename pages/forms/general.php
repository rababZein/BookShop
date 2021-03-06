<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Books Shop</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="../../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="../../dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-blue">
  <?php

   session_start();
    if(!isset($_SESSION['userName'])){
      header("Location: ../examples/login.php");
    }

    require '../../ormProject.php';
    require '../../validation.php';
    $connection=ORM::getInstance();
    $connection->setTable('category');

    $categories=$connection->selectAll( "*");

    $imageFlag=0;

    if ($_POST) {
       $validation = new Validation();
       $title=$_POST['Title'];
       $author=$_POST['Author'];
       $intro=$_POST['Intro'];
       $cat=$_POST['Cat'];
       //echo $cat;
       $condition = array('name' => $cat, );
       $catId=$connection->selectRow("id",$condition);
       //var_dump($catId['id']); exit();
       $rules=array(
                 'Cat'=>'required',
                 'Title'=>'required',
                 'Author'=>'required',
              
             );
       //var_dump($_FILES); exit();
      if ($_FILES['userfile']['error'] > 0)
      {
      
      switch ($_FILES['userfile']['error'])
      {
        case 1: $imageError= 'File exceeded upload_max_filesize';
        break;
        case 2: $imageError= 'File exceeded max_file_size';
        break;
        case 3: $imageError= 'File only partially uploaded';
        break;
        case 4: $imageError= 'No file uploaded';
        break;
        case 6: $imageError= 'Cannot upload file: No temp directory specified';
        break;
        case 7: $imageError= 'Upload failed: Cannot write to disk';
        break;
      }
      //exit;
    }
    else{ 
      //echo "hhgg";
      // put the file where we’d like it
      $upfile = '../../uploads/'.$_FILES['userfile']['name'];
      //echo "hh"; exit;
      // Does the file have the right MIME type?
      if ($_FILES['userfile']['type'] != 'image/jpeg' and $_FILES['userfile']['type'] != 'image/png')
      {
        $imageError= 'Problem: file is not jpeg or png image  ';
        //exit;
      }
      if (is_uploaded_file($_FILES['userfile']['tmp_name']))
      {
        if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $upfile))
        {
          $imageError= 'Problem: Could not move file to destination directory';
          //exit;
        }
        else
        {
          $imageError= 'success';
          //exit;
          $imageFlag=1;
        }
      }
      else
      {
        $imageError= 'Problem: Possible file upload attack. Filename: ';
        echo $_FILES['userfile']['name'];
        //exit;
      }
      //echo 'File uploaded successfully<br><br>';
      //exit;
    
    } 
        if($validation->validate($_POST,$rules)==true && $imageFlag==1){
         // echo "string"; exit();
           $data=array(

            'title'=>$title,
            'auther'=>$author,
            'intro'=>$intro,
            'catId'=>$catId['id'],
            'cover'=>$_FILES['userfile']['name'],
            
            );
          $connection->setTable('book');
          $insert=$connection->insert($data);
          if($insert==1){

            header("Location: ../layout/collapsed-sidebar.php");
            
          }else{

            $errorInsert="there is a title with the same name"; 
          }
        }else{

          foreach($validation->errors as $error){
            $validationError[]=$error;
            //echo $error;
          }
          
        }
    }
    
    if(!empty($_GET['editid'])){
        $connection->setTable('book');
        $condition = array('id' => $_GET['editid'], );
        $bookPopulate=$connection->selectRow("*",$condition);
        //$_FILES['userfile']['name']=$bookPopulate['cover'];

    }
     
    $allBooks=$connection->selectAll("*"); 

    $connection->setTable('category');

    $categories=$connection->selectAll( "*");

    $connection->setTable('book');
    $allBooks=$connection->selectAll("*");


    if (!empty($_POST['search'])) {
       # code...
      $search=$_POST['search'];
      $connection->setTable('book');
      $condition = array('title' => $search, );

      $bookSearch=$connection->selectAll("*",$condition);
     // var_dump($bookSearch[0]['id']); exit();
      if (!empty($bookSearch)) {
        # code...
        header( "Location: ../examples/blank.php?id=".$bookSearch[0]['id']);
      }else{
        header( "Location: ../examples/404.php");
      }
      
  

     }



  ?>
    <!-- Site wrapper -->
    <div class="wrapper">
      
      <header class="main-header">
        <a href="#" class="logo"><b>Books</b>Shop</a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
            
              <!-- Notifications: style can be found in dropdown.less -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning"><?php echo count($allBooks);?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have <?php echo count($allBooks);?> books</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                    <?php for($i = 0; $i<count($allBooks); $i++){ ?>
                      <li>
                        <a href="../examples/blank.php?id=<?php echo $allBooks[$i]['id']?>">
                          <i class="fa fa-users text-aqua"></i> <?php echo $allBooks[$i]['title'];?>
                        </a>
                      </li>
                      <?php }?>
                    </ul>
                  </li>
                  <li class="footer"><a href="../layout/collapsed-sidebar.php?all='all'">View all</a></li>
                </ul>
              </li>
            
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="../../dist/img/rabab.jpg" class="user-image" alt="User Image"/>
                  <span class="hidden-xs">Rabab Zein</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="../../dist/img/rabab.jpg" class="img-circle" alt="User Image" />
                    <p>
                      Rabab Zein - Web Developer
                      <small>ITI intake 35</small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <li class="user-body">
                    <div class="col-xs-4 text-center">
                      <a href="https://www.facebook.com/rabab.zein">FaceBook</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="https://github.com/rababZein">GitHub</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="https://eg.linkedin.com/pub/rabab-zein/67/619/48b">LinkedIn</a>
                    </div>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="https://www.facebook.com/rabab.zein" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="../layout/logout.php" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- =============================================== -->

      <!-- Left side column. contains the sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="../../dist/img/avatar5.png" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p><p><?php echo $_SESSION['userName']?></p></p>

              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
           <!-- search form -->
          <form action="#" method="post" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="search"  class="form-control" placeholder="Enter Full TiTle name..." onkeyup="myAutocomplete(this.value)"  id="quickSearch"/>
              <span class="input-group-btn">
                <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
           
           
            <li class="treeview active">
              <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Categories</span>
                <span class="label label-primary pull-right"><?php echo count($categories) ; ?></span>
              </a>
              <ul class="treeview-menu">
              <li><a href="../layout/collapsed-sidebar.php?all='all'">All Book</a></li>
              <?php  

              for($i = 0; $i<count($categories); $i++){
              //foreach ($categories[$i] as $key=>$value){ ?>
              
                <li><a href="../layout/collapsed-sidebar.php?id=<?php echo $categories[$i]['id'];?>"><i class="fa fa-circle-o"></i> <?php echo $categories[$i]['name'] ;?></a></li>
             <!--    <li><a href="boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
                <li><a href="fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
                <li class="active"><a href="collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
               -->
               <?php }?>
               </ul>
            </li>
            
            
            
            <li class="treeview">
              <a href="#">
                <i class="fa fa-edit"></i> <span>addition</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="../forms/general.php"><i class="fa fa-circle-o"></i>Add Book</a></li>
                <li><a href="../forms/advanced.php"><i class="fa fa-circle-o"></i> Add Category</a></li>
      
              </ul>
            </li>
            
            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Addition</a></li>
            <li class="active">Add Book</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
               
            <!-- right column -->
            <div class="col-md-6">
              <!-- general form elements disabled -->
              <div class="box box-warning">
                <div class="box-header">
                  <h3 class="box-title">Add Book</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <form action="#" method="post" enctype="multipart/form-data">
                    <!-- text input -->

                   


                    <div class="form-group">
                      <label>Title</label>
                       <?php
                      if(!empty($validationError)){
                        foreach ($validationError as $error){
                          $pieces = explode(" ", $error);
                          if($pieces[0]=="Title"){
                          ?>
                          <div class="form-group has-error">
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> Input with error : <?php echo $error; ?></label>
                          
                          <?php

                          }
                        }
                      }
                     ?>

                     <?php
                        if (!empty($errorInsert)) { ?>
                          <div class="form-group has-error">
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> Input with error : <?php echo $errorInsert; ?></label>
                     <?php
                        }
                     ?>
                      <input type="text" name="Title" class="form-control" placeholder="Enter ..." value="<?php if(!empty($_GET['editid'])) {echo $bookPopulate['title'];}elseif(!empty($_POST['Title'])){echo $_POST['Title'];}?>" id="inputError" />
                    <?php
                    if(!empty($validationError)){
                        foreach ($validationError as $error){
                          $pieces = explode(" ", $error);

                     if($pieces[0]=="Title"){
                    echo "</div>";

                    }}}?>


                      <?php
                        if (!empty($errorInsert)) { ?>
                          </div>
                     <?php
                        }
                     ?>


                    </div>


                    <div class="form-group">
                      <label>Author</label>
                       <?php
                      if(!empty($validationError)){
                        foreach ($validationError as $error){
                          $pieces = explode(" ", $error);
                          if($pieces[0]=="Author"){
                          ?>
                          <div class="form-group has-error">
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> Input with error : <?php echo $error; ?></label>
                          
                          <?php

                          }
                        }
                      }
                     ?>
                      <input type="text" name="Author" class="form-control" placeholder="Enter ..." value="<?php if(!empty($_GET['editid'])) echo $bookPopulate['auther']; elseif(!empty($_POST['Author'])) echo $_POST['Author'];?>" />
                    

                    <?php
                    if(!empty($validationError)){
                        foreach ($validationError as $error){
                          $pieces = explode(" ", $error);

                     if($pieces[0]=="Title"){
                    echo "</div>";

                    }}}?>
                    </div>

                    <!-- textarea -->
                    <div class="form-group">
                      <label>Introduction</label>
                      
                      <textarea name="Intro" class="form-control" rows="3" placeholder="Enter ..."  > <?php if(!empty($_GET['editid'])) echo $bookPopulate['intro'];?> </textarea>

                    </div>
     

                    <!-- select -->
                    <div class="form-group">
                      <label>Select Category :</label>
                      <select class="form-control" name="Cat">
                  <?php for($i = 0; $i<count($categories); $i++){ 
                        if ($_POST['Cat']==$categories[$i]['name']) {
                          # code...
                        

                    ?>

                        <option selected><?php echo $categories[$i]['name'];?></option>
                  <?php }else{?>
                      <option ><?php echo $categories[$i]['name'];?></option>
                      <?php }}?>
                      </select>
                    </div>
                    

                    <div class="form-group">
                      <label for="exampleInputFile">Upload Cover</label>
                      <?php
                      if(!empty($imageError)){
                        
                          ?>
                          <div class="form-group has-error">
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> Input with error : <?php echo $imageError; ?></label>
                          
                          <?php

                      }
                     
                     ?>
                      <input type="file" name="userfile" id="exampleInputFile" >
			

                         <?php
                    if(!empty($imageError)){
                        

                     
                    echo "</div>";

                   }?>
                    </div>
                      
                      <p class="help-block">Upload cover for book .....</p>
                    </div>


                    <div class="box-footer">

                        <button class="btn btn-primary" type="submit">

                            Submit

                        </button>

                    </div>

                  </form>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!--/.col (right) -->
          </div>   <!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.0
        </div>
        <strong>Copyright &copy; 2015-6-25 <a href="https://www.facebook.com/rabab.zein">Rabab Zein</a>.</strong> All rights reserved.
      </footer>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.3 -->
    <script src="../../plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="../../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='../../plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/app.min.js" type="text/javascript"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../dist/js/demo.js" type="text/javascript"></script>
  </body>
</html>
