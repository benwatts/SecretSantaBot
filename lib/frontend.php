<?php

/** 
 * Secret Santa Bot (Frontend)
 * 
 * Handles ajax requests by clicking on the Test/Email buttons in the frontend. 
 *
 * @author      Ben Watts
 * @url         http://www.benwatts.ca/
 * @version     x
 * @date        Dec 06, 2009
 *
 */

    // don't want the output to be cached by the browser 
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Sat, 06 Dec 1988 05:00:00 GMT");
    
    // RECAPTCHA SUPPORT SOON, leave these empty. They won't work :)
    // enter keys for your domain from recaptcha.net, if you're so inclined
    // not a huge deal if this is going to be kept private
    $recaptcha_publickey = '';
    $recaptcha_privatekey = '';

    // very necessary
    require_once('SecretSantaBot.php');
    

    // check the mode, do different things depending on whether or not it's set to 'test' or 'email'
    if( isset($_POST) ){
        if( $_POST['mode'] == 'try' ){
            runTest();
        } elseif( $_POST['mode'] == 'email-confirm' ){
            confirmBeforeSending($recaptcha_publickey, $recaptcha_privatekey);
        } elseif( $_POST['mode'] == 'email' ){
            runEmail();
        } else {
            echo '<p class="error">Unexpected mode.</p>';
        }
    }
    
    
    /** 
     * Kind of gross. 
     * Takes everything that's been POSTed, removes anything that doesn't have a key that starts with 'person', then resets the keys. 
     */
    function prepPeopleArray(){
        $modified_post_array = $_POST;
        
        foreach($modified_post_array as $key => $val ){
            if( strpos($key, 'person') === false ){
                unset($modified_post_array[$key]);    
            }
        }
        
        return array_values($modified_post_array);        
    }
    
    
    /** 
     * Run the test. 
     */    
    function runTest(){
        $people = prepPeopleArray();        
        try{
            $ss = new SecretSantaBot($people);   
            $ss->sendEmails(); 
        } catch (Exception $e ){
           echo '<p class="error"><strong>Error:</strong> '.$e->getMessage().'</p>';
        }
    }
    
    
    /** 
     * Confirm before sending. 
     * Ensure user is not a damn dirty machine 
     */
    function confirmBeforeSending($recaptcha_publickey, $recaptcha_privatekey){
        require_once('recaptchalib.php');
        
        if( !empty($recaptcha_publickey) || !empty($recaptcha_privatekey) ){    
            echo '<h2>Is all information correct?</h2>
                  <p>If it looks good to you, prove that you\'re not a machine by completing the captcha below and click the "send email" button to get\'r\'done.';                  
            echo recaptcha_get_html($recaptcha_publickey);
            echo '<a id="engage-ssb" class="button">Start your '.date('Y').' Secret Santa</a>';         
        } else {
            echo '<h2>Are you sure?</h2>
                  <p>Click the button below to engage SecretSantaBot.</p>
                  <a id="engage-ssb" class="button">Start your '.date('Y').' Secret Santa</a>';            
        }
              
    } 
    
    
    /** 
     * Email people
     */
    function runEmail(){
        $people = prepPeopleArray();        
        try{
            $ss = new SecretSantaBot($people, false);   
            $ss->sendEmails(); 
        } catch (Exception $e ){
           echo '<p class="error"><strong>Error:</strong> '.$e->getMessage().'</p>';
        }
    }
   