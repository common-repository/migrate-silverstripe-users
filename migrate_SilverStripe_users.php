<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<br /><br />
<div>
	<p>
		<strong>instructions:</strong> 
		<ul>
			<li>Set Host, Username and Password of SilverStripe database same as wordpress database. </li>
			<li>Delete users of wordPress site excluding admin user.</li>
			<li>To remove duplication of users ID in users table, please make unique ID of admin user ID in user table and user meta table which are not exists in SilverStripe users IDs.</li>
		</ul>
	</p>
</div>
<?php 
//Check form submition
if(isset($_POST['submit'])){
	$SilverStripeDbName = sanitize_text_field($_POST['sw_SilverStripeDbName']);
	$sw_host 		= sanitize_text_field($_POST['sw_host']);
	$sw_port 		= sanitize_text_field($_POST['sw_port']);
	$sw_username 	= sanitize_text_field($_POST['sw_username']);
	$sw_password 	= sanitize_text_field($_POST['sw_password']);
	
	if($SilverStripeDbName != 'SilverStripeDBname' ){
			// Check db
			// 1st Method - Declaring $wpdb as global and using it to execute an SQL query statement that returns a PHP object			
			//WP DB Prefix
			$lwpdb 		= new wpdb( $sw_username, $sw_password, $SilverStripeDbName, $sw_host );
			//$lwpdb->show_errors();
	      	//---------------------------------------------XXXXXX---------------------------------------------------------     
	      	//Insert records into users meta       
	      	$jUser = $lwpdb->get_results( $lwpdb->prepare( "SELECT 
					      u.ID user_id, 
						  u.Email user_login,
	      				  u.Password password
					 FROM 
					        ".$SilverStripeDbName.".member u 
					 ORDER BY u.ID","",""));
	      	global $wpdb;
	      	$wpdb->show_errors();
	      	$wpPrefix	=	$wpdb->prefix;
			if($jUser){
		   		foreach($jUser as $jUserVal){ 
		           //$this_id 		= 	$jUserVal->user_id."<br />"; 
		           $user_login 		= 	$jUserVal->user_login;
		           $password 		= 	$jUserVal->password; 
				   //Insert into users
		           if($user_login){
		           	$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."users (  user_login, user_pass, user_nicename, user_email,  user_registered, user_status, display_name ) 
		           						VALUES (  '$user_login', '$password', '', '', '', '', '' )","",""));
		           	$this_id=$wpdb->insert_id;;
		           }
		           
			       if($this_id){ 		
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'rich_editing', 'true' )","",""));
						//Insert comment shortcuts status
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'comment_shortcuts', 'false' )","",""));
						//Insert admin color
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'admin_color', 'fresh' )","",""));
						//Insert Nickname
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'use_ssl', 0 )","",""));
						//Insert show admin bar front status
						$wpdb->query( $wpdb->prepare(  "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'show_admin_bar_front', 'true' )","",""));
			       }	
				   $i++;
		     	}
				echo '<span style="color:green;">Users has been inserted successfully. !!! ENJOY !!!</span>';   
			 }  
		}else{ 
			echo '<span style="color:red;">Error establishing a database connection. </span>';
		}
}else{
		$SilverStripeDbName='SilverStripeDBname';
}
?>
<form method="post">
<table>
<tr><th>Insert SilverStripe database name with prefix<span style="color:red;"> (ex - SilverStripeDBname) *</span></th><td><input type="text" name="sw_SilverStripeDbName" id="sw_SilverStripeDbName" onfocus="this.value=='SilverStripeDBname'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='SilverStripeDBname':this.value=this.value;" value="<?php if(isset($SilverStripeDbName)) { echo $SilverStripeDbName; } ?>" maxlength="50"></td></tr>
<tr><th>Hostname <span style="color:red;">*</span></th><td><input type="text" name="sw_host" id="sw_host" onfocus="this.value=='Hostname'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Hostname':this.value=this.value;" value="<?php if(isset($sw_host)) { echo $sw_host; } ?>" maxlength="100"></td></tr>
<tr><th>Port <span style="color:red;">*</span></th><td><input type="text" name="sw_port" id="sw_port" onfocus="this.value=='Port'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Port':this.value=this.value;" value="<?php if(isset($sw_port)) { echo $sw_port; } ?>" maxlength="100"></td></tr>
<tr><th>Username <span style="color:red;">*</span></th><td><input type="text" name="sw_username" id="sw_username" onfocus="this.value=='Username'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Username':this.value=this.value;" value="<?php if(isset($sw_username)) { echo $sw_username; } ?>" maxlength="100"></td></tr>
<tr><th>Password <span style="color:red;">*</span></th><td><input type="password" name="sw_password" id="sw_password" onfocus="this.value=='Password'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Password':this.value=this.value;" value="<?php if(isset($sw_password)) { echo $sw_password; } ?>" maxlength="100"></td></tr>
<tr><td>&nbsp;</td><td><input type="submit" name="submit"></td></tr>
</tr>
</table>
</form><font face="Arial, Helvetica, sans-serif"></font>