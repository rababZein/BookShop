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
      header("Location: login.php");
    }


    require '../../ormProject.php';
    $connection=ORM::getInstance();

    if (!empty($_POST['search'])) {
       # code...
      $search=$_POST['search'];
      $connection->setTable('book');
      $condition = array('title' => $search, );

      $bookSearch=$connection->selectAll("*",$condition);
     // var_dump($bookSearch[0]['id']); exit();
      if (!empty($bookSearch)) {
        # code...
        header( "Location: blank.php?id=".$bookSearch[0]['id']);
      }else{
        header( "Location: 404.php");
      }
      
  

     }

    $connection->setTable('category');

    $allBooks=$connection->selectAll("*"); 

    $connection->setTable('category');

    $categories=$connection->selectAll( "*");

    $connection->setTable('book');
    $allBooks=$connection->selectAll("*");


    




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
                  <li class="footer"><a href="collapsed-sidebar.php?all='all'">View all</a></li>
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
              <p><?php echo $_SESSION['userName']?></p>

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
               ?>
              
                <li><a href="../layout/collapsed-sidebar.php?id=<?php echo $categories[$i]['id'];?>"><i class="fa fa-circle-o"></i> <?php echo $categories[$i]['name'] ;?></a></li>
             
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
          <h1>
            404 Error Page
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Books</a></li>
            <li class="active">404 error</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

          <div class="error-page">
            <h2 class="headline text-yellow"> 404</h2>
            <div class="error-content">
              <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found There are not book with this title .</h3>
              <p>
                We could not find the page you were looking for.
                Meanwhile, you may <a href='../layout/collapsed-sidebar.php'>return to Home</a> or try using the search form.
              </p>
              <form action="#" method="post" class='search-form'>
                <div class='input-group'>
                  <input type="text" name="search" class='form-control' placeholder="Search"/>
                  <div class="input-group-btn">
                    <button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i></button>
                  </div>
                </div><!-- /.input-group -->
              </form>
            </div><!-- /.error-content -->
          </div><!-- /.error-page -->
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
