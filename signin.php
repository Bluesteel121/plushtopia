<?php
	ob_start();
	session_start();
	$pageTitle = 'Login';
	if (isset($_SESSION['user'])) {
		header('Location: index.php');
	}
	include 'init.php';

	$backgroundImage = 'images/webBackground5.jpg';

	// Check If User Coming From HTTP Post Request

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		if (isset($_POST['login'])) {

			
			

			$formErrors = array();

			$username 	= $_POST['username'];
			$password 	= $_POST['password'];
			$password2 	= $_POST['password2'];
			$email 		= $_POST['email'];
			$fullname	= $_POST['fullname'];

			// Upload Variables



			// Get Avatar Extension
				
		
			
			// Get Variables From The Form

			if (isset($username)) {

				$filterdUser = filter_var($username, FILTER_SANITIZE_STRING);

				if (strlen($filterdUser) < 4) {

					$formErrors[] = 'Username Must Be Larger Than 4 Characters';

				}

			}

			if (isset($password) && isset($password2)) {

				if (empty($password)) {

					$formErrors[] = 'Sorry Password Cant Be Empty';

				}

				if (sha1($password) !== sha1($password2)) {

					$formErrors[] = 'Sorry Password Is Not Match';

				}

			}

			if (isset($email)) {

				$filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

				if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {

					$formErrors[] = 'This Email Is Not Valid';

				}

			}

			// Check If There's No Error Proceed The User Add

			if (empty($formErrors)) {

				
				// Check If User Exist in Database

				$check = checkItem("Username", "users", $username);

				if ($check == 1) {

					$formErrors[] = 'Signup unsuccessful. This user exists';

				} else {

					// Insert Userinfo In Database

					$stmt = $con->prepare("INSERT INTO 
											customer(Username, Password, Email, FullName)
										VALUES(:zuser, :zpass, :zmail, :zname)");
					$stmt->execute(array(

						'zuser' => $username,
						'zpass' => sha1($password),
						'zmail' => $email,
						'zname' => $fullname,
					

					));

					// Echo Success Message

					$succesMsg = 'You Are Now Registered User';
					header("Location: login.php");
					exit();

				}

			}

		}  else {

			
			$formErrors = array();

			$username 	= $_POST['username'];
			$password 	= $_POST['password'];
			$password2 	= $_POST['password2'];
			$email 		= $_POST['email'];
			$fullname	= $_POST['fullname'];

			// Upload Variables

			$avatarName = $_FILES['pictures']['name'];
			$avatarSize = $_FILES['pictures']['size'];
			$avatarTmp	= $_FILES['pictures']['tmp_name'];
			$avatarType = $_FILES['pictures']['type'];

			// List Of Allowed File Typed To Upload

			$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

			// Get Avatar Extension
				
			$ref = explode('.', $avatarName);
			$avatarExtension = strtolower(end($ref));
			
			// Get Variables From The Form

			if (isset($username)) {

				$filterdUser = filter_var($username, FILTER_SANITIZE_STRING);

				if (strlen($filterdUser) < 4) {

					$formErrors[] = 'Username Must Be Larger Than 4 Characters';

				}

			}

			if (isset($password) && isset($password2)) {

				if (empty($password)) {

					$formErrors[] = 'Sorry Password Cant Be Empty';

				}

				if (sha1($password) !== sha1($password2)) {

					$formErrors[] = 'Sorry Password Is Not Match';

				}

			}

			if (isset($email)) {

				$filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

				if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {

					$formErrors[] = 'This Email Is Not Valid';

				}

			}

			// Check If There's No Error Proceed The User Add

			if (empty($formErrors)) {

				$avatar = rand(0, 10000000000) . '_' . $avatarName;

				move_uploaded_file($avatarTmp, "admin/uploads/avatars/" . $avatarName);

				// Check If User Exist in Database

				$check = checkItem("Username", "users", $username);

				if ($check == 1) {

					$formErrors[] = 'Sorry This User Is Exists';

				} else {

					// Insert Userinfo In Database

					$stmt = $con->prepare("INSERT INTO 
											users(Username, Password, Email, FullName, RegStatus, Date, avatar)
										VALUES(:zuser, :zpass, :zmail, :zname, 0, now(), :zpic)");
					$stmt->execute(array(

						'zuser' => $username,
						'zpass' => sha1($password),
						'zmail' => $email,
						'zname' => $fullname,
						'zpic'	=> $avatar

					));

					session_start();
					$_SESSION['success_msg'] = 'You Are Now Registered User';
				
					// Redirect to login.php
					header('Location: login.php');
					exit();
					
					

				}

			}



		}

	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Background Image Example</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('<?php echo $backgroundImage; ?>');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh;
        }
    </style>
</head>
<body>


<div class="container login-page">
	<h1 class="text-center">
		<span class="selected" data-class="login">Customer</span> | 
		<span data-class="signup">Seller</span>
	</h1>
	<!-- Start Login Form -->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

		<div class="input-container">
			<input 
				pattern=".{4,}"
				title="Username Must Be Between 4 Chars"
				class="form-control" 
				type="text" 
				name="username" 
				autocomplete="off"
				placeholder="Username" 
				required />
		</div>
		<div class="input-container">
			<input 
				minlength="4"
				class="form-control" 
				type="password" 
				name="password" 
				autocomplete="new-password"
				placeholder="Password" 
				required />
		</div>
		<div class="input-container">
			<input 
				minlength="4"
				class="form-control" 
				type="password" 
				name="password2" 
				autocomplete="new-password"
				placeholder="Confirm Password" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="email" 
				name="email" 
				placeholder="Email" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="text" 
				name="fullname" 
				placeholder="Full name" 
				required />
		</div>
		
		
		<input class="btn btn-success btn-block" style="background-color:#0c5a9e;" name="login" type="submit" value="Signup" />
		<a href="login.php" style="margin-left: 285px; text-align: center; color: #0c5a9e; text-decoration: none; font-weight: bold;">Login Here</a>
	</form>
	<!-- End Login Form -->
	<!-- Start Signup Form -->
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST"  enctype="multipart/form-data">
		<div class="input-container">
			<input 
				pattern=".{4,}"
				title="Username Must Be Between 4 Chars"
				class="form-control" 
				type="text" 
				name="username" 
				autocomplete="off"
				placeholder="Username" 
				required />
		</div>
		<div class="input-container">
			<input 
				minlength="4"
				class="form-control" 
				type="password" 
				name="password" 
				autocomplete="new-password"
				placeholder="Password" 
				required />
		</div>
		<div class="input-container">
			<input 
				minlength="4"
				class="form-control" 
				type="password" 
				name="password2" 
				autocomplete="new-password"
				placeholder="Confirm Password" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="email" 
				name="email" 
				placeholder="Email" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="text" 
				name="fullname" 
				placeholder="Full name" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="file" 
				name="pictures" 
				 />
		</div>
		<input class="btn btn-success btn-block" style="background-color:#0c5a9e;" name="signup" type="submit" value="Signup" />
		<a href="login.php" style="margin-left: 285px; text-align: center; color: #0c5a9e; text-decoration: none; font-weight: bold;">Login Here</a>
	</form>
	<!-- End Signup Form -->
	<div class="the-errors text-center">
		<?php 

			if (!empty($formErrors)) {

				foreach ($formErrors as $error) {

					echo '<div class="msg error">' . $error . '</div>';

				}

			}

			if (isset($succesMsg)) {

				echo '<div class="msg success">' . $succesMsg . '</div>';

			}

		?>
	</div>
</div>

</body>
</html>




<?php 
	include $tpl . 'footer.php';
	ob_end_flush();
?>