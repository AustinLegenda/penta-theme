<?php

namespace MatrixAddons\EasyInvoice\Admin\Fields\Invoices;

use MatrixAddons\EasyInvoice\Admin\Fields\Base;

class ManualPaymentFields extends Base
{
    public function get_settings()
    {
        return [
            'manual_payment_type' => [
                'type' => 'select',
                'title' => __('Payment Type', 'easy-invoice'),
                'options' => [
                    '' => __('Select Payment Type', 'easy-invoice'),
                    'ach' => __('ACH', 'easy-invoice'),
                    'cash' => __('CASH', 'easy-invoice'),
                    'check' => __('Check', 'easy-invoice'),
                    'venmo' => __('Venmo', 'easy-invoice'),
                    'zelle' => __('Zelle', 'easy-invoice'),

                ],
            ],
            'manual_payment_status' => [
                'type' => 'select',
                'title' => __('Payment Status', 'easy-invoice'),
                'options' => [
                    '' => __('Select Payment Status', 'easy-invoice'),
                    'partial' => __('Partial', 'easy-invoice'),
                    'full' => __('Full', 'easy-invoice'),
                ],
            ],
            'manual_payment_note' => [
                'type' => 'textarea',
                'title' => __('Payment Note', 'easy-invoice'),
            ],

        ];
    }

    public function render()
    {
        $this->output();
    }


    public function nonce_id()
    {
        return 'easy_invoice_manual_payment_fields';
    }
}
