<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentTemplate\Business\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\PaymentTemplate\Business\Payment\PaymentReaderInterface;
use Spryker\Zed\PaymentTemplate\PaymentTemplateConfig;

class OmsConditionChecker implements OmsConditionCheckerInterface
{
    public function __construct(
        protected PaymentReaderInterface $paymentReader,
        protected PaymentTemplateConfig $config,
    ) {
    }

    public function isPaymentAuthorized(SpySalesOrderItem $orderItemEntity): bool
    {
        $paymentTemplateTransfer = $this->paymentReader->findPaymentByOrderItem($orderItemEntity);

        if ($paymentTemplateTransfer === null) {
            return false;
        }

        // TODO: Replace placeholder return value with actual status check.
        // Check if the payment status matches the authorized state.
        // The status constant must match what you set in OmsCommandHandler::updatePaymentAfterAuthorize().
        // e.g.
        //return $paymentTemplateTransfer->getStatus() === $this->config::PAYMENT_STATUS_AUTHORIZED;
        return true; // Placeholder - replace with actual status check
    }

    public function isPaymentAuthorizationFailed(SpySalesOrderItem $orderItemEntity): bool
    {
        $paymentTemplateTransfer = $this->paymentReader->findPaymentByOrderItem($orderItemEntity);

        if ($paymentTemplateTransfer === null) {
            return false;
        }

        // TODO: Replace placeholder return value with actual status check.
        // Check if the payment status matches the authorization failed state.
        // The status constant must match what you set in OmsCommandHandler::handleAuthorizeError().
        // e.g.
        //return $paymentTemplateTransfer->getStatus() === $this->config::PAYMENT_STATUS_AUTHORIZATION_FAILED;
        return false; // Placeholder - replace with actual status check
    }

    public function isPaymentCaptured(SpySalesOrderItem $orderItemEntity): bool
    {
        $paymentTemplateTransfer = $this->paymentReader->findPaymentByOrderItem($orderItemEntity);

        if ($paymentTemplateTransfer === null) {
            return false;
        }

        // TODO: Replace placeholder return value with actual status check.
        // Check if the payment status matches the captured state.
        // The status constant must match what you set in OmsCommandHandler::updatePaymentAfterCapture().
        // e.g.
        //return $paymentTemplateTransfer->getStatus() === $this->config::PAYMENT_STATUS_CAPTURED;
        return true; // Placeholder - replace with actual status check
    }

    public function isPaymentRefunded(SpySalesOrderItem $orderItemEntity): bool
    {
        $paymentTemplateTransfer = $this->paymentReader->findPaymentByOrderItem($orderItemEntity);

        if ($paymentTemplateTransfer === null) {
            return false;
        }

        // TODO: Replace placeholder return value with actual status check.
        // Check if the payment status matches the refunded state.
        // The status constant must match what you set in OmsCommandHandler::updatePaymentAfterRefund().
        // e.g.
        //return $paymentTemplateTransfer->getStatus() === $this->config::PAYMENT_STATUS_REFUNDED;
        return true; // Placeholder - replace with actual status check
    }
}
