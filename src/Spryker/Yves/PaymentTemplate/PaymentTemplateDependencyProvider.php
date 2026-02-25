<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\PaymentTemplate;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

/**
 * @method \Spryker\Yves\PaymentTemplate\PaymentTemplateConfig getConfig()
 */
class PaymentTemplateDependencyProvider extends AbstractBundleDependencyProvider
{
    public const string CLIENT_PAYMENT_TEMPLATE = 'CLIENT_PAYMENT_TEMPLATE';

    public const string CLIENT_QUOTE = 'CLIENT_QUOTE';

    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addPaymentTemplateClient($container);
        $container = $this->addQuoteClient($container);

        return $container;
    }

    protected function addPaymentTemplateClient(Container $container): Container
    {
        $container->set(static::CLIENT_PAYMENT_TEMPLATE, function (Container $container) {
            return $container->getLocator()->paymentTemplate()->client();
        });

        return $container;
    }

    protected function addQuoteClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container) {
            return $container->getLocator()->quote()->client();
        });

        return $container;
    }
}
