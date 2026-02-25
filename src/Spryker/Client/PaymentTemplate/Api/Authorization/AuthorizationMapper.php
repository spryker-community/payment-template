<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\PaymentTemplate\Api\Authorization;

use Generated\Shared\Transfer\PaymentTemplateApiErrorResponseTransfer;
use Generated\Shared\Transfer\PaymentTemplateAuthorizeRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateAuthorizeResponseTransfer;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class AuthorizationMapper
{
    public function mapRequest(PaymentTemplateAuthorizeRequestTransfer $requestTransfer): string
    {
        return json_encode($requestTransfer->toArray());
    }

    public function mapResponse(ResponseInterface $httpResponse): PaymentTemplateAuthorizeResponseTransfer
    {
        $responseBody = json_decode($httpResponse->getBody()->getContents(), true);

        return (new PaymentTemplateAuthorizeResponseTransfer())->fromArray($responseBody);
    }

    public function mapErrorResponse(RequestException $exception): PaymentTemplateAuthorizeResponseTransfer
    {
        $errorResponseTransfer = (new PaymentTemplateApiErrorResponseTransfer())
            ->setMessage($exception->getMessage())
            ->setStatusCode($exception->getCode());

        if ($exception->hasResponse()) {
            $errorResponseTransfer
                ->setStatusCode($exception->getResponse()->getStatusCode())
                ->setBody($exception->getResponse()->getBody()->getContents());
        }

        return (new PaymentTemplateAuthorizeResponseTransfer())
            ->setIsSuccess(false)
            ->setErrorResponse($errorResponseTransfer);
    }
}
