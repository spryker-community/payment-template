<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentTemplate\Business\Payment;

use ArrayObject;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentTemplatePaymentMethodsRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\PaymentTemplate\PaymentTemplateClientInterface;
use Spryker\Shared\PaymentTemplate\PaymentTemplateConfig as SharedPaymentTemplateConfig;
use Spryker\Zed\PaymentTemplate\PaymentTemplateConfig;

class PaymentMethodFilter implements PaymentMethodFilterInterface
{
    public function __construct(
        protected PaymentTemplateClientInterface $paymentTemplateClient,
        protected PaymentTemplateConfig $paymentTemplateConfig,
    ) {
    }

    public function filterPaymentMethods(
        PaymentMethodsTransfer $paymentMethodsTransfer,
        QuoteTransfer $quoteTransfer,
    ): PaymentMethodsTransfer {
        $filteredMethods = [];

        $paymentTemplateAvailablePaymentMethods = $this->getPaymentTemplateAvailablePaymentMethods($quoteTransfer);

        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethodTransfer) {
            if ($paymentMethodTransfer->getPaymentProvider()?->getPaymentProviderKey() !== SharedPaymentTemplateConfig::PAYMENT_PROVIDER_NAME) {
                $filteredMethods[] = $paymentMethodTransfer;

                continue;
            }

            if ($this->isPaymentMethodAllowed($paymentMethodTransfer->getMethodName(), $paymentTemplateAvailablePaymentMethods)) {
                $filteredMethods[] = $paymentMethodTransfer;
            }
        }

        return $paymentMethodsTransfer->setMethods(new ArrayObject($filteredMethods));
    }

    /**
     * @param string $paymentMethodName
     * @param array<string> $paymentTemplateAvailablePaymentMethods
     *
     * @return bool
     */
    protected function isPaymentMethodAllowed(string $paymentMethodName, array $paymentTemplateAvailablePaymentMethods): bool
    {
        // TODO: Replace placeholder return value with actual check logic.
        // Check if the current payment method is among the allowed methods returned by your payment service provider.
        // The available methods list comes from getPaymentTemplateAvailablePaymentMethods().
        // e.g.
        // return in_array($paymentMethodName, $paymentTemplateAvailablePaymentMethods, true);
        return true; // Placeholder - replace with actual check
    }

    protected function buildPaymentMethodsRequest(QuoteTransfer $quoteTransfer): PaymentTemplatePaymentMethodsRequestTransfer
    {
        // TODO: Compose the request transfer from data in the QuoteTransfer.
        // Include data required by your payment service provider to determine available payment methods
        // (e.g., total amount, currency, customer location, billing address).
        // If additional data is needed beyond QuoteTransfer, add parameters to this method signature.
        // e.g.
        // return (new PaymentTemplatePaymentMethodsRequestTransfer())
        //     ->setAmount($quoteTransfer->getTotals()->getGrandTotal())
        //     ->setCurrency($quoteTransfer->getCurrency()->getCode())
        //     ->setCountryCode($quoteTransfer->getBillingAddress()->getIso2Code());
        return (new PaymentTemplatePaymentMethodsRequestTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string>
     */
    public function getPaymentTemplateAvailablePaymentMethods(QuoteTransfer $quoteTransfer): array
    {
        $paymentTemplatePaymentMethodsRequestTransfer = $this->buildPaymentMethodsRequest($quoteTransfer);
        $paymentTemplatePaymentMethodsResponseTransfer = $this->paymentTemplateClient->getPaymentMethods($paymentTemplatePaymentMethodsRequestTransfer);

        if (!$paymentTemplatePaymentMethodsResponseTransfer->getIsSuccess()) {
            // If an error occurred, no payment methods are available, return empty array.
            return [];
        }

        // TODO: Replace placeholder return value with actual payment methods extraction.
        // Extract the list of available payment method names from the payment service provider response.
        // The returned array should contain payment method identifiers that match your configured method names.
        // e.g.
        // return $paymentTemplatePaymentMethodsResponseTransfer->getPaymentMethods();
        return []; // Placeholder - replace with actual payment methods list
    }
}
