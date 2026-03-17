<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Shared\PaymentTemplate;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class PaymentTemplateConfig extends AbstractSharedConfig
{
    public const string PAYMENT_METHOD_CREDIT_CARD = 'paymentTemplateCreditCard';

    public const string PAYMENT_METHOD_INVOICE = 'paymentTemplateInvoice';

    public const string PAYMENT_PROVIDER_NAME = 'PaymentTemplate';

    public const string OMS_PROCESS_LOCATION = APPLICATION_ROOT_DIR . '/vendor/spryker-community/payment-template/config/Zed/oms';

    /**
     * Gets API key from environment configuration.
     *
     * @api
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->get(PaymentTemplateConstants::API_KEY, '');
    }

    /**
     * Gets API secret from environment configuration.
     *
     * @api
     *
     * @return string
     */
    public function getApiSecret(): string
    {
        return $this->get(PaymentTemplateConstants::API_SECRET, '');
    }

    /**
     * Gets API base URL from environment configuration.
     *
     * @api
     *
     * @return string
     */
    public function getApiBaseUrl(): string
    {
        return $this->get(PaymentTemplateConstants::API_BASE_URL, '');
    }

    /**
     * Gets API timeout in seconds.
     *
     * @api
     *
     * @return int
     */
    public function getApiTimeout(): int
    {
        return $this->get(PaymentTemplateConstants::API_TIMEOUT, 30);
    }

    public function getAuthorizePath(): string
    {
        return $this->get(PaymentTemplateConstants::API_AUTHORIZE_PATH, '/api/v1/authorize');
    }

    public function getCapturePath(): string
    {
        return $this->get(PaymentTemplateConstants::API_CAPTURE_PATH, '/api/v1/capture');
    }

    public function getRefundPath(): string
    {
        return $this->get(PaymentTemplateConstants::API_REFUND_PATH, '/api/v1/refund');
    }

    public function getCancelPath(): string
    {
        return $this->get(PaymentTemplateConstants::API_CANCEL_PATH, '/api/v1/cancel');
    }

    public function getPaymentMethodsPath(): string
    {
        return $this->get(PaymentTemplateConstants::API_PAYMENT_METHODS_PATH, '/api/v1/payment-methods');
    }
}
