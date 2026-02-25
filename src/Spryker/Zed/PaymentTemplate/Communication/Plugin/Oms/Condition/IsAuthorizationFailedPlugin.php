<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentTemplate\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \Spryker\Zed\PaymentTemplate\Business\PaymentTemplateFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentTemplate\Communication\PaymentTemplateCommunicationFactory getFactory()
 * @method \Spryker\Zed\PaymentTemplate\PaymentTemplateConfig getConfig()
 */
class IsAuthorizationFailedPlugin extends AbstractPlugin implements ConditionInterface
{
    /**
     * {@inheritDoc}
     * - Called by OMS to determine if transition to "authorization failed" state is actual.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItemEntity): bool
    {
        return $this->getFacade()->isPaymentAuthorizationFailed($orderItemEntity);
    }
}
