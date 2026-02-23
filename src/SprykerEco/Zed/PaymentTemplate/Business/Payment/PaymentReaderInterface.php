<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\PaymentTemplate\Business\Payment;

use Generated\Shared\Transfer\PaymentTemplateTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

interface PaymentReaderInterface
{
    public function findPaymentByIdSalesOrder(int $idSalesOrder): ?PaymentTemplateTransfer;

    public function findPaymentByOrderItem(SpySalesOrderItem $orderItemEntity): ?PaymentTemplateTransfer;

    public function findPaymentByProviderReference(string $providerReference): ?PaymentTemplateTransfer;
}
