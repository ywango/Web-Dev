<?php
	session_start();
	if(array_key_exists("logout", $_GET)) {
		unset($_SESSION);
		setcookie("id", "", time()-60*60);
		$_COOKIE["id"]="";
		session_destroy(); //Very important, which is omitted by Rob
	} else if ((array_key_exists("id", $_SESSION) && $_SESSION["id"]!="") || (array_key_exists("id", $_COOKIE) && $_COOKIE["id"]!="")) {
		header("Location: secret diary-login.php");
	}
	
	if(array_key_exists("submit", $_POST)) {
		//print_r($_POST);
		$error = "";
		if(!$_POST["email"]) {
			$error .= "An email address is required<br>";
		}
		if(!$_POST["password"]) {
			$error .= "A password is required<br>";
		}
		
		if($error != "") {
			$error = '<p>Errors found in the form:</p>'.$error;
		} else {
			$link = mysqli_connect("shareddb1d.hosting.stackcp.net", "secretdi-3231d4eb", "9qkYaAFdntUL", "secretdi-3231d4eb");
			if(mysqli_connect_error())
				die("There was an error connecting with the database!");
		
			if($_POST["signUp"]=="1") {
				$query = "SELECT `email` FROM `users` WHERE email = '".mysqli_real_escape_string($link,$_POST["email"])."' LIMIT 1";
				$result = mysqli_query($link, $query);
				if(mysqli_num_rows($result)>0) {
					$error = 'The email address is taken!';
				} else {
					$query = "INSERT INTO `users` (`email`,`password`) VALUE('".mysqli_real_escape_string($link,$_POST["email"])."', '".mysqli_real_escape_string($link,$_POST["password"])."')";
					if(mysqli_query($link, $query)) {
						$query = "UPDATE `users` SET password='".md5(md5(mysqli_insert_id($link)).$_POST["password"])."' WHERE id=".mysqli_insert_id($link)." LIMIT 1";
						mysqli_query($link, $query);
						$_SESSION["id"] = mysqli_insert_id($link);
						if(array_key_exists("stayLoggedIn", $_POST) && $_POST["stayLoggedIn"] == "1") {
							setcookie("id", mysqli_insert_id($link), time()+60*60*24);
						}
						header("Location: secret diary-login.php");
					} else {
						$error = 'There was a problem to signing you up, please try it later!';
					}
				}
			} else {
				//print_r($_POST);
				$query = "SELECT * FROM `users` WHERE email='".mysqli_real_escape_string($link,$_POST["email"])."'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_array($result);
				if(isset($row)) {
					$hashPassword = md5(md5($row["id"]).$_POST["password"]);
					if($hashPassword == $row["password"]) {
						$_SESSION["id"] = $row["id"];
						if(array_key_exists("stayLoggedIn", $_POST) && $_POST["stayLoggedIn"] == "1") 
							setcookie("id", $row["id"], time()+60*60*24);
						header("Location: secret diary-login.php");
					} else {
						$error = 'Wrong password, please try again!';
					}
				} else {
					$error = 'Please sign up at first!';
				}
			}
		}
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
			.container {
				text-align: center;
				width: 400px;
				margin-top: 120px;
				
			}
			#logInForm {
				display: none;
			}

			h1{
				margin-bottom: 30px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<h1>Secret Diary</h1>
			<p><em>Store your diary permanently and securely</em></p>
			<form method="post" id="signUpForm">
				<p>Interested? Sign up right now!</p>
				<div class="form-group">	
					<input type="email" class="form-control" name="email" placeholder="Your email">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="password" placeholder="Password">
				</div>
				<div class="form-check">
					<label>
						<input type="checkbox" class="form-check-input" name="stayLoggedIn" value="1">
						Stay Logged In
					</label>
				</div>				
				<div class="form-group">
					<input type="hidden" name="signUp" value="1">
					<input type="submit" class="btn btn-success" name="submit" value="SIGN UP">
				</div>
				<p><a class="toggleForms"><u>Log In</u></a></p>
			</form>

			<form method="post" id="logInForm">
				<p>Log in using your email and password</p>
				<div class="form-group">	
					<input type="email" name="email" class="form-control" placeholder="Your email">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="password" placeholder="Password">
				</div>
				<div class="form-check">
					<label>
						<input type="checkbox" class="form-check-input" name="stayLoggedIn" value="1">
						Stay Logged In
					</label>
				</div>
				<div class="form-group">
					<input type="hidden" name="signUp" value="0">
					<input type="submit" class="btn btn-success" name="submit" value="LOG IN">
				</div>
				<p><a class="toggleForms"><u>Sign Up</u></a></p>
			</form>	

			<div id="error"><?php if(!empty($error)){echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';}?></div>
			
		</div>
		
		<!-- jQuery first, then Tether, then Bootstrap JS. -->
		<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		<script type="text/javascript">
			$(".toggleForms").click(function(){
				//alert('Hi');
				$("#signUpForm").toggle();
				$("#logInForm").toggle();
			});
			$(".toggleForms").hover(function(){
				$(this).css("color", "#4F9FCF");
			}, function() {
				$(this).css("color", "#291A11");
			});
		</script>
	</body>
</html>

