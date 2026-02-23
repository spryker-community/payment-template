<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\PaymentTemplate;

use GuzzleHttp\Client;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use SprykerEco\Client\PaymentTemplate\Api\ApiLogger;
use SprykerEco\Client\PaymentTemplate\Api\Authorization\AuthorizationApiRequest;
use SprykerEco\Client\PaymentTemplate\Api\Authorization\AuthorizationMapper;
use SprykerEco\Client\PaymentTemplate\Api\Cancel\CancelApiRequest;
use SprykerEco\Client\PaymentTemplate\Api\Cancel\CancelMapper;
use SprykerEco\Client\PaymentTemplate\Api\Capture\CaptureApiRequest;
use SprykerEco\Client\PaymentTemplate\Api\Capture\CaptureMapper;
use SprykerEco\Client\PaymentTemplate\Api\PaymentMethods\PaymentMethodsApiRequest;
use SprykerEco\Client\PaymentTemplate\Api\PaymentMethods\PaymentMethodsMapper;
use SprykerEco\Client\PaymentTemplate\Zed\PaymentTemplateStub;
use SprykerEco\Client\PaymentTemplate\Zed\PaymentTemplateStubInterface;

/**
 * @method \SprykerEco\Client\PaymentTemplate\PaymentTemplateConfig getConfig()
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
