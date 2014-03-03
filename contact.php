<!DOCTYPE html>
<html lang="en-US">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Contact MS&#38;F</title>

<?php include 'css_js.php'; ?>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/contact.js"></script>

</head>

<body class="contactBody">
<?php include 'analytics.php'; ?>
<div id="wrapper">

<?php include_once('header.php'); ?>

			<div id="contentWrapper"> 
			<div id="validationfail">
				<p class="red">You have failed to enter all fields correctly, please verify your inputs!</p>
			</div> 
		     
	       	<form id="form" method="post" action="contact-form-handler.php" enctype="multipart/form-data">
				<input name="token" type="hidden" value="<?php echo time(); ?>" />	
	       		<fieldset  id="rfqField">
	      			<legend>Contact Form</legend>
			    	
			    	<label for="name">Name<span class="requiredStyle">*</span></label>
					<input class="required text" name="name" type="text" id="name"/>
<br/><br/>

			    	<label for="company">Company<span class="requiredStyle">*</span></label>
					<input class="required text" name="company" type="text" id="company"/>
<br/><br/>
			
			        <label for="email">Email<span class="requiredStyle">*</span></label>
					<input class="required email" type="text" name="email" id="email" />
<br/><br/>
			
			        <label for="phone">Phone</label>
					<input type="text" name="phone" id="phone" />
<br/><br/>
			      		      
			        <label for="message">Message<span class="requiredStyle">*</span></label>
			        <textarea class="required" name="message" id="message" cols="40" rows="8"></textarea>

<br/><br/>   	
			      	<div id="challengequestion">
						<p>Are you a human?</p>
						<label id="lblchallenge" for="challenge"></label>
						<input type="text" id="challenge" name="challenge" class="required challengecheck text" />
					</div>
					
					<input class="clearfix" type="submit" type="button" name="Submit" id="formSubmit" value="Submit" />
				</fieldset>
	    	</form>
	    	
	    	<div class="contactNumbers">
	    		<p>Feel free to give us a call <br/><br/>at (614) 396-5970<br/><br/>8:00AM-5:00PM ET</p>
	    	<br/>
	    		<p>Our Fax number is (614) 396-5987</p>
	    	
	    	
	    	</div>
		<div class="clearfix"></div>
			</div>
<!-- end of Wrapper --></div>

<?php include_once('footer.php'); ?>

</body>
</html>
