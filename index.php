<!DOCTYPE html>
<html lang="en-US">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>MS&F</title>

<?php include 'css_js.php'; ?>

</head>

<body class="homeBody">
<?php include 'analytics.php'; ?>

<div id="wrapper">

<?php include_once('header.php'); ?>
			
			<div id="contentWrapper">
				<h1 class="homeH1"><span>Morris, Smith & Feyh, (MS&F) Incorporated</span> is a leading full service commercial real estate mortgage banking firm based in Columbus, Ohio. Since 1984, MS&F has financed over $4.0 billion of commercial real estate loans and Schedule D Bond placements in every region of the continental United States. Read more about MS&F.</h1>
			
			

      <div id="banner-fade">

        <!-- start Basic Jquery Slider -->
        <ul class="bjqs">
          <li id="slide-1">
	          <img src="images/gowdyii.jpg"/>
	          <div>
		          <h2>Finance Title</h2>
		          <p>Address<br/>Address 2</p>
		          <p><span>Financing</span><br/>Amount $000,000</p>
		          <p><span>Closed</span><br/>00/00/0000</p>
	          </div>
          </li>          
          <li id="slide-2">
			<img src="images/food4less.jpg"/><div>
		          <h2>Finance Title</h2>
		          <p>Address<br/>Address 2</p>
		          <p><span>Financing</span><br/>Amount $000,000</p>
		          <p><span>Closed</span><br/>00/00/0000</p>
	          </div> </li>
          <li id="slide-3"><img src="images/mibank.jpg"/><div>
		          <h2>Finance Title</h2>
		          <p>Address<br/>Address 2</p>
		          <p><span>Financing</span><br/>Amount $000,000</p>
		          <p><span>Closed</span><br/>00/00/0000</p>
	          </div> </li>
        </ul>
        <!-- end Basic jQuery Slider -->
<div class="clearfix"></div>	
      <!--end of banner-fade --></div>
	  
	  <div class="slideDeck">
		  <p id="title">FEATURED FINANCINGS</p>
	  	<div class="onDeck">
	  		<a id="slide-1-thumb" class="first" href="#">
			  	<img class="thumbnailSlide" src="images/gowdyiiThumb.jpg"/>
			  	<p class="highlighted">Example Slide 1</p>
			  	<p class="highlighted">Financed $400,000</p>
			  	<p class="highlighted">Closed 8/17/2013</p>
			  	<span class="highlighted"></span>
			  	
		  	</a>
		  	<div class="clearfix"></div>	
	  	</div>
	 
	  	

	  	<div class="onDeck">
  	<a id="slide-2-thumb" class="second" href="#">
		  	<img class="thumbnailSlide" src="images/food4lessThumb.jpg"/>
		  	<p>Example Slide 1</p>
		  	<p>Financed $400,000</p>
		  	<p>Closed 8/17/2013</p>
		  	<span></span>
		  	<div class="clearfix"></div>	
  	</a>
	  	</div>

	  	
  	
	  	<div class="onDeck">
	<a id="slide-3-thumb" class="third" href="#">
		  	<img class="thumbnailSlide" src="images/mibankThumb.jpg"/>
		  	<p>Example Slide 1</p>
		  	<p>Financed $400,000</p>
		  	<p>Closed 8/17/2013</p>
		  	<span></span>
		  	<div class="clearfix"></div>	
  	</a>	  	
	  	</div>
	  	
	  	<a id="seeAll" href="recent-financings.php">See all Financings</a>
	  </div>
<section id="col4">
	
	<a class="box" href="#">Company News
		<!-- Add 3 most recent transactions here. Pull from PHP -->
	</a>
	

	<a class="box" id="boxImage" href="#">Content Box #2
		
	</a>
	
	<a  class="box" href="#">Market Data
		
	</a>
	
<!-- end of 4col --></section>
			
			
			
<div class="clearfix"></div>		
			<!-- END OF CONTENT WRAPPER --></div>
	
<!--END OF WRAPPER --></div>

		<?php include_once('footer.php'); ?>




			
	<script class="secret-source">
		$(document).ready(function($) {
			/* Extend the jQuery.show() function to trigger an event */
			var _showFn = $.fn.show;
			$.fn.show = function(a, b, c) {
				if(this.hasClass('bjqs-slide')) {
					this.trigger('highlightThumbnail');
				}				
				// Call the normal jQuery.show() function with this object
				_showFn.apply(this, arguments);
			}
			
			$('#banner-fade').bjqs({
				height      : 250,
				width       : 690,
				responsive  : false
			});
			
			/* On display change of slider item, show
			 * or hide the appropriate thumbnail */
			$('li.bjqs-slide').on('highlightThumbnail', function(e) {
				var thumbId = $(this).attr('id') + '-thumb';
				$('div.onDeck a').children().removeClass('highlighted');
				$('div a#' + thumbId).children().addClass('highlighted');
			});
		});
	</script>
</body>
</html>
