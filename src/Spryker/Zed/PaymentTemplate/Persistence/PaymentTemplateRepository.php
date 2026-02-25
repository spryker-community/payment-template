<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentTemplate\Persistence;

use Generated\Shared\Transfer\PaymentTemplateTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PaymentTemplate\Persistence\PaymentTemplatePersistenceFactory getFactory()
 */
class PaymentTemplateRepository extends AbstractRepository implements PaymentTemplateRepositoryInterface
{
    public function findPaymentTemplateByIdSalesOrder(int $idSalesOrder): ?PaymentTemplateTransfer
    {
        $paymentEntity = $this->getFactory()
            ->createPaymentTemplateQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->findOne();

        if ($paymentEntity === null) {
            return null;
        }

        return (new PaymentTemplateTransfer())->fromArray($paymentEntity->toArray(), true);
    }

    public function findPaymentTemplateByProviderReference(string $providerReference): ?PaymentTemplateTransfer
    {
        // TODO: Implement filtering by external reference number by adding filterBy to the query below.
        $paymentEntity = $this->getFactory()
            ->createPaymentTemplateQuery()
            // e.g.
            //->filterByProviderReference($providerReference)
            ->findOne();

        if ($paymentEntity === null) {
            return null;
        }

        return (new PaymentTemplateTransfer())->fromArray($paymentEntity->toArray(), true);
    }
}
