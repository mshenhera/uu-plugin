<?php
class XooPublisher
{
	var $act_message;
	var $errors;
	
	var $mPostLabelSingular;
	var $mPostLabelPlural;
	
	
	function __construct() 
	{		
		
		$this->mDateToday =  date("Y-m-d"); 		
		
		add_action( 'init', array($this, 'handle_post') );
		add_action( 'wp_ajax_featured_post_img_upload', array($this, 'featured_img_upload') );
		add_action( 'wp_ajax_featured_img_delete', array($this, 'featured_img_delete') );
		add_action( 'wp_ajax_uultra_set_post_main_picture', array($this, 'uultra_set_post_main_picture') );			
		
		
	}
	
	function handle_post()		
	{
		
		if (is_user_logged_in()) 
		{
			$this->uultra_set_post_labels();
			
			
			if(isset($_POST['uultra-conf-publisher-post']))
			{
							
				//add new post
				$this->submit_post();	
				
			
			}
			
			if(isset($_POST['uultra-conf-edition-post']))
			{
				//edit post
				$this->edit_conf_post();
			
			}
			
			if(isset($_GET['uultra-delete-post']) && $_GET['uultra-delete-post']=='uultra-del-post-conf')
			{
				
				//edit post
				$this->handle_delete_post();
			
			}
		
		}
		
	}
	
	
	function uultra_set_post_labels()
	{
		global $wpdb, $current_user, $xoouserultra;	
		
		$this->mPostLabelSingular = $xoouserultra->get_option('uultra_front_publisher_post_type_label_singular');
		$this->mPostLabelPlural = $xoouserultra->get_option('uultra_front_publisher_post_type_label_plural');
		
		if($this->mPostLabelSingular==''){$this->mPostLabelSingular='Post';}
		if($this->mPostLabelPlural==''){$this->mPostLabelPlural='Posts';}
		
		
	
	}
	
	function uultra_can_be_edited()
	{
		global $wpdb, $current_user, $xoouserultra;	
		
		//check role capability
		$can_edit = '';
		
		if ( current_user_can('edit_posts') ) 
		{
			$can_edit = 'yes';
			
			$can_edit_setting = $xoouserultra->get_option('enable_post_edit');
			
			if($can_edit_setting=='no')
			{
				$can_edit = 'no';			
			
			}
			
			
		}else{
			
			
			$can_edit = 'no';
		
		}
		
		
		
		
		return $can_edit ;
	
	}
	
	function uultra_can_be_deleted()
	{
		global $wpdb, $current_user, $xoouserultra;	
		
		//check role capability
		$can_edit = '';
		
		if ( current_user_can('delete_posts') ) 
		{
			$can_edit = 'yes';
			
			$can_edit_setting = $xoouserultra->get_option('enable_post_del');
			
			if($can_edit_setting=='no')
			{
				$can_edit = 'no';			
			
			}
			
			
		}else{
			
			
			$can_edit = 'no';
		
		}
		
		
		
		
		return $can_edit ;
	
	}
	
	function handle_delete_post()
	{
		
		global $wpdb, $current_user, $xoouserultra;				
		require_once(ABSPATH . 'wp-includes/general-template.php');		
		
		$user_id = get_current_user_id();	
		
		if($user_id >0)
		{
			$post_id = $_GET["post_id"];	
			
			if($post_id >0)
			{
				
				if($this->uultra_is_this_my_post($post_id))
				{
					//delete all post content					
					wp_delete_post( $post_id);	
					
					$this->act_message = __('The post has been deleted!','xoousers');
					
				
				}			
			
			}	
			
	
		}
	
	
	}
	
	function uultra_is_this_my_post($post_id)		
	{
		global $wpdb, $current_user, $xoouserultra;				
		require_once(ABSPATH . 'wp-includes/general-template.php');			
		$user_id = get_current_user_id();	
		
		if (is_user_logged_in()) 
		{
			$res = $wpdb->get_results( 'SELECT `ID`, `post_author` FROM ' . $wpdb->prefix . 'posts WHERE `post_author` = "' . $user_id. '" AND  `ID` = "'.$post_id.'" ' );
			
			if ( empty( $res ) )
			{
				return false;
			
			}else{
				
				return true;
				
			}			
			
				
		}	
	
	}
	//edit_post
	
	function edit_post($id)		
	{
		global $wpdb, $current_user, $xoouserultra;	
		
	
		require_once(ABSPATH . 'wp-includes/general-template.php');
		
		$user_id = get_current_user_id();	
		
		$res = $wpdb->get_results( 'SELECT `ID`, `post_author`, `post_date`, `post_title`, `post_content` , `post_status` FROM ' . $wpdb->prefix . 'posts WHERE `post_author` = "' . $user_id. '" AND  `ID` = "'.$id.'" AND (`post_status` = "publish" OR `post_status` = "pending" ) ORDER BY `post_date` DESC' );
		
		
		if ( !empty( $res ) )
		{
			foreach($res as $rc)
			{
				$post = $rc;
			
			}
			
			
		}else{
			
			//not valid post
			
			$this->errors = __('Invalid '.$this->mPostLabelSingular.'', 'xoousers');
			
			
		
		}
		
		
		
		$post_tags = wp_get_post_tags( $post->ID );
		
		$post_id =$post->ID;
		
		$tagsarray = array();
		
		foreach ($post_tags as $tag) {
            $tagsarray[] = $tag->name;
        }
		
        $tagslist = implode( ', ', $tagsarray );
		
		$categories = get_the_category( $post->ID );
		
		
		$cat_type = 'normal';
		$exclude ="";
		
		$editor_id = 'uultra_post_content';
		$content = '';
		
		$html = "";		
		
		$can_edit = $this->uultra_can_be_edited();
		
		
		
		 ?> 
         
         
         <div class="commons-panel xoousersultra-shadow-borers" >
         
         
                  <div class="commons-panel-heading">
                             <h2> <?php echo  __('Edit '.$this->mPostLabelSingular.'','xoousers');?> </h2>
                                    
                   </div>
                   
                   
            <div class="commons-panel-content" >  
         
                           
             
                 <div class="uultra-post-publish">
                 
                 <?php
				 
				 if($can_edit=='no')
				 {
					  echo "<div class='uupublic-ultra-error'>".__("You can't edit this ".$this->mPostLabelSingular."", 'xoousers')."</div>";
					
				 }
                 
				 if($this->errors!="")
				 {
					 
					  echo "<div class='uupublic-ultra-error'>".$this->errors."</div>";
					  
				 }else{
					 
					 if($this->act_message!="")
					 {
						 echo "<div class='uupublic-ultra-success'>".$this->act_message."</div>";	 
						 
					 }
				 ?>
                 
                 <form method="post" name="uultra-front-publisher-post">
                 <input type="hidden" name="uultra-conf-edition-post" value="ok" />
                 
                 <input type="hidden" name="post_id"  id="post_id"  value="<?php echo $id?>" />
                 
                 <div class="tablenav_post">
                
                <p><a class="uultra-btn-commm" href="?module=posts" title="<?php echo __('Back','xoousers')?>" ><span><i class="fa fa-angle-double-left  fa-lg"></i></span> <?php echo __('Back','xoousers')?> </a></p>
					                    
                                        
				</div>
                 
                     <div class="field_row">
                         <p><?php echo __('Title:','xoousers')?></p>
                         <p><input name="uultra_post_title" type="text" class="xoouserultra-input" value="<?php echo $post->post_title?>" /></p>
                     
                     </div>
                      <?php if($xoouserultra->get_option( 'uultra_front_publisher_allows_category' )!='no'){?>
                     <div class="field_row">
                       <p><?php echo __('Category:','xoousers')?></p>
                     
                      <p>
               
                 <?php 
				 
				 
				 $cats = get_the_category( $post->ID );
                 $selected = 0;
                 if ( $cats )
				 {
                       $selected = $cats[0]->term_id;
                 }
							
                   
                                    if ( $cat_type == 'normal' ) {
                                        wp_dropdown_categories( 'show_option_none=' . __( '-- Select --', 'xoousers' ) . '&hierarchical=1&hide_empty=0&orderby=name&name=uultra_post_category[]&id=uultra_post_category&show_count=0&title_li=&use_desc_for_title=1&class=xoouserultra-input requiredField&exclude=' . $exclude . '&selected=' . $selected );
                                    } else if ( $cat_type == 'ajax' ) {
                                        wp_dropdown_categories( 'show_option_none=' . __( '-- Select --', 'xoousers' ) . '&hierarchical=1&hide_empty=0&orderby=name&name=uultra_post_category[]&id=uultra_post_category-ajax&show_count=0&title_li=&use_desc_for_title=1&class=xoouserultra-input requiredField&depth=1&exclude=' . $exclude . '&selected=' . $selected );
                                    } else {
                                        wpuf_category_checklist( $curpost->ID, false, 'uultra_post_category', $exclude);
                                    }
                                   
                
                ?>
                 </p>
                 </div>
                 
                 
                 <?php }?>
                 
                  <div class="field_row">
                 <p><?php echo __(''.$this->mPostLabelSingular.' Photos:','xoousers')?></p>
                 </div>
                 
                 <div class="pr_post_images">
                 
                 <?php 
				 
				 if($can_edit!='no')
				 {
				 	echo $this->get_post_photo_uploader();
                 }
				 
				  ?>
                  <div id="uuultra_filelist_uploaded"  class="uultra-post-plist">
                   <ul><?php echo $this->uultra_edit_attachment($post_id);?></ul>
                   </div>
                 
                 </div>
                 
                  <p><?php echo __('Description:','xoousers')?></p>
                 
                <?php       
				
				$editor_settings = array('media_buttons' => false , 'textarea_rows' => 15, 'teeny' =>true); 
				
				$content = $post->post_content;                
                wp_editor( $content, $editor_id , $editor_settings);
                
                ?>
                
                   <div class="field_row">
                     <p><?php echo __('Tags:','xoousers')?></p>
                     <p><input name="uultra_post_tags" type="text" class="xoouserultra-input" value="<?php echo $tagslist?>" /></p>
                 
                 </div>
                
                <div class="field_row">
                
                <?php if($can_edit!='no')
				 {?>
                    
                     <p><input type="submit" name="xoouserultra-submit-post"  class="xoouserultra-button" value="Update"></p>
                     
                     <?php } //end if?>
                 
                 </div>
                 
                 </form>
                 
                 
                 <?php } //end error?>
                
                </div>
            
            
            </div>
        
        
         </div> <!--  End post wrapper-->
        
        <?php 
		
		//return $html;
	
	}
	
	function get_all_post_types()		
	{
		global $wpdb, $xoouserultra, $current_user;
		
		require_once(ABSPATH . 'wp-includes/post.php');	
		require_once(ABSPATH . 'wp-includes/user.php');
		
		$widget = array();
		$widget = $xoouserultra->customizer->get_widget_appearance(5);
		$post_types = $widget['widget_post_types'];
		$types_array= array();
		
		$types_array = explode(",",$post_types);
		
		
		$html = '';
		
		// types will be a list of the post type names
		$args = array(
		   'public'   => true,
		   '_builtin' => false
		);
		
		$output = 'objects'; // names or objects, note names is the default
		$operator = 'and'; // 'and' or 'or'
		
		$post_types = get_post_types( $args, $output, $operator ); 
		
		if(in_array('page',$types_array)){$selected_page='checked="checked"';}
		if(in_array('post',$types_array)){$selected_post='checked="checked"';}
		
		
		$html .= '<label>
		<input type="checkbox" name="uultra_all_post_types[]" value="page" id="uultra_all_post_types" '.$selected_page.' />'.__('page','xoousers').'</label>
		<br />';
		$html .= '<label>
		<input type="checkbox" name="uultra_all_post_types[]" value="post" id="uultra_all_post_types" '.$selected_post.' />'.__('post','xoousers').'</label>
		<br />';
		
			
		// get the registered data about each post type with get_post_type_object
		foreach( $post_types as $type )
		{
						
			$c_name =  $type->name;
			$slug = $type->rewrite['slug'];
			
			if( $c_name!="" )
			{
				$selected = "";
				if(in_array($slug,$types_array)){$selected='checked="checked"';}
					
					$html .= '<label><input type="checkbox" name="uultra_all_post_types[]" value="'.$slug.'" id="uultra_all_post_types" '.$selected.'  />
							'.$c_name.'</label>
						  <br />';
			}
			
		}
		
		return $html;
			
	}
	function get_max_allowed_posts()		
	{
		global $wpdb, $xoouserultra;
		
		require_once(ABSPATH . 'wp-includes/general-template.php');	
		require_once(ABSPATH . 'wp-includes/user.php');		
		
		//if this a paid client		
		$user_id =  get_current_user_id();	
		$current_package_id = get_user_meta($user_id, 'usersultra_user_package_id', true);	
		
		
		if($current_package_id!='') //this is a paid client		
		{
			$current_user_package = $xoouserultra->paypal->get_package($current_package_id);			
			$max_allowed_posts = $current_user_package->package_limit_posts;
			
			
			if($max_allowed_posts==''){
				
				$max_allowed_posts = 0;
			
			}			
		
		}else{ //this is a free member
			
			$max_allowed_posts = $xoouserultra->get_option( 'uultra_front_publisher_default_amount' );	
		
		
		}
				
			
		return $max_allowed_posts;

	}

private $cat_list = NULL;
	
	function show_front_publisher($atts)		
	{
		global $wpdb, $xoouserultra, $current_user;
		
		require_once(ABSPATH . 'wp-includes/general-template.php');	
		require_once(ABSPATH . 'wp-includes/user.php');	
		
			
		$cat_type = 'normal';
		$exclude ="";
		
		$editor_id = 'uultra_post_content';
		$content = '';
		
		$html = "";
		
		//post type      		
		$post_type = $this->get_allow_post_type_by_user();
		
		//control total amount of posts
		$user_id = get_current_user_id();		
		$max_allowed_posts = $this->get_max_allowed_posts();
		$user_post_count = $this->count_user_posts_by_type( $user_id , $post_type);		
		
		
		 ?> 
         
         
         <div class="commons-panel xoousersultra-shadow-borers" >
         
         
                  <div class="commons-panel-heading">
                             <h2> <?php echo  __('Add New '.$this->mPostLabelSingular.'','xoousers');?> </h2>
                                    
                   </div>
                   
                   
            <div class="commons-panel-content" >  
         
                           
             
                 <div class="uultra-post-publish">
                 
                 <div class='uupublic-ultra-info'><?php echo  __('Max Allowed '.$this->mPostLabelPlural.': ','xoousers');?> <?php echo $max_allowed_posts?>, <?php echo  __('You have posted: '.$user_post_count.' already ','xoousers');?></div> 
                 
                 <?php
                 
				 if($this->act_message!="")
				 {
					 echo "<div class='uupublic-ultra-success'>".$this->act_message."</div>";
					 
					 
				 }
				 
				  if($this->act_limits!="")
				 {
					 echo "<div class='uupublic-ultra-error'>".$this->act_limits."</div>";
					 
					 
				 }
				 ?>
                 
                 <form method="post" name="uultra-front-publisher-post" enctype="multipart/form-data">
                 <input type="hidden" name="uultra-conf-publisher-post" value="ok" />
                 
                 <div class="tablenav_post">
                
                <p><a class="uultra-btn-commm" href="?module=posts" title="<?php echo __('Back','xoousers')?>" ><span><i class="fa fa-angle-double-left  fa-lg"></i></span> <?php echo __('Back','xoousers')?> </a></p>
					                    
                                        
				</div>
                 
                     <div class="field_row">
                         <p><?php echo __('Title:','xoousers')?></p>
                         <p><input name="uultra_post_title" type="text" class="xoouserultra-input" /></p>
                     
                     </div>
                     
                     <?php if($xoouserultra->get_option( 'uultra_front_publisher_allows_category' )!='no'){?>
                     
                     <div class="field_row">
                       <p><?php echo __('Category1111:','xoousers')?></p>
                     
                      <p>
               
                 <?php 
                                               
                                       
                           if ( $cat_type == 'normal' )
                           {                 
                       // Get the current user in the admin
                $user = new WP_User( $current_user->ID );

                // Get the user role
                $user_cap = $user->roles;

                // Placeholder category (only used to ensure saving while paging works)
                $defaults = array( 'RestrictCategoriesDefault' );

                 // Get selected categories for Roles
                $settings = get_option( 'RestrictCats_options' );

foreach ( $user_cap as $key ) {
                                // Make sure the settings from the DB isn't empty before building the category list
                                if ( is_array( $settings ) && !empty( $settings[ $key . '_cats' ] ) ) {
                                        // Strip out the placeholder category, which is only used to make sure the checkboxes work

                                        $settings[ $key . '_cats' ] = array_values( array_diff( $settings[ $key . '_cats' ], $defaults ) );
                                        $test_a = array_diff( $settings[ $key . '_cats' ], $defaults );

                                        // Build the category list
                                        foreach ( $settings[ $key . '_cats' ] as $category ) {

                                                $term_id = get_term_by( 'slug', $category, 'category' )->term_id;

                                                // If WPML is installed, return the translated ID
                                                if ( function_exists( 'icl_object_id' ) )
                                                        $term_id = icl_object_id( $term_id, 'category', true );

                                                $this->cat_list .= $term_id . ',';
                                        }
                                }

                        //        $this->cat_filters( $this->cat_list );
                        }

					$exclude = " AND ( t.term_id IN ( $this->cat_list ) OR tt.taxonomy NOT IN ( 'category' ) )";
                                      // wp_dropdown_categories(array('class' => 'xoouserultra-input requiredField', 'hide_empty'=> 0, 'exclude' => $exclude));
                                     wp_dropdown_categories( 'show_option_none=' .  __( '-- Select --', 'xoousers' ) . '&hierarchical=1&hide_empty=0&orderby=name&name=uultra_post_category[]&id=uultra_post_category&show_count=0&title_li=&use_desc_for_title=1&class=xoouserultra-input requiredField&exclude=' . $exclude );
									 
                                                } else if ( $cat_type == 'ajax' ) {
                                                    wp_dropdown_categories( 'show_option_none=' . __( '-- Select --', 'xoousers' ) . '&hierarchical=1&hide_empty=0&orderby=name&name=uultra_post_category[]&id=uultra_post_category-ajax&show_count=0&title_li=&use_desc_for_title=1&class=cat requiredField&depth=1&exclude=' . $exclude );
                                                } else {
                                                    wpuf_category_checklist(0, false, 'uultra_post_category', $exclude);
                                                }
                                              
                
                ?>
                 </p>
                 </div>
                 
                 <?php }?>
                 
                 <div class="field_row">
                 <p><?php echo __(''.$this->mPostLabelSingular.' Photos:','xoousers')?></p>
                 </div>
                 
                 <div class="pr_post_images">
                 
                 <?php echo $this->get_post_photo_uploader(); ?>
                 
                  <div id="uuultra_filelist_uploaded"  class="uultra-post-plist"> <ul></ul> </div>
                 
                 </div>
                 
                 
                
                 
                  <p><?php echo __('Description:','xoousers')?></p>
                 
                <?php       
				
				$editor_settings = array('media_buttons' => false, 'textarea_rows' => 15,  'teeny' =>true);      
                
                 wp_editor( $content, $editor_id , $editor_settings);
                
                ?>
                
                   <div class="field_row">
                     <p><?php echo __('Tags:','xoousers')?></p>
                     <p><input name="uultra_post_tags" type="text" class="xoouserultra-input" /></p>
                 
                 </div>
                
                <div class="field_row">
                    
                     <p><input type="submit" name="xoouserultra-submit-post"  class="xoouserultra-button" value="Submit"></p>
                 
                 </div>
                 
                 </form>
                 
                
                </div>
            
            
            </div>
        
        
         </div> <!--  End post wrapper-->
        
        <?php 
		
	
	}
	
	
	/**
	 * My Posts Widget
	 */
	function show_my_posts_widget($user_id, $howmany)
	{
		global $wpdb, $current_user, $xoouserultra;	
	
	
		$res = $wpdb->get_results( 'SELECT `ID`, `post_author`, `post_date`, `post_type`, `post_title`, `post_content` , `post_status` FROM ' . $wpdb->prefix . 'posts WHERE `post_author` = "' . $user_id. '" AND `post_status` ="publish" AND `post_type` = "post" ORDER BY `post_date` DESC LIMIT '.$howmany.'' );
		
		return $res;
		

	
	}
	
	function uultra_show_posts($atts)
	{
		global $wpdb, $current_user, $xoouserultra;	
		
		extract( shortcode_atts( array(
		
			'template' => 'posts_default', //this is the template file's name	
			'user_id' => '', //this is the template file's name	
			'howmany' => 20, 			
			'post_type' => 'post', 	
			'template_width' => '100%' //this is the template file's name			
			
		), $atts ) );
		
		//set post types
		
		
		$loop = new WP_Query( array(
    		'post_type' => $post_type,
			'orderby'=> 'post_date',
			'author' =>$user_id,
			'post_status' => array('publish')
			
		));
		
		
		//turn on output buffering to capture script output
        ob_start();
		
		include(xoousers_path.'/templates/'.xoousers_template."/".$template.".php");
		
        $content = ob_get_clean();		
		return $content ;
	


	
	}
	
	function the_excerpt_max_charlength($charlength, $excerpt) 
	{
		
		$charlength++;
	
		if ( mb_strlen( $excerpt ) > $charlength ) {
			$subex = mb_substr( $excerpt, 0, $charlength - 5 );
			$exwords = explode( ' ', $subex );
			$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
			if ( $excut < 0 ) {
				echo mb_substr( $subex, 0, $excut );
			} else {
				echo $subex;
			}
			echo '[...]';
		} else {
			echo $excerpt;
		}
	}
	
	function get_excerpt_by_id($the_excerpt,$excerpt_length)
	{
		$the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
		$words = explode(' ', $the_excerpt, $excerpt_length + 1);
	
		if(count($words) > $excerpt_length) :
			array_pop($words);
			array_push($words, '… ');
			$the_excerpt = implode(' ', $words);
		endif;
	
		$the_excerpt = '<p>' . $the_excerpt . '</p>';
	
		return $the_excerpt;
	}
	
	/**
	 * My Posts in Profile
	 */
	function show_my_posts_in_profile($user_id, $type, $from=null, $howmany=null)
	{
		global $wpdb, $current_user, $xoouserultra;
		
		if($type==''){$type='post';}
	
	    $sql = 'SELECT `ID`, `post_author`, `post_date`, `post_type`, `post_title`, `post_content` , `post_status` FROM ' . $wpdb->prefix . 'posts WHERE `post_author` = "' . $user_id. '" AND `post_status` ="publish" AND `post_type` = "'.$type.'" ORDER BY `post_date` DESC ';
		
		$res = $wpdb->get_results($sql );
		
		//echo $sql;
		
		
		$html="";
		
		foreach($res as $post)
		{
			$permalink = get_permalink( $post->ID ); 
			
			$thumb = get_the_post_thumbnail($post->ID, 'medium');
			$comment_count = wp_count_comments($post->ID);
			
			$desc = $this->get_excerpt_by_id($post->post_content, 15);
			//$desc = $post->post_content;
			
			$html .='<li>';
			
			$html .= '<h1 class="uultra-post-title"><a href="'.$permalink.'">'.$post->post_title.'</a></h1>';
			
			$html .='<div class="uultra-my-post-thumb">';
			$html .='<a href="'.$permalink.'">'.$thumb  .'</a>';
			
			$html .='</div>';
			
			$html .='<div class="uultra-my-post-desc">';
			$html .='<p>'.$desc.'</p>';
			$html .='</div>';
			
			$html .='<div class="uultra-my-post-info-bar">';
			$html .='<p><span class="uultra-post-date"> <i class="fa fa-calendar "></i>'.date("m/d/Y",strtotime($post->post_date)).'</span>  <span class="uultra-post-comments"><i class="fa fa-comment-o "></i>'.$comment_count->approved .'</span> <span class="uultra-post-see"> <i class="fa fa-eye "> <a href="'.$permalink.'">read more </a></i></span></p>';
			$html .='</div>';
			
			$html .='</li>';
		
		}
		
		return $html;
	
	}
	
	function count_user_posts_published( $userid, $post_type) 
	{
		global $wpdb;

	
		$where = " WHERE post_author = '$userid' AND post_status = 'publish' AND post_type = '$post_type' ";	
		$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );	
		return apply_filters( 'get_usernumposts', $count, $userid );
	}
	
	
	/**
	 * My Posts 
	 */
	function show_my_posts()
	{
		global $wpdb, $current_user, $xoouserultra;
		
		$user_id = get_current_user_id();
		
		//post type      		
		$post_type = $this->get_allow_post_type_by_user();
		
		//$post_types = $this->getPostableTypes();
		//print_r($post_types);
		
		$loop = new WP_Query( array(
    		'post_type' => $post_type,
			'orderby'=> 'post_date',
			'author' =>$user_id,
			'post_status' => array('publish','pending','future')
			
		));
		
	
        
		echo '<div class="tablenav_post">
                
                <p><a class="uultra-btn-commm" href="?module=posts&act=add" title="'. __('Add New '.$this->mPostLabelSingular.'','xoousers').'" ><span><i class="fa fa-plus  fa-lg"></i></span> '.__('Add New '.$this->mPostLabelSingular.'','xoousers').' </a></p>
					                    
				</div>';
				
		 if($this->act_message!="")
					 {
						 echo "<div class='uupublic-ultra-success'>".$this->act_message."</div>";	 
						 
					 }
					 
		
		if ( !empty( $status ) )
		{
			echo '<div id="message" class="updated fade"><p>', $status, '</p></div>';
		}
		if ( !$loop->have_posts() )
		{
			echo '<p>', __( 'You have no '.$this->mPostLabelPlural.'.', 'xoousers' ), '</p>';
		}
		else
		{
			$n = count( $msgs );
			
			
			?>
			<form  method="get" id="uultra-form-publisher">
				<?php //wp_nonce_field( 'usersultra-bulk-action_inbox' ); ?>
				<input type="hidden" name="page" value="usersultra_inbox" />
                <input type="hidden" name="module" value="posts" />
                
                <input type="hidden" name="uultra-delete-post"  id="uultra-delete-post" value="" />
                <input type="hidden" name="post_id"  id="post_id"  />
              
	
				
	
				<table class="widefat fixed" id="table-3" cellspacing="0">
					<thead>
					<tr>
						
                       
						<th class="manage-column" ><?php _e( 'Title', 'xoousers' ); ?></th>
						<th class="manage-column"><?php _e( 'Date', 'xoousers' ); ?></th>
						<th class="manage-column" ><?php _e( 'Status', 'xoousers' ); ?></th>
                        <th class="manage-column" ><?php _e( 'Actions', 'xoousers' ); ?></th>
					</tr>
					</thead>
					<tbody>
						<?php
						while ( $loop->have_posts() ) : $loop->the_post();
							$post_id = $loop->post->ID;
							
							?>
						<tr>
							                         
                            
							<td><?php echo $loop->post->post_title; ?></td>
							<td> <?php echo $loop->post->post_date; ?></td>
							<td><?php echo $loop->post->post_status; ?></td>
                            
                            <td>
                            
                             <a href="?module=posts&act=edit&post_id=<?php echo $post_id ; ?>" title="<?php echo __('Edit','xoousers')?>" ><span><i class="fa fa-pencil-square-o fa-lg"></i></span> </a>
                            
                            <?php
							$can_delete = $this->uultra_can_be_deleted();
							 if($can_delete){?>
                            
                            <a href="#"  title="<?php echo __('Delete','xoousers')?>" class="uultra-del-user-postlink" data-id="<?php echo $post_id ; ?>" ><span><i class="fa fa-times fa-lg"></i></span> </a>
                            
                            <?php }?>
                            
                            
                            </td>
						</tr>
							<?php
	
						endwhile;
						?>
					</tbody>
					
				</table>
                
                
			</form>
			<?php
	
		}
		?>

	<?php
	}
	
	function uultra_show_post_status( $status ) 
	{

		if ( $status == 'publish' ) {
	
			$title = __( 'Live', 'xoousers' );
			$fontcolor = '#33CC33';
		} else if ( $status == 'draft' ) {
	
			$title = __( 'Offline', 'xoousers' );
			$fontcolor = '#bbbbbb';
		} else if ( $status == 'pending' ) {
	
			$title = __( 'Awaiting Approval', 'xoousers' );
			$fontcolor = '#C00202';
		} else if ( $status == 'future' ) {
			$title = __( 'Scheduled', 'xoousers' );
			$fontcolor = '#bbbbbb';
		}
	
		echo '<span style="color:' . $fontcolor . ';">' . $title . '</span>';
	}
	
	function uultra_edit_attachment( $post_id )
	{
		$attach = $this->uultra_get_attachments( $post_id );
		
		//has post thum
		
		$has_thumb = false;
		$post_thumbnail_id='';
		
		if ( has_post_thumbnail($post_id) )
		{
			$has_thumb = true;	
			$post_thumbnail_id = get_post_thumbnail_id( $post_id );	
		}
	
		if ( $attach ) 
		{
			$count = 1;
			foreach ($attach as $a) 
			{
				$attach_id = $a['id'];
				
				//print_r( $a);	
				
				$html = sprintf( '<li class="uultra-featu-img-item" id="attachment-%d">', $attach_id );
				$html .= sprintf( '<img src="%s" alt="%s" />', $a['url'], esc_attr($a['title'] ) );
				$html .= '<a class="uultra-btn-delpostimg  uu-photopost-delete" href="#" id="" data-id="'. $attach_id.'" ><span><i class="fa fa-times"></i>'.__("Delete Image", 'xoousers').'</span></a>';
				
				if($attach_id==$post_thumbnail_id)
				{					
					$html .= '<a class="uultra-btn-post-featured-image  " href="#" id="" data-id="'. $attach_id.'" ><span><i class="fa fa-check-square-o "></i>'.__("Main Image", 'xoousers').'</span></a>';
				
				}else{
					
					$html .= '<a class="uultra-btn-delpostimg  uu-photopost-set-as-main" href="#" id="" data-id="'. $attach_id.'" ><span><i class="fa fa-image "></i>'.__("Set As Main", 'xoousers').'</span></a>';
				}				
				
				$html .= '</li>';
		
				$count++;
				
				echo $html;
			}
		}
		
		
	}
	
	
	function uultra_clean_tags( $string )
	{
		$string = preg_replace( '/\s*,\s*/', ',', rtrim( trim( $string ), ' ,' ) );
    	return $string;
	}
	
	public function getPostableTypes()
    {
		
		$_aPostableTypes = array(
        'post',
        'page'
        
   		 );
		 
		 $post_types_array = array();
	
		$_aPostableTypes = array_merge($_aPostableTypes, get_post_types(array('public' => true, '_builtin' => false ), 'names'));
        $_aPostableTypes = array_unique($_aPostableTypes);
		
		foreach ($_aPostableTypes as $sPostableType) 
		{
			$post_types_array[]= $sPostableType;
			
        }	
		
        return $post_types_array;
    }
	
	/* Setting for available user groups at registration */
    public function uultra_get_available_post_types()
	{
      		
      	$up_types = array();
		$p_types = $this->getPostableTypes();

        foreach( $p_types as $ptype ) 
		{
            $up_types[$ptype] = $ptype;
        }

        return $up_types;
    }
	
	
	
	function edit_conf_post()
	{
       global $wpdb, $xoouserultra;
	   
	   require_once(ABSPATH . 'wp-includes/pluggable.php');

        $errors = array();

       
        $title = trim( $_POST['uultra_post_title'] );
        $content = trim( $_POST['uultra_post_content'] );

        $tags = '';
        if ( isset( $_POST['uultra_post_tags'] ) )
		{
            $tags = $this->uultra_clean_tags( $_POST['uultra_post_tags'] );
        }

        //validate title
        if ( empty( $title ) ) 
		{
            $errors[] = __( 'Empty title', 'xoousers' );
			
        } else {
            $title = trim( strip_tags( $title ) );
        }
		
		
		$can_edit = $this->uultra_can_be_edited();
		
		 if ( $can_edit=='no' ) 
		{
            $errors[] = __( "You can't edit this ".$this->mPostLabelSingular." ", 'xoousers' );
	
        }
		
		$cat_type = 'normal';

        //validate cat
     		
		if($xoouserultra->get_option( 'uultra_front_publisher_allows_category' )=='yes')
	    {	
            if ( !isset( $_POST['uultra_post_category'] ) ) 
			{
				
                $errors[] = __( 'Please choose a category', 'xoousers' );
				
            } else if ( $cat_type == 'normal' && $_POST['uultra_post_category'][0] == '-1' ) {
				
                $errors[] = __( 'Please choose a category', 'xoousers' );
				
            } else {
				
                if ( count( $_POST['uultra_post_category'] ) < 1 ) {
                    $errors[] = __( 'Please choose a category', 'xoousers' );
                }
            }
		
		}
        
        //validate post content
        if ( empty( $content ) )
		{
            $errors[] = __( 'Empty content', 'xoousers' );
			
        } else {
			
            $content = trim( $content );
        }

        //process tags
        if ( !empty( $tags ) )
		 {
            $tags = explode( ',', $tags );
        }

        
       
       //if not any errors, proceed
        if ( $errors ) 
		{
           // echo uultra_error_msg( $errors );
           // return;
        }

        $post_stat = $xoouserultra->get_option( 'uultra_front_publisher_default_status' );
        $post_author =  get_current_user_id();

        //users are allowed to choose category
        if ( $xoouserultra->get_option( 'uultra_front_publisher_allows_category' )== 'yes' ) 
		{
            $post_category = $_POST['uultra_post_category'];
			
        } else {
			
            $post_category = array($xoouserultra->get_option( 'uultra_front_publisher_default_category') );
        }

       $post_update = array(
                'ID' => trim( $_POST['post_id'] ),
                'post_title' => $title,
                'post_content' => $content,
                'post_category' => $post_category,
                'tags_input' => $tags
            );

        //plugin API to extend the functionality
        $post_update = apply_filters( 'uultra_edit_post_args', $post_update );

        $post_id = wp_update_post( $post_update );

        if ( $post_id )
		{
		   $this->set_custom_post_photos( $post_id);    		   
		   $this->act_message= __('Updated successfully.', 'xoousers') ;

        }
    }
	
	function set_custom_post_photos ($post_id)
	{
		$images = $_POST["uultra_featured_img"];
		$i = 1;
		
		if(isset($images) && !empty( $images ) )
		{
			foreach ($images as $image)
			{
				
				if($i==1 && !has_post_thumbnail($post_id))
				{
					set_post_thumbnail( $post_id, $image );			
				}
				
				$my_post = array(
					  'ID'           => $image,
					  'post_parent' => $post_id
				  );
				
				// Update the post into the database
				 wp_update_post( $my_post ); 
				
				$i++;
			
			}
		
		}
		
	}
	
	  /**
     * Delete a featured image via ajax
     *
     */
    function featured_img_delete() 
	{
       // check_ajax_referer( 'uultra_nonce', 'nonce' );

        $attach_id = isset( $_POST['attach_id'] ) ? intval( $_POST['attach_id'] ) : 0;
        $attachment = get_post( $attach_id );

        //post author or editor role
        if ( get_current_user_id() == $attachment->post_author || current_user_can( 'delete_private_pages' ) ) 
		{
            wp_delete_attachment( $attach_id, true );
            echo 'success';
        }

        exit;
    }
	
	function count_user_posts_by_type( $userid, $post_type) 
	{
		global $wpdb;

		$where = " WHERE post_author = '$userid' AND post_type = '$post_type' ";	
		$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );	
		return apply_filters( 'get_usernumposts', $count, $userid );
		
	}
	
	/*This function returns the allow post_type for this user*/	
	function get_allow_post_type_by_user()
	{
		global $wpdb, $xoouserultra;
				 
	   $post_type = $xoouserultra->get_option( 'uultra_front_publisher_post_type');		
	   if( $post_type==''){$post_type = 'post';}
	   
	   return $post_type;	
	
	}
	
	
	
	function check_if_reached_limit()
	{
	   global $wpdb, $xoouserultra;
	   
	   require_once(ABSPATH . 'wp-includes/pluggable.php');
	   require_once(ABSPATH . 'wp-includes/user.php');
	   
	   $max_post_per_user = $this->get_max_allowed_posts();
	   
	   $res = true; //can post;
	   
	   
	   $post_type = $this->get_allow_post_type_by_user();
   
	   //get total user_posts
	   $post_author =  get_current_user_id();	   
	   $user_post_count = $this->count_user_posts_by_type( $post_author , $post_type);
	   
	   if($max_post_per_user<$user_post_count+1)
	   {
		    $res = false; //can't post;
					   
	   }
	   
	   return $res;
		
	
	}
	
	function submit_post()
	{
       global $wpdb, $xoouserultra;
	   
	   require_once(ABSPATH . 'wp-includes/pluggable.php');

        $errors = array();

        $title = trim( $_POST['uultra_post_title'] );
        $content = trim( $_POST['uultra_post_content'] );

        $tags = '';
        if ( isset( $_POST['uultra_post_tags'] ) )
		{
			$tags = $this->uultra_clean_tags( $_POST['uultra_post_tags'] ); 
	    }

        //validate title
        if ( empty( $title ) ) 
		{
            $errors[] = __( 'Empty '.$this->mPostLabelSingular.' title', 'xoousers' );
			
        } else {
            $title = trim( strip_tags( $title ) );
        }
		
		$cat_type = 'normal';

        //validate cat
		
		
		if($xoouserultra->get_option( 'uultra_front_publisher_allows_category' )=='yes')
	    {
			
            if ( !isset( $_POST['uultra_post_category'] ) ) 
			{
				
                $errors[] = __( 'Please choose a category', 'xoousers' );
				
            } else if ( $cat_type == 'normal' && $_POST['uultra_post_category'][0] == '-1' ) {
				
                $errors[] = __( 'Please choose a category', 'xoousers' );
				
            } else {
				
                if ( count( $_POST['uultra_post_category'] ) < 1 ) {
                    $errors[] = __( 'Please choose a category', 'xoousers' );
                }
            }
		
		}
        
        //validate post content
        if ( empty( $content ) )
		{
            $errors[] = __( 'Empty content', 'xoousers' );
			
        } else {
			
            $content = trim( $content );
        }

        //process tags
        if ( !empty( $tags ) )
		 {
            $tags = explode( ',', $tags );
        }


        //post type      		
		$post_type = $this->get_allow_post_type_by_user();		
      
        //if not any errors, proceed
        if ( $errors ) 
		{
           
           // return;
        }

        $post_stat = $xoouserultra->get_option( 'uultra_front_publisher_default_status' );
        $post_author =  get_current_user_id();

        //users are allowed to choose category
        if ( $xoouserultra->get_option( 'uultra_front_publisher_allows_category' )== 'yes' )
		 {
            $post_category = $_POST['uultra_post_category'];
        } else {
			//set default category			
            $post_category = array($xoouserultra->get_option( 'uultra_front_publisher_default_category' ));
        }

        $my_post = array(
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => $post_stat,
            'post_author' => $post_author,
            'post_category' => $post_category,
            'post_type' => $post_type,
            'tags_input' => $tags
        );
		
		//check pacakge limits		
		if(!$this->check_if_reached_limit()){
		
			$this->act_limits= __('You cannot add more '.$this->mPostLabelSingular.' ', 'xoousers') ;
			
		}else{			
			 //insert the post
       		 $post_id = wp_insert_post( $my_post );
		
		}
		

       

        if ( $post_id  &&  $this->act_message=="" &&  $this->act_limits=="")
		{

		
            //send mail notification
                    
            //set post thumbnail if has any
            if ( isset($_POST["uultra_featured_img"]) && !empty($_POST["uultra_featured_img"])) 
			{
				$this->set_custom_post_photos($post_id);
				
               
            }

            //Set Post expiration date if has any
            if ( !empty( $_POST['expiration-date'] ) && $post_expiry == 'on' )
			{
                $post = get_post( $post_id );
                $post_date = strtotime( $post->post_date );
                $expiration = (int) $_POST['expiration-date'];
                $expiration = $post_date + ($expiration * 60 * 60 * 24);

                add_post_meta( $post_id, 'expiration-date', $expiration, true );
            }

            //plugin API to extend the functionality
		   
		   $this->act_message= __('Post published successfully. The admin will review it soon', 'xoousers') ;

           
        }
    }
	
	/**
	 * Displays attachment information upon upload as featured image
	 *
	 */
	function uultra_feat_img_html( $attach_id ) 
	{
		$image = wp_get_attachment_image_src( $attach_id, 'thumbnail' );
		$post = get_post( $attach_id );
	
		$html = sprintf( '<li class="uultra-featu-img-item" id="attachment-%d">', $attach_id );
		$html .= sprintf( '<img src="%s" alt="%s" />', $image[0], esc_attr( $post->post_title ) );
		$html .= '<a class="uultra-btn-delpostimg  uu-photopost-delete" href="#" id="" data-id="'. $attach_id.'" ><span><i class="fa fa-times"></i>'.__("Delete Image", 'xoousers').'</span></a>';
		$html .= sprintf( '<input type="hidden" name="uultra_featured_img[]" value="%d" />', $attach_id );
		$html .= '</li>';
		
		
	
		return $html;
	}
	
	
	function uultra_set_post_main_picture() 
	{
		$post_id = $_POST['post_id'];
		$attachment_id = $_POST['attach_id'];
		set_post_thumbnail( $post_id, $attachment_id );
		
		//get photo list
		$html = $this->uultra_edit_attachment($post_id);
		echo $html;
		die();
	
	}
	 /* Get the attachments of a post
	 *
	 * @param int $post_id
	 * @return array attachment list
	 */
	function uultra_get_attachments( $post_id ) {
		$att_list = array();
	
		$args = array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post_parent' => $post_id,
			'order' => 'ASC',
			'orderby' => 'menu_order'
		);
	
		$attachments = get_posts( $args );
	
		foreach ($attachments as $attachment) {
			$att_list[] = array(
				'id' => $attachment->ID,
				'title' => $attachment->post_title,
				'url' => wp_get_attachment_url( $attachment->ID ),
				'mime' => $attachment->post_mime_type
			);
		}
	
		return $att_list;
	}
	
	/**
	 * Generic function to upload a file
	 *
	 * @since 0.8
	 * @param string $field_name file input field name
	 * @return bool|int attachment id on success, bool false instead
	 */
	function uultra_upload_file( $upload_data ) 
	{
	
		$uploaded_file = wp_handle_upload( $upload_data, array('test_form' => false) );
	
		// If the wp_handle_upload call returned a local path for the image
		if ( isset( $uploaded_file['file'] ) ) {
			$file_loc = $uploaded_file['file'];
			$file_name = basename( $upload_data['name'] );
			$file_type = wp_check_filetype( $file_name );
	
			$attachment = array(
				'post_mime_type' => $file_type['type'],
				'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $file_name ) ),
				'post_content' => '',
				'post_status' => 'inherit'
			);
	
			$attach_id = wp_insert_attachment( $attachment, $file_loc );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file_loc );
			wp_update_attachment_metadata( $attach_id, $attach_data );
	
			return $attach_id;
		}
	
		return false;
	}
	
	/**
     * Upload Featured image via ajax
     */
    function featured_img_upload() 
	{
        //check_ajax_referer( 'uultra_featured_img', 'nonce' );

        $upload_data = array(
            'name' => $_FILES['uultra_featured_img']['name'],
            'type' => $_FILES['uultra_featured_img']['type'],
            'tmp_name' => $_FILES['uultra_featured_img']['tmp_name'],
            'error' => $_FILES['uultra_featured_img']['error'],
            'size' => $_FILES['uultra_featured_img']['size']
        );

        $attach_id = $this->uultra_upload_file( $upload_data );
		
		//echo "Attach id: ". $attach_id;

        if ( $attach_id ) 
		{
            $html = $this->uultra_feat_img_html( $attach_id );

            $response = array(
                'success' => true,
                'html' => $html,
            );

            echo json_encode( $response );
            exit;
        }

        $response = array('success' => false);
        echo json_encode( $response );
        exit;
    }
	
	public function get_post_photo_uploader() 
	{
		
	   // Uploading functionality trigger:
	  // (Most of the code comes from media.php and handlers.js)
	      $template_dir = get_template_directory_uri();
		  
		  $user_id = get_current_user_id();
?>
		
		<div id="uploadContainer" style="margin-top: 10px;">
			
			
			<!-- Uploader section -->
			<div id="uploaderSection" style="position: relative;">
				<div id="plupload-upload-ui-postphoto" class="hide-if-no-js">
                
					<div id="drag-drop-area-postphoto">
						<div class="drag-drop-inside">
                        
                       
							<p class="drag-drop-info"><?php	_e('Drop Images Here', 'xoousers') ; ?></p>
                            <div style="display:">
							<p><?php _ex('or', 'Uploader: Drop files here - or - Select Files'); ?></p>
							<p class="drag-drop-buttons"><input id="plupload-browse-button-postphoto" type="button" value="<?php _e('Select Files', 'xoousers') ; ?>" class="button" /></p>
								</div>						
						</div>
                        
                        <div id="progressbar-postphoto"></div>                 
                         <div id="uuultra_filelist_postphoto" class="cb"></div>
                         
                          
					</div>
				</div>
                
                 
			
			</div>
            
           
		</div>
        
       

		<?php
			
			$plupload_init = array(
				'runtimes'            => 'html5,silverlight,flash,html4',
				'browse_button'       => 'plupload-browse-button-postphoto',
				'container'           => 'plupload-upload-ui-postphoto',
				'drop_element'        => 'drag-drop-area-postphoto',
				'file_data_name'      => 'uultra_featured_img',
				'multiple_queues'     => true,
				'multi_selection'	  => false,
				'max_file_size'       => wp_max_upload_size().'b',
				//'max_file_size'       => get_option('drag-drop-filesize').'b',
				'url'                 => admin_url('admin-ajax.php'),
				'flash_swf_url'       => includes_url('js/plupload/plupload.flash.swf'),
				'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
				//'filters'             => array(array('title' => __('Allowed Files', $this->text_domain), 'extensions' => "jpg,png,gif,bmp,mp4,avi")),
				'filters'             => array(array('title' => __('Allowed Files', "xoousers"), 'extensions' => "jpg,png,gif,jpeg")),
				'multipart'           => true,
				'urlstream_upload'    => true,

				// Additional parameters:
				'multipart_params'    => array(
					'_ajax_nonce' => wp_create_nonce('photo-upload'),
					'action'      => 'featured_post_img_upload' // The AJAX action name
					
				),
			);
			

			// Apply filters to initiate plupload:
			$plupload_init = apply_filters('plupload_init', $plupload_init); ?>

			<script type="text/javascript">
			
				jQuery(document).ready(function($){
					
					// Create uploader and pass configuration:
					var uploader_postphoto = new plupload.Uploader(<?php echo json_encode($plupload_init); ?>);

					// Check for drag'n'drop functionality:
					uploader_postphoto.bind('Init', function(up){
						
						var uploaddiv_postphoto = $('#plupload-upload-ui-postphoto');
						
						// Add classes and bind actions:
						if(up.features.dragdrop){
							uploaddiv_postphoto.addClass('drag-drop');
							
							$('#drag-drop-area-postphoto')
								.bind('dragover.wp-uploader', function(){ uploaddiv_postphoto.addClass('drag-over'); })
								.bind('dragleave.wp-uploader, drop.wp-uploader', function(){ uploaddiv_postphoto.removeClass('drag-over'); });

						} else{
							uploaddiv_postphoto.removeClass('drag-drop');
							$('#drag-drop-area').unbind('.wp-uploader');
						}
						
						
						
            			

					});

					
					// Init ////////////////////////////////////////////////////
					uploader_postphoto.init(); 
					
					// Selected Files //////////////////////////////////////////
					uploader_postphoto.bind('FilesAdded', function(up, files) {
						
						
						var hundredmb = 100 * 1024 * 1024, max = parseInt(up.settings.max_file_size, 10);
						
						// Limit to one limit:
						if (files.length > 1){
							//alert("<?php// _e('You may only upload one image at a time!', 'xoousers'); ?>");
							//return false;
						}
						
						// Remove extra files:
						//if (up.files.length > 1){
						//	up.removeFile(uploader_postphoto.files[0]);
						//	up.refresh();
						//}
						
						// Loop through files:
						plupload.each(files, function(file){
							
							// Handle maximum size limit:
							if (max > hundredmb && file.size > hundredmb && up.runtime != 'html5'){
								alert("<?php _e('The file you selected exceeds the maximum filesize limit.', 'xoousers'); ?>");
								return false;
							}
						
						});
						
						jQuery.each(files, function(i, file) {
							jQuery('#uuultra_filelist_postphoto').append('<div class="addedFile" id="' + file.id + '">' + file.name + '</div>');
						});
						
						 						
						up.refresh(); 
						uploader_postphoto.start();
						
					});
					
					// A new file was uploaded:
					uploader_postphoto.bind('FileUploaded', function(up, file, response) {
							var resp = $.parseJSON(response.response);
							$('#' + file.id).remove();
							//console.log(resp);
							if( resp.success ) {
								window.wpufFileCount += 1;
								$('#uuultra_filelist_uploaded ul').append(resp.html);		
								
			
								
							}
					});
				  
				  // Error Alert /////////////////////////////////////////////
					uploader_postphoto.bind('Error', function(up, err) {
						alert("Error: " + err.code + ", Message: " + err.message + (err.file ? ", File: " + err.file.name : "") + "");
						up.refresh(); 
					});
					
					// Progress bar ////////////////////////////////////////////
					uploader_postphoto.bind('UploadProgress', function(up, file) {
						
						var progressBarValue = up.total.percent;
						
						jQuery('#progressbar-postphoto').fadeIn().progressbar({
							value: progressBarValue
						});
						
						jQuery('#progressbar-postphoto').html('<span class="progressTooltip">' + up.total.percent + '%</span>');
					});
					
					// Close window after upload ///////////////////////////////
					uploader_postphoto.bind('UploadComplete', function() {
						
						//jQuery('.uploader').fadeOut('slow');						
						jQuery('#progressbar-postphoto').fadeIn().progressbar({
							value: 0
						});
						
						$('#uuultra_filelist_uploaded ul').sortable({
							cursor: 'crosshair'
						});
						
						
						
						
					});
					
					//
					
					$(document).on("click", "a.uu-photopost-delete", function(e) {						
								
						e.preventDefault();		
											
							var attach_id =  jQuery(this).attr("data-id");	
																		
							jQuery.ajax({
								type: 'POST',
								url: ajaxurl,
								data: {"action": "featured_img_delete", "attach_id": attach_id },
								
								success: function(data){
									
									//remove from list	
									$('#attachment-'+attach_id).fadeOut();								
									$('#attachment-'+attach_id).remove();														
									
									}
							});
						
						
						 // Cancel the default action
						 return false;
						e.preventDefault();
					 
						
					});
					
					
					$(document).on("click", "a.uu-photopost-set-as-main", function(e) {						
								
						e.preventDefault();		
											
							var attach_id =  jQuery(this).attr("data-id");
							var post_id =  $('#post_id').val();
																		
							jQuery.ajax({
								type: 'POST',
								url: ajaxurl,
								data: {"action": "uultra_set_post_main_picture", "attach_id": attach_id  , "post_id": post_id },
								
								success: function(data){
									
									//remove from list	
									$('#uuultra_filelist_uploaded ul').html(data);								
									//$('#attachment-'+attach_id).remove();
														
									
									}
							});
						
						
						 // Cancel the default action
						 return false;
						e.preventDefault();
					 
						
					});
					
					
			
					
				});
				
					
			</script>
			
		<?php
	
	
	}


}
$key = "publisher";
$this->{$key} = new XooPublisher();
