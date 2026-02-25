<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\PaymentTemplate\Plugin\StepEngine;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;
use Spryker\Yves\PaymentTemplate\Form\DataProvider\PaymentTemplateCreditCardDataProvider;

/**
 * @method \Spryker\Yves\PaymentTemplate\PaymentTemplateFactory getFactory()
 */
class PaymentTemplateCreditCardSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    public function createSubForm(): SubFormInterface
    {
        return $this->getFactory()->createPaymentTemplateCreditCardSubForm();
    }

    public function createSubFormDataProvider(): PaymentTemplateCreditCardDataProvider
    {
        return $this->getFactory()->createPaymentTemplateCreditCardDataProvider();
    }
}
