<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\PaymentTemplate\Api\PaymentMethods;

use Generated\Shared\Transfer\PaymentTemplatePaymentMethodsRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplatePaymentMethodsResponseTransfer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use SprykerEco\Client\PaymentTemplate\Api\ApiLogger;
use SprykerEco\Client\PaymentTemplate\PaymentTemplateConfig;

class PaymentMethodsApiRequest
{
    public function __construct(
        protected PaymentTemplateConfig $paymentTemplateConfig,
        protected ApiLogger $apiLogger,
        protected Client $guzzleClient,
        protected PaymentMethodsMapper $paymentMethodsMapper,
    ) {
    }

    public function request(
        PaymentTemplatePaymentMethodsRequestTransfer $paymentTemplatePaymentMethodsRequestTransfer,
    ): PaymentTemplatePaymentMethodsResponseTransfer {
        try {
            $this->apiLogger->logRequest($this->getUrl(), $paymentTemplatePaymentMethodsRequestTransfer);

            $requestBody = $this->paymentMethodsMapper->mapRequest($paymentTemplatePaymentMethodsRequestTransfer);
            $httpResponse = $this->sendRequest($requestBody);

            $paymentTemplateApiResponseTransfer = $this->paymentMethodsMapper->mapResponse($httpResponse);
            $this->apiLogger->logResponse($httpResponse, $paymentTemplateApiResponseTransfer);

            return $paymentTemplateApiResponseTransfer;
        } catch (RequestException $exception) {
            $this->apiLogger->logErrorRequest($exception, $exception->hasResponse() ? $exception->getResponse() : null);

            return $this->paymentMethodsMapper->mapErrorResponse($exception);
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
            'GET',
            $this->getUrl(),
            $requestOptions,
        );
    }

    protected function getUrl(): string
    {
        return $this->paymentTemplateConfig->getPaymentMethodsUrl();
    }
}
