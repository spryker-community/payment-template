<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\PaymentTemplate\Business\Notification;

use Generated\Shared\Transfer\PaymentTemplateWebhookPayloadTransfer;
use Generated\Shared\Transfer\PaymentTemplateWebhookProcessResponseTransfer;

interface NotificationProcessorInterface
{
    public function processWebhook(
        PaymentTemplateWebhookPayloadTransfer $webhookPayloadTransfer
    ): PaymentTemplateWebhookProcessResponseTransfer;
}
