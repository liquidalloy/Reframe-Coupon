<?php
require 'core/init-coupon.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	// check if the user entered the right captcha
	// and if the input fields are entered
	if ( (!$resp->is_valid) ) {
		 $errors[] = 'The reCAPTCHA entered was incorrect.';
	}
	else if ( (empty($_POST['name'])) || (empty($_POST['last'])) || (empty($_POST['email'])) ) {
		$errors[] = 'The names and email are required';
	}
	// passed through captcha and no empty fields
	else {
		// Validate user inputs
		if ( !ctype_alpha($_POST['name']) ) {
			$errors[] = 'The first name must be alphabets';
		}
		if ( strlen($_POST['name']) < 2 ) {
			$errors[] = 'The first name must be at least 2 letters';
		} elseif ( strlen($_POST['name']) > 15 ) {
			$errors[] = 'The first name must not be greater than 15 letters';
		}
		if ( !ctype_alpha($_POST['last']) ) {
			$errors[] = 'The last name must be alphabets';
		}
		if ( strlen($_POST['last']) < 2 ) {
			$errors[] = 'The last name must be at least 2 letters';
		} elseif ( strlen($_POST['last']) > 15 ) {
			$errors[] = 'The last name must not be greater than 15 letters';
		}
		if ( filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false ) {
			$errors[] = 'Please enter a valid email address';
		}
	}

	// if there are no elements in the $errors array we are free to process the form
	if ( empty($errors) === true ) {
		$name = trim(htmlentities(ucfirst($_POST['name'])));
		$last = trim(htmlentities(ucfirst($_POST['last'])));
		$email = trim(htmlentities($_POST['email']));
		if ( isset($_POST['subscribe']) ) {
			$subscribe = htmlentities($_POST['subscribe']);
		}
		else {
			$subscribe = 0;
		}
		// Initiate FPDI
		$pdf = new FPDI();
		// add a page 
		$pdf->AddPage(); 
		// set the sourcefile 
		$pdf->setSourceFile('core/FPDI/coupon.pdf'); 
		// import page 1 
		$tplIdx = $pdf->importPage(1); 
		// use the imported page as the template 
		$pdf->useTemplate($tplIdx, 0, 0); 

		// now write some text above the imported page 
		$pdf->AddFont('Hanalei','','Hanalei-Regular.php');
		$pdf->SetFont('Hanalei','','Hanalei-Regular.php'); 
		$pdf->SetTextColor(255,0,0); 
		$pdf->SetXY(40, 46.5);
		$code = $general->generateRandomString(20);
		$pdf->Write(0, $code); 

		$users->add($name, $last, $email, $code, $subscribe);
		$pdf->Output('newpdf.pdf', 'D'); 
		header('Location: coupon.php?success');
		exit();
	}

}

// if form was successfully sent send a message to the user
if ( isset($_GET['success']) && empty($_GET['success']) ) {
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css" >
	
	<title>Coupon</title>
</head>
<body>	
	<div id="container">
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="coupon.php">Coupon</a></li>
			<li><a href="login.php">Login</a></li>
		</ul>
	</div>
	<div class="main">
		<h1>Reframe Coupon</h1>
		<p>Enter in the fields and click submit. Save the PDF and print out the coupon.</p>
		<?php
			if ( !empty($errors) || $resp->error) {
				echo '<p class="error">' . implode('</p><p class="error">', $errors) . '</p>';
				echo $error_code;
			} elseif ( isset($_GET['success']) && empty($_GET['success']) )  {
				echo "<p class='success'>Thank you filling out the coupon fields. Please save the file and print it out.</p>";
			}
		?>
		<form action="" method="post">
			<ul>
				<li>
					<label for="name" class="required">First Name:</label>
					<input type="text" name="name" id="name" maxlength="20" placeholder="First Name..." value="<?= isset($_POST['name']) ? htmlentities($_POST['name']) : '' ?>" required/>
				</li>

				<li>
					<label for="last" class="required">Last Name:</label>
					<input type="text" name="last" id="last" maxlength="20" placeholder="Last Name..." value="<?= isset($_POST['last']) ? htmlentities($_POST['last']) : '' ?>" required/>
				</li>

				<li>
					<label for="email" class="required">Email:</label>
					<input type="text" name="email" id="email" maxlength="50" placeholder="email@email.com" value="<?= isset($_POST['email']) ? htmlentities($_POST['email']) : '' ?>" required/>
				</li>
				<li>
					<?php
						$publickey = '6Leuhu0SAAAAAEh6aLB7kU7kkmI0LvATAMe_sGsV';
						echo recaptcha_get_html($publickey, $error_code);
					?>
				</li>
				<li>
					<input type="checkbox" name="subscribe" id="subscribe" value="1">
					<label for="subscribe">I would like to receive your email newsletter</label>
				</li>
				<li>
					<input type="submit" value="submit">
				</li>
			</ul>
		</form>
	</div>
</body>
</html>