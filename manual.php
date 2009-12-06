<?php

/** 
 * Secret Santa Bot (Manual Example)
 * 
 * Obviously you don't need to use the frontend to get this whole thing to work.
 * This is a stripped down example.  
 *
 * @author      Ben Watts
 * @url         http://www.benwatts.ca/
 * @version     x
 * @date        Dec 06, 2009
 *
 */

    require_once('lib/SecretSantaBot.php');
    
	$people = array( 
		array(
			"name" => "ben", 
			"email" => "testmail_1@email.com"),
		array(
			"name" => "priya", 
			"email" => "testmail_2@email.com"), 
		array(
			"name" => "nathan", 
			"email" => "testmail_3@email.com"), 
		array(
			"name" => "mike", 
			"email" => "testmail_4@email.com"), 
		array(
			"name" => "nick", 
			"email" => "testmail_5@email.com"), 			
	);

    $ss = new SecretSantaBot($people, true); // <-- set to false and it will attempt to send emails. 
    $ss->sendEmails();

?>