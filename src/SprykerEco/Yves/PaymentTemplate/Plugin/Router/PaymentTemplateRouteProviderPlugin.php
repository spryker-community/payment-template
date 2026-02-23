<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\PaymentTemplate\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class PaymentTemplateRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_PAYMENT_TEMPLATE_NOTIFICATION = 'payment-template-notification';

    /**
     * Specification:
     * - Adds routes for webhook notification.
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addPaymentTemplateNotificationRoute($routeCollection);

        return $routeCollection;
    }

    protected function addPaymentTemplateNotificationRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildPostRoute('/payment-template/notification', 'PaymentTemplate', 'Notification', 'notificationAction');
        $routeCollection->add(static::ROUTE_PAYMENT_TEMPLATE_NOTIFICATION, $route);

        return $routeCollection;
    }
}
