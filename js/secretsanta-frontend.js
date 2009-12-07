/** 
 * SecretSantaBot Frontend 
 * 
 * Extra stuffs to support the frontend ...
 *
 *
 * @author      Ben Watts
 * @url         http://www.benwatts.ca/
 * @date        Nov. 29, 2009
 *
 */
 
var numberOfInputs,
	$partList; 
 
$(document).ready( function(){

    $('#add-person').click(addExtraInput);
    $('#try-it-out').click(tryIt);
    $('#send-out-email').click(confirmBeforeSending);
    $('#engage-ssb').live('click', sendEmail);
    
    $partList = $('#participant-list');
    numberOfInputs = $partList.find('li').length;
   
    
    $partList.find('input.text').each( function(){
        textReplacement($(this));
    });
});
 
 
function addExtraInput(){
    numberOfInputs++
    var inputNum = numberOfInputs;

    var inputName = '<input type="text" id="person-'+inputNum+'-name" name="name[]" value="Name" class="name" />';
    var inputEmail = '<input type="text" id="person-'+inputNum+'-email" name="email[]" value="Email" class="email" />';
    var removeInput = '<button id="remove-'+inputNum+'" class="remove">remove</button>';
   
    $partList
    	.append('<li>'+inputName+' '+inputEmail+removeInput+'</li>')
    	.find('li:last')
    	.hide()
    	.fadeIn()
    	.find('input')
    	.each(function(){
    		textReplacement($(this));
   		});
    
    var removeBtn = $partList.find('li:last button');
    removeBtn.click(hideExtraInput);
    
    return false;
}
 
function hideExtraInput(){
	$(this).parent().fadeOut('normal', function(){ $(this).remove()} );
    return false;
}
 
 
function deleteInput(){
	$(this).remove();
}
 
 



/**
 * Handles user clicking on the 'Try' button
 */
function tryIt(){
    submitData('try');
    return false;
}


/** 
 * Actually send email!
 */
function sendEmail(){
    submitData('email');
    return false;
}
 

/** 
 * Handles user clicking on the 'Email' button
 */
function confirmBeforeSending(){
    submitData('email-confirm');
    return false;
}

/**
 * Performs the simple lil' ajax request, handles output from the frontend script. 
 */ 
function submitData(mode){
    $('#people #mode').val(mode);

    $.post('./lib/frontend.php', $("#people").serialize(), function(data){
       $('#output').empty().append(data).hide().fadeIn();
    });
}




/** -------------------------------------------------------
 ** UTILITY FUNCTIONS 
 **/
 
function textReplacement(input){
    var originalvalue = input.val();
    input.focus( function(){
        if( $.trim(input.val()) == originalvalue ){ input.val(''); }
    });
    input.blur( function(){
        if( $.trim(input.val()) == '' ){ input.val(originalvalue); }
    });
}
