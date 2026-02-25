<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\PaymentTemplate;

use Spryker\Client\Quote\QuoteClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Client\PaymentTemplate\PaymentTemplateClientInterface;
use Spryker\Yves\PaymentTemplate\Form\DataProvider\PaymentTemplateCreditCardDataProvider;
use Spryker\Yves\PaymentTemplate\Form\DataProvider\PaymentTemplateInvoiceDataProvider;
use Spryker\Yves\PaymentTemplate\Form\PaymentTemplateCreditCardSubForm;
use Spryker\Yves\PaymentTemplate\Form\PaymentTemplateInvoiceSubForm;

/**
 * @method \Spryker\Yves\PaymentTemplate\PaymentTemplateConfig getConfig()
 */
class PaymentTemplateFactory extends AbstractFactory
{
    public function createPaymentTemplateCreditCardSubForm(): PaymentTemplateCreditCardSubForm
    {
        return new PaymentTemplateCreditCardSubForm();
    }

    public function createPaymentTemplateCreditCardDataProvider(): PaymentTemplateCreditCardDataProvider
    {
        return new PaymentTemplateCreditCardDataProvider();
    }

    public function createPaymentTemplateInvoiceSubForm(): PaymentTemplateInvoiceSubForm
    {
        return new PaymentTemplateInvoiceSubForm();
    }

    public function createPaymentTemplateInvoiceDataProvider(): PaymentTemplateInvoiceDataProvider
    {
        return new PaymentTemplateInvoiceDataProvider();
    }

    public function getPaymentTemplateClient(): PaymentTemplateClientInterface
    {
        return $this->getProvidedDependency(PaymentTemplateDependencyProvider::CLIENT_PAYMENT_TEMPLATE);
    }

    public function getQuoteClient(): QuoteClientInterface
    {
        return $this->getProvidedDependency(PaymentTemplateDependencyProvider::CLIENT_QUOTE);
    }
}
