<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
        <title>
            MS&F
        </title><?php include 'css_js.php'; ?>
    </head>
    <body class="homeBody">

        <div id="wrapper">
            <?php include_once('header.php'); ?>
            <div id="contentWrapper">
                <h1 class="homeH1">
                    For 30 years, sophisticated commercial borrowers have trusted Morris, Smith & Feyh, Incorporated (MS&F) to identify and execute the debt and equity solutions they need to capitalize on opportunities. We are owners and investors in commercial real estate who understand the interests of borrowers and lenders, and our full-service mortgage banking firm has financed over $6.0 billion of commercial real estate in 22 states. Every loan we have committed with our financially strong, market-leading lender clients has closed. <a href="about.php">Learn more about us</a>
                </h1>
                <div id="recent-financings"></div>

				<section id="col4">

					<a  class="box" href="#">

						<ul id="treasury">
							<li class="headerText">U.S. Treasury Rate</li>
							<li>7 Year&nbsp;&nbsp;<span id="treasury-7-year">-.--</span>%</li>
							<li>10 Year&nbsp;&nbsp;<span id="treasury-10-year">-.--</span>%</li>
                            <li id="treasury-updated" style="font-size: 9px"><li>
						</ul>

						<ul id="swap">
							<li class="headerText">U.S. Swap Rate</li>
							<li>7 Year&nbsp;&nbsp;<span id="swap-7-year">-.--</span>%</li>
							<li>10 Year&nbsp;&nbsp;<span id="swap-10-year">-.--</span>%</li>
                            <li id="swap-updated" style="font-size: 9px"><li>
						</ul>
					</a>

					<!--
<a class="box" id="boxImage" href="#">Content Box #2

					</a>
-->

				<!-- end of 4col --></section>

                <div class="clearfix"></div><!-- END OF CONTENT WRAPPER -->
            </div><!--END OF WRAPPER -->
        </div>
        <?php include_once('footer.php'); ?>
        <script class="secret-source" type="text/javascript">
            function configureSlideshow() {
                $('#banner-fade').bjqs({
                    height: 250,
                    width: 690,
                    responsive: false
                });
                /* Extend the jQuery.show() function to trigger an event */
                var _showFn = $.fn.show;
                $.fn.show = function (a, b, c) {
                    if (this.hasClass('bjqs-slide')) {
                        this.trigger('highlightThumbnail');
                    }
                    // Call the normal jQuery.show() function with this object
                    _showFn.apply(this, arguments);
                }

                /* On display change of slider item, show
                 * or hide the appropriate thumbnail */
                $('li.bjqs-slide').on('highlightThumbnail', function (e) {
                    var thumbId = $(this).attr('id') + '-thumb';
                    $('div.onDeck a').children().removeClass('highlighted');
                    $('div a#' + thumbId).children().addClass('highlighted');
                });

                /* Handle click of thumbnail images */
                $('div.onDeck a').on('click', function () {
                    var sliderId = $(this).attr('id').replace('-thumb', '');
                    $('li.bjqs-slide').fadeOut();
                    $('#' + sliderId).fadeIn();
                });
            }

            /* jQuery document ready event */
            $(document).ready(function ($) {
                // Get most recent properties
                $.get('/msf/properties/recent/3', function (data) {
                    if (data) {
                        $('#recent-financings').html(data);
                        configureSlideshow();
                    }
                });

                // Get treasury and swap rates
                $.get('/msf/interest_rates/', function (data) {
                    if (data) {
                        $('#treasury-updated').html(data['treasury'][0]);
                        $('#treasury-7-year').html(data['treasury'][1]);
                        $('#treasury-10-year').html(data['treasury'][2]);
                        $('#swap-updated').html(data['swap'][0]);
                        $('#swap-7-year').html(data['swap'][1]);
                        $('#swap-10-year').html(data['swap'][2]);
                    }
                });
            });
        </script>
    </body>
</html>
