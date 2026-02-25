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
use Spryker\Client\Kernel\AbstractClient;

/**
 * {@inheritDoc}
 *
 * @api
 *
 * @method \Spryker\Client\PaymentTemplate\PaymentTemplateFactory getFactory()
 */
class PaymentTemplateClient extends AbstractClient implements PaymentTemplateClientInterface
{
    public function authorize(PaymentTemplateAuthorizeRequestTransfer $paymentTemplateAuthorizeRequestTransfer): PaymentTemplateAuthorizeResponseTransfer
    {
        return $this->getFactory()->createAuthorizeRequest()->request($paymentTemplateAuthorizeRequestTransfer);
    }

    public function capture(PaymentTemplateCaptureRequestTransfer $paymentTemplateAuthorizeResponseTransfer): PaymentTemplateCaptureResponseTransfer
    {
        return $this->getFactory()->createCaptureRequest()->request($paymentTemplateAuthorizeResponseTransfer);
    }

    public function cancel(PaymentTemplateCancelRequestTransfer $paymentTemplateCancelRequestTransfer): PaymentTemplateCancelResponseTransfer
    {
        return $this->getFactory()->createCancelRequest()->request($paymentTemplateCancelRequestTransfer);
    }

    public function getPaymentMethods(
        PaymentTemplatePaymentMethodsRequestTransfer $paymentTemplatePaymentMethodsRequestTransfer,
    ): PaymentTemplatePaymentMethodsResponseTransfer {
        return $this->getFactory()->createPaymentMethodsRequest()->request($paymentTemplatePaymentMethodsRequestTransfer);
    }

    public function processWebhook(
        PaymentTemplateWebhookPayloadTransfer $webhookPayloadTransfer,
    ): PaymentTemplateWebhookProcessResponseTransfer {
        return $this->getFactory()->createZedStub()->processWebhook($webhookPayloadTransfer);
    }
}
