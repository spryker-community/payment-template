<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\PaymentTemplate\Api\Capture;

use Generated\Shared\Transfer\PaymentTemplateApiErrorResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplateCaptureRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateCaptureResponseTransfer;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class CaptureMapper
{
    public function mapRequest(PaymentTemplateCaptureRequestTransfer $requestTransfer): string
    {
        return json_encode($requestTransfer->toArray());
    }

    public function mapResponse(ResponseInterface $httpResponse): PaymentTemplateCaptureResponseTransfer
    {
        $responseBody = json_decode($httpResponse->getBody()->getContents(), true);

        return (new PaymentTemplateCaptureResponseTransfer())->fromArray($responseBody);
    }

    public function mapErrorResponse(RequestException $exception): PaymentTemplateCaptureResponseTransfer
    {
        $errorResponseTransfer = (new PaymentTemplateApiErrorResponseTransfer())
            ->setMessage($exception->getMessage())
            ->setStatusCode($exception->getCode());

        if ($exception->hasResponse()) {
            $errorResponseTransfer
                ->setStatusCode($exception->getResponse()->getStatusCode())
                ->setBody($exception->getResponse()->getBody()->getContents());
        }

        return (new PaymentTemplateCaptureResponseTransfer())
            ->setIsSuccess(false)
            ->setErrorResponse($errorResponseTransfer);
    }
}
