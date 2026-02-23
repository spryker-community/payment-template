<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\PaymentTemplate\Persistence;

use Orm\Zed\PaymentTemplate\Persistence\SpyPaymentTemplateNotificationQuery;
use Orm\Zed\PaymentTemplate\Persistence\SpyPaymentTemplateOrderItemQuery;
use Orm\Zed\PaymentTemplate\Persistence\SpyPaymentTemplateQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \SprykerEco\Zed\PaymentTemplate\PaymentTemplateConfig getConfig()
 * @method \SprykerEco\Zed\PaymentTemplate\Persistence\PaymentTemplateRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\PaymentTemplate\Persistence\PaymentTemplateEntityManagerInterface getEntityManager()
 */
class PaymentTemplatePersistenceFactory extends AbstractPersistenceFactory
{
    public function createPaymentTemplateQuery(): SpyPaymentTemplateQuery
    {
        return SpyPaymentTemplateQuery::create();
    }

    public function createPaymentTemplateOrderItemQuery(): SpyPaymentTemplateOrderItemQuery
    {
        return SpyPaymentTemplateOrderItemQuery::create();
    }

    public function createPaymentTemplateNotificationQuery(): SpyPaymentTemplateNotificationQuery
    {
        return SpyPaymentTemplateNotificationQuery::create();
    }
}
