<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\PaymentTemplate\Api\Cancel;

use Generated\Shared\Transfer\PaymentTemplateApiErrorResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplateCancelRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateCancelResponseTransfer;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class CancelMapper
{
    public function mapRequest(PaymentTemplateCancelRequestTransfer $requestTransfer): string
    {
        return json_encode($requestTransfer->toArray());
    }

    public function mapResponse(ResponseInterface $httpResponse): PaymentTemplateCancelResponseTransfer
    {
        $responseBody = json_decode($httpResponse->getBody()->getContents(), true);

        return (new PaymentTemplateCancelResponseTransfer())->fromArray($responseBody);
    }

    public function mapErrorResponse(RequestException $exception): PaymentTemplateCancelResponseTransfer
    {
        $errorResponseTransfer = (new PaymentTemplateApiErrorResponseTransfer())
            ->setMessage($exception->getMessage())
            ->setStatusCode($exception->getCode());

        if ($exception->hasResponse()) {
            $errorResponseTransfer
                ->setStatusCode($exception->getResponse()->getStatusCode())
                ->setBody($exception->getResponse()->getBody()->getContents());
        }

        return (new PaymentTemplateCancelResponseTransfer())
            ->setIsSuccess(false)
            ->setErrorResponse($errorResponseTransfer);
    }
}
