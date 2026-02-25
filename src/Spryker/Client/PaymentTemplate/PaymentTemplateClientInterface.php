<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\PaymentTemplate;

use Generated\Shared\Transfer\PaymentTemplateAuthorizeRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateAuthorizeResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplateCancelRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateCancelResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplateCaptureRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateCaptureResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplatePaymentMethodsRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplatePaymentMethodsResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplateWebhookPayloadTransfer;
use Generated\Shared\Transfer\PaymentTemplateWebhookProcessResponseTransfer;

interface PaymentTemplateClientInterface
{
    /**
     * Specification:
     * - Sends authorization request to payment provider.
     * - Maps request data to provider-specific format.
     * - Returns authorization response.
     * - Logs the request, response and error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTemplateAuthorizeRequestTransfer $paymentTemplateAuthorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTemplateAuthorizeResponseTransfer
     */
    public function authorize(PaymentTemplateAuthorizeRequestTransfer $paymentTemplateAuthorizeRequestTransfer): PaymentTemplateAuthorizeResponseTransfer;

    /**
     * Specification:
     * - Sends capture request to payment provider to capture authorized amount.
     * - Maps request data to provider-specific format.
     * - Returns capture response.
     * - Logs the request, response and error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTemplateCaptureRequestTransfer $paymentTemplateAuthorizeResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTemplateCaptureResponseTransfer
     */
    public function capture(PaymentTemplateCaptureRequestTransfer $paymentTemplateAuthorizeResponseTransfer): PaymentTemplateCaptureResponseTransfer;

    /**
     * Specification:
     * - Sends cancel request to payment provider to cancel authorized payment.
     * - Maps request data to provider-specific format.
     * - Returns cancellation response.
     * - Logs the request, response and error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTemplateCancelRequestTransfer $paymentTemplateCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTemplateCancelResponseTransfer
     */
    public function cancel(PaymentTemplateCancelRequestTransfer $paymentTemplateCancelRequestTransfer): PaymentTemplateCancelResponseTransfer;

    /**
     * Specification:
     * - Retrieves available payment methods from payment provider.
     * - Maps request data to provider-specific format.
     * - Returns list of available payment methods for current context.
     * - Logs the request, response and error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTemplatePaymentMethodsRequestTransfer $paymentTemplatePaymentMethodsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTemplatePaymentMethodsResponseTransfer
     */
    public function getPaymentMethods(
        PaymentTemplatePaymentMethodsRequestTransfer $paymentTemplatePaymentMethodsRequestTransfer,
    ): PaymentTemplatePaymentMethodsResponseTransfer;

    /**
     * Specification:
     * - Sends webhook payload to Zed for processing.
     * - Zed will save webhook to database and update payment status.
     * - Returns webhook processing response.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTemplateWebhookPayloadTransfer $webhookPayloadTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTemplateWebhookProcessResponseTransfer
     */
    public function processWebhook(
        PaymentTemplateWebhookPayloadTransfer $webhookPayloadTransfer,
    ): PaymentTemplateWebhookProcessResponseTransfer;
}
