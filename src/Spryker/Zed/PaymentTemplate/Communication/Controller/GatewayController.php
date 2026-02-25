<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentTemplate\Communication\Controller;

use Generated\Shared\Transfer\PaymentTemplateWebhookPayloadTransfer;
use Generated\Shared\Transfer\PaymentTemplateWebhookProcessResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\PaymentTemplate\Business\PaymentTemplateFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentTemplate\Communication\PaymentTemplateCommunicationFactory getFactory()
 * @method \Spryker\Zed\PaymentTemplate\Persistence\PaymentTemplateRepositoryInterface getRepository()
 */
class GatewayController extends AbstractGatewayController
{
    public function processWebhookAction(
        PaymentTemplateWebhookPayloadTransfer $webhookPayloadTransfer
    ): PaymentTemplateWebhookProcessResponseTransfer {
        return $this->getFacade()->processWebhook($webhookPayloadTransfer);
    }
}
