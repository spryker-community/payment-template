<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\PaymentTemplate\Zed;

use Generated\Shared\Transfer\PaymentTemplateWebhookPayloadTransfer;
use Generated\Shared\Transfer\PaymentTemplateWebhookProcessResponseTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class PaymentTemplateStub implements PaymentTemplateStubInterface
{
    public function __construct(
        protected ZedRequestClientInterface $zedRequestClient,
    ) {
    }

    public function processWebhook(
        PaymentTemplateWebhookPayloadTransfer $paymentTemplateWebhookPayloadTransfer,
    ): PaymentTemplateWebhookProcessResponseTransfer {
        /** @var \Generated\Shared\Transfer\PaymentTemplateWebhookProcessResponseTransfer $paymentTemplateWebhookProcessResponseTransfer */
        $paymentTemplateWebhookProcessResponseTransfer = $this->zedRequestClient->call(
            '/payment-template/gateway/process-webhook',
            $paymentTemplateWebhookPayloadTransfer,
        );

        return $paymentTemplateWebhookProcessResponseTransfer;
    }
}
