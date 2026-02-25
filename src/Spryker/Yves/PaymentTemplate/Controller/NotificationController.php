<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\PaymentTemplate\Controller;

use Exception;
use Generated\Shared\Transfer\PaymentTemplateWebhookPayloadTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Yves\PaymentTemplate\PaymentTemplateFactory getFactory()
 */
class NotificationController extends AbstractController
{
    public function notificationAction(Request $request): Response
    {
        $webhookPayloadTransfer = $this->createWebhookPayload($request);

        try {
            $paymentTemplateWebhookProcessResponseTransfer = $this->getFactory()
                ->getPaymentTemplateClient()
                ->processWebhook($webhookPayloadTransfer);

            if (!$paymentTemplateWebhookProcessResponseTransfer->getIsSuccess()) {
                return new Response(
                    'Webhook processing failed',
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                );
            }

            // TODO: Change the default response to respond with the status and content the PSP expects.
            return new Response('OK', Response::HTTP_OK);
        } catch (Exception $exception) {
            return new Response(
                'Webhook processing failed: ' . $exception->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }

    protected function createWebhookPayload(Request $request): PaymentTemplateWebhookPayloadTransfer
    {
        // TODO: Extract webhook data from the request and populate the transfer.
        // Parse the request body, headers, and any PSP-specific data.
        // e.g.
        // $webhookPayloadTransfer = new PaymentTemplateWebhookPayloadTransfer();
        // $webhookPayloadTransfer->setRawPayload($request->getContent());
        // $webhookPayloadTransfer->setHeaders($request->headers->all());
        // $webhookPayloadTransfer->setProviderReference($this->extractProviderReference($request));
        // $webhookPayloadTransfer->setEventType($this->extractEventType($request));
        $webhookPayloadTransfer = new PaymentTemplateWebhookPayloadTransfer();

        return $webhookPayloadTransfer;
    }
}
