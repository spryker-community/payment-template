<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\PaymentTemplate\Business\Payment;

use Generated\Shared\Transfer\PaymentTemplateTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerEco\Zed\PaymentTemplate\Persistence\PaymentTemplateRepositoryInterface;

class PaymentReader implements PaymentReaderInterface
{
    public function __construct(
        protected PaymentTemplateRepositoryInterface $repository,
    ) {
    }

    public function findPaymentByIdSalesOrder(int $idSalesOrder): ?PaymentTemplateTransfer
    {
        return $this->repository->findPaymentTemplateByIdSalesOrder($idSalesOrder);
    }

    public function findPaymentByOrderItem(SpySalesOrderItem $orderItemEntity): ?PaymentTemplateTransfer
    {
        return $this->repository->findPaymentTemplateByIdSalesOrder($orderItemEntity->getFkSalesOrder());
    }

    public function findPaymentByProviderReference(string $providerReference): ?PaymentTemplateTransfer
    {
        return $this->repository->findPaymentTemplateByProviderReference($providerReference);
    }
}
