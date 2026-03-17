<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\PaymentTemplate\Api\Refund;

use Generated\Shared\Transfer\PaymentTemplateApiErrorResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplateCaptureRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateCaptureResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplateRefundRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateRefundResponseTransfer;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class RefundMapper
{
    public function mapRequest(PaymentTemplateRefundRequestTransfer $requestTransfer): string
    {
        return json_encode($requestTransfer->toArray());
    }

    public function mapResponse(ResponseInterface $httpResponse): PaymentTemplateRefundResponseTransfer
    {
        $responseBody = json_decode($httpResponse->getBody()->getContents(), true);

        return (new PaymentTemplateRefundResponseTransfer())->fromArray($responseBody);
    }

    public function mapErrorResponse(RequestException $exception): PaymentTemplateRefundResponseTransfer
    {
        $errorResponseTransfer = (new PaymentTemplateApiErrorResponseTransfer())
            ->setMessage($exception->getMessage())
            ->setStatusCode($exception->getCode());

        if ($exception->hasResponse()) {
            $errorResponseTransfer
                ->setStatusCode($exception->getResponse()->getStatusCode())
                ->setBody($exception->getResponse()->getBody()->getContents());
        }

        return (new PaymentTemplateRefundResponseTransfer())
            ->setIsSuccess(false)
            ->setErrorResponse($errorResponseTransfer);
    }
}
