<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\PaymentTemplate\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \SprykerEco\Zed\PaymentTemplate\Business\PaymentTemplateFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PaymentTemplate\Communication\PaymentTemplateCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\PaymentTemplate\PaymentTemplateConfig getConfig()
 */
class IsCapturedPlugin extends AbstractPlugin implements ConditionInterface
{
    /**
     * {@inheritDoc}
     * - Checks if payment capture is confirmed.
     * - Reads status from spy_payment_template table.
     * - Called by OMS to determine if transition to "captured" state is actual.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItemEntity): bool
    {
        return $this->getFacade()->isPaymentCaptured($orderItemEntity);
    }
}
