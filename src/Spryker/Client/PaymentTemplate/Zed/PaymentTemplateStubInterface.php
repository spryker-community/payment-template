<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\PaymentTemplate\Zed;

use Generated\Shared\Transfer\PaymentTemplateWebhookPayloadTransfer;
use Generated\Shared\Transfer\PaymentTemplateWebhookProcessResponseTransfer;

interface PaymentTemplateStubInterface
{
    public function processWebhook(
        PaymentTemplateWebhookPayloadTransfer $paymentTemplateWebhookPayloadTransfer,
    ): PaymentTemplateWebhookProcessResponseTransfer;
}
