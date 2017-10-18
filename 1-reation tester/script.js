			function getRandomColor() {
				var letters = '0123456789ABCDEF'.split('');
				var color = '#';
				for(var i=0; i<6; i++)
					color += letters[Math.floor(Math.random()*16)];
				return color;
			}
			
			function makeShapeAppear() {
				var top = Math.random() * 300;
				var left = Math.random() * 800;
				var width = 100 + Math.random() * 200;
				if(Math.random() > 0.5) {
					document.getElementById("shape").style.borderRadius = "50%";
				}
				else {
					document.getElementById("shape").style.borderRadius = "0%";
				}
				document.getElementById("shape").style.backgroundColor = getRandomColor();			
				document.getElementById("shape").style.width = width + "px";
				document.getElementById("shape").style.height = width + "px";
				document.getElementById("shape").style.top = top + "px";
				document.getElementById("shape").style.left = left + "px";
				document.getElementById("shape").style.display = "block";
				start = new Date().getTime();
			}
			
			function appearAfterDelay() {
				setTimeout(makeShapeAppear, Math.random() * 2000);
			}
			
			var start = new Date().getTime();
			var minTimeTaken = Number.MAX_SAFE_INTEGER;
			appearAfterDelay();			
			document.getElementById("shape").onclick = function() {
				document.getElementById("shape").style.display = "none";
				var end = new Date().getTime();
				var timeTaken = (end - start)/1000;
				if(timeTaken < minTimeTaken)
					minTimeTaken = timeTaken;
				document.getElementById("timeTaken").innerHTML = timeTaken + " s";
				document.getElementById("minTimeTaken").innerHTML = minTimeTaken + " s";
				appearAfterDelay();
			}