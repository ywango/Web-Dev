<?php
	session_start();
	$diaryContent = "";
	if(array_key_exists("id", $_COOKIE)) {
		$_SESSION["id"] = $_COOKIE["id"];
	}
	if(array_key_exists("id", $_SESSION)) {
		//echo "<p>Logged In!<a href='secret diary-homepage.php?logout=1'>Log Out</a></p>";
		include("connection.php");
		$query = "SELECT `diary` FROM `users` WHERE id='".mysqli_real_escape_string($link,$_SESSION["id"])."' LIMIT 1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_array($result);
		$diaryContent = $row["diary"];
	} else {
		header("Location: secret diary-homepage.php");
	}	
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<script src="jquery-3.2.1.min.js"></script>
		<style type="text/css">
			html { 
				background: url(background.jpg) no-repeat center center fixed; 
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
			}
			body {
				background:none;
				color: #291A11;
				}
			#diary {
				width: 100%;
				height: 100%;
			}
			.fill-height {
				height: 85vh;
			}
			.nav {
				margin: 15px 10px;
			}
			#title {
				font-weight: bold;
				font-size:120%;
			}
		</style>
	</head>
	<body>
		<ul class="nav justify-content-end">
			<li class="nav-item">
				<a class="nav-link" href="" id="title">Secret Diary</a>
			</li>
			<li class="nav-item">
				<a href="secret diary-homepage.php?logout=1"><button class="btn btn-outline-success my-2 my-sm-0" type="submit">Log Out</button></a>
			</li>
		</ul>
		
		<div class="container-fluid fill-height">
			<textarea id="diary" class="form-control"><?php echo $diaryContent;?></textarea>
		</div>
		
		<!-- jQuery first, then Tether, then Bootstrap JS.-->
		<!--
		<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
		-->
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		<script type="text/javascript">		
			$("#diary").bind("input propertychange", function() {
				$.ajax({
					method: "POST",
					url: "update database.php",
					data: { content: $("#diary").val() }
				})
				/*
				.done(function( msg ) {
					alert( "Data Saved: " + msg );
				});*/
			});
		</script>
	</body>
</html>