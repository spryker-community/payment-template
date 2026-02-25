<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\PaymentTemplate\Api\Cancel;

use Generated\Shared\Transfer\PaymentTemplateCancelRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateCancelResponseTransfer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Spryker\Client\PaymentTemplate\Api\ApiLogger;
use Spryker\Client\PaymentTemplate\PaymentTemplateConfig;

class CancelApiRequest
{
    public function __construct(
        protected PaymentTemplateConfig $paymentTemplateConfig,
        protected ApiLogger $apiLogger,
        protected Client $guzzleClient,
        protected CancelMapper $cancelMapper,
    ) {
    }

    public function request(PaymentTemplateCancelRequestTransfer $paymentTemplateCancelRequestTransfer): PaymentTemplateCancelResponseTransfer
    {
        try {
            $this->apiLogger->logRequest($this->getUrl(), $paymentTemplateCancelRequestTransfer);

            $requestBody = $this->cancelMapper->mapRequest($paymentTemplateCancelRequestTransfer);
            $httpResponse = $this->sendRequest($requestBody);

            $paymentTemplateCancelResponseTransfer = $this->cancelMapper->mapResponse($httpResponse);
            $this->apiLogger->logResponse($httpResponse, $paymentTemplateCancelResponseTransfer);

            return $paymentTemplateCancelResponseTransfer;
        } catch (RequestException $exception) {
            $this->apiLogger->logErrorRequest($exception, $exception->hasResponse() ? $exception->getResponse() : null);

            return $this->cancelMapper->mapErrorResponse($exception);
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
        return $this->paymentTemplateConfig->getCancelUrl();
    }
}
