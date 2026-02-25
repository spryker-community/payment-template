<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\PaymentTemplate\Plugin\StepEngine;

use Generated\Shared\Transfer\PaymentTemplateCreditCardTransfer;
use Generated\Shared\Transfer\PaymentTemplateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;
use Spryker\Shared\PaymentTemplate\PaymentTemplateConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\PaymentTemplate\PaymentTemplateFactory getFactory()
 */
class PaymentTemplateCreditCardStepHandlerPlugin extends AbstractPlugin implements StepHandlerPluginInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addToDataClass(Request $request, AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        $paymentTransfer = $quoteTransfer->getPayment();

        if ($paymentTransfer === null) {
            return $quoteTransfer;
        }

        $paymentTransfer->setPaymentProvider(PaymentTemplateConfig::PAYMENT_PROVIDER_NAME);
        $paymentTransfer->setPaymentMethod(PaymentTemplateConfig::PAYMENT_METHOD_CREDIT_CARD);
        $paymentTransfer->setPaymentSelection(PaymentTemplateConfig::PAYMENT_METHOD_CREDIT_CARD);
        $paymentTransfer->setPaymentTemplate(
            (new PaymentTemplateTransfer()),
            //->setAmount(1000)
        );
        $paymentTransfer->setPaymentTemplateCreditCard(
            (new PaymentTemplateCreditCardTransfer()),
            //->setPaymentMethodToken('token')
        );

        return $quoteTransfer;
    }
}
