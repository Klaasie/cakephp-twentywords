<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts.Emails.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title><?php echo $title_for_layout;?></title>
	<style>
		body {
			position: relative;
			width: 600px;
			margin: 15px auto;
			background: -moz-linear-gradient(top,  rgba(127,193,218,0.67) 0%, rgba(127,193,218,0.5) 50%, rgba(127,193,218,1) 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(127,193,218,0.67)), color-stop(50%,rgba(127,193,218,0.5)), color-stop(100%,rgba(127,193,218,1))); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,  rgba(127,193,218,0.67) 0%,rgba(127,193,218,0.5) 50%,rgba(127,193,218,1) 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,  rgba(127,193,218,0.67) 0%,rgba(127,193,218,0.5) 50%,rgba(127,193,218,1) 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top,  rgba(127,193,218,0.67) 0%,rgba(127,193,218,0.5) 50%,rgba(127,193,218,1) 100%); /* IE10+ */
			background: linear-gradient(to bottom,  rgba(127,193,218,0.67) 0%,rgba(127,193,218,0.5) 50%,rgba(127,193,218,1) 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ab7fc1da', endColorstr='#7fc1da',GradientType=0 ); /* IE6-9 */
			background-attachment: fixed;
			font-size: 14px;
		}
		.header {

		}
		.content {

		}
		.title {
			position: relative;
			padding: 15px;
			background: #062a4c;
			font-family: 'Asap', sans-serif;
			font-size: 18px;
			font-weight: bold;
			font-style: italic;
			text-align: center;
			width: 100%;
			margin: 15px 0 15px 0;
			color: #fff;
		}
		.body {
			position: relative;
			width: 100%;
			background: #dff0f6;
			margin-top: 20px;
			padding: 15px;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
		}
		.footer {
			position: relative;
			width: 100%;
			margin: 15px 0;
		}
	</style>
	<link href='http://fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
</head>
<body>
	<div class="header">
		<?= $this->Html->image("logo.png", array('fullBase' => true)); ?>
	</div>
	<div class="content">
		<?php echo $this->fetch('content');?>
	</div>
</body>
</html>