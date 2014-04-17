<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
        <title>
            Contact MS&#38;F
        </title><?php include 'css_js.php'; ?>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js" type=
        "text/javascript">
</script>
        <script src="js/contact.js" type="text/javascript">
</script>
    </head>
    <body class="contactBody">
        
        <div id="wrapper">
            <?php include_once('header.php'); ?>
            <div id="contentWrapper">
                <div id="validationfail">
                    <p class="red">
                        You have failed to enter all fields correctly, please verify your inputs!
                    </p>
                </div>
                <form action="contact-form-handler.php" enctype="multipart/form-data" id="form" method="post">
                    <input name="token" type="hidden" value="<?php echo time(); ?>">
                    <fieldset id="rfqField">
                        <legend>Contact Form</legend> <label for="name">Name<span class=
                        "requiredStyle">*</span></label> <input class="required text" id="name" name="name" type=
                        "text">
                        <br>
                        <br>
                        <label for="company">Company<span class="requiredStyle">*</span></label> <input class=
                        "required text" id="company" name="company" type="text">
                        <br>
                        <br>
                        <label for="email">Email<span class="requiredStyle">*</span></label> <input class=
                        "required email" id="email" name="email" type="text">
                        <br>
                        <br>
                        <label for="phone">Phone</label> <input id="phone" name="phone" type="text">
                        <br>
                        <br>
                        <label for="message">Message<span class="requiredStyle">*</span></label> 
                        <textarea class="required" cols="40" id="message" name="message" rows="8">
</textarea>
                        <br>
                        <br>
                        <div id="challengequestion">
                            <p>
                                Are you a human?
                            </p><label for="challenge" id="lblchallenge"></label> <input class=
                            "required challengecheck text" id="challenge" name="challenge" type="text">
                        </div><input class="clearfix" id="formSubmit" name="Submit" type="button" value="Submit">
                    </fieldset>
                </form>
                <div class="contactNumbers">
                    <p>
                        Feel free to give us a call
                        <br>
                        <br>
                        at (614) 396-5970
                        <br>
                        <br>
                        8:00AM-5:00PM ET
                    </p>
                    <br>
                    <p>
                        Our Fax number is (614) 396-5987
                    </p>
                </div>
                <div class="clearfix"></div>
            </div><!-- end of Wrapper -->
        </div><?php include_once('footer.php'); ?>
    </body>
</html>
