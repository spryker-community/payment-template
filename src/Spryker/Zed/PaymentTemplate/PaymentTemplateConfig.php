<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentTemplate;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PaymentTemplateConfig extends AbstractBundleConfig
{
    public const string PAYMENT_STATUS_NEW = 'new';

    public const string PAYMENT_STATUS_AUTHORIZED = 'authorized';

    public const string PAYMENT_STATUS_AUTHORIZATION_FAILED = 'authorization_failed';

    public const string PAYMENT_STATUS_CAPTURE_FAILED = 'capture_failed';

    public const string PAYMENT_STATUS_REFUND_FAILED = 'refund_failed';

    public const string PAYMENT_STATUS_CANCEL_FAILED = 'cancel_failed';
}
