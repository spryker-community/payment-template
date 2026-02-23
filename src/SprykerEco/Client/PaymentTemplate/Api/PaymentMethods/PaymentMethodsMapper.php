<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\PaymentTemplate\Api\PaymentMethods;

use Generated\Shared\Transfer\PaymentTemplateApiErrorResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplatePaymentMethodsRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplatePaymentMethodsResponseTransfer;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class PaymentMethodsMapper
{
    public function mapRequest(PaymentTemplatePaymentMethodsRequestTransfer $requestTransfer): string
    {
        return json_encode($requestTransfer->toArray());
    }

    public function mapResponse(ResponseInterface $httpResponse): PaymentTemplatePaymentMethodsResponseTransfer
    {
        $responseBody = json_decode($httpResponse->getBody()->getContents(), true);

        return (new PaymentTemplatePaymentMethodsResponseTransfer())->fromArray($responseBody);
    }

    public function mapErrorResponse(RequestException $exception): PaymentTemplatePaymentMethodsResponseTransfer
    {
        $errorResponseTransfer = (new PaymentTemplateApiErrorResponseTransfer())
            ->setMessage($exception->getMessage())
            ->setStatusCode($exception->getCode());

        if ($exception->hasResponse()) {
            $errorResponseTransfer
                ->setStatusCode($exception->getResponse()->getStatusCode())
                ->setBody($exception->getResponse()->getBody()->getContents());
        }

        return (new PaymentTemplatePaymentMethodsResponseTransfer())
            ->setIsSuccess(false)
            ->setErrorResponse($errorResponseTransfer);
    }
}
