<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentTemplate\Communication;

use Spryker\Zed\Calculation\Business\CalculationFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\PaymentTemplate\PaymentTemplateDependencyProvider;

/**
 * @method \Spryker\Zed\PaymentTemplate\PaymentTemplateConfig getConfig()
 * @method \Spryker\Zed\PaymentTemplate\Business\PaymentTemplateFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentTemplate\Persistence\PaymentTemplateRepositoryInterface getRepository()
 * @method \Spryker\Zed\PaymentTemplate\Persistence\PaymentTemplateEntityManagerInterface getEntityManager()
 */
class PaymentTemplateCommunicationFactory extends AbstractCommunicationFactory
{
    public function getCalculationFacade(): CalculationFacadeInterface
    {
        return $this->getProvidedDependency(PaymentTemplateDependencyProvider::FACADE_CALCULATION);
    }

    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(PaymentTemplateDependencyProvider::FACADE_SALES);
    }
}
