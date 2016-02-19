

<html lang="en">
	<head>
		<title>Form Processing</title>
	</head>
	<body>

		<pre>
		<?php
			print_r($_POST);
		?>
		</pre>
		<br />
		<?php
			// detect form submission
			if (isset($_POST['submit'])) {
				echo "form was submitted<br />";

				// set default values
				if (!empty($_POST["username"])) {
					$name = $_POST["username"];
				} else {
					echo "please enter";
					$name = "";
				}				
				
				// set default values using ternary operator
				// boolean_test ? value_if_true : value_if_false				
				$email = !empty($_POST['email']) ? $_POST['email'] : "";
				
			} else {

				$name = "";
				$email = "";
			}
		?>

		<?php
			echo $name;
			echo "<br />";
			echo $email;
		?>
		
	</body>
</html>