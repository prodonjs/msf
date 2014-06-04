<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
        <title>
            About MS&#38;F | Commercial Real Estate Lending
        </title><?php include 'css_js.php'; ?>
        <link href="/css/properties.css" rel="stylesheet" type="text/css">
    </head>
    <body class="financeBody">
        <div id="wrapper">
            <?php include_once('header.php'); ?>
            <div id="contentWrapper">
                <h1>
                    Recent Financings
                </h1>
                <ul id="propertyLinks">
                    <li>
                        <a href="#all">ALL</a>
                    </li>
                    <li>|
                    </li>
                    <li>
                        <a href="#multi-family">Multi-Family</a>
                    </li>
                    <li>|
                    </li>
                    <li>
                        <a href="#retail">Retail</a>
                    </li>
                    <li>|
                    </li>
                    <li>
                        <a href="#office">Office</a>
                    </li>
                    <li>|
                    </li>
                    <li>
                        <a href="#industry">Industry</a>
                    </li>
                    <li>|
                    </li>
                </ul>
                <br>
                <br>
		<div id="recent-financings"></div>
            </div>
        </div>
        <?php include_once('footer.php'); ?>
        <script type="text/javascript" src="/js/jquery.lightbox_me.js"></script>
        <script type="text/javascript">
            $(document).ready(function($) {
                // Handle filtering on anchor tags
                $('a[href^="#"]').click(function() {
                    var filter = $(this).attr('href').substr(1);
                    if (filter === 'all') {
                        $('div.listing').show();
                    }
                    else {
                        $('div.listing').hide();
                        $('div.listing.' + filter).show();
                    }
                });

                // Configure lightbox events
                $('#recent-financings').on('click', 'a.lightbox-link', function() {
                    var detailsBoxId = $(this).attr('href');
                    $(detailsBoxId).lightbox_me({'closeSelector' : detailsBoxId + ' p.closeBox'});
                });

                // Get properties content
                $.get('/msf/properties/recent/100', function(data) {
                    if (data) {
                        $('#recent-financings').html(data);
                    }
                });
            });
	</script>
    </body>
</html>
