<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\PaymentTemplate\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Client\PaymentTemplate\PaymentTemplateClientInterface;
use SprykerEco\Zed\PaymentTemplate\Business\Notification\NotificationProcessor;
use SprykerEco\Zed\PaymentTemplate\Business\Notification\NotificationProcessorInterface;
use SprykerEco\Zed\PaymentTemplate\Business\Oms\Command\OmsCommandHandler;
use SprykerEco\Zed\PaymentTemplate\Business\Oms\Command\OmsCommandHandlerInterface;
use SprykerEco\Zed\PaymentTemplate\Business\Oms\Condition\OmsConditionChecker;
use SprykerEco\Zed\PaymentTemplate\Business\Oms\Condition\OmsConditionCheckerInterface;
use SprykerEco\Zed\PaymentTemplate\Business\Payment\PaymentAuthorizer;
use SprykerEco\Zed\PaymentTemplate\Business\Payment\PaymentAuthorizerInterface;
use SprykerEco\Zed\PaymentTemplate\Business\Payment\PaymentMethodFilter;
use SprykerEco\Zed\PaymentTemplate\Business\Payment\PaymentMethodFilterInterface;
use SprykerEco\Zed\PaymentTemplate\Business\Payment\PaymentReader;
use SprykerEco\Zed\PaymentTemplate\Business\Payment\PaymentReaderInterface;
use SprykerEco\Zed\PaymentTemplate\Business\Payment\PaymentSaver;
use SprykerEco\Zed\PaymentTemplate\Business\Payment\PaymentSaverInterface;
use SprykerEco\Zed\PaymentTemplate\PaymentTemplateDependencyProvider;

/**
 * @method \SprykerEco\Zed\PaymentTemplate\PaymentTemplateConfig getConfig()
 * @method \SprykerEco\Zed\PaymentTemplate\Persistence\PaymentTemplateRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\PaymentTemplate\Persistence\PaymentTemplateEntityManagerInterface getEntityManager()
 */
class PaymentTemplateBusinessFactory extends AbstractBusinessFactory
{
    public function createPaymentSaver(): PaymentSaverInterface
    {
        return new PaymentSaver(
            $this->getEntityManager(),
            $this->getConfig(),
        );
    }

    public function createPaymentAuthorizer(): PaymentAuthorizerInterface
    {
        return new PaymentAuthorizer(
            $this->getPaymentTemplateClient(),
            $this->createPaymentReader(),
            $this->getEntityManager(),
            $this->getConfig(),
        );
    }

    public function createPaymentMethodFilter(): PaymentMethodFilterInterface
    {
        return new PaymentMethodFilter(
            $this->getPaymentTemplateClient(),
            $this->getConfig(),
        );
    }

    public function createPaymentReader(): PaymentReaderInterface
    {
        return new PaymentReader(
            $this->getRepository(),
        );
    }

    public function createOmsCommandHandler(): OmsCommandHandlerInterface
    {
        return new OmsCommandHandler(
            $this->getPaymentTemplateClient(),
            $this->createPaymentReader(),
            $this->getEntityManager(),
        );
    }

    public function createOmsConditionChecker(): OmsConditionCheckerInterface
    {
        return new OmsConditionChecker(
            $this->createPaymentReader(),
            $this->getConfig(),
        );
    }

    public function createNotificationProcessor(): NotificationProcessorInterface
    {
        return new NotificationProcessor(
            $this->getEntityManager(),
        );
    }

    public function getPaymentTemplateClient(): PaymentTemplateClientInterface
    {
        return $this->getProvidedDependency(PaymentTemplateDependencyProvider::CLIENT_PAYMENT_TEMPLATE);
    }
}
