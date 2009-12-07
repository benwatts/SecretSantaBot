<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title></title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
    
    <script src="http://www.google.com/jsapi" type="text/javascript"></script>
    <script type="text/javascript">
        google.load("jquery", "1.3.2");
    </script>
    <script type="text/javascript" src="js/secretsanta-frontend.js"></script>

    <link rel="stylesheet" href="css/secretsantabot.css" type="text/css" media="all" />


</head>
<body>
    <div id="wrapper">
    
        <div id="header">
            <h1>SecretSantaBot</h1>
            <a href="http://www.benwatts.ca/" title="Back to benwatts.ca" id="goback">Back to benwatts.ca</a>
        </div>
    
        <div id="leftcolumn">
        
            <div class="box">
                <h2>What?</h2>
                <p>A lil' PHP class for assisting with secret santas. It helps avoid a situation where one person is aware of who every is paired with, or circumvents the need to 'pull names from a hat' if organizing such a thing is impractical or a pain in the ass to setup. </p>
            </div>
            
            <div class="box">
                <h2>Usage</h2>
                <ol>
                    <li>Download the source <a href="http://github.com/benwatts/SecretSantaBot">(github)</a></li>
                    <li>Upload to your server. </li>
                    <li>Visit the script in your browser (eg. http://domain.com/secretsantabot/)</li>
                    <li>
                        Fill out the form
                        <ul>
                            <li>Click on "demo it" to see how the script might pair people up. It will not send any emails out.</li>
                            <li>Click on "send email" to send an email to each specified person. You will not know how they are paired up. </li>
                        </ul>
                    </li>
                    <li>Alternatively, you can scipt all the of the frontend. Check out manual.php for an example of that. </li>
                    <li>Sit and wait for your next opportunity to use this script.</li>    
                </ol>         
            </div>
            
            <div class="box">
                <h2>Requirements</h2>
                <ul>
                    <li>Web server, email (if you want the script to email)</li>
                    <li>PHP 5+</li>
                </ul>
                <p><small>This is a self-hosted solution. This site was not created for you to send out your own secret santa's. It has the potential to get way too spammy, sorry!</small></p>
            </div>
            
        </div><!-- /#leftcolumn-->
        
        <div id="rightcolumn">
            <h2>Try it Out</h2>
            <p>At the very least you require three people with unique names for a Secret Santa to work.</p>
            
            <form name="people" id="people" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                
                <input type="hidden" value="" id="mode" name="mode" />
                
                <ol id="participant-list">
                    <li>
                        <input type="text" id="person-1-name" name="name[]" value="Name" class="name text" />
                        <input type="text" id="person-1-email" name="email[]" value="Email" class="email text" />                
                    </li>
                    <li>
                        <input type="text" id="person-2-name" name="name[]" value="Name" class="name text" />
                        <input type="text" id="person-2-email" name="email[]" value="Email" class="email text" />                
                    </li>
                    <li>
                        <input type="text" id="person-3-name" name="name[]" value="Name" class="name text" />
                        <input type="text" id="person-3-email" name="email[]" value="Email" class="email text" />                
                    </li>
                </ol>
                
                <a href="" class="button" id="add-person">Add Someone Else</a>
                <a href="" class="button" id="try-it-out">Demo it</a>
                <a href="" class="button" id="send-out-email">Send out Email</a>
                
                <div id="output"></div>
                
            </form>
        
        </div><!-- /#rightcolumn -->
        
        <div id="footer">
        
        </div>
    
    </div><!-- /#wrapper -->
</body>
</html>