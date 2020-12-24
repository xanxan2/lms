<?php
/**
 * Base meta box class.
 *
 * @package pragyan
 */

defined('ABSPATH') || exit;

/**
 * Class Pragyan_Meta_Box
 */
class Pragyan_Meta_Box
{

	/**
	 * Keep record if meta box is saved once.
	 *
	 * @var boolean
	 */
	private static $saved_meta_boxes = false;

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct()
	{
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		add_action('admin_print_styles-post-new.php', array($this, 'enqueue'));
		add_action('admin_print_styles-post.php', array($this, 'enqueue'));
		add_action('save_post', array($this, 'save_meta_boxes'), 1, 2);

		// Save Page Settings Meta Boxes.
		add_action('pragyan_process_page_settings_meta', 'Pragyan_Meta_Box_Page_Settings::save', 10, 2);
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_boxes()
	{
		add_meta_box('pragyan-page-setting', esc_html__('Pragyan Page Settings', 'pragyan'), 'Pragyan_Meta_Box_Page_Settings::render', array(
			'post',
			'page',
		));
	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueue()
	{
		$cur_screen = get_current_screen();
		$id = isset($cur_screen->id) ? $cur_screen->id : '';
		if (!in_array($id, array('post', 'page'))) {
			return;
		}

		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('pragyan-meta-box', PRAGYAN_THEME_URI . '/core/meta-boxes/assets/js/meta-box.js', array('jquery-ui-tabs', 'wp-i18n'), PRAGYAN_THEME_VERSION, true);
		wp_enqueue_style('pragyan-meta-box', PRAGYAN_THEME_URI . '/core/meta-boxes/assets/css/meta-box.css', array(), PRAGYAN_THEME_VERSION);
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
	}

	/**
	 * Handles saving the meta box.
	 *
	 * @param int $post_id Post ID.
	 * @param WP_Post $post Post object.
	 *
	 * @return null
	 */
	public function save_meta_boxes($post_id, $post)
	{
		// Check the nonce.
		if (!isset($_POST['pragyan_meta_nonce']) || !wp_verify_nonce(sanitize_key($_POST['pragyan_meta_nonce']), 'pragyan_nonce_action')) {
			return;
		}

		// $post_id and $post are required.
		if (empty($post_id) || empty($post) || self::$saved_meta_boxes) {
			return;
		}

		// Check for revisions or autosaves.
		if (defined('DOING_AUTOSAVE') || is_int(wp_is_post_revision($post)) || is_int(wp_is_post_autosave($post))) {
			return;
		}

		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
		if (empty($_POST['post_ID']) || intval($_POST['post_ID']) !== $post_id) {
			return;
		}

		// Check user's permisstion.
		if (isset($_POST['post_type']) && ('page' === $_POST['post_type'])) {
			if (!current_user_can('edit_page', $post_id)) {
				return $post_id;
			}
		} else {
			if (!current_user_can('edit_post', $post_id)) {
				return $post_id;
			}
		}

		self::$saved_meta_boxes = true;

		// Trigger action.
		$process_actions = array('page_settings');
		foreach ($process_actions as $process_action) {
			do_action('pragyan_process_' . $process_action . '_meta', $post_id, $post);
		}

	}
}

new Pragyan_Meta_Box();
