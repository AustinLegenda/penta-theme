<?php
/**
 * @package
 * @subpackage  Classes/Cron
 * @since 1.1.3
 */
namespace MatrixAddons\EasyInvoice;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Cron Class
 *
 * This class handles scheduled events
 *
 * @since 1.1.3
 */
class Cron
{
	/**
	 * Init WordPress hook
	 *
	 * @since 1.1.3
	 * @see Cron::weekly_events()
	 */
	public static function init()
	{
		$self = new self;

		add_filter('cron_schedules', array($self, 'add_schedules'));
		add_action('init', array($self, 'schedule_events'));
	}
	/**
	 * Registers new cron schedules
	 *
	 * @param array $schedules Schedules.
	 * @return array
	 * @since 1.1.3
	 */
	public function add_schedules($schedules = array())
	{
		/*Adds once in hourly to the existing schedules*/
		$schedules['hourly'] = array(
			'interval' => (HOUR_IN_SECONDS * 1),
			'display' => __('Every 1 hour', 'easy-invoice'),
		);

		return $schedules;
	}

	/**
	 * Schedules our events
	 *
	 * @return void
	 * @since 1.1.3
	 */
	public function schedule_events()
	{
		$this->usage_cron();
	}

	/**
	 * Schedule hourly events
	 *
	 * @return void
	 * @since 1.1.3
	 */
	private function usage_cron()
	{
		if (!wp_next_scheduled('easy_invoice_scheduled_events')) {
			wp_schedule_event(time(), 'hourly', 'easy_invoice_scheduled_events');
		}
	}

}
