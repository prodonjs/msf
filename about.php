<!DOCTYPE html>
<html lang="en-US">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>About MS&#38;F | Commercial Real Estate Lending</title>

<?php include 'css_js.php'; ?>
</head>

<body class="aboutBody">
<?php include 'analytics.php'; ?>

<div id="wrapper">
<?php include_once('header.php'); ?>

			<div id="contentWrapper">

		   <h1>About MS&#38;F</h1>

		   <div id="squareWrapper">

	   		   <div class="aboutSquares">
	   		   		<h2>COMPANY</h2>
	   		   		<a href="#"><span class="company"><!--Image Replacement --></span></a>
	   		   </div>

	   		   <div class="aboutSquares">
	   		   		<h2>MARKET</h2>
	   		   		<a href="#"><span class="market"><!--Image Replacement --></span></a>
	   		   </div>

	   		   <div class="aboutSquares">
	   		   		<h2>STAFF</h2>
	   		   		<a href="#"><span class="staff"><!--Image Replacement --></span></a>
	   		   </div>

	   		   <div class="aboutSquares">
	   		   		<h2>SERVICE</h2>
	   		   		<a href="#"><span class="service"><!--Image Replacement --></span></a>
	   		   </div>

		   </div>

		   	<div class="company_info info">
		   		<h3>COMPANY</h3>
			  <p >Since 1984, MS&F has financed over $6.0 billion of commercial real estate in nearly every state in the continental U.S.. We stand out for our ability, as real estate investors ourselves, to anticipate and address challenges before they become issues.</p>
			  <br/>
			  <p>Our clients say we are true team members from the very beginning through the very end, and we take pride in proactively managing the small details that keep the process moving smoothly. </p>
			  <br/>
			  <p>Over the past decade, our full-service commercial real estate mortgage banking firm has tripled in size and diversified its business lines to provide financing for all types of commercial and investment real estate properties throughout the United States. We have accomplished this incredible growth through continual emphasis on increasing and strengthening our lender relationships, expanding our marketing and customer service efforts, bringing on quality personnel, and adopting cutting-edge lending and servicing technologies. </p>
			  <br/>
			  <p>MS&F is nationally ranked by the Mortgage Bankers Association as one of the nation’s top commercial mortgage bankers by loan origination and loan servicing.</p>

			   </div>

		   	<div class="market_info info">
		   		<h3>MARKET TERRITORIES</h3>
			  <p>MS&F has clients with assets in 22 states, and many of our relationships have developed over 10, 15 or even 30 years. We do not engage in one-off transactions, but, instead we build relationships. We follow our clients across geographies to serve them wherever they need us. MS&F integrates into our clients’ businesses from day one through post-closing, communicating their stories and serving as an extension of their own teams.</p>
<br/>
<p>MS&F is headquartered in Columbus, Ohio and has an office in Cleveland, from which we are easily able to travel to meet our clients where they need us.</p>
</p>
		   </div>

		   	<div class="staff_info info">
		   		<h3>OUR PEOPLE</h3>
				<p>MS&F’s team of professionals has diverse commercial  real estate investment experience among them. They combine owners’ perspectives with proven track records in all areas of commercial real estate financing, including origination, underwriting, closing, and loan servicing.</p>
				<br/>
				<p>Our leadership regularly give back to the community through various charities. They also provide industry education, having lectured for both the Mortgage Bankers of America and for the MBA Real Estate Finance classes at The Ohio State University.</p>
				<br/>
				<p><a href="key-personnel.php">Learn more about our Key Personnel here.</a> </p>

		   </div>

		   	<div class="service_info info">
		   		<h3>EXPERT SERVICE</h3>

				<p>Mortgage loan servicing has become an integral component of MS&F, as most correspondent lending relationships have had increased demands placed on them by rating and regulatory agencies. We currently services upward of $1 billion in commercial loans in 22 states.</p>
				<br/>
				<p>Our servicing team prides itself in the personalized attention offered to each client. When you close your loan with MS&F, our servicing department will collect your payments, coordinate annual property inspections/financial reporting requirements, remit your tax/insurance payments, and handle any other issue or problem that needs to be addressed. </p>
				<br/>
				<p>MS&F has also stayed abreast of the latest in loan servicing technology and employs REALSynergy to ensure our loan customers and represented lending institutions have access to the best monitoring, processing and reporting available. </p>
				<br/>
				<p>In addition to loan servicing, MS&F can assist in a variety of commercial real estate capacities including, among others, asset management, loan workouts, consulting, capital raises, equity participation and acquisition/disposition analysis.</p>





		   </div>



<div class="clearfix"></div>

			</div>

</div>

<?php include_once('footer.php'); ?>

<script type="text/javascript">
    $(document).ready(function(){
        $('div.about_squares a').click(function() {
           var infoType = $(this).children().first().attr('class');

           // Remove any existing info popups and hover states
           $('div.info').hide();
           $('hoverStock').removeClass('hoverStick');

           // Add the hover state
           $('.' + infoType).toggleClass('hoverStick');
           $('div.' + infoType + '_info').show();
        });
    });
</script>

</body>
</html>