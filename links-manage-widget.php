<?php
/*
 * Plugin Name:   Links Manage Widget
 * Version:       1.0
 * Plugin URI:    http://wordpress.org/extend/plugins/links-manage-widget/
 * Description:   This plugin provides full featured links management widget with multiple instance and configurable options. Adjust your settings <a href="options-general.php?page=links-manage-widget/links-manage-widget.php">here</a>.
 * Author:        MaxBlogPress
 * Author URI:    http://www.maxblogpress.com
 *
 * License:       GNU General Public License
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 * Copyright (C) 2007 www.maxblogpress.com
 *
 * This is the improved version of "Breukie's link Widget" plugin by Arnold Breukhoven
 *
 */
$mbplmw_path      = preg_replace('/^.*wp-content[\\\\\/]plugins[\\\\\/]/', '', __FILE__);
$mbplmw_path      = str_replace('\\','/',$mbplmw_path);
$mbplmw_dir       = substr($mban_path,0,strrpos($mbplmw_path,'/'));
$mbplmw_siteurl   = get_bloginfo('wpurl');
$mbplmw_siteurl   = (strpos($mbplmw_siteurl,'http://') === false) ? get_bloginfo('siteurl') : $mbplmw_siteurl;
$mbplmw_fullpath  = $mbplmw_siteurl.'/wp-content/plugins/'.$mbplmw_dir.'';
$mbplmw_fullpath  = $mbplmw_fullpath.'links-manage-widget/';
$mbplmw_abspath   = str_replace("\\","/",ABSPATH); 

define('MBP_LMW_ABSPATH', $mbplmw_path);
define('MBP_LMW_LIBPATH', $mbplmw_fullpath);
define('MBP_LMW_SITEURL', $mbplmw_siteurl);
define('MBP_LMW_NAME', 'Links Manage Widget');
define('MBP_LMW_VERSION', '1.0');  
define('MBP_LMW_LIBPATH', $mbplmw_fullpath);
global $wp_version;

if ($wp_version > '2.3') {
	

	function mbp_lmw_options() {
		add_options_page('Links Manage Widget', 'Links Manage Widget', 10, __FILE__, 'mbp_lmw_activate');
	} 
	
	function mbp_lmw_activate() {
		$mbp_lmw_activate = get_option('mbp_lmw_activate');
		$reg_msg = '';
		$mbp_lmw_msg = '';
		$form_1 = 'mbp_lmw_reg_form_1';
		$form_2 = 'mbp_lmw_reg_form_2';
			// Activate the plugin if email already on list
		if ( trim($_GET['mbp_onlist']) == 1 ) {
			$mbp_lmw_activate = 2;
			update_option('mbp_lmw_activate', $mbp_lmw_activate);
			$reg_msg = 'Thank you for registering the plugin. It has been activated'; 
		} 
		// If registration form is successfully submitted
		if ( ((trim($_GET['submit']) != '' && trim($_GET['from']) != '') || trim($_GET['submit_again']) != '') && $mbp_lmw_activate != 2 ) { 
			update_option('mbp_lmw_name', $_GET['name']);
			update_option('mbp_lmw_email', $_GET['from']);
			$mbp_lmw_activate = 1;
			update_option('mbp_lmw_activate', $mbp_lmw_activate);
		}
		if ( intval($mbp_lmw_activate) == 0 ) { // First step of plugin registration
			global $userdata;
			mbp_lmwRegisterStep1($form_1,$userdata);
		} else if ( intval($mbp_lmw_activate) == 1 ) { // Second step of plugin registration
			$name  = get_option('mbp_lmw_name');
			$email = get_option('mbp_lmw_email');
			mbp_lmwRegisterStep2($form_2,$name,$email);
		} else if ( intval($mbp_lmw_activate) == 2 ) { // Options page
				if ( trim($reg_msg) != '' ) {
					echo '<div id="message" class="updated fade"><p><strong>'.$reg_msg.'</strong></p></div>';
				}			
			}
		if($mbp_lmw_activate != '' && !$_GET['submit']) {
		?>
			
		<div class="wrap">
			<h2><?php echo MBP_LMW_NAME.' '.MBP_LMW_VERSION; ?></h2>
		<strong><img src="<?php echo MBP_LMW_LIBPATH;?>images/how.gif" border="0" align="absmiddle" /> <a href="http://wordpress.org/extend/plugins/links-manage-widget/other_notes/" target="_blank">How to use it</a>&nbsp;&nbsp;&nbsp;
				<img src="<?php echo MBP_LMW_LIBPATH;?>images/commentimg.gif" border="0" align="absmiddle" /> <a href="http://www.maxblogpress.com/forum/forumdisplay.php?f=38" target="_blank">Community</a>
		&nbsp;&nbsp;&nbsp;
		<img src="<?php echo MBP_LMW_LIBPATH;?>images/helpimg.gif" border="0" align="absmiddle" /> 
		<a href="http://www.maxblogpress.com/revived-plugins/" target="_blank">View our revived plugins</a>				
				
				
				</strong>
		<br/><br/>				
				
				<div id="message" class="updated fade">
					<p>
						<strong>You have already registered. Please go to the <a href="<?php echo MBP_LMW_SITEURL;?>/wp-admin/widgets.php">Widgets</a> section to enable and configure the widget.</strong>
					</p>
				</div>

<?php
if($_POST['submit'] == "Remove"){
	$lmw_pwdby = array(''.$_POST['lmw_pwdby'].'');
	update_option('mbp_lmw_pwdby_option', $lmw_pwdby);
	$lmw_pwdby = get_option('mbp_lmw_pwdby_option');
	if( $lmw_pwdby[0] == 'lmw_pwdby' ) $lmw_pwdby = 'checked';
}
?>				
<form action="" method="post">
    <table border="0" width="100%" bgcolor="#f1f1f1" style="border:1px solid #e5e5e5">
     <tr >
		<td style="padding:3px 3px 3px 3px; background-color:#fff">
<input name="lmw_pwdby" type="checkbox" value="lmw_pwdby" <?php echo $lmw_pwdby; ?> /> &nbsp;Remove "powered by <?php echo MBP_LMW_NAME; ?>"&nbsp; <br>
		</td>
	</tr>
	
<tr>
<td style="padding:3px 3px 3px 3px; background-color:#f1f1f1">
<input name="submit" type="Submit" value="Remove"  class="button" />
</td>
</tr>
	</table>
</form>				
<br/><br/>		
<div align="center" style="background-color:#f1f1f1; padding:5px 0px 5px 0px" >
<p align="center"><strong><?php echo MBP_LMW_NAME.' '.MBP_LMW_VERSION; ?> by <a href="http://www.maxblogpress.com" target="_blank">MaxBlogPress</a></strong></p>
<p align="center">This plugin is the result of <a href="http://www.maxblogpress.com/blog/219/maxblogpress-revived/" target="_blank">MaxBlogPress Revived</a> project.</p>
</div>						
		</div>	
		<?php
		}
	}
	function widget_lmw( $args, $widget_args = 1 ) {
		extract( $args, EXTR_SKIP );
		if ( is_numeric($widget_args) )
			$widget_args = array( 'number' => $widget_args );
		$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
		extract( $widget_args, EXTR_SKIP );
	
		$options 			= get_option('widget_lmw');
		
		if ( !isset($options[$number]) )
			return;			
			
		//check if registered or not
		
		$mbp_lmw_activate 	= get_option('mbp_lmw_activate');				
		if ($mbp_lmw_activate == '') {
			echo "Please register in the admin panel to activate the `Links Manage Widget` widget";
		} else {			
		
	?>
		<?php echo $before_widget; ?>
	
		<div class="link_manage">
			
			<?php
			//for category output
				$title 				= empty($options[$number]['title']) ? '' : $options[$number]['title'];
				$orderby 			= empty($options[$number]['orderby']) ? 'name' : $options[$number]['orderby'];
				$order 				= empty($options[$number]['order']) ? 'ASC' : $options[$number]['order'];
				$limit 				= empty($options[$number]['limit']) ? '-1' : $options[$number]['limit'];
				$categorize 		= ($options[$number]['categorize'] == 'on') ? 0 : 1;		
				$categories 		= empty($options[$number]['categories']) ? '' : $options[$number]['categories'];			
				$category_orderby 	= empty($options[$number]['category_orderby']) ? '' : $options[$number]['category_orderby'];					
				$category_order 	= empty($options[$number]['category_order']) ? 'ASC' : $options[$number]['category_order'];
				$category_before 	= empty($options[$number]['category_before']) ? '' : $options[$number]['category_before'];
				$category_after 	= empty($options[$number]['category_after']) ? '' : $options[$number]['category_after'];
				$title_li 			= empty($options[$number]['title_li']) ? '' : $options[$number]['title_li'];
				$title_before 		= empty($options[$number]['title_before']) ? '<h2>' : $options[$number]['title_before'];
				$title_after 		= empty($options[$number]['title_after']) ? '</h2>' : $options[$number]['title_after'];
				$between 			= empty($options[$number]['between']) ? '<br/>' : $options[$number]['between'];
				$hide_invisible 	= empty($options[$number]['hide_invisible']) ? '0' : $options[$number]['hide_invisible'];
				$description 		= empty($options[$number]['description']) ? '0' : $options[$number]['description'];		
				$images 			= empty($options[$number]['images']) ? '0' : $options[$number]['images'];
				$rating 			= empty($options[$number]['rating']) ? '0' : $options[$number]['rating'];
				$updated 			= empty($options[$number]['updated']) ? '0' : $options[$number]['updated'];
			
				echo "<div class='" . $title . "'>" .  $title_before . $title . $title_after .  "</div>";
				wp_list_bookmarks(array('orderby' 		=> '' . $orderby . '',
									 'order' 			=> '' . $order . '',
									 'limit' 			=> $limit,
									 'category' 		=> '' . $categories . '',
									 'hide_invisible'	=> $hide_invisible, 
									 'show_updated'		=> $updated,
									 'categorize' 		=> $categorize, 
									 'title_li' 		=> '' . $title_li . '', 
									 'title_before' 	=> '' . $title_before . '', 
									 'title_after' 		=> '' . $title_after . '',
									 'category_orderby' => '' . $category_orderby . '', 
									 'category_order' 	=> '' . $category_order . '', 
									 'between' 			=> '' . $between . '',
									 'category_before' 	=> '' . $category_before . '', 
									 'category_after' 	=> '' . $category_after . '', 
									 'show_rating' 		=> '' . $rating . '', 
									 'show_images' 		=> '' . $images . '', 
									 'show_description' => '' . $description . '')
								 );				
			?>
		</div>
		<?php echo powered_by();?>
		<?php echo $after_widget; ?>
	<?php
		}//user registered or not
	}
	
	function powered_by() {
		$lmw_pwdby = get_option('mbp_lmw_pwdby_option');
		if ($lmw_pwdby[0] != 'lmw_pwdby') {
			return "<a target='_blank' href='http://wordpress.org/extend/plugins/links-manage-widget/'>Powered by Links Manage Widget</a>";
		} else {
			return;
		}	
	 }
	
	function widget_lmw_control( $widget_args = 1 ) {
		global $wp_registered_widgets;
		static $updated = false; // Whether or not we have already updated the data after a POST submit
	
		if ( is_numeric($widget_args) )
			$widget_args = array( 'number' => $widget_args );
		$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
		extract( $widget_args, EXTR_SKIP );
	
		// Data is stored as array:	 array( number => data for that instance of the widget, ... )
		$options = get_option('widget_lmw');
		if ( !is_array($options) )
			$options = array();
	
		// We need to update the data
		if ( !$updated && !empty($_POST['sidebar']) ) {
			// Tells us what sidebar to put the data in
			$sidebar = (string) $_POST['sidebar'];
	
			$sidebars_widgets = wp_get_sidebars_widgets();
			if ( isset($sidebars_widgets[$sidebar]) )
				$this_sidebar =& $sidebars_widgets[$sidebar];
			else
				$this_sidebar = array();
	
			foreach ( $this_sidebar as $_widget_id ) {
				if ( 'widget_lmw' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
					$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
					if ( !in_array( "lmw-$widget_number", $_POST['widget-id'] ) ) // the widget has been removed
						unset($options[$widget_number]);
				}
			}
	
			foreach ( (array) $_POST['widget-lmw'] as $widget_number => $widget_lmw ) {
				if ( !isset($widget_lmw['title']) && isset($options[$widget_number]) ) // user clicked cancel
					continue;
				
				$title 						= wp_specialchars( $widget_lmw['title'] );
				$orderby 					= $widget_lmw['orderby'] ;
				$order 						= $widget_lmw['order'] ;
				$limit 						= $widget_lmw['limit'];
				$categorize 				= $widget_lmw['categorize'];
				$categories 				= @implode(",", $_POST['categories' . $widget_number]);
				$category_orderby			= $widget_lmw['category_orderby'];
				$category_order				= $widget_lmw['category_order'];
				$title_li 					= $widget_lmw['title_li'];
				$category_before 			= $widget_lmw['category_before'];
				$category_after 			= $widget_lmw['category_after'];
				$category_before 			= $widget_lmw['category_before'];
				$title_before 				= $widget_lmw['title_before'];
				$title_after 				= $widget_lmw['title_after'];
				$between 					= $widget_lmw['between'];
				$hide_invisible 			= $widget_lmw['hide_invisible'];
				$description 				= $widget_lmw['description'];
				$images 					= $widget_lmw['images'];			
				$rating 					= $widget_lmw['rating'];			
				$updated 					= $widget_lmw['updated'];			
					
				$image 		= wp_specialchars( $widget_lmw['image'] );
				$alt 		= wp_specialchars( $widget_lmw['alt'] );
				$link 		= wp_specialchars( $widget_lmw['link'] );
				$new_window = isset( $widget_lmw['new_window'] );
				$options[$widget_number] 	= compact('image', 
														'alt', 
														'link', 
														'new_window',
														 'title',
														 'orderby',
														 'order',
														 'limit',
														 'categorize',
														 'categories',
														 'category_orderby',
														 'category_order',
														 'category_before',
														 'category_after',
														 'title_li',
														 'title_before',
														 'title_after',
														 'between',
														 'hide_invisible',
														 'description',
														 'images',
														 'rating',
														 'updated');			
			}
	
			update_option('widget_lmw', $options);
			$updated = true; // So that we don't go through this more than once
		}
		
		//print_r($options);
		if ( -1 == $number ) { 
			 $title				= '';
			 $orderby			= '';
			 $order				= '';
			 $limit				= '';
			 $categorize		= '';
			 $categories		= '';
			 $category_orderby	= '';
			 $category_order	= '';
			 $category_before	= '';
			 $category_after	= '';
			 $title_li			= '';
			 $title_before		= '';
			 $title_after		= '';
			 $between			= '';
			 $hide_invisible	= '';
			 $description		= '';
			 $images			= '';
			 $rating			= '';
			 $updated 			= '';	
	
			$image = '';
			$alt = '';
			$link = '';
			$new_window = '';
			$number = '%i%';
		} else {
			$title 						= attribute_escape($options[$number]['title']);
			$orderby 					= attribute_escape($options[$number]['orderby']);
			$order 						= attribute_escape($options[$number]['order']);
			$limit 						= attribute_escape($options[$number]['limit']);
			$categorize 				= attribute_escape($options[$number]['categorize']);
			$categories					= attribute_escape($options[$number]['categories']);
			$category_orderby 			= attribute_escape($options[$number]['category_orderby']);
			$category_order 			= attribute_escape($options[$number]['category_order']);
			$title_li 					= attribute_escape($options[$number]['title_li']);
			$category_before 			= attribute_escape($options[$number]['category_before']);
			$category_after 			= attribute_escape($options[$number]['category_after']);
			$title_before 				= attribute_escape($options[$number]['title_before']);
			$title_after 				= attribute_escape($options[$number]['title_after']);
			$between 					= attribute_escape($options[$number]['between']);
			$hide_invisible 			= attribute_escape($options[$number]['hide_invisible']);
			$description 				= attribute_escape($options[$number]['description']);
			$images 					= attribute_escape($options[$number]['images']);	
			$rating 					= attribute_escape($options[$number]['rating']);
			$updated 					= attribute_escape($options[$number]['updated']);						
	
			$image 		= attribute_escape($options[$number]['image']);
			$alt 		= attribute_escape($options[$number]['alt']);
			$link 		= attribute_escape($options[$number]['link']);
			$new_window = attribute_escape($options[$number]['new_window']);
		}
	?>
			<p>
				<label for="lmw-title-<?php echo $number; ?>">
					<?php _e('Title:'); ?>
					<input class="widefat" id="lmw-title-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" />
				</label>
			</p>		
			
			<p>
				<label for="lmw-orderby-<?php echo $number; ?>">
					<?php _e('Sort Options:'); ?> <br/>
					<select id="widget-lmw-orderby-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][orderby]">
					<?php echo "<option value=\"\">Select</option>"; ?>
					<?php echo "<option value=\"name\"" . ($orderby=='name' ? " selected='selected'" : '') .">Name</option>"; ?>
					<?php echo "<option value=\"id\"" . ($orderby=='id' ? " selected='selected'" : '') .">ID</option>"; ?>
					<?php echo "<option value=\"url\"" . ($orderby=='url' ? " selected='selected'" : '') .">Url</option>"; ?>
					<?php echo "<option value=\"target\"" . ($orderby=='target' ? " selected='selected'" : '') .">Target</option>"; ?>
					<?php echo "<option value=\"description\"" . ($orderby=='description' ? " selected='selected'" : '') .">Description</option>"; ?>
					<?php echo "<option value=\"owner\"" . ($orderby=='owner' ? " selected='selected'" : '') .">Owner</option>"; ?>
					<?php echo "<option value=\"rating\"" . ($orderby=='rating' ? " selected='selected'" : '') .">Rating</option>"; ?>
					<?php echo "<option value=\"updated\"" . ($orderby=='updated' ? " selected='selected'" : '') .">Updated</option>"; ?>
					<?php echo "<option value=\"rel\"" . ($orderby=='rel' ? " selected='selected'" : '') .">Rel</option>"; ?>
					<?php echo "<option value=\"notes\"" . ($orderby=='notes' ? " selected='selected'" : '') .">Notes</option>"; ?>
					<?php echo "<option value=\"rss\"" . ($orderby=='rss' ? " selected='selected'" : '') .">Rss</option>"; ?>	
					<?php echo "<option value=\"length\"" . ($orderby=='length' ? " selected='selected'" : '') .">Length</option>"; ?>
					<?php echo "<option value=\"rand\"" . ($orderby=='rand' ? " selected='selected'" : '') .">Random</option>"; ?>																									
					</select>&nbsp; <select id="widget-lmw-order-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][order]" value="<?php echo $order; ?>">
					<?php echo "<option value=\"\">Select</option>"; ?>
					<?php echo "<option value=\"asc\"" . ($order=='asc' ? " selected='selected'" : '') .">ASC</option>"; ?>
					<?php echo "<option value=\"desc\"" . ($order=='desc' ? " selected='selected'" : '') .">DESC</option>"; ?>
					</select><br/>
					
					<?php _e('Limit:'); ?>
					<input size="10" id="lmw-limit-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][limit]" type="text" value="<?php echo $limit; ?>" />					
									
				</label>
			</p>			
			
<style type="text/css">
<!--
#wpcontent select {
	height:auto;
}
-->
</style>		

			<p>
				<label style="height:20px;" for="lmw-categories-<?php echo $number; ?>">
					<?php _e('Categories:'); ?>		
					<?php
						//tweak for breaking cat id
						$catid_vals = explode(",",$categories);
						foreach($catid_vals as $key=>$val) {
							$arr_catid[] = $val;
						}
					?>
					
					<select id="lmw-categories-<?php echo $number; ?>" name="categories<?php echo $number;?>[]" multiple="multiple">
					<?php
						global $wpdb;
						$query_cat = "SELECT
											a.term_id,
											a.name
									FROM
										". $wpdb->terms ." a
										INNER JOIN " . $wpdb->term_taxonomy ." b ON(a.term_id=b.term_id)
									WHERE 
										b.taxonomy='link_category'";
						$sql_cat   = mysql_query($query_cat);
						while($rs_cat	    = mysql_fetch_array($sql_cat)) {
							$sel = (in_array($rs_cat['term_id'], $arr_catid)) ? ' selected="selected"':'';				
					?>						
						<option <?php echo $sel;?> value="<?php echo $rs_cat['term_id'];?>">
							<?php echo $rs_cat['name'];?>
						</option>
					<?php } ?>						
					</select>
				</label>
			</p>				
								
			
			<p>
				<label for="lmw-category_orderby-<?php echo $number; ?>">
					<?php _e('Category Order By:'); ?>
					<select id="widget-lmw-category_orderby-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][category_orderby]">
					<?php echo "<option value=\"\">Select</option>"; ?>
					<?php echo "<option value=\"name\"" . ($category_orderby == 'name' ? " selected='selected'" : '') .">Name</option>"; ?>
					<?php echo "<option value=\"id\"" . ($category_orderby   =='id' ? " selected='selected'" : '') .">ID</option>"; ?>
					</select>&nbsp;&nbsp<select id="widget-lmw-category_order-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][category_order]" value="<?php echo $category_order; ?>">
					<?php echo "<option value=\"\">Select</option>"; ?>
					<?php echo "<option value=\"asc\"" . ($category_order=='asc' ? " selected='selected'" : '') .">ASC</option>"; ?>
					<?php echo "<option value=\"desc\"" . ($category_order=='desc' ? " selected='selected'" : '') .">DESC</option>"; ?>
					</select><br/>							
				</label>
			</p>			
			
			<p>
				<label for="lmw-category_before-<?php echo $number; ?>">
					<?php _e('Category Before:'); ?>
					<input class="widefat" id="lmw-category_before-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][category_before]" type="text" value="<?php echo $category_before; ?>" />
				</label>
			</p>			
			
			<p>
				<label for="lmw-category_after-<?php echo $number; ?>">
					<?php _e('Category After:'); ?>
					<input class="widefat" id="lmw-category_after-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][category_after]" type="text" value="<?php echo $category_after; ?>" />
				</label>
			</p>				
			
			<p>
				<label for="lmw-categorize-<?php echo $number; ?>">
					<?php _e('Categorize:'); ?>
					<input id="widget-lmw-categorize-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][categorize]" type="checkbox" <?php if ($categorize) echo 'checked="checked"'; ?> />
				</label> 
			</p>			

			<p>
				<label for="lmw-title_li-<?php echo $number; ?>">
					<?php _e('Title li:'); ?>
					<input class="widefat" id="lmw-title_li-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][title_li]" type="text" value="<?php echo $title_li; ?>" />
				</label>
			</p>			
			
			<p>
				<label for="lmw-title_before-<?php echo $number; ?>">
					<?php _e('Title Before:'); ?>
					<input class="widefat" id="lmw-title_before-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][title_before]" type="text" value="<?php echo $title_before; ?>" />
				</label>
			</p>			
			
			<p>
				<label for="lmw-title_after-<?php echo $number; ?>">
					<?php _e('Title After:'); ?>
					<input class="widefat" id="lmw-title_after-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][title_after]" type="text" value="<?php echo $title_after; ?>" />
				</label>
			</p>				
			
			<p>
				<label for="lmw-between-<?php echo $number; ?>">
					<?php _e('Between:'); ?>
					<input class="widefat" id="lmw-between-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][between]" type="text" value="<?php echo $between; ?>" />
				</label>
			</p>			
			
			<p>
				<label for="lmw-hide_invisible-<?php echo $number; ?>">
					<?php _e('Private Links(show/hide):'); ?>
					<input id="widget-lmw-hide_invisible-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][hide_invisible]" type="checkbox" <?php if ($hide_invisible) echo 'checked="checked"'; ?> />
				</label> 
			</p>				
			
			<p>
				<label for="lmw-description-<?php echo $number; ?>">
					<?php _e('Description(show/hide):'); ?>
					<input id="widget-lmw-description-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][description]" type="checkbox" <?php if ($description) echo 'checked="checked"'; ?> />
				</label> 
			</p>			
			
			<p>
				<label for="lmw-images-<?php echo $number; ?>">
					<?php _e('Images(show/hide):'); ?>
					<input id="widget-lmw-images-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][images]" type="checkbox" <?php if ($images) echo 'checked="checked"'; ?> />
				</label> 
			</p>
			
			<p>
				<label for="lmw-rating-<?php echo $number; ?>">
					<?php _e('Rating(show/hide):'); ?>
					<input id="widget-lmw-rating-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][rating]" type="checkbox" <?php if ($rating) echo 'checked="checked"'; ?> />
				</label> 
			</p>
			
			<p>
				<label for="lmw-updated-<?php echo $number; ?>">
					<?php _e('Updated(show/hide):'); ?>
					<input id="widget-lmw-updated-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][updated]" type="checkbox" <?php if ($updated) echo 'checked="checked"'; ?> />
				</label> 
			</p>			
	
			<input type="hidden" id="widget-lmw-submit-<?php echo $number; ?>" name="widget-lmw[<?php echo $number; ?>][submit]" value="1" />
	<?php
	}
	
	// Registers each instance of widget on startup
	function widget_lmw_register() {
		if ( !$options = get_option('widget_lmw') )
			$options = array();
	
		$widget_ops = array('classname' => 'widget_lmw', 'description' => __('Links Management'));
		$control_ops = array( 'id_base' => 'lmw');
		$name = __(MBP_LMW_NAME);
	
		$registered = false;
		foreach ( array_keys($options) as $o ) {
			// Old widgets can have null values for some reason
			if ( !isset($options[$o]['image']) )
				continue;
	
			$id = "lmw-$o"; // Never never never translate an id
			$registered = true;
			wp_register_sidebar_widget( $id, $name, 'widget_lmw', $widget_ops, array( 'number' => $o ) );
			wp_register_widget_control( $id, $name, 'widget_lmw_control', $control_ops, array( 'number' => $o ) );
		}
	
		// If there are none, we register the widget's existance with a generic template
		if ( !$registered ) {
			wp_register_sidebar_widget( 'lmw-1', $name, 'widget_lmw', $widget_ops, array( 'number' => -1 ) );
			wp_register_widget_control( 'lmw-1', $name, 'widget_lmw_control', $control_ops, array( 'number' => -1 ) );
		}
	}
	
	
// Srart Registration.

/**
 * Plugin registration form
 */
function mbp_lmwRegistrationForm($form_name, $submit_btn_txt='Register', $name, $email, $hide=0, $submit_again='') {
	$wp_url = get_bloginfo('wpurl');
	$wp_url = (strpos($wp_url,'http://') === false) ? get_bloginfo('siteurl') : $wp_url;
	$plugin_pg    = 'options-general.php';
	$thankyou_url = $wp_url.'/wp-admin/'.$plugin_pg.'?page='.$_GET['page'];
	$onlist_url   = $wp_url.'/wp-admin/'.$plugin_pg.'?page='.$_GET['page'].'&amp;mbp_onlist=1';
	if ( $hide == 1 ) $align_tbl = 'left';
	else $align_tbl = 'center';
	?>
	
	<?php if ( $submit_again != 1 ) { ?>
	<script><!--
	function trim(str){
		var n = str;
		while ( n.length>0 && n.charAt(0)==' ' ) 
			n = n.substring(1,n.length);
		while( n.length>0 && n.charAt(n.length-1)==' ' )	
			n = n.substring(0,n.length-1);
		return n;
	}
	function mbp_lmwValidateForm_0() {
		var name = document.<?php echo $form_name;?>.name;
		var email = document.<?php echo $form_name;?>.from;
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		var err = ''
		if ( trim(name.value) == '' )
			err += '- Name Required\n';
		if ( reg.test(email.value) == false )
			err += '- Valid Email Required\n';
		if ( err != '' ) {
			alert(err);
			return false;
		}
		return true;
	}
	//-->
	</script>
	<?php } ?>
	<table align="<?php echo $align_tbl;?>">
	<form name="<?php echo $form_name;?>" method="post" action="http://www.aweber.com/scripts/addlead.pl" <?php if($submit_again!=1){;?>onsubmit="return mbp_lmwValidateForm_0()"<?php }?>>
	 <input type="hidden" name="unit" value="maxbp-activate">
	 <input type="hidden" name="redirect" value="<?php echo $thankyou_url;?>">
	 <input type="hidden" name="meta_redirect_onlist" value="<?php echo $onlist_url;?>">
	 <input type="hidden" name="meta_adtracking" value="mr-links-manage-widget">
	 <input type="hidden" name="meta_message" value="1">
	 <input type="hidden" name="meta_required" value="from,name">
	 <input type="hidden" name="meta_forward_vars" value="1">	
	 <?php if ( $submit_again == 1 ) { ?> 	
	 <input type="hidden" name="submit_again" value="1">
	 <?php } ?>		 
	 <?php if ( $hide == 1 ) { ?> 
	 <input type="hidden" name="name" value="<?php echo $name;?>">
	 <input type="hidden" name="from" value="<?php echo $email;?>">
	 <?php } else { ?>
	 <tr><td>Name: </td><td><input type="text" name="name" value="<?php echo $name;?>" size="25" maxlength="150" /></td></tr>
	 <tr><td>Email: </td><td><input type="text" name="from" value="<?php echo $email;?>" size="25" maxlength="150" /></td></tr>
	 <?php } ?>
	 <tr><td>&nbsp;</td><td><input type="submit" name="submit" value="<?php echo $submit_btn_txt;?>" class="button" /></td></tr>
	 </form>
	</table>
	<?php
}

/**
 * Register Plugin - Step 2
 */
function mbp_lmwRegisterStep2($form_name='frm2',$name,$email) {
	$msg = 'You have not clicked on the confirmation link yet. A confirmation email has been sent to you again. Please check your email and click on the confirmation link to activate the plugin.';
	if ( trim($_GET['submit_again']) != '' && $msg != '' ) {
		echo '<div id="message" class="updated fade"><p><strong>'.$msg.'</strong></p></div>';
	}
	?>
	<style type="text/css">
	table, tbody, tfoot, thead {
		padding: 8px;
	}
	tr, th, td {
		padding: 0 8px 0 8px;
	}
	</style>
	<div class="wrap"><h2> <?php echo MBP_LMW_NAME.' '.MBP_LMW_VERSION; ?></h2>
	 <center>
	 <table width="100%" cellpadding="3" cellspacing="1" style="border:1px solid #e3e3e3; padding: 8px; background-color:#f1f1f1;">
	 <tr><td align="center">
	 <table width="650" cellpadding="5" cellspacing="1" style="border:1px solid #e9e9e9; padding: 8px; background-color:#ffffff; text-align:left;">
	  <tr><td align="center"><h3>Almost Done....</h3></td></tr>
	  <tr><td><h3>Step 1:</h3></td></tr>
	  <tr><td>A confirmation email has been sent to your email "<?php echo $email;?>". You must click on the link inside the email to activate the plugin.</td></tr>
	  <tr><td><strong>The confirmation email will look like:</strong><br /><img src="http://www.maxblogpress.com/images/activate-plugin-email.jpg" vspace="4" border="0" /></td></tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr><td><h3>Step 2:</h3></td></tr>
	  <tr><td>Click on the button below to Verify and Activate the plugin.</td></tr>
	  <tr><td><?php mbp_lmwRegistrationForm($form_name.'_0','Verify and Activate',$name,$email,$hide=1,$submit_again=1);?></td></tr>
	 </table>
	 </td></tr></table><br />
	 <table width="100%" cellpadding="3" cellspacing="1" style="border:1px solid #e3e3e3; padding:8px; background-color:#f1f1f1;">
	 <tr><td align="center">
	 <table width="650" cellpadding="5" cellspacing="1" style="border:1px solid #e9e9e9; padding:8px; background-color:#ffffff; text-align:left;">
	   <tr><td><h3>Troubleshooting</h3></td></tr>
	   <tr><td><strong>The confirmation email is not there in my inbox!</strong></td></tr>
	   <tr><td>Dont panic! CHECK THE JUNK, spam or bulk folder of your email.</td></tr>
	   <tr><td>&nbsp;</td></tr>
	   <tr><td><strong>It's not there in the junk folder either.</strong></td></tr>
	   <tr><td>Sometimes the confirmation email takes time to arrive. Please be patient. WAIT FOR 6 HOURS AT MOST. The confirmation email should be there by then.</td></tr>
	   <tr><td>&nbsp;</td></tr>
	   <tr><td><strong>6 hours and yet no sign of a confirmation email!</strong></td></tr>
	   <tr><td>Please register again from below:</td></tr>
	   <tr><td><?php mbp_lmwRegistrationForm($form_name,'Register Again',$name,$email,$hide=0,$submit_again=2);?></td></tr>
	   <tr><td><strong>Help! Still no confirmation email and I have already registered twice</strong></td></tr>
	   <tr><td>Okay, please register again from the form above using a DIFFERENT EMAIL ADDRESS this time.</td></tr>
	   <tr><td>&nbsp;</td></tr>
	   <tr>
		 <td><strong>Why am I receiving an error similar to the one shown below?</strong><br />
			 <img src="http://www.maxblogpress.com/images/no-verification-error.jpg" border="0" vspace="8" /><br />
		   You get that kind of error when you click on &quot;Verify and Activate&quot; button or try to register again.<br />
		   <br />
		   This error means that you have already subscribed but have not yet clicked on the link inside confirmation email. In order to  avoid any spam complain we don't send repeated confirmation emails. If you have not recieved the confirmation email then you need to wait for 12 hours at least before requesting another confirmation email. </td>
	   </tr>
	   <tr><td>&nbsp;</td></tr>
	   <tr><td><strong>But I've still got problems.</strong></td></tr>
	   <tr><td>Stay calm. <strong><a href="http://www.maxblogpress.com/contact-us/" target="_blank">Contact us</a></strong> about it and we will get to you ASAP.</td></tr>
	 </table>
	 </td></tr></table>
	 </center>		
	<p style="text-align:center;margin-top:3em;"><strong><?php echo MBP_LMW_NAME.' '.MBP_LMW_VERSION; ?> by <a href="http://www.maxblogpress.com/" target="_blank" >MaxBlogPress</a></strong></p>
	</div>
	<?php
}

/**
 * Register Plugin - Step 1
 */
function mbp_lmwRegisterStep1($form_name='frm1',$userdata) {
	$name  = trim($userdata->first_name.' '.$userdata->last_name);
	$email = trim($userdata->user_email);
	?>
	<style type="text/css">
	tabled , tbody, tfoot, thead {
		padding: 8px;
	}
	tr, th, td {
		padding: 0 8px 0 8px;
	}
	</style>
	<div class="wrap"><h2> <?php echo MBP_LMW_NAME.' '.MBP_LMW_VERSION; ?></h2>
	 <center>
	 <table width="100%" cellpadding="3" cellspacing="1" style="border:2px solid #e3e3e3; padding: 8px; background-color:#f1f1f1;">
	  <tr><td align="center">
		<table width="548" align="center" cellpadding="3" cellspacing="1" style="border:1px solid #e9e9e9; padding: 8px; background-color:#ffffff;">
		  <tr><td align="center"><h3>Please register the plugin to activate it. (Registration is free)</h3></td></tr>
		  <tr><td align="left">In addition you'll receive complimentary subscription to MaxBlogPress Newsletter which will give you many tips and tricks to attract lots of visitors to your blog.</td></tr>
		  <tr><td align="center"><strong>Fill the form below to register the plugin:</strong></td></tr>
		  <tr><td align="center"><?php mbp_lmwRegistrationForm($form_name,'Register',$name,$email);?></td></tr>
		  <tr><td align="center"><font size="1">[ Your contact information will be handled with the strictest confidence <br />and will never be sold or shared with third parties ]</font></td></tr>
		</table>
	  </td></tr></table>
	 </center>
	<p style="text-align:center;margin-top:3em;"><strong><?php echo MBP_LMW_NAME.' '.MBP_LMW_VERSION; ?> by <a href="http://www.maxblogpress.com/" target="_blank" >MaxBlogPress</a></strong></p>
	</div>
	<?php
}	
	
	// add a option page
	add_action('admin_menu', 'mbp_lmw_options');
	// Hook for the registration
	add_action( 'widgets_init', 'widget_lmw_register' );
} else if ($wp_version < '2.5') {
function mbp_lmw_widget_init()
{
	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

function mbp_lmw_widget($args, $number = 1) {
	extract($args);
	$options = get_option('mbp_lmw_widget');
	$title = $options[$number]['title'];
	$orderby = $options[$number]['orderby'];
	$order = $options[$number]['order'];
	$limit = $options[$number]['limit'];
	$category_single = $options[$number]['category_single'];
	$category_kies = $options[$number]['category_kies'];
	$category_name = $options[$number]['category_name'];
	$hide_invisible = $options[$number]['hide_invisible'];
	$show_updated = $options[$number]['show_updated'];
	$categorize = $options[$number]['categorize'];
	$title_li = $options[$number]['title_li'];
	$title_before = $options[$number]['title_before'];
	$title_after = $options[$number]['title_after'];
	$category_orderby = $options[$number]['category_orderby'];
	$category_order = $options[$number]['category_order'];
	$between = $options[$number]['between'];
	$category_before = $options[$number]['category_before'];
	$category_after = $options[$number]['category_after'];
	$show_rating = $options[$number]['show_rating'];
	$show_images = $options[$number]['show_images'];
	$show_description = $options[$number]['show_description'];
	$include = $options[$number]['include'];
	$exclude = $options[$number]['exclude'];
?>
		<?php echo $before_widget; ?>
			<?php $title ? print($before_title . $title . $after_title) : null; ?>
			<div class="mbp_lmwwidget">
<?php echo $category?>
<?php
// Check of ze gevuld zijn
	$title = $title != '' ? $title : '';
	$orderby = $orderby != '' ? $orderby : 'name';
	$order = $order != '' ? $order : 'ASC';
	$limit = $limit != '' ? $limit : -1;
	$category_kies = $category_kies != '' ? $category_kies : ( $category_single != -1 ? $category_single : '');
	$category_name = $category_name != '' ? $category_name : '';
	$hide_invisible = $hide_invisible != '' ? $hide_invisible : '1';
	$show_updated = $show_updated != '' ? $show_updated : '0';
	$categorize = $categorize != '' ? $categorize : '1';
	$title_li = $title_li != '' ? $title_li : __('Bookmarks');
	$title_before = $title_before != '' ? $title_before : '<h2>';
	$title_after = $title_after != '' ? $title_after : '</h2>';
	$between = $between != '' ? $between : '<br>';
	$category_orderby = $category_orderby != '' ? $category_orderby : 'name';
	$category_order = $category_order != '' ? $category_order : 'ASC';
	$category_before = $category_before != '' ? $category_before : '<li>';
	$category_after = $category_after != '' ? $category_after : '</li>';
	$show_rating = $show_rating != '' ? $show_rating : '0';
	$show_images = $show_images != '' ? $show_images : '1';
	$show_description = $show_description != '' ? $show_description : '0';
	$include = $include != '' ? $include : '';
	$exclude = $exclude != '' ? $exclude : '';

// Pak de links.
		wp_list_bookmarks(array('orderby' => '' . $orderby . '', 'order' => '' . $order . '', 'limit' => $limit, 'category' => '' . $category_kies . '',
'category_name' => '' . $category_name . '', 'hide_invisible' => $hide_invisible, 'show_updated' => $show_updated,
'categorize' => $categorize, 'title_li' => $title_li, 'title_before' => '' . $title_before . '', 'title_after' => '' . $title_after . '',
'category_orderby' => '' . $category_orderby . '', 'category_order' => '' . $category_order . '', 'between' => '' . $between . '',
'category_before' => '' . $category_before . '', 'category_after' => '' . $category_after . '', 'show_rating' => '' . $show_rating . '', 'show_images' => '' . $show_images . '', 'show_description' => '' . $show_description . '', 'include' => '' . $include . '', 'exclude' => '' . $exclude . ''));
?>
			</div>
		<?php echo $after_widget; ?>
<?php
}

function mbp_lmw_widget_control($number) {
	global $wpdb;
	$options = $newoptions = get_option('mbp_lmw_widget');
	if ( $_POST["mbp_lmw-submit-$number"] ) {
		$newoptions[$number]['title'] = strip_tags(stripslashes($_POST["mbp_lmw-title-$number"]));
		$newoptions[$number]['orderby'] = stripslashes($_POST["mbp_lmw-orderby-$number"]);
		$newoptions[$number]['order'] = stripslashes($_POST["mbp_lmw-order-$number"]);
		$newoptions[$number]['limit'] = stripslashes($_POST["mbp_lmw-limit-$number"]);
		$newoptions[$number]['category_single'] = stripslashes($_POST["mbp_lmw-category_single-$number"]);
		$newoptions[$number]['category_kies'] = stripslashes($_POST["mbp_lmw-category_kies-$number"]);
		$newoptions[$number]['category_name'] = stripslashes($_POST["mbp_lmw-category_name-$number"]);
		$newoptions[$number]['hide_invisible'] = stripslashes($_POST["mbp_lmw-hide_invisible-$number"]);
		$newoptions[$number]['show_updated'] = stripslashes($_POST["mbp_lmw-show_updated-$number"]);
		$newoptions[$number]['categorize'] = stripslashes($_POST["mbp_lmw-categorize-$number"]);
		$newoptions[$number]['title_li'] = stripslashes($_POST["mbp_lmw-title_li-$number"]);
		$newoptions[$number]['title_before'] = stripslashes($_POST["mbp_lmw-title_before-$number"]);
		$newoptions[$number]['title_after'] = stripslashes($_POST["mbp_lmw-title_after-$number"]);
		$newoptions[$number]['category_orderby'] = stripslashes($_POST["mbp_lmw-category_orderby-$number"]);
		$newoptions[$number]['category_order'] = stripslashes($_POST["mbp_lmw-category_order-$number"]);
		$newoptions[$number]['between'] = stripslashes($_POST["mbp_lmw-between-$number"]);
		$newoptions[$number]['category_before'] = stripslashes($_POST["mbp_lmw-category_before-$number"]);
		$newoptions[$number]['category_after'] = stripslashes($_POST["mbp_lmw-category_after-$number"]);
		$newoptions[$number]['show_rating'] = stripslashes($_POST["mbp_lmw-show_rating-$number"]);
		$newoptions[$number]['show_images'] = stripslashes($_POST["mbp_lmw-show_images-$number"]);
		$newoptions[$number]['show_description'] = stripslashes($_POST["mbp_lmw-show_description-$number"]);
		$newoptions[$number]['include'] = stripslashes($_POST["mbp_lmw-include-$number"]);
		$newoptions[$number]['exclude'] = stripslashes($_POST["mbp_lmw-exclude-$number"]);

	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('mbp_lmw_widget', $options);
	}
	$title = htmlspecialchars($options[$number]['title'], ENT_QUOTES);
	$orderby = htmlspecialchars($options[$number]['orderby'], ENT_QUOTES);
	$order = htmlspecialchars($options[$number]['order'], ENT_QUOTES);
	$limit = htmlspecialchars($options[$number]['limit'], ENT_QUOTES);
	$category_single = htmlspecialchars($options[$number]['category_single'], ENT_QUOTES);
	$category_kies = htmlspecialchars($options[$number]['category_kies'], ENT_QUOTES);
	$category_name = htmlspecialchars($options[$number]['category_name'], ENT_QUOTES);
	$hide_invisible = htmlspecialchars($options[$number]['hide_invisible'], ENT_QUOTES);
	$show_updated = htmlspecialchars($options[$number]['show_updated'], ENT_QUOTES);
	$categorize = htmlspecialchars($options[$number]['categorize'], ENT_QUOTES);
	$title_li = htmlspecialchars($options[$number]['title_li'], ENT_QUOTES);
	$title_before = htmlspecialchars($options[$number]['title_before'], ENT_QUOTES);
	$title_after = htmlspecialchars($options[$number]['title_after'], ENT_QUOTES);
	$category_orderby = htmlspecialchars($options[$number]['category_orderby'], ENT_QUOTES);
	$category_order = htmlspecialchars($options[$number]['category_order'], ENT_QUOTES);
	$between = htmlspecialchars($options[$number]['between'], ENT_QUOTES);
	$category_before = htmlspecialchars($options[$number]['category_before'], ENT_QUOTES);
	$category_after = htmlspecialchars($options[$number]['category_after'], ENT_QUOTES);
	$show_rating = htmlspecialchars($options[$number]['show_rating'], ENT_QUOTES);
	$show_images = htmlspecialchars($options[$number]['show_images'], ENT_QUOTES);
	$show_description = htmlspecialchars($options[$number]['show_description'], ENT_QUOTES);
	$include = htmlspecialchars($options[$number]['include'], ENT_QUOTES);
	$exclude = htmlspecialchars($options[$number]['exclude'], ENT_QUOTES);

?>
<center>Check <a href="http://codex.wordpress.org/wp_list_bookmarks" target="_blank">wp_list_bookmarks</a> for help with parameters.</center>
<table align="center" cellpadding="1" cellspacing="1" width="400">
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Title Widget:
</td>
<td align="left" valign="middle">
<input style="width: 300px;" id="mbp_lmw-title-<?php echo "$number"; ?>" name="mbp_lmw-title-<?php echo "$number"; ?>" type="mbp_lmw" value="<?php echo $title; ?>" />
</td>
</tr>
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Order By:
</td>
<td align="left" valign="middle" nowrap="nowrap">
<select id="mbp_lmw-orderby-<?php echo "$number"; ?>" name="mbp_lmw-orderby-<?php echo "$number"; ?>" value="<?php echo $options[$number]['orderby']; ?>">
<?php echo "<option value=\"\">Select</option>"; ?>
<?php echo "<option value=\"id\"" . ($options[$number]['orderby']=='id' ? " selected='selected'" : '') .">ID</option>"; ?>
<?php echo "<option value=\"url\"" . ($options[$number]['orderby']=='url' ? " selected='selected'" : '') .">Url</option>"; ?>
<?php echo "<option value=\"target\"" . ($options[$number]['orderby']=='target' ? " selected='selected'" : '') .">Target</option>"; ?>
<?php echo "<option value=\"description\"" . ($options[$number]['orderby']=='description' ? " selected='selected'" : '') .">Description</option>"; ?>
<?php echo "<option value=\"owner\"" . ($options[$number]['orderby']=='owner' ? " selected='selected'" : '') .">Owner</option>"; ?>
<?php echo "<option value=\"rating\"" . ($options[$number]['orderby']=='rating' ? " selected='selected'" : '') .">Rating</option>"; ?>
<?php echo "<option value=\"updated\"" . ($options[$number]['orderby']=='updated' ? " selected='selected'" : '') .">Updated</option>"; ?>
<?php echo "<option value=\"rel\"" . ($options[$number]['orderby']=='rel' ? " selected='selected'" : '') .">Rel</option>"; ?>
<?php echo "<option value=\"notes\"" . ($options[$number]['orderby']=='notes' ? " selected='selected'" : '') .">Notes</option>"; ?>
<?php echo "<option value=\"rss\"" . ($options[$number]['orderby']=='rss' ? " selected='selected'" : '') .">RSS</option>"; ?>
<?php echo "<option value=\"length\"" . ($options[$number]['orderby']=='length' ? " selected='selected'" : '') .">Length</option>"; ?>
<?php echo "<option value=\"rand\"" . ($options[$number]['orderby']=='rand' ? " selected='selected'" : '') .">Random</option>"; ?>
</select>&nbsp; <select id="mbp_lmw-order-<?php echo "$number"; ?>" name="mbp_lmw-order-<?php echo "$number"; ?>" value="<?php echo $options[$number]['order']; ?>">
<?php echo "<option value=\"\">Select</option>"; ?>
<?php echo "<option value=\"ASC\"" . ($options[$number]['order']=='ASC' ? " selected='selected'" : '') .">ASC</option>"; ?>
<?php echo "<option value=\"DESC\"" . ($options[$number]['order']=='DESC' ? " selected='selected'" : '') .">DESC</option>"; ?>
</select>&nbsp; Limit: <input style="width: 30px;" id="mbp_lmw-limit-<?php echo "$number"; ?>" name="mbp_lmw-limit-<?php echo "$number"; ?>" type="mbp_lmw" value="<?php echo $limit; ?>" />
</td>
</tr>
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Categorize:
</td>
<td align="left" valign="middle">
<select id="mbp_lmw-categorize-<?php echo "$number"; ?>" name="mbp_lmw-categorize-<?php echo "$number"; ?>" value="<?php echo $options[$number]['categorize']; ?>">
<?php echo "<option value=\"\">Select</option>"; ?>
<?php echo "<option value=\"1\"" . ($options[$number]['categorize']=='1' ? " selected='selected'" : '') .">Yes</option>"; ?>
<?php echo "<option value=\"0\"" . ($options[$number]['categorize']=='0' ? " selected='selected'" : '') .">No</option>"; ?>
</select>
</td>
</tr>
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Single Category:
</td>
<td align="left" valign="middle" nowrap="nowrap">
<select id="mbp_lmw-category_single-<?php echo "$number"; ?>" name="mbp_lmw-category_single-<?php echo "$number"; ?>" value="<?php echo $options[$number]['category_single']; ?>">
<?php
$cat_id = -1;
echo "<option value=\"-1\">Select</option>";
$results = $wpdb->get_results( "SELECT cat_ID, cat_name FROM $wpdb->categories" );
	if( $results )
	{
		foreach ( $results as $result )
		{
			$cat_id = $result->cat_ID;
			$cat_name = $result->cat_name;
			echo "<option value=\"$cat_id\"" . ($options[$number]['category_single']==$cat_id ? " selected='selected'" : '') .">$cat_name</option>";
		}
	}
?>
			</select>

</td>
</tr>
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Category ID('s):
</td>
<td align="left" valign="middle">
<input style="width: 300px;" id="mbp_lmw-category_kies-<?php echo "$number"; ?>" name="mbp_lmw-category_kies-<?php echo "$number"; ?>" type="mbp_lmw" value="<?php echo $category_kies; ?>" />
</td>
</tr>
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Category Name:
</td>
<td align="left" valign="middle">
<input style="width: 300px;" id="mbp_lmw-category_name-<?php echo "$number"; ?>" name="mbp_lmw-category_name-<?php echo "$number"; ?>" type="mbp_lmw" value="<?php echo $category_name; ?>" />
</td>
</tr>
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Category Order By:
</td>
<td align="left" valign="middle" nowrap="nowrap">
<select id="mbp_lmw-category_orderby-<?php echo "$number"; ?>" name="mbp_lmw-category_orderby-<?php echo "$number"; ?>" value="<?php echo $options[$number]['category_orderby']; ?>">
<?php echo "<option value=\"\">Select</option>"; ?>
<?php echo "<option value=\"name\"" . ($options[$number]['category_orderby']=='name' ? " selected='selected'" : '') .">Name</option>"; ?>
<?php echo "<option value=\"id\"" . ($options[$number]['category_orderby']=='id' ? " selected='selected'" : '') .">ID</option>"; ?>
</select>&nbsp; <select id="mbp_lmw-category_order-<?php echo "$number"; ?>" name="mbp_lmw-category_order-<?php echo "$number"; ?>" value="<?php echo $options[$number]['category_order']; ?>">
<?php echo "<option value=\"\">Select</option>"; ?>
<?php echo "<option value=\"ASC\"" . ($options[$number]['category_order']=='ASC' ? " selected='selected'" : '') .">ASC</option>"; ?>
<?php echo "<option value=\"DESC\"" . ($options[$number]['category_order']=='DESC' ? " selected='selected'" : '') .">DESC</option>"; ?>
</select></td>
</tr>
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Include:
</td>
<td align="left" valign="middle">
<input style="width: 80px;" id="mbp_lmw-include-<?php echo "$number"; ?>" name="mbp_lmw-include-<?php echo "$number"; ?>" type="mbp_lmw" value="<?php echo $include; ?>" />&nbsp; Exclude: <input style="width: 80px;" id="mbp_lmw-exclude-<?php echo "$number"; ?>" name="mbp_lmw-exclude-<?php echo "$number"; ?>" type="mbp_lmw" value="<?php echo $exclude; ?>" />
</td>
</tr>
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Category Before:
</td>
<td align="left" valign="middle">
<input style="width: 80px;" id="mbp_lmw-category_before-<?php echo "$number"; ?>" name="mbp_lmw-category_before-<?php echo "$number"; ?>" type="mbp_lmw" value="<?php echo $category_before; ?>" />&nbsp; Category After: <input style="width: 80px;" id="mbp_lmw-category_after-<?php echo "$number"; ?>" name="mbp_lmw-category_after-<?php echo "$number"; ?>" type="mbp_lmw" value="<?php echo $category_after; ?>" />
</td>
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Title Li:
</td>
<td align="left" valign="middle">
<input style="width: 300px;" id="mbp_lmw-title_li-<?php echo "$number"; ?>" name="mbp_lmw-title_li-<?php echo "$number"; ?>" type="mbp_lmw" value="<?php echo $title_li; ?>" />
</td>
</tr>

<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Title Before:
</td>
<td align="left" valign="middle">
<input style="width: 80px;" id="mbp_lmw-title_before-<?php echo "$number"; ?>" name="mbp_lmw-title_before-<?php echo "$number"; ?>" type="mbp_lmw" value="<?php echo $title_before; ?>" />&nbsp; Title After: <input style="width: 80px;" id="mbp_lmw-title_after-<?php echo "$number"; ?>" name="mbp_lmw-title_after-<?php echo "$number"; ?>" type="mbp_lmw" value="<?php echo $title_after; ?>" /></td>
</tr>
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Between:
</td>
<td align="left" valign="middle">
<input style="width: 300px;" id="mbp_lmw-between-<?php echo "$number"; ?>" name="mbp_lmw-between-<?php echo "$number"; ?>" type="mbp_lmw" value="<?php echo $between; ?>" />
</td>
</tr>
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Hide Invincible:
</td>
<td align="left" valign="middle">
<select id="mbp_lmw-hide_invisible-<?php echo "$number"; ?>" name="mbp_lmw-hide_invisible-<?php echo "$number"; ?>" value="<?php echo $options[$number]['hide_invisible']; ?>">
<?php echo "<option value=\"\">Select</option>"; ?>
<?php echo "<option value=\"1\"" . ($options[$number]['hide_invisible']=='1' ? " selected='selected'" : '') .">Yes</option>"; ?>
<?php echo "<option value=\"0\"" . ($options[$number]['hide_invisible']=='0' ? " selected='selected'" : '') .">No</option>"; ?>
</select>
</td>
</tr>
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Description:
</td>
<td align="left" valign="middle">
<select id="mbp_lmw-show_description-<?php echo "$number"; ?>" name="mbp_lmw-show_description-<?php echo "$number"; ?>" value="<?php echo $options[$number]['show_description']; ?>">
<?php echo "<option value=\"\">Select</option>"; ?>
<?php echo "<option value=\"1\"" . ($options[$number]['show_description']=='1' ? " selected='selected'" : '') .">Yes</option>"; ?>
<?php echo "<option value=\"0\"" . ($options[$number]['show_description']=='0' ? " selected='selected'" : '') .">No</option>"; ?>
</select>
</td>
</tr>
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Images:
</td>
<td align="left" valign="middle">
<select id="mbp_lmw-show_images-<?php echo "$number"; ?>" name="mbp_lmw-show_images-<?php echo "$number"; ?>" value="<?php echo $options[$number]['show_images']; ?>">
<?php echo "<option value=\"\">Select</option>"; ?>
<?php echo "<option value=\"1\"" . ($options[$number]['show_images']=='1' ? " selected='selected'" : '') .">Yes</option>"; ?>
<?php echo "<option value=\"0\"" . ($options[$number]['show_images']=='0' ? " selected='selected'" : '') .">No</option>"; ?>
</select>
</td>
</tr>
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Rating:
</td>
<td align="left" valign="middle">
<select id="mbp_lmw-show_rating-<?php echo "$number"; ?>" name="mbp_lmw-show_rating-<?php echo "$number"; ?>" value="<?php echo $options[$number]['show_rating']; ?>">
<?php echo "<option value=\"\">Select</option>"; ?>
<?php echo "<option value=\"1\"" . ($options[$number]['show_rating']=='1' ? " selected='selected'" : '') .">Yes</option>"; ?>
<?php echo "<option value=\"0\"" . ($options[$number]['show_rating']=='0' ? " selected='selected'" : '') .">No</option>"; ?>
</select>
</td>
</tr>
<tr>
<td align="left" valign="middle" width="90" nowrap="nowrap">
Updated:
</td>
<td align="left" valign="middle">
<select id="mbp_lmw-show_updated-<?php echo "$number"; ?>" name="mbp_lmw-show_updated-<?php echo "$number"; ?>" value="<?php echo $options[$number]['show_updated']; ?>">
<?php echo "<option value=\"\">Select</option>"; ?>
<?php echo "<option value=\"1\"" . ($options[$number]['show_updated']=='1' ? " selected='selected'" : '') .">Yes</option>"; ?>
<?php echo "<option value=\"0\"" . ($options[$number]['show_updated']=='0' ? " selected='selected'" : '') .">No</option>"; ?>
</select>
</td>
</tr>
</table>

			<input type="hidden" id="mbp_lmw-submit-<?php echo "$number"; ?>" name="mbp_lmw-submit-<?php echo "$number"; ?>" value="1" />
<?php
}

function mbp_lmw_widget_setup() {
	$options = $newoptions = get_option('mbp_lmw_widget');
	if ( isset($_POST['mbp_lmw-number-submit']) ) {
		$number = (int) $_POST['mbp_lmw-number'];
		if ( $number > 9 ) $number = 9;
		if ( $number < 1 ) $number = 1;
		$newoptions['number'] = $number;
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('mbp_lmw_widget', $options);
		mbp_lmw_widget_register($options['number']);
	}
}

function mbp_lmw_widget_page() {
	$options = $newoptions = get_option('mbp_lmw_widget');
?>
	<div class="wrap">
		<form method="POST">
			<h2> Links Manage Widget</h2>
			<p style="line-height: 30px;"><?php _e('How many Links manage widgets would you like?'); ?>
			<select id="mbp_lmw-number" name="mbp_lmw-number" value="<?php echo $options['number']; ?>">
<?php for ( $i = 1; $i < 10; ++$i ) echo "<option value='$i' ".($options['number']==$i ? "selected='selected'" : '').">$i</option>"; ?>
			</select>
			<span class="submit"><input type="submit" name="mbp_lmw-number-submit" id="mbp_lmw-number-submit" value="<?php _e('Save'); ?>" /></span></p>
		</form>
	</div>
<?php
}

function mbp_lmw_widget_register() {
	$options = get_option('mbp_lmw_widget');
	$number = $options['number'];
	if ( $number < 1 ) $number = 1;
	if ( $number > 9 ) $number = 9;
	for ($i = 1; $i <= 9; $i++) {
		$name = array('Links Manage Widget%s', null, $i);
		register_sidebar_widget($name, $i <= $number ? 'mbp_lmw_widget' : /* unregister */ '', '', $i);
		register_widget_control($name, $i <= $number ? 'mbp_lmw_widget_control' : /* unregister */ '', 460, 580, $i);
	}
	add_action('sidebar_admin_setup', 'mbp_lmw_widget_setup');
	add_action('sidebar_admin_page', 'mbp_lmw_widget_page');
}
// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
mbp_lmw_widget_register();
}

// Tell Dynamic Sidebar about our new widget and its control
add_action('plugins_loaded', 'mbp_lmw_widget_init');
	
}
?>