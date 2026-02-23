<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\PaymentTemplate\Api\Capture;

use Generated\Shared\Transfer\PaymentTemplateCaptureRequestTransfer;
use Generated\Shared\Transfer\PaymentTemplateCaptureResponseTransfer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use SprykerEco\Client\PaymentTemplate\Api\ApiLogger;
use SprykerEco\Client\PaymentTemplate\PaymentTemplateConfig;

class CaptureApiRequest
{
    public function __construct(
        protected PaymentTemplateConfig $paymentTemplateConfig,
        protected ApiLogger $apiLogger,
        protected Client $guzzleClient,
        protected CaptureMapper $captureMapper,
    ) {
    }

    public function request(PaymentTemplateCaptureRequestTransfer $paymentTemplateCaptureRequestTransfer): PaymentTemplateCaptureResponseTransfer
    {
        try {
            $this->apiLogger->logRequest($this->getUrl(), $paymentTemplateCaptureRequestTransfer);

            $requestBody = $this->captureMapper->mapRequest($paymentTemplateCaptureRequestTransfer);
            $httpResponse = $this->sendRequest($requestBody);

            $paymentTemplateApiResponseTransfer = $this->captureMapper->mapResponse($httpResponse);
            $this->apiLogger->logResponse($httpResponse, $paymentTemplateApiResponseTransfer);

            return $paymentTemplateApiResponseTransfer;
        } catch (RequestException $exception) {
            $this->apiLogger->logErrorRequest($exception, $exception->hasResponse() ? $exception->getResponse() : null);

            return $this->captureMapper->mapErrorResponse($exception);
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
        return $this->paymentTemplateConfig->getCaptureUrl();
    }
}
