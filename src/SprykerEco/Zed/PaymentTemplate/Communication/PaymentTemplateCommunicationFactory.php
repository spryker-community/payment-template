<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\PaymentTemplate\Communication;

use Spryker\Zed\Calculation\Business\CalculationFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use SprykerEco\Zed\PaymentTemplate\PaymentTemplateDependencyProvider;

/**
 * @method \SprykerEco\Zed\PaymentTemplate\PaymentTemplateConfig getConfig()
 * @method \SprykerEco\Zed\PaymentTemplate\Business\PaymentTemplateFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PaymentTemplate\Persistence\PaymentTemplateRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\PaymentTemplate\Persistence\PaymentTemplateEntityManagerInterface getEntityManager()
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
