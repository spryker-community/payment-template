<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\PaymentTemplate\Api\Refund;

use Generated\Shared\Transfer\PaymentTemplateRefundRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateRefundResponseTransfer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Spryker\Client\PaymentTemplate\Api\ApiLogger;
use Spryker\Client\PaymentTemplate\PaymentTemplateConfig;

class RefundApiRequest
{
    public function __construct(
        protected PaymentTemplateConfig $paymentTemplateConfig,
        protected ApiLogger $apiLogger,
        protected Client $guzzleClient,
        protected RefundMapper $refundMapper,
    ) {
    }

    public function request(PaymentTemplateRefundRequestTransfer $paymentTemplateRefundRequestTransfer): PaymentTemplateRefundResponseTransfer
    {
        try {
            $this->apiLogger->logRequest($this->getUrl(), $paymentTemplateRefundRequestTransfer);

            $requestBody = $this->refundMapper->mapRequest($paymentTemplateRefundRequestTransfer);
            $httpResponse = $this->sendRequest($requestBody);

            $paymentTemplateApiResponseTransfer = $this->refundMapper->mapResponse($httpResponse);
            $this->apiLogger->logResponse($httpResponse, $paymentTemplateApiResponseTransfer);

            return $paymentTemplateApiResponseTransfer;
        } catch (RequestException $exception) {
            $this->apiLogger->logErrorRequest($exception, $exception->hasResponse() ? $exception->getResponse() : null);

            return $this->refundMapper->mapErrorResponse($exception);
        }
    }

    protected function sendRequest(string $requestBody): ResponseInterface
    {
        $requestOptions = [
            RequestOptions::HEADERS => $this->paymentTemplateConfig->getDefaultHeaders(),
            RequestOptions::TIMEOUT => $this->paymentTemplateConfig->getApiTimeout(),
            RequestOptions::BODY => $requestBody,
        ];

        return $this->guzzleClient->request(
            'POST',
            $this->getUrl(),
            $requestOptions,
        );
    }

    protected function getUrl(): string
    {
        return $this->paymentTemplateConfig->getRefundUrl();
    }
}
