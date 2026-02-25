<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentTemplate\Business\Notification;

use Generated\Shared\Transfer\PaymentTemplateWebhookPayloadTransfer;
use Generated\Shared\Transfer\PaymentTemplateWebhookProcessResponseTransfer;
use Spryker\Zed\PaymentTemplate\Persistence\PaymentTemplateEntityManagerInterface;

class NotificationProcessor implements NotificationProcessorInterface
{
    public function __construct(
        protected PaymentTemplateEntityManagerInterface $entityManager,
    ) {
    }

    public function processWebhook(
        PaymentTemplateWebhookPayloadTransfer $webhookPayloadTransfer,
    ): PaymentTemplateWebhookProcessResponseTransfer {
        // TODO: Implement webhook processing logic based on your payment service provider webhook specifications.
        // 1. Validate webhook signature or authentication if required by your payment service provider.
        // 2. Parse the webhook payload to identify the event type (authorization, capture, refund, status change, etc.).
        // 3. Find the related PaymentTemplateTransfer using PaymentReader::findPaymentByProviderReference().
        // 4. Update payment status using PaymentTemplateEntityManager::updatePaymentStatus() based on the webhook event.
        // 5. Optionally trigger Order Management System state machine transitions if payment status changed.
        // 6. Set appropriate response status (isSuccess, errorMessage) in PaymentTemplateWebhookProcessResponseTransfer.
        // The webhook payload is saved to the database for audit and debugging purposes.
        // e.g.
        // $providerReference = $webhookPayloadTransfer->getProviderReference();
        // $paymentTemplateTransfer = $this->paymentReader->findPaymentByProviderReference($providerReference);
        // if ($paymentTemplateTransfer !== null) {
        //     $this->entityManager->updatePaymentStatus(
        //         $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
        //         $this->mapWebhookEventToPaymentStatus($webhookPayloadTransfer->getEventType()),
        //     );
        // }

        $this->saveNotification($webhookPayloadTransfer);

        $webhookProcessResponseTransfer = new PaymentTemplateWebhookProcessResponseTransfer();

        return $webhookProcessResponseTransfer;
    }

    protected function saveNotification(
        PaymentTemplateWebhookPayloadTransfer $paymentTemplateWebhookPayloadTransfer,
    ): void {
        $this->entityManager->saveNotification(
            json_encode($paymentTemplateWebhookPayloadTransfer->toArray()),
        );
    }
}
