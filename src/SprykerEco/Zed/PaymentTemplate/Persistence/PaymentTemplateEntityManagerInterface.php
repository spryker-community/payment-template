<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\PaymentTemplate\Persistence;

use Generated\Shared\Transfer\PaymentTemplateTransfer;

interface PaymentTemplateEntityManagerInterface
{
    public function savePayment(PaymentTemplateTransfer $paymentTemplateTransfer): PaymentTemplateTransfer;

    public function updatePaymentStatus(
        int $idPaymentTemplate,
        string $status,
        ?string $providerReference = null,
    ): void;

    public function savePaymentOrderItem(
        int $idPaymentTemplate,
        int $idSalesOrderItem,
        string $status,
    ): void;

    public function saveNotification(string $payload): void;
}
