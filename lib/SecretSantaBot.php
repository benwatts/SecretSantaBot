<?php

/** 
 * Secret Santa Bot 
 * 
 * A class for generating a paired list of people for a secret santa. 
 * The goal is to be automated to prevent one person from having to deal with knowing who has who.
 *
 *
 * @author      Ben Watts
 * @url         http://www.benwatts.ca/
 * @version     2.0
 * @date        Nov. 29, 2009
 *
 */
class SecretSantaBot{

    public $paired;

    private $test_mode;
    private $people;
    
    const ERROR_MATCHING_NAMES = "Names of people match, that's confusing";
    const ERROR_NOT_ENOUGH_PEOPLE = "Need at least 3 people for a Secret Santa";
    const ERROR_EXPECTING_ARRAY = "Expecting an array of people and emails. Woops.";
    const ERROR_EMAILING_PAIRS = "Only one pair was found. That's odd. Not emailing.";
    const ERROR_INVALID_EMAIL_ADDRESS = 'An invalid email address was found.';

    
    /**
     * Constructor 
     * Ensures that the people array is usable.
     *
     * @param       people          Multidimensonal array [[name, email], ..] of names and emails
     * @param       test_mode       Boolean value to determine if debug/test information should be displayed
     */
    public function __construct($people, $test_mode = true){
    
        $this->people = $people;
        $this->test_mode = $test_mode;
            
        if( is_array($people) ){
            if( count($people) >= 3 ){
                if( !$this->anyNamesMatch() ){
                    if( !$this->anyInvalidEmails() ){
                
                        $this->pairPeople();
                        //$this->sendEmails();
                        
                    } else {
                        throw New Exception(self::ERROR_INVALID_EMAIL_ADDRESS);
                    }
                } else {
                    throw New Exception (self::ERROR_MATCHING_NAMES);
                }
            } else {
                throw new Exception(self::ERROR_NOT_ENOUGH_PEOPLE);
            }        
        } else {
             throw new Exception(self::ERROR_EXPECTING_ARRAY);
        }
    
    }
    
    
    /** 
     * Does a quick lil' check through the array of people to make sure no two people have the same name. 
     * It would be weird if you had two "Johns", even if they did have different email's ... 
     * Converts to lowercase, trims whitespace when comparing. Nothing fancy. 
     *
     * @return          boolean         returns true if any names match eachother. 
     */
    private function anyNamesMatch(){
        
        $people = $this->people;
        
        while( count($people) > 0 ){
            $name = strtolower(trim($people[0]['name']));
            array_splice($people, 0, 1); // we don't need this name in the array, remove! 
            
            for( $c = 0; $c < count($people); $c++ ){
                $compareto = strtolower(trim($people[$c]['name']));
                if( $name == $compareto ){
                    return true;
                }
            }
            
        }
        return false;
        
    }
    
    
    /**
     * Check through the emails to make sure they're formatted properly.
     * Uh. I guess this is super-rigorous and totally overkill, but whatever.  
     * 
     * @return          boolean         returns true if any emails are invalid
     * @see             http://code.google.com/p/php-email-address-validation/
     */
    private function anyInvalidEmails(){
    
        $people = $this->people;    
    
        require_once('EmailAddressValidator.php');
        $validator = new EmailAddressValidator;
    
        for( $c = 0; $c < count($people); $c++ ){
            $email = trim($people[$c]['email']);
            if( !$validator->check_email_address($email) ){
    		  return true;
    		}
		}
		
		return false;
    }
    
    
    /** 
     * If test_mode is set to true, it will output the content of the email to the screen. 
     * If test_mode is false, it will send out emails and provide no feedback. 
     */ 
    public function sendEmails(){
    
        $output = '';
    
        if( count($this->paired) >= 1 ){
            foreach( $this->paired as $key => $person ){   
                         
                $giver = $person[0];
                $receiver = $person[1];
                
        		$subject = '** SECRET SANTA '. date('Y') .' **';
        		$message = "
        		  <h2>SECRET SANTA EMAIL</h2>
        		  <p>Dear <strong>{$giver['name']}</strong>,</p>
        		  <p>This glorious year, you are giving the gift of Christmas to <strong>{$receiver['name']}</strong>.
        		  <p><small>This automated email sent by SecretSantaScript on <a href=\"http://{$_SERVER['HTTP_HOST']}\">{$_SERVER['HTTP_HOST']}</a></small></p>
        		  ";
        		$headers  = 'MIME-Version: 1.0' . "\r\n";
        		$headers .= 'Content-type: text/html; charset=utf-8';
        		
    		    $giver_gravatar = '<img src="http://www.gravatar.com/avatar.php?gravatar_id='.md5( strtolower($giver['email']) ).'&default='.urlencode('images/default.jpg').'&size=30" alt="gravatar" />'; 
    		    $receiver_gravatar = '<img src="http://www.gravatar.com/avatar.php?gravatar_id='.md5( strtolower($receiver['email']) ).'&default='.urlencode('images/default.jpg').'&size=30" alt="gravatar" />'; 
        				
        		if( $this->test_mode ) {
        			$output .= "<li> $giver_gravatar <strong>{$giver['name']}</strong> is giving to <strong>{$receiver['name']}</strong> $receiver_gravatar</li>";          			
        		} else {
        			mail($giver['email'], $subject, $message, $headers);
        			$output .= "<li>$giver_gravatar Sending email to: <strong>{$giver['email']}</strong>.</li>";
        		}        		
                
            }
            
            if( $this->test_mode ){
                echo '<h2>Test Output</h2>';
                echo '<p>What you see below is how the script <em>could</em> pair people together. <br /><em>No email has been sent.</em></p>';
                echo '<ul id="test-output">'.$output.'</ul>';
            } else {
                echo '<h2>Successfully Sent</h2>';
                echo '<p>Emails should now be in everyone\'s inbox. Cheers!</p>';
                echo '<ul id="test-output">'.$output.'</ul>';
            }
            
            
		} else {
		  throw new Exception(ERROR_EMAILING_PAIRS);
		}
    
    }
    
    
    /** 
     * The meat of SecrestSantaBot. 
     * The idea here is to mimic 'pulling a name out of a hat'.
     * As cumbersome as this function may be, it is an improvement over the original: there's no getting caught in potentially-infinite while loops.
     */
    private function pairPeople(){
            
        $num = count($this->people);
        $people_giving = $this->people;
        $people_recieving = $this->people;
    	$paired = array();           
        
        /* 
         This wasn interesting issue: if $people_giving[n] == $people_recieving[n] then you run into a situation 
         where the first two people can get paired up and you're screwed because it means the last person gets their own name. 
         To get around that, the receiver array is shuffled until the names at the end of the two arrays do not match.
         */ 
        do {
            shuffle($people_recieving);
        } while( $people_giving[$num-1]['name'] == $people_recieving[$num-1]['name'] );
        
        /* 
         Loop through all people, if the giver == receiver, increase the index of the receiver (isn't that just magical?).
         Remove the giver from the giver array, receiver from the receiver array, when done. 
         */
    	while( count($people_recieving) > 0 ){
    	
    	   $receiver_index = 0;
    	   if( $people_giving[0]['name'] == $people_recieving[$receiver_index]['name'] ){
    	       $receiver_index = 1;     	       
    	   }
    	   
    	   $paired[] = array($people_giving[0], $people_recieving[$receiver_index]);    	   
                        
            array_splice($people_recieving, $receiver_index, 1);
            array_splice($people_giving, 0, 1);    	   
    	}
    	
    	$this->paired = $paired;    	    	
    	return $paired;
    	
    }



}