<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\PaymentTemplate\Api\Authorization;

use Generated\Shared\Transfer\PaymentTemplateAuthorizeRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateAuthorizeResponseTransfer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use SprykerEco\Client\PaymentTemplate\Api\ApiLogger;
use SprykerEco\Client\PaymentTemplate\PaymentTemplateConfig;

class AuthorizationApiRequest
{
    public function __construct(
        protected PaymentTemplateConfig $paymentTemplateConfig,
        protected ApiLogger $apiLogger,
        protected Client $guzzleClient,
        protected AuthorizationMapper $authorizeMapper,
    ) {
    }

    public function request(PaymentTemplateAuthorizeRequestTransfer $paymentTemplateAuthorizeRequestTransfer): PaymentTemplateAuthorizeResponseTransfer
    {
        try {
            $this->apiLogger->logRequest($this->getUrl(), $paymentTemplateAuthorizeRequestTransfer);

            $requestBody = $this->authorizeMapper->mapRequest($paymentTemplateAuthorizeRequestTransfer);
            $httpResponse = $this->sendRequest($requestBody);

            $paymentTemplateApiResponseTransfer = $this->authorizeMapper->mapResponse($httpResponse);
            $this->apiLogger->logResponse($httpResponse, $paymentTemplateApiResponseTransfer);

            return $paymentTemplateApiResponseTransfer;
        } catch (RequestException $exception) {
            $this->apiLogger->logErrorRequest($exception, $exception->hasResponse() ? $exception->getResponse() : null);

            return $this->authorizeMapper->mapErrorResponse($exception);
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
        return $this->paymentTemplateConfig->getAuthorizeUrl();
    }
}
