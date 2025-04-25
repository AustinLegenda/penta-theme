<?php

namespace MatrixAddons\EasyInvoice\API;

abstract class PaymentWebhookBase
{
	protected $rest_route_id;

	protected $method = \WP_REST_Server::CREATABLE;

	public function __construct($rest_route_id)
	{
		$this->rest_route_id = $rest_route_id;
	}

	public function register_routes()
	{
		register_rest_route(EASY_INVOICE_REST_WEBHOOKS_NAMESPACE, $this->rest_route_id, array(
			'methods' => $this->method,
			'callback' => array($this, 'handle_webhook_request'),
			'permission_callback' => array($this, 'validate_webhook_request')
		));
	}

	abstract public function validate_webhook_request(\WP_REST_Request $request);

	abstract public function handle_webhook_request(\WP_REST_Request $request);

}

