<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentTemplate\Persistence;

use Generated\Shared\Transfer\PaymentTemplateTransfer;
use Orm\Zed\PaymentTemplate\Persistence\SpyPaymentTemplateNotification;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\PaymentTemplate\Persistence\PaymentTemplatePersistenceFactory getFactory()
 */
class PaymentTemplateEntityManager extends AbstractEntityManager implements PaymentTemplateEntityManagerInterface
{
    public function savePayment(PaymentTemplateTransfer $paymentTemplateTransfer): PaymentTemplateTransfer
    {
        $paymentEntity = $this->getFactory()
            ->createPaymentTemplateQuery()
            ->filterByIdPaymentTemplate($paymentTemplateTransfer->getIdPaymentTemplate())
            ->findOneOrCreate();

        $paymentEntity->fromArray($paymentTemplateTransfer->modifiedToArray());
        $paymentEntity->save();

        $paymentTemplateTransfer->setIdPaymentTemplate($paymentEntity->getIdPaymentTemplate());

        return $paymentTemplateTransfer;
    }

    public function updatePaymentStatus(
        int $idPaymentTemplate,
        string $status,
        ?string $providerReference = null,
    ): void {
        $paymentEntity = $this->getFactory()
            ->createPaymentTemplateQuery()
            ->filterByIdPaymentTemplate($idPaymentTemplate)
            ->findOne();

        if ($paymentEntity === null) {
            return;
        }

        // TODO: Implement status update based on the PSP-specific implementation and DB schema.
        // e.g.
        // $paymentEntity->setStatus($status);
        // if ($providerReference !== null) {
        //    $paymentEntity->setProviderReference($providerReference);
        //}

        $paymentEntity->save();
    }

    public function savePaymentOrderItem(
        int $idPaymentTemplate,
        int $idSalesOrderItem,
        string $status
    ): void {
        $orderItemEntity = $this->getFactory()
            ->createPaymentTemplateOrderItemQuery()
            ->filterByFkPaymentTemplate($idPaymentTemplate)
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->findOneOrCreate();

        $orderItemEntity
            ->setFkPaymentTemplate($idPaymentTemplate)
            ->setFkSalesOrderItem($idSalesOrderItem)
            ->save();
    }

    public function saveNotification(string $payload): void
    {
        $notificationEntity = new SpyPaymentTemplateNotification();

        $notificationEntity
            ->setPayload($payload)
            ->save();
    }
}
