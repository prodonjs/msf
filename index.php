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
                    <span>Morris, Smith & Feyh, (MS&F) Incorporated</span> is a leading full service commercial real
                    estate mortgage banking firm based in Columbus, Ohio. Since 1984, MS&F has financed over $4.0
                    billion of commercial real estate loans and Schedule D Bond placements in every region of the
                    continental United States. Read more about MS&F.
                </h1>
                <div id="recent-financings"></div><a class="box" href="#">Company News 
                <!-- Add 3 most recent transactions here. Pull from PHP --></a> <a class="box" href="#" id=
                "boxImage">Content Box #2</a> <a class="box" href="#">Market Data</a> <!-- end of 4col -->
                <div class="clearfix"></div><!-- END OF CONTENT WRAPPER -->
            </div><!--END OF WRAPPER -->
        </div><?php include_once('footer.php'); ?><script class="secret-source" type="text/javascript">
    function configureSlideshow() {
                $('#banner-fade').bjqs({
                    height      : 250,
                    width       : 690,
                    responsive  : false
                });
                /* Extend the jQuery.show() function to trigger an event */
                var _showFn = $.fn.show;
                $.fn.show = function(a, b, c) {
                    if(this.hasClass('bjqs-slide')) {
                        this.trigger('highlightThumbnail');
                    }
                    // Call the normal jQuery.show() function with this object
                    _showFn.apply(this, arguments);
                }

                /* On display change of slider item, show
                 * or hide the appropriate thumbnail */
                $('li.bjqs-slide').on('highlightThumbnail', function(e) {
                    var thumbId = $(this).attr('id') + '-thumb';
                    $('div.onDeck a').children().removeClass('highlighted');
                    $('div a#' + thumbId).children().addClass('highlighted');
                });
            }

            /* jQuery document ready event */
            $(document).ready(function($) {
                $.get('/msf/properties/recent/3', function(data) {
                    if (data) {
                        $('#recent-financings').html(data);
                        configureSlideshow();
                    }
                });
            });
        </script>
    </body>
</html>
