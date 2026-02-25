<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentTemplate\Persistence;

use Generated\Shared\Transfer\PaymentTemplateTransfer;

interface PaymentTemplateRepositoryInterface
{
    public function findPaymentTemplateByIdSalesOrder(int $idSalesOrder): ?PaymentTemplateTransfer;

    public function findPaymentTemplateByProviderReference(string $providerReference): ?PaymentTemplateTransfer;
}
