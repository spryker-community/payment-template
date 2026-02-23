<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\PaymentTemplate\Communication\Controller;

use Generated\Shared\Transfer\PaymentTemplateWebhookPayloadTransfer;
use Generated\Shared\Transfer\PaymentTemplateWebhookProcessResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerEco\Zed\PaymentTemplate\Business\PaymentTemplateFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PaymentTemplate\Communication\PaymentTemplateCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\PaymentTemplate\Persistence\PaymentTemplateRepositoryInterface getRepository()
 */
class GatewayController extends AbstractGatewayController
{
    public function processWebhookAction(
        PaymentTemplateWebhookPayloadTransfer $webhookPayloadTransfer
    ): PaymentTemplateWebhookProcessResponseTransfer {
        return $this->getFacade()->processWebhook($webhookPayloadTransfer);
    }
}
