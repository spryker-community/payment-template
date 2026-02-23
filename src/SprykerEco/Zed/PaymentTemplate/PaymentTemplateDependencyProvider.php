<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\PaymentTemplate;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \SprykerEco\Zed\PaymentTemplate\PaymentTemplateConfig getConfig()
 */
class PaymentTemplateDependencyProvider extends AbstractBundleDependencyProvider
{
    public const string CLIENT_PAYMENT_TEMPLATE = 'CLIENT_PAYMENT_TEMPLATE';

    public const string FACADE_SALES = 'FACADE_SALES';

    public const string FACADE_CALCULATION = 'FACADE_CALCULATION';

    public const string FACADE_OMS = 'FACADE_OMS';

    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addPaymentTemplateClient($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addSalesFacade($container);

        return $container;
    }

    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addCalculationFacade($container);

        return $container;
    }

    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container) {
            return $container->getLocator()->sales()->facade();
        });

        return $container;
    }

    protected function addCalculationFacade(Container $container): Container
    {
        $container->set(static::FACADE_CALCULATION, function (Container $container) {
            return $container->getLocator()->calculation()->facade();
        });

        return $container;
    }

    protected function addOmsFacade(Container $container): Container
    {
        $container->set(static::FACADE_OMS, function (Container $container) {
            return $container->getLocator()->oms()->facade();
        });

        return $container;
    }

    protected function addPaymentTemplateClient(Container $container): Container
    {
        $container->set(static::CLIENT_PAYMENT_TEMPLATE, function (Container $container) {
            return $container->getLocator()->paymentTemplate()->client();
        });

        return $container;
    }
}
