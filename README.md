<h1>Reframe Coupon App</h1>
<p>written classes are in the core/classes folder</p>
<p>Use of classes and methods are in various public facing pages.</p>
<p>coupon.php allows a user to enter in their first name, last name, email. When submitted the application will output a pdf with a random generated coupon code.</p>
<p>home.php is the admin page and requires the admin to log in to view all the users who have submitted and downloaded a pdf coupon.</p>
<p>Full validation, reCaptcha and login capability. </p>
<p>uses <a href="http://www.setasign.com/products/fpdi/about/">FPDI</a> to generate and write to an existing pdf template.</p>
<p>DEMO at http://coupon.reframemarketing.com</p>
<h3>The application will store in the users table the following information from user input</h3>
<ul>
	<li>first name</li>
	<li>last name</li>
	<li>email</li>
	<li>subscription status as a 0 or 1</li>
</ul>
