<?php
global $xoouserultra;
	
?>

 <div class="user-ultra-sect ">
        
        <h3><?php _e('Validate your copy','xoousers'); ?></h3>
        <p><?php _e("Please fill out the form below with the serial number generated when you registered your domain through your account at UsersUltra.com",'xoousers'); ?></p>
        
        <p> <?php _e('INPUT YOUR SERIAL KEY','xoousers'); ?></p>
         <p><input type="text" name="p_serial" id="p_serial" style="width:200px" /></p>
        
        
        <p class="submit">
	<input type="submit" name="submit" id="uultradmin-btn-validate-copy" class="button button-primary " value="<?php _e('CLICK HERE TO VALIDATE YOUR COPY','xoousers'); ?>"  /> &nbsp; <span id="loading-animation">  <img src="<?php echo xoousers_url?>admin/images/loaderB16.gif" width="16" height="16" /> &nbsp; <?php _e('Please wait ...','xoousers'); ?> </span>
	
       </p>
       
       <p id='uultra-validation-results'>
       
       </p>
                     
       
    
</div>  

