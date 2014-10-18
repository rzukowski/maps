<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Bootstrap, from Twitter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/look.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->request->baseUrl.'/css/jquery.datetimepicker.css'; ?>" />



    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->

  </head>
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.datetimepicker.js" ></script>

  <script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/lookscripts.js" ></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/protected/views/event/OpenLayers-2.13.1/OpenLayers.js" ></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/protected/views/event/json2.js" ></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/openlayersimplementation.js" ></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/uiblock.js" ></script>
  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">Project name</a>
          <div class="nav-collapse collapse">
              
              <?php $this->widget('zii.widgets.CMenu',array(
                         'htmlOptions'=>array('class'=>'nav'),
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
                                array('label'=>'Rejestruj','url'=>array('/site/register')),
                            array('label'=>'Szukaj','url'=>array('/event/admin')),
                            array('label'=>'Event','url'=>array('/event/create')),
                             array('label'=>'Moje wydarzenia','url'=>array('/event/index')),
			),
		)); ?>
            
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <?php echo $content; ?>
    </div> <!-- /container -->

  



  </body>
</html>
