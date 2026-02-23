<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\PaymentTemplate\Api;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;

class ApiLogger
{
    use LoggerTrait;

    /**
     * @param string $url
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $requestTransfer
     *
     * @return void
     */
    public function logRequest(string $url, TransferInterface $requestTransfer): void
    {
        $this->getLogger()->info('PaymentTemplate request', [
            'url' => $url,
            'request' => $this->sanitizeLogData($requestTransfer->toArray()),
        ]);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $httpResponse
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $responseTransfer
     *
     * @return void
     */
    public function logResponse(ResponseInterface $httpResponse, TransferInterface $responseTransfer): void
    {
        $this->getLogger()->info('PaymentTemplate response', [
            'status' => (string)$httpResponse->getStatusCode(),
            'response' => $this->sanitizeLogData($httpResponse->getBody()),
            'responseTransfer' => $this->sanitizeLogData($responseTransfer->toArray()),
        ]);
    }

    /**
     * @param \GuzzleHttp\Exception\RequestException $exception
     * @param \Psr\Http\Message\ResponseInterface|null $httpResponse
     *
     * @return void
     */
    public function logErrorRequest(RequestException $exception, ?ResponseInterface $httpResponse = null): void
    {
        $this->getLogger()->error('PaymentTemplate API request failed', [
            'exception' => $this->sanitizeLogData($exception),
            'status' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'body' => $httpResponse ? $this->sanitizeLogData($httpResponse->getBody()) : 'N/A',
        ]);
    }

    /**
     * TODO: sanitize the data that will be logged (remove/mask customer or other sensitive information)
     *
     * @param mixed $data
     *
     * @return mixed
     */
    protected function sanitizeLogData(mixed $data): mixed
    {
        return $data;
    }
}
