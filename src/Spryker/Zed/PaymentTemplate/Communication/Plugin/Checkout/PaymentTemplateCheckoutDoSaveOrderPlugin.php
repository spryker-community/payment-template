<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentTemplate\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PaymentTemplate\Business\PaymentTemplateFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentTemplate\Communication\PaymentTemplateCommunicationFactory getFactory()
 * @method \Spryker\Zed\PaymentTemplate\PaymentTemplateConfig getConfig()
 */
class PaymentTemplateCheckoutDoSaveOrderPlugin extends AbstractPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * {@inheritDoc}
     * - Saves payment data to database after order is created.
     * - Creates payment record in spy_payment_template table.
     * - Creates payment-item mapping records.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->getFacade()->saveOrderPayment($quoteTransfer, $saveOrderTransfer);
    }
}
