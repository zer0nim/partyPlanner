<?php include('../view/header.php') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
</head>
<body class="container-fluid">

	<header>
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="accueil.ctrl.php">My Wedding</a>
				</div>

				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav">
						<li><a href="accueil.ctrl.php">Accueil</a></li>
						<li><a href="creation.ctrl.php">Paramètres mariage</a></li>
						<li class="active"><a href="">Page Publique</a></li>
						<li><a href="mon_compte.ctrl.php">Mon compte</a></li>
					</ul>

					<ul class="nav navbar-nav navbar-right">
						<li><a href="session_delete.ctrl.php"><span class="glyphicon glyphicon-user"></span> Sign out</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</header>


<?php
 include('../view/scripts.php');
 include('../view/footer.php');
?>
