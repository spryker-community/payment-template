<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\PaymentTemplate;

use GuzzleHttp\Client;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PaymentTemplate\Api\Refund\RefundApiRequest;
use Spryker\Client\PaymentTemplate\Api\Refund\RefundMapper;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use Spryker\Client\PaymentTemplate\Api\ApiLogger;
use Spryker\Client\PaymentTemplate\Api\Authorization\AuthorizationApiRequest;
use Spryker\Client\PaymentTemplate\Api\Authorization\AuthorizationMapper;
use Spryker\Client\PaymentTemplate\Api\Cancel\CancelApiRequest;
use Spryker\Client\PaymentTemplate\Api\Cancel\CancelMapper;
use Spryker\Client\PaymentTemplate\Api\Capture\CaptureApiRequest;
use Spryker\Client\PaymentTemplate\Api\Capture\CaptureMapper;
use Spryker\Client\PaymentTemplate\Api\PaymentMethods\PaymentMethodsApiRequest;
use Spryker\Client\PaymentTemplate\Api\PaymentMethods\PaymentMethodsMapper;
use Spryker\Client\PaymentTemplate\Zed\PaymentTemplateStub;
use Spryker\Client\PaymentTemplate\Zed\PaymentTemplateStubInterface;

/**
 * @method \Spryker\Client\PaymentTemplate\PaymentTemplateConfig getConfig()
 */
class PaymentTemplateFactory extends AbstractFactory
{
    protected function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(PaymentTemplateDependencyProvider::CLIENT_ZED_REQUEST);
    }

    protected function createApiLogger(): ApiLogger
    {
        return new ApiLogger();
    }

    protected function createGuzzleClient(): Client
    {
        return new Client();
    }

    public function createAuthorizeRequest(): AuthorizationApiRequest
    {
        return new AuthorizationApiRequest(
            $this->getConfig(),
            $this->createApiLogger(),
            $this->createGuzzleClient(),
            $this->createAuthorizationMapper(),
        );
    }

    public function createAuthorizationMapper(): AuthorizationMapper
    {
        return new AuthorizationMapper();
    }

    public function createCaptureRequest(): CaptureApiRequest
    {
        return new CaptureApiRequest(
            $this->getConfig(),
            $this->createApiLogger(),
            $this->createGuzzleClient(),
            $this->createCaptureMapper(),
        );
    }

    public function createCaptureMapper(): CaptureMapper
    {
        return new CaptureMapper();
    }

    public function createRefundRequest(): RefundApiRequest
    {
        return new RefundApiRequest(
            $this->getConfig(),
            $this->createApiLogger(),
            $this->createGuzzleClient(),
            $this->createRefundMapper(),
        );
    }

    public function createRefundMapper(): RefundMapper
    {
        return new RefundMapper();
    }

    public function createCancelRequest(): CancelApiRequest
    {
        return new CancelApiRequest(
            $this->getConfig(),
            $this->createApiLogger(),
            $this->createGuzzleClient(),
            $this->createCancelMapper(),
        );
    }

    public function createCancelMapper(): CancelMapper
    {
        return new CancelMapper();
    }

    public function createPaymentMethodsRequest(): PaymentMethodsApiRequest
    {
        return new PaymentMethodsApiRequest(
            $this->getConfig(),
            $this->createApiLogger(),
            $this->createGuzzleClient(),
            $this->createPaymentMethodsMapper(),
        );
    }

    public function createPaymentMethodsMapper(): PaymentMethodsMapper
    {
        return new PaymentMethodsMapper();
    }

    public function createZedStub(): PaymentTemplateStubInterface
    {
        return new PaymentTemplateStub($this->getZedRequestClient());
    }
}
