<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\PaymentTemplate\Business\Oms\Command;

use Exception;
use Generated\Shared\Transfer\PaymentTemplateApiErrorResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplateAuthorizeRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateAuthorizeResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplateCancelRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateCancelResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplateCaptureRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateCaptureResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplateTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Client\PaymentTemplate\PaymentTemplateClientInterface;
use SprykerEco\Zed\PaymentTemplate\Business\Payment\PaymentReaderInterface;
use SprykerEco\Zed\PaymentTemplate\PaymentTemplateConfig;
use SprykerEco\Zed\PaymentTemplate\Persistence\PaymentTemplateEntityManagerInterface;

class OmsCommandHandler implements OmsCommandHandlerInterface
{
    public function __construct(
        protected PaymentTemplateClientInterface $client,
        protected PaymentReaderInterface $paymentReader,
        protected PaymentTemplateEntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     *
     * @return void
     */
    public function executeAuthorizeCommand(SpySalesOrder $orderEntity, array $orderItems): void
    {
        $paymentTemplateTransfer = $this->paymentReader->findPaymentByIdSalesOrder(
            $orderEntity->getIdSalesOrder(),
        );

        if ($paymentTemplateTransfer === null) {
            return;
        }

        $paymentTemplateAuthorizeRequestTransfer = $this->buildAuthorizeRequest($paymentTemplateTransfer);

        try {
            $paymentTemplateAuthorizeResponseTransfer = $this->client->authorize($paymentTemplateAuthorizeRequestTransfer);

            if (!$paymentTemplateAuthorizeResponseTransfer->getIsSuccess()) {
                $this->handleAuthorizeError($paymentTemplateTransfer, $paymentTemplateAuthorizeRequestTransfer, $paymentTemplateAuthorizeResponseTransfer->getErrorResponse());

                return;
            }

            $this->updatePaymentAfterAuthorize($paymentTemplateTransfer, $paymentTemplateAuthorizeRequestTransfer, $paymentTemplateAuthorizeResponseTransfer);
        } catch (Exception $exception) {
            $this->handleAuthorizeError($paymentTemplateTransfer, $paymentTemplateAuthorizeRequestTransfer, null);
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     *
     * @return void
     */
    public function executeCaptureCommand(SpySalesOrder $orderEntity, array $orderItems): void
    {
        $paymentTemplateTransfer = $this->paymentReader->findPaymentByIdSalesOrder(
            $orderEntity->getIdSalesOrder(),
        );

        if ($paymentTemplateTransfer === null) {
            return;
        }

        $paymentTemplateCaptureRequestTransfer = $this->buildCaptureRequest($paymentTemplateTransfer);

        try {
            $paymentTemplateCaptureResponseTransfer = $this->client->capture($paymentTemplateCaptureRequestTransfer);

            if (!$paymentTemplateCaptureResponseTransfer->getIsSuccess()) {
                $this->handleCaptureError($paymentTemplateTransfer, $paymentTemplateCaptureRequestTransfer, $paymentTemplateCaptureResponseTransfer->getErrorResponse());

                return;
            }

            $this->updatePaymentAfterCapture($paymentTemplateTransfer, $paymentTemplateCaptureRequestTransfer, $paymentTemplateCaptureResponseTransfer);
        } catch (Exception $exception) {
            $this->handleCaptureError($paymentTemplateTransfer, $paymentTemplateCaptureRequestTransfer, null);
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     *
     * @return void
     */
    public function executeCancelCommand(SpySalesOrder $orderEntity, array $orderItems): void
    {
        $paymentTemplateTransfer = $this->paymentReader->findPaymentByIdSalesOrder(
            $orderEntity->getIdSalesOrder(),
        );

        if ($paymentTemplateTransfer === null) {
            return;
        }

        $paymentTemplateCancelRequestTransfer = $this->buildCancelRequest($paymentTemplateTransfer);

        try {
            $paymentTemplateCancelResponseTransfer = $this->client->cancel($paymentTemplateCancelRequestTransfer);

            if (!$paymentTemplateCancelResponseTransfer->getIsSuccess()) {
                $this->handleCancelError($paymentTemplateTransfer, $paymentTemplateCancelRequestTransfer, $paymentTemplateCancelResponseTransfer->getErrorResponse());

                return;
            }

            $this->updatePaymentAfterCancel($paymentTemplateTransfer, $paymentTemplateCancelRequestTransfer, $paymentTemplateCancelResponseTransfer);
        } catch (Exception $exception) {
            $this->handleCancelError($paymentTemplateTransfer, $paymentTemplateCancelRequestTransfer, null);
        }
    }

    protected function buildAuthorizeRequest(PaymentTemplateTransfer $paymentTemplateTransfer): PaymentTemplateAuthorizeRequestTransfer
    {
        // TODO: Compose the request transfer from data in the PaymentTemplateTransfer.
        // If your payment service provider requires additional data not available in PaymentTemplateTransfer,
        // add additional parameters to this method signature and pass them from executeAuthorizeCommand.
        // e.g.
        // return (new PaymentTemplateAuthorizeRequestTransfer())
        //     ->setAmount($paymentTemplateTransfer->getAmount())
        //     ->setCurrency($paymentTemplateTransfer->getCurrency())
        //     ->setPaymentMethodToken($paymentTemplateTransfer->getPaymentMethodToken())
        //     ->setOrderReference($paymentTemplateTransfer->getOrderReference());
        return (new PaymentTemplateAuthorizeRequestTransfer());
    }

    protected function handleAuthorizeError(
        PaymentTemplateTransfer $paymentTemplateTransfer,
        PaymentTemplateAuthorizeRequestTransfer $paymentTemplateAuthorizeRequestTransfer,
        ?PaymentTemplateApiErrorResponseTransfer $paymentTemplateApiErrorResponseTransfer,
    ): void {
        // TODO: Define status constants in PaymentTemplateConfig based on payment service provider specific statuses.
        // Extract error details from $paymentTemplateApiErrorResponseTransfer if needed and pass appropriate status to updatePaymentStatus.
        // You may need to handle different error types with different status constants.
        // e.g.
        // $this->entityManager->updatePaymentStatus(
        //     $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
        //     PaymentTemplateConfig::PAYMENT_STATUS_AUTHORIZATION_FAILED,
        //     $paymentTemplateApiErrorResponseTransfer?->getProviderReference(),
        // );
        $this->entityManager->updatePaymentStatus(
            $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
            PaymentTemplateConfig::PAYMENT_STATUS_AUTHORIZATION_FAILED,
        );
    }

    protected function updatePaymentAfterAuthorize(
        PaymentTemplateTransfer $paymentTemplateTransfer,
        PaymentTemplateAuthorizeRequestTransfer $paymentTemplateAuthorizeRequestTransfer,
        PaymentTemplateAuthorizeResponseTransfer $paymentTemplateAuthorizeResponseTransfer,
    ): void {
        // TODO: Define status constants in PaymentTemplateConfig based on payment service provider specific statuses.
        // Extract the appropriate status from $paymentTemplateAuthorizeResponseTransfer and pass it to updatePaymentStatus.
        // e.g.
        // $this->entityManager->updatePaymentStatus(
        //     $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
        //     PaymentTemplateConfig::PAYMENT_STATUS_AUTHORIZED,
        //     $paymentTemplateAuthorizeResponseTransfer->getProviderReference(),
        // );
        $this->entityManager->updatePaymentStatus(
            $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
            '',
        );
    }

    protected function buildCaptureRequest(PaymentTemplateTransfer $paymentTemplateTransfer): PaymentTemplateCaptureRequestTransfer
    {
        // TODO: Compose the request transfer from data in the PaymentTemplateTransfer.
        // If your payment service provider requires additional data not available in PaymentTemplateTransfer,
        // add additional parameters to this method signature and pass them from executeCaptureCommand.
        // e.g.
        // return (new PaymentTemplateCaptureRequestTransfer())
        //     ->setProviderReference($paymentTemplateTransfer->getProviderReference())
        //     ->setAmount($paymentTemplateTransfer->getAmount())
        //     ->setCurrency($paymentTemplateTransfer->getCurrency());
        return (new PaymentTemplateCaptureRequestTransfer());
    }

    protected function handleCaptureError(
        PaymentTemplateTransfer $paymentTemplateTransfer,
        PaymentTemplateCaptureRequestTransfer $paymentTemplateCaptureRequestTransfer,
        ?PaymentTemplateApiErrorResponseTransfer $paymentTemplateApiErrorResponseTransfer
    ): void {
        // TODO: Define status constants in PaymentTemplateConfig based on payment service provider specific statuses.
        // Extract error details from $paymentTemplateApiErrorResponseTransfer if needed and pass appropriate status to updatePaymentStatus.
        // You may need to handle different error types with different status constants.
        // e.g.
        // $this->entityManager->updatePaymentStatus(
        //     $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
        //     PaymentTemplateConfig::PAYMENT_STATUS_CAPTURE_FAILED,
        //     $paymentTemplateApiErrorResponseTransfer?->getProviderReference(),
        // );
        $this->entityManager->updatePaymentStatus(
            $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
            PaymentTemplateConfig::PAYMENT_STATUS_CAPTURE_FAILED,
        );
    }

    protected function updatePaymentAfterCapture(
        PaymentTemplateTransfer $paymentTemplateTransfer,
        PaymentTemplateCaptureRequestTransfer $paymentTemplateCaptureRequestTransfer,
        PaymentTemplateCaptureResponseTransfer $paymentTemplateCaptureResponseTransfer,
    ): void {
        // TODO: Define status constants in PaymentTemplateConfig based on payment service provider specific statuses.
        // Extract the appropriate status from $paymentTemplateCaptureResponseTransfer and pass it to updatePaymentStatus.
        // e.g.
        // $this->entityManager->updatePaymentStatus(
        //     $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
        //     PaymentTemplateConfig::PAYMENT_STATUS_CAPTURED,
        //     $paymentTemplateCaptureResponseTransfer->getProviderReference(),
        // );
        $this->entityManager->updatePaymentStatus(
            $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
            '',
        );
    }

    protected function buildCancelRequest(PaymentTemplateTransfer $paymentTemplateTransfer): PaymentTemplateCancelRequestTransfer
    {
        // TODO: Compose the request transfer from data in the PaymentTemplateTransfer.
        // If your payment service provider requires additional data not available in PaymentTemplateTransfer,
        // add additional parameters to this method signature and pass them from executeCancelCommand.
        // e.g.
        // return (new PaymentTemplateCancelRequestTransfer())
        //     ->setProviderReference($paymentTemplateTransfer->getProviderReference())
        //     ->setCancellationReason('Customer requested cancellation');
        return (new PaymentTemplateCancelRequestTransfer())->fromArray($paymentTemplateTransfer->toArray(), true);
    }

    protected function handleCancelError(
        PaymentTemplateTransfer $paymentTemplateTransfer,
        PaymentTemplateCancelRequestTransfer $paymentTemplateCancelRequestTransfer,
        ?PaymentTemplateApiErrorResponseTransfer $paymentTemplateApiErrorResponseTransfer
    ): void {
        // TODO: Define status constants in PaymentTemplateConfig based on payment service provider specific statuses.
        // Extract error details from $paymentTemplateApiErrorResponseTransfer if needed and pass appropriate status to updatePaymentStatus.
        // You may need to handle different error types with different status constants.
        // e.g.
        // $this->entityManager->updatePaymentStatus(
        //     $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
        //     PaymentTemplateConfig::PAYMENT_STATUS_CANCEL_FAILED,
        //     $paymentTemplateApiErrorResponseTransfer?->getProviderReference(),
        // );
        $this->entityManager->updatePaymentStatus(
            $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
            PaymentTemplateConfig::PAYMENT_STATUS_CANCEL_FAILED,
        );
    }

    protected function updatePaymentAfterCancel(
        PaymentTemplateTransfer $paymentTemplateTransfer,
        PaymentTemplateCancelRequestTransfer $paymentTemplateCancelRequestTransfer,
        PaymentTemplateCancelResponseTransfer $paymentTemplateCancelResponseTransfer
    ): void {
        // TODO: Define status constants in PaymentTemplateConfig based on payment service provider specific statuses.
        // Extract the appropriate status from $paymentTemplateCancelResponseTransfer and pass it to updatePaymentStatus.
        // e.g.
        // $this->entityManager->updatePaymentStatus(
        //     $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
        //     PaymentTemplateConfig::PAYMENT_STATUS_CANCELLED,
        //     $paymentTemplateCancelResponseTransfer->getProviderReference(),
        // );
        $this->entityManager->updatePaymentStatus(
            $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
            '',
        );
    }
}
