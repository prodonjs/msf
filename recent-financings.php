<!DOCTYPE html>
<html lang="en-US">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>About MS&#38;F | Commercial Real Estate Lending</title>

<?php include 'css_js.php'; ?>
</head>

<body class="financeBody">
<?php include 'analytics.php'; ?>

<div id="wrapper">
<?php include_once('header.php'); ?>
		
			<div id="contentWrapper">   
		   
		   <h1>Recent Financings</h1>
		   
		   <ul id="propertyLinks">
			   <li><a href="#">ALL</a></li>
			   <li>|</li>
			   <li><a href="#">Multi-Family</a></li>
			   <li>|</li>
			   <li><a href="#">Retail</a></li>
			   <li>|</li> 
			   <li><a href="#">Office</a></li>
			   <li>|</li>
			   <li><a href="#">Industry</a></li>
			   <li>|</li>
			   <li><a href="#">Hotels</a></li>
		   </ul>
		   
		   <br/><br/>
		   <!- Start Property Listings -->
		   
		   <div class="listing">
			   <!-- images for each listing will be 300 pixels wide by 160px tall -->
			   <!-- image needs an anchor tag to make it clickable to expand into the "More Details" JQuery pop up box -->	
			   <a href="#"><img src="images/image_300x160.jpg"></a>
			   <p class="name">Name</p>
			   
			   <!-- City and State go on the same line, divided by a comma -->
			   <p class="city">City</p>
			   <p class="state">State</p>
			   
			   <p class="amount">$XXX,XXX</p>
			   
			   <!-- type of proerty listed - page is to be filtered by this type, this should be a drop down in the add a new property feature -->
			   <p class="type">Type</p>
			   
			   <a class="moreDetails" href="#">More Details<span><!--Button Replacement--></span></a>
		
</div>		   
		   
		   
		   <!-- POP UP BOX DIV -->
		   <div class="propertiesPop">
		   
		   	   <a href="#"><img src="images/image_600x320.jpg"></a>
			   <p class="name">Name</p>
			   
			   <!-- City and State go on the same line, divided by a comma -->
			   <p class="city">City</p>
			   <p class="state">State</p>
			   
			   <p class="amount">$XXX,XXX</p>
			   
			   <!-- type of proerty listed - page is to be filtered by this type, this should be a drop down in the add a new property feature -->
			   <p class="type">Type</p>
		
			   <!-- this is only to be shown when the user clikcs on the more info button-->
			   <p class="date">Closing Date</p>
			   
			   <!-- this is only to be shown when the user clikcs on the more info button -->
			   <p class="description"><span>Description:</span> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.</p>
		   </div>
		   
		   
		   <!-- END OF POP UP BOX DIV -->
		   
		   
		   
		   
		   
		   <div class="clearfix"></div>	

</div>

</div>

<?php include_once('footer.php'); ?>
   
</body>
</html>