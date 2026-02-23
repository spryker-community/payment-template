<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\PaymentTemplate\Business\Payment;

use Generated\Shared\Transfer\PaymentTemplateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use SprykerEco\Shared\PaymentTemplate\PaymentTemplateConfig as SharedPaymentTemplateConfig;
use SprykerEco\Zed\PaymentTemplate\PaymentTemplateConfig;
use SprykerEco\Zed\PaymentTemplate\Persistence\PaymentTemplateEntityManagerInterface;

class PaymentSaver implements PaymentSaverInterface
{
    public function __construct(
        protected PaymentTemplateEntityManagerInterface $entityManager,
        protected PaymentTemplateConfig $config,
    ) {
    }

    public function saveOrderPayment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $paymentTransfer = $quoteTransfer->getPayment();
        $paymentTemplateTransfer = $paymentTransfer->getPaymentTemplate();

        if ($paymentTransfer->getPaymentProvider() !== SharedPaymentTemplateConfig::PAYMENT_PROVIDER_NAME) {
            return;
        }

        $paymentTemplateTransfer = $this->createPaymentTemplateTransfer(
            $paymentTemplateTransfer,
            $saveOrderTransfer,
        );

        $paymentTemplateTransfer = $this->entityManager->savePayment($paymentTemplateTransfer);

        $this->saveOrderItems($paymentTemplateTransfer, $saveOrderTransfer);
    }

    protected function createPaymentTemplateTransfer(
        PaymentTemplateTransfer $paymentTemplateTransfer,
        SaveOrderTransfer $saveOrderTransfer,
    ): PaymentTemplateTransfer {
        $paymentTemplateTransfer
            ->setFkSalesOrder($saveOrderTransfer->getIdSalesOrderOrFail());

        return $paymentTemplateTransfer;
    }

    protected function saveOrderItems(
        PaymentTemplateTransfer $paymentTemplateTransfer,
        SaveOrderTransfer $saveOrderTransfer,
    ): void {
        foreach ($saveOrderTransfer->getOrderItems() as $itemTransfer) {
            $this->entityManager->savePaymentOrderItem(
                $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
                $itemTransfer->getIdSalesOrderItemOrFail(),
                $this->config::PAYMENT_STATUS_NEW,
            );
        }
    }
}
