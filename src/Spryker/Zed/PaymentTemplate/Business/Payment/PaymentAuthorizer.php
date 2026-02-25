<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentTemplate\Business\Payment;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplateApiErrorResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplateAuthorizeRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateAuthorizeResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\PaymentTemplate\PaymentTemplateClientInterface;
use Spryker\Shared\PaymentTemplate\PaymentTemplateConfig as SharedPaymentTemplateConfig;
use Spryker\Zed\PaymentTemplate\PaymentTemplateConfig;
use Spryker\Zed\PaymentTemplate\Persistence\PaymentTemplateEntityManagerInterface;

class PaymentAuthorizer implements PaymentAuthorizerInterface
{
    public function __construct(
        protected PaymentTemplateClientInterface $client,
        protected PaymentReaderInterface $paymentReader,
        protected PaymentTemplateEntityManagerInterface $entityManager,
        protected PaymentTemplateConfig $config,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function executePostSaveHook(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer,
    ): void {
        if ($quoteTransfer->getPayment()->getPaymentProvider() !== SharedPaymentTemplateConfig::PAYMENT_PROVIDER_NAME) {
            return;
        }

        $paymentTemplateTransfer = $this->paymentReader->findPaymentByIdSalesOrder(
            $checkoutResponseTransfer->getSaveOrderOrFail()->getIdSalesOrderOrFail(),
        );

        if ($paymentTemplateTransfer === null) {
            return;
        }

        $paymentTemplateAuthorizeRequestTransfer = $this->buildAuthorizeRequest($paymentTemplateTransfer);

        $paymentTemplateAuthorizeResponseTransfer = $this->client->authorize($paymentTemplateAuthorizeRequestTransfer);

        if (!$paymentTemplateAuthorizeResponseTransfer->getIsSuccess()) {
            $this->handleAuthorizationError($paymentTemplateTransfer, $paymentTemplateAuthorizeResponseTransfer->getErrorResponse());

            return;
        }

        $this->updatePaymentAfterAuthorization($paymentTemplateTransfer, $paymentTemplateAuthorizeResponseTransfer);
    }

    protected function buildAuthorizeRequest(PaymentTemplateTransfer $paymentTemplateTransfer): PaymentTemplateAuthorizeRequestTransfer
    {
        // TODO: Compose the request transfer from data in the PaymentTemplateTransfer.
        // If your payment service provider requires additional data not available in PaymentTemplateTransfer,
        // add additional parameters to this method signature (e.g., QuoteTransfer, CheckoutResponseTransfer)
        // and pass them from executePostSaveHook.
        // e.g.
        // return (new PaymentTemplateAuthorizeRequestTransfer())
        //     ->setAmount($paymentTemplateTransfer->getAmount())
        //     ->setCurrency($paymentTemplateTransfer->getCurrency())
        //     ->setPaymentMethodToken($paymentTemplateTransfer->getPaymentMethodToken())
        //     ->setOrderReference($paymentTemplateTransfer->getOrderReference());
        return (new PaymentTemplateAuthorizeRequestTransfer());
    }

    protected function handleAuthorizationError(
        PaymentTemplateTransfer $paymentTemplateTransfer,
        ?PaymentTemplateApiErrorResponseTransfer $paymentTemplateApiErrorResponseTransfer
    ): void {
        // TODO: Define status constants in PaymentTemplateConfig based on payment service provider specific statuses.
        // Extract error details from $paymentTemplateApiErrorResponseTransfer if needed and pass appropriate status to updatePaymentStatus.
        // You may need to handle different error types with different status constants.
        // e.g.
        // $this->entityManager->updatePaymentStatus(
        //     $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
        //     $this->config::PAYMENT_STATUS_AUTHORIZATION_FAILED,
        //     $paymentTemplateApiErrorResponseTransfer?->getProviderReference(),
        // );
        $this->entityManager->updatePaymentStatus(
            $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
            $this->config::PAYMENT_STATUS_AUTHORIZATION_FAILED,
        );
    }

    protected function updatePaymentAfterAuthorization(
        PaymentTemplateTransfer $paymentTemplateTransfer,
        PaymentTemplateAuthorizeResponseTransfer $paymentTemplateAuthorizeResponseTransfer
    ): void {
        // TODO: Define status constants in PaymentTemplateConfig based on payment service provider specific statuses.
        // Extract the appropriate status from $paymentTemplateAuthorizeResponseTransfer and pass it to updatePaymentStatus.
        // e.g.
        // $this->entityManager->updatePaymentStatus(
        //     $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
        //     $this->config::PAYMENT_STATUS_AUTHORIZED,
        //     $paymentTemplateAuthorizeResponseTransfer->getProviderReference(),
        // );
        $this->entityManager->updatePaymentStatus(
            $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
            $this->config::PAYMENT_STATUS_AUTHORIZED,
        );
    }
}
