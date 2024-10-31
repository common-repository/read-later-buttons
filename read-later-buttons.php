<?php

/*
Plugin Name: Read Later Buttons
Plugin URI: http://wordpress.org/extend/plugins/read_later_buttons
Description: Adds "read later" buttons to a site. Can send content to Kindle (additional plugin required), Instapaper, Pocket, and Readability.
Version: 1.2
Author: Dave Ross
Author URI: http://davidmichaelross.com
License: MIT
License URI: http://daveross.mit-license.org
*/

class ReadLaterButtons extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {

		parent::__construct(
			'read_later_buttons', // Base ID
			'Read Later Buttons', // Name
			array( 'description' => __( 'Read Later Buttons', 'text_domain' ), ) // Args
		);

	}

	/**
	 * Render the widget
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		// Convoluted, but safer than extract()
		$args_defaults = array(
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
		);
		$args          = wp_parse_args( $args, $args_defaults );
		$before_widget = $args['before_widget'];
		$after_widget  = $args['after_widget'];
		$before_title  = $args['before_title'];
		$after_title   = $args['after_title'];

		$instance = wp_parse_args( $instance, self::instance_defaults() );
		$title    = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		$params = array();
		if ( isset( $instance['kindle'] ) && $instance['kindle'] ) {
			$params[] = 'kindle';
		}
		if ( isset( $instance['instapaper'] ) && $instance['instapaper'] ) {
			$params[] = 'instapaper';
		}
		if ( isset( $instance['pocket'] ) && $instance['pocket'] ) {
			$params[] = 'pocket';
		}
		if ( isset( $instance['readability'] ) && $instance['readability'] ) {
			$params[] = 'readability';
		}

		echo self::shortcode( $params );
		echo $after_widget;

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance                = array();
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['kindle']      = strip_tags( $new_instance['kindle'] );
		$instance['instapaper']  = strip_tags( $new_instance['instapaper'] );
		$instance['pocket']      = strip_tags( $new_instance['pocket'] );
		$instance['readability'] = strip_tags( $new_instance['readability'] );

		return $instance;

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		// Default arguments
		$instance = wp_parse_args( $instance, self::instance_defaults() );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'read-later-buttons' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />

		<div>
			<input type="hidden" name="kindle" value="" />
			<?php if ( class_exists( 'STK_Button' ) ) : ?>
				<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'kindle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'kindle' ) ); ?>" type="checkbox" value="1" <?php checked( 1, $instance['kindle'], true ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'kindle' ) ); ?>"><?php _e( 'Kindle', 'read-later-buttons' ); ?></label>
			<?php else : ?>
				<p><?php printf( __( 'To enable the Send to Kindle button, install the %s plugin', 'read-later-buttons' ), '<a href="http://wordpress.org/extend/plugins/send-to-kindle/" target="_blank">' . __( 'Send to Kindle', 'read-later-buttons' ) . '</a>' ); ?></p>
			<?php endif; ?>
		</div>

		<div>
			<input type="hidden" name="instapaper" value="" />
			<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'instapaper' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instapaper' ) ); ?>" type="checkbox" value="1" <?php checked( 1, $instance['instapaper'], true ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'instapaper' ) ); ?>"><?php _e( 'Instapaper', 'read-later-buttons' ); ?></label>
		</div>

		<div>
			<input type="hidden" name="pocket" value="" />
			<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'pocket' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pocket' ) ); ?>" type="checkbox" value="1" <?php checked( 1, $instance['pocket'], true ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'pocket' ) ); ?>"><?php _e( 'Pocket', 'read-later-buttons' ); ?></label>
		</div>

		<div>
			<input type="hidden" name="readability" value="" />
			<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'readability' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'readability' ) ); ?>" type="checkbox" value="1" <?php checked( 1, $instance['readability'], true ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'readability' ) ); ?>"><?php _e( 'Readability', 'read-later-buttons' ); ?></label>
		</div>

		</p>
	<?php

	}

	/**
	 * init handler
	 */
	public static function init() {

		load_plugin_textdomain( 'read-later-buttons', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

	/**
	 * wp_enqueue_scripts handler
	 */
	public static function wp_enqueue_scripts() {

		$do_css = apply_filters( 'read_later_buttons_do_css', true );
		if ( $do_css ) {
			wp_register_style( 'read_later_buttons', plugins_url( 'read-later-buttons.css', __FILE__ ) );
			wp_enqueue_style( 'read_later_buttons' );
		}

		if ( defined( 'SCRIPT_DEBUG' ) ) {
			wp_register_script( 'read_later_buttons', plugins_url( 'read-later-buttons.js', __FILE__ ) );
		}
		else {
			wp_register_script( 'read_later_buttons', plugins_url( 'read-later-buttons.min.js', __FILE__ ) );
		}
		wp_enqueue_script( 'read_later_buttons' );

	}

	/**
	 * read_later_buttons shortcode
	 */
	public static function shortcode( $attributes ) {

		if ( empty( $attributes ) ) {
			$attributes = array( 'all' );
		}

		$html = '<div class="read_later_buttons">';
		if ( in_array( 'time', $attributes ) ) {
			$html .= self::render_reading_time();
		}
		if ( in_array( 'kindle', $attributes ) || in_array( 'all', $attributes ) ) {
			if ( class_exists( 'STK_Button' ) ) {
				$kindle_plugin = STK_Button::get_instance();
				$html .= $kindle_plugin->get_button_html();
			}
		}
		if ( in_array( 'instapaper', $attributes ) || in_array( 'all', $attributes ) ) {
			$html .= self::render_instapaper();
		}
		if ( in_array( 'pocket', $attributes ) || in_array( 'all', $attributes ) ) {
			$html .= self::render_pocket();
		}
		if ( in_array( 'readability', $attributes ) || in_array( 'all', $attributes ) ) {
			$html .= self::render_readability();
		}
		$html .= '</div>';
		return $html;

	}

	/**
	 * Render the Instapaper button
	 * @return string HTML
	 */
	private static function render_instapaper() {

		global $post;

		$link  = urlencode( self::get_link() );
		$title = get_the_title();

		$html = '';
		$html .= '<span class="read_later_service">';
		$html .= '<a class="button instapaper" href="http://www.instapaper.com/hello2?url=' . $link . ( ( ! empty( $title ) ) ? '&title=' . urlencode( $title ) : '' ) . '">';
		$html .= '<img src="' . plugins_url( '/images/instapaper.png', __FILE__ ) . '" />';
		$html .= '<span>' . __( 'Send to Instapaper', 'read-later-buttons' ) . '</span>';
		$html .= '</a>';
		$html .= '</span>';

		$html = apply_filters( 'read_later_buttons_instapaper', $html );
		return $html;

	}

	/**
	 * Render the Pocket button
	 * @return string HTML
	 */
	private static function render_pocket() {

		global $post;

		$link  = urlencode( self::get_link() );
		$title = get_the_title();

		$html = '';
		$html .= '<span class="read_later_service">';
		$html .= '<a class="button pocket" href="https://getpocket.com/save?url=' . $link . ( ( ! empty( $title ) ) ? '&title=' . urlencode( $title ) : '' ) . '" target="_blank">';
		$html .= '<img src="' . plugins_url( '/images/pocket.png', __FILE__ ) . '" />';
		$html .= '<span>' . __( 'Send to Pocket', 'read-later-buttons' ) . '</span>';
		$html .= '</a>';
		$html .= '</span>';

		$html = apply_filters( 'read_later_buttons_pocket', $html );
		return $html;

	}

	/**
	 * Render the Readability button
	 * @return string HTML
	 */
	private static function render_readability() {

		global $post;

		$link  = urlencode( self::get_link() );
		$title = get_the_title();

		$html = '';
		$html .= '<span class="read_later_service">';
		$html .= '<a class="button readability" href="http://www.readability.com/save?url=' . $link . ( ( ! empty( $title ) ) ? '&title=' . urlencode( $title ) : '' ) . '">';
		$html .= '<img src="' . plugins_url( '/images/readability.png', __FILE__ ) . '" />';
		$html .= '<span>' . __( 'Send to Readability', 'read-later-buttons' ) . '</span>';
		$html .= '</a>';
		$html .= '</span>';

		$html = apply_filters( 'read_later_buttons_readability', $html );
		return $html;

	}

	/**
	 * Render the reading time estimate
	 * @return string HTML
	 */
	private static function render_reading_time() {

		$html = '';
		if ( get_the_content() || in_the_loop() ) {
			$reading_time = self::calc_reading_time( get_the_content() );
			$html .= '<p class="reading_estimate">';
			$html .= __( 'The estimated reading time for this post is', 'read-later-buttons' ) . ' ';
			$html .= sprintf( _n( __( '%d minute' ), __( '%d minutes' ), $reading_time['minutes'] ), $reading_time['minutes'] );
			$html .= ', ';
			$html .= sprintf( _n( __( '%d second' ), __( '%d seconds' ), $reading_time['seconds'] ), $reading_time['seconds'] );
			$html .= '</p>';
		}
		else {
			if ( defined( 'WP_DEBUG' ) ) {
				echo '<div class="warning">' . __( 'Read Later Buttons time estimate only works inside a loop.', 'read-later-buttons' ) . '</div>';
			}
		}

		$html = apply_filters( 'read_later_buttons_reading_time', $html );
		return $html;

	}

	/**
	 * Calculate the reading time for a piece of content
	 *
	 * @param string  $content
	 * @param integer $wpm Words Per Minute reading speed
	 *
	 * @return array minutes => integer, seconds => integer
	 */
	private static function calc_reading_time( $content, $wpm = 250 ) {

		$wpm = absint( $wpm );

		$word            = str_word_count( strip_tags( $content ) );
		$time            = array();
		$time['minutes'] = floor( $word / $wpm );
		$time['seconds'] = floor( $word % $wpm / ( $wpm / 60 ) );
		return $time;

	}

	/**
	 * Get the URL to include on buttons, either the loop's current $post or the current page
	 */
	private static function get_link() {

		global $post;

		if ( get_permalink() || in_the_loop() ) {
			$link = get_permalink();
		}
		else {
			// from http://www.stephenharris.info/2012/how-to-get-the-current-url-in-wordpress/
			$link = home_url( add_query_arg( array(), $wp->request ) );
		}

		$link = apply_filters( 'read_later_buttons_link', $link );
		return $link;

	}

	/**
	 * Returns an array of default values for the $instance array
	 * @return array
	 */
	private static function instance_defaults() {
		static $defaults;
		if ( ! isset( $defaults ) ) {
			$defaults = array( 'title' => __( 'Read Later', 'read-later-buttons' ), 'kindle' => false, 'instapaper' => false, 'pocket' => false, 'readability' => false );
		}
		return $defaults;
	}
}

add_action( 'wp_enqueue_scripts', 'ReadLaterButtons::wp_enqueue_scripts' );
add_action( 'init', 'ReadLaterButtons::init' );
add_action( 'widgets_init', create_function( '', 'register_widget( "ReadLaterButtons" );' ) );
add_shortcode( 'read_later_buttons', 'ReadLaterButtons::shortcode' );