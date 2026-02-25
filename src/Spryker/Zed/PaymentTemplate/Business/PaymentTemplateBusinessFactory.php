<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentTemplate\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Client\PaymentTemplate\PaymentTemplateClientInterface;
use Spryker\Zed\PaymentTemplate\Business\Notification\NotificationProcessor;
use Spryker\Zed\PaymentTemplate\Business\Notification\NotificationProcessorInterface;
use Spryker\Zed\PaymentTemplate\Business\Oms\Command\OmsCommandHandler;
use Spryker\Zed\PaymentTemplate\Business\Oms\Command\OmsCommandHandlerInterface;
use Spryker\Zed\PaymentTemplate\Business\Oms\Condition\OmsConditionChecker;
use Spryker\Zed\PaymentTemplate\Business\Oms\Condition\OmsConditionCheckerInterface;
use Spryker\Zed\PaymentTemplate\Business\Payment\PaymentAuthorizer;
use Spryker\Zed\PaymentTemplate\Business\Payment\PaymentAuthorizerInterface;
use Spryker\Zed\PaymentTemplate\Business\Payment\PaymentMethodFilter;
use Spryker\Zed\PaymentTemplate\Business\Payment\PaymentMethodFilterInterface;
use Spryker\Zed\PaymentTemplate\Business\Payment\PaymentReader;
use Spryker\Zed\PaymentTemplate\Business\Payment\PaymentReaderInterface;
use Spryker\Zed\PaymentTemplate\Business\Payment\PaymentSaver;
use Spryker\Zed\PaymentTemplate\Business\Payment\PaymentSaverInterface;
use Spryker\Zed\PaymentTemplate\PaymentTemplateDependencyProvider;

/**
 * @method \Spryker\Zed\PaymentTemplate\PaymentTemplateConfig getConfig()
 * @method \Spryker\Zed\PaymentTemplate\Persistence\PaymentTemplateRepositoryInterface getRepository()
 * @method \Spryker\Zed\PaymentTemplate\Persistence\PaymentTemplateEntityManagerInterface getEntityManager()
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
