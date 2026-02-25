<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Shared\PaymentTemplate;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface PaymentTemplateConstants
{
    public const string API_KEY = 'PAYMENT_TEMPLATE:API_KEY';

    public const string API_SECRET = 'PAYMENT_TEMPLATE:API_SECRET';

    public const string API_BASE_URL = 'PAYMENT_TEMPLATE:API_BASE_URL';

    public const string API_TIMEOUT = 'PAYMENT_TEMPLATE:API_TIMEOUT';

    public const string API_AUTHORIZE_PATH = 'PAYMENT_TEMPLATE:API_AUTHORIZE_PATH';

    public const string API_CAPTURE_PATH = 'PAYMENT_TEMPLATE:API_CAPTURE_PATH';

    public const string API_CANCEL_PATH = 'PAYMENT_TEMPLATE:API_CANCEL_PATH';

    public const string API_PAYMENT_METHODS_PATH = 'PAYMENT_TEMPLATE:API_PAYMENT_METHODS_PATH';
}
