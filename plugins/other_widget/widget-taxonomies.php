<?php

/*-------------------------------------------*/
/*	Taxonomy list widget
/*-------------------------------------------*/
class WP_Widget_VK_taxonomy_list extends WP_Widget {
    // ウィジェット定義
	function __construct() {
		$widget_name = vkExUnit_get_short_name().'_'. __( 'Categories/Custom taxonomies list', 'vkExUnit' );

		parent::__construct(
			'WP_Widget_VK_taxonomy_list',
			$widget_name,
			array( 'description' => __( 'Displays a categories and custom taxonomies list.', 'vkExUnit' ) )
		);
	}


	function widget($args, $instance) {
		$arg = array(
			'echo'               => 1,
			'style'              => 'list',
			'show_count'         => false,
			'show_option_all'    => false,
			'hierarchical'       => true,
			'title_li'           => '',
			);

		$arg['taxonomy'] = $instance['tax_name'];

	?>
	<?php echo $args['before_widget']; ?>
	<div class="sideWidget widget_taxonomies widget_nav_menu">
		<?php echo $args['before_title'] . $instance['label'] . $args['after_title']; ?>
		<ul class="localNavi">
			<?php wp_list_categories($arg); ?>
		</ul>
	</div>
	<?php echo $args['after_widget']; ?>
	<?php
	}


	function form($instance){
		$defaults = array(
			'tax_name'     => 'category',
			'label'        => __( 'Category', 'vkExUnit' ),
			'hide'         => __( 'Category', 'vkExUnit' ),
			'title'        => 'Category',
			'_builtin'     => false,
		);
		$instance = wp_parse_args((array) $instance, $defaults);
		$taxs = get_taxonomies( array('public'=> true),'objects'); 
		?>
		<p>
		<label for="<?php echo $this->get_field_id('label'); ?>"><?php _e( 'Label to display', 'vkExUnit' ); ?></label>
		<input type="text"  id="<?php echo $this->get_field_id('label'); ?>-title" name="<?php echo $this->get_field_name('label'); ?>" value="<?php echo $instance['label']; ?>" ><br/>
		<input type="hidden" name="<?php echo $this->get_field_name('hide'); ?>" ><br/>

		<label for="<?php echo $this->get_field_id('tax_name'); ?>"><?php _e('Display page', 'vkExUnit') ?></label>
		<select name="<?php echo $this->get_field_name('tax_name'); ?>" >

		<?php foreach($taxs as $tax){ ?>
			<option value="<?php echo $tax->name; ?>" <?php if($instance['tax_name'] == $tax->name) echo 'selected="selected"'; ?> ><?php echo $tax->labels->name; ?></option>
		<?php } ?>
		</select></p>
		<script type="text/javascript">
		jQuery(document).ready(function($){
			var post_labels = new Array();
			<?php
				foreach($taxs as $tax){
					if(isset($tax->labels->name)){
						echo 'post_labels["'.$tax->name.'"] = "'.$tax->labels->name.'";';
					}
				}
				echo 'post_labels["blog"] = "'. __( 'Blog', 'vkExUnit' ) . '";'."\n";
			?>
			var posttype = jQuery("[name=\"<?php echo $this->get_field_name('tax_name'); ?>\"]");
			var lablfeld = jQuery("[name=\"<?php echo $this->get_field_name('label'); ?>\"]");
			posttype.change(function(){
				lablfeld.val(post_labels[posttype.val()]+" <?php _e( 'Archives', 'vkExUnit' ) ?>");
			});
		});
		</script>
		<?php
	}


	function update($new_instance, $old_instance){
		$instance = $old_instance;

		$instance['tax_name'] = $new_instance['tax_name'];

		if(!$new_instance['label']){
			$new_instance['label'] = $new_instance['hide'];
		}
		$instance['label'] = esc_html($new_instance['label']);

		return $instance;
	}
} // class WP_Widget_top_list_info

add_action('widgets_init', create_function('', 'return register_widget("WP_Widget_VK_taxonomy_list");'));