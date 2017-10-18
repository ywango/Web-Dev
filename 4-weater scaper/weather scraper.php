<?php
	//if($_GET['city']) {
	if(array_key_exists('city', $_GET)) {
		$city = str_replace(" ", "", $_GET['city']);
		//$weather = "";
		//$error = "";
		
		$file_headers = @get_headers("http://www.weather-forecast.com/locations/".$city."/forecasts/latest");
		if($file_headers[0]== 'HTTP/1.1 404 Not Found') {
			$error = "That city couldn't be found!";
		} else {
			$forecast = file_get_contents("http://www.weather-forecast.com/locations/".$city."/forecasts/latest");
			$tmp1 = explode('3 Day Weather Forecast Summary:</b><span class="read-more-small"><span class="read-more-content"> <span class="phrase">',$forecast);
			if(sizeof($tmp1) > 1) {
				$tmp2 = explode('</span></span></span>',$tmp1[1]);
				if(sizeof($tmp1) > 0) {
					$weather = $tmp2[0];
				} else {
					$error = "That city couldn't be found!";					
				}
			} else {
				$error = "That city couldn't be found!";
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
		
		<title>Weather Scraper</title>
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
			}
			.container {
				text-align:center;
				margin-top:200px;
				width:500px;
				font-family:Comic Sans MS;
			}
			input {
				margin:20px 0;
			}
			#info {
				margin:20px 0;
			}
		</style>
	</head>
	  
	<body>
		<div class="container">
			<h1>What's the Weather?</h1>
			<form>
				<div class="form-group">
					<label for="city">Enter the name of the city below</label>
					<input type="text" class="form-control" id="city" placeholder="Eg: Beijing, London" name="city" value="<?php if(array_key_exists('city', $_GET)) {echo $_GET['city'];}?>">
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
			<div id="info">
				<?php 
					if(isset($weather)) {
						echo '<div class="alert alert-info"role="alert">'.$weather.'</div>';
					}
					else if(isset($error)) {
						echo '<div class="alert alert-danger"role="alert">'.$error.'</div>';
					}
				?>
			</div>
		</div>
		<!-- jQuery first, then Tether, then Bootstrap JS. -->
		<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	</body>
</html>