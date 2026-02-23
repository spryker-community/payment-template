# Payment Module Integration Guide

This guide explains how to integrate a PSP payment module (built from the PaymentTemplate) into your Spryker project.

> **Are you building a PSP integration module from the PaymentTemplate?** See [IMPLEMENTATION.md](IMPLEMENTATION.md) for the module development guide.

## Prerequisites

- Spryker Commerce OS project
- Composer installed
- Access to Zed Back Office
- Docker SDK (if using Spryker Docker setup)

## 1. Installation

### Add Module via Composer

If the module is published on Packagist:
```bash
composer require your-org/your-psp
```

If the module is in a private repository:
```json
// Add to composer.json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/your-org/your-psp.git"
    }
  ],
  "require": {
    "your-org/your-psp": "^1.0"
  }
}
```

Then run:
```bash
composer update your-org/your-psp
```

## 2. Module Configuration

### Configure PSP API Credentials (if applicable)

If the PSP module requires API credentials, configure them appropriately for your environment.

#### Development/Local Environment

**Location:** `config/Shared/config_local.php` (never commit this file)

```php
use SprykerEco\Shared\YourPsp\YourPspConstants;

// Example - check module documentation for actual constants
$config[YourPspConstants::API_KEY] = 'your-sandbox-api-key';
$config[YourPspConstants::API_SECRET] = 'your-sandbox-api-secret';
$config[YourPspConstants::WEBHOOK_SECRET] = 'your-webhook-secret';
$config[YourPspConstants::API_BASE_URL] = 'https://sandbox.yourpsp.com';
$config[YourPspConstants::ENVIRONMENT] = 'sandbox';
```

#### Production/Staging Environment

**Location:** `config/Shared/config_default.php`

Use environment variables for production credentials - never hardcode production secrets:

```php
use SprykerEco\Shared\YourPsp\YourPspConstants;

$config[YourPspConstants::API_KEY] = getenv('YOUR_PSP_API_KEY') ?: '';
$config[YourPspConstants::API_SECRET] = getenv('YOUR_PSP_API_SECRET') ?: '';
$config[YourPspConstants::WEBHOOK_SECRET] = getenv('YOUR_PSP_WEBHOOK_SECRET') ?: '';
$config[YourPspConstants::API_BASE_URL] = getenv('YOUR_PSP_API_BASE_URL') ?: '';
$config[YourPspConstants::ENVIRONMENT] = getenv('YOUR_PSP_ENVIRONMENT') ?: 'production';
```

Then set these environment variables in your deployment:
- `YOUR_PSP_API_KEY` - Production API key
- `YOUR_PSP_API_SECRET` - Production API secret
- `YOUR_PSP_WEBHOOK_SECRET` - Webhook signature secret
- `YOUR_PSP_API_BASE_URL` - Production API base URL
- `YOUR_PSP_ENVIRONMENT` - Environment identifier (e.g., 'production', 'staging')

**Note:** Refer to the module's README or documentation for specific configuration constants.

## 3. Data Import

### Import Payment Methods

The module provides pre-configured data import files for payment methods, store assignments, and translations.

#### Option 1: Import Using Module's Configuration File

```bash
docker/sdk cli
vendor/bin/console data:import --config=vendor/your-org/your-psp/data/import/payment_template.yml
```

#### Option 2: Copy Files and Import Individually

```bash
# Copy import files to project
cp vendor/your-org/your-psp/data/import/*.csv data/import/common/common/

# Import
docker/sdk cli
vendor/bin/console data:import payment-method
vendor/bin/console data:import payment-method-store
vendor/bin/console data:import glossary
```

#### Option 3: Add to Project's Main Import Configuration

Add the import actions to your project's main data import configuration file and include in your regular import pipeline.

### Customize Payment Methods

Before importing, you can customize the payment method data:

**File:** `vendor/your-org/your-psp/data/import/payment_method.csv`
- Update payment method names
- Enable/disable methods
- Add additional payment methods

**File:** `vendor/your-org/your-psp/data/import/payment_method_store.csv`
- Configure which stores each payment method is available in

**File:** `vendor/your-org/your-psp/data/import/glossary.csv`
- Customize translations for payment method names
- Add additional locales

### Verify Import

Check Zed Back Office:
1. Go to **Administration → Payment → Payment Methods**
2. Verify payment methods appear with correct names and provider
3. Verify methods are assigned to correct stores
4. Go to **Administration → Glossary** and verify translations

## 4. Plugin Registration

Register the module's plugins in your project's dependency providers.

### Checkout Plugins

**Location:** `src/Pyz/Zed/Checkout/CheckoutDependencyProvider.php`

```php
use SprykerEco\Zed\YourPsp\Communication\Plugin\Checkout\YourPspCheckoutDoSaveOrderPlugin;
use SprykerEco\Zed\YourPsp\Communication\Plugin\Checkout\YourPspCheckoutPostSavePlugin;

/**
 * @param \Spryker\Zed\Kernel\Container $container
 *
 * @return array<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface>
 */
protected function getCheckoutOrderSavers(Container $container): array
{
    return [
        // ... other plugins
        new YourPspCheckoutDoSaveOrderPlugin(), // Saves payment data during checkout
    ];
}

/**
 * @param \Spryker\Zed\Kernel\Container $container
 *
 * @return array<\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface>
 */
protected function getCheckoutPostHooks(Container $container): array
{
    return [
        // ... other plugins
        new YourPspCheckoutPostSavePlugin(), // Optional: for synchronous authorization during checkout
    ];
}
```

**Note:** Only use `CheckoutPostSavePlugin` if the PSP requires synchronous authorization during checkout. Otherwise, authorization happens via OMS commands.

### Payment Method Plugins

**Location:** `src/Pyz/Zed/Payment/PaymentDependencyProvider.php`

```php
use SprykerEco\Zed\YourPsp\Communication\Plugin\Payment\YourPspPaymentMethodFilterPlugin;

/**
 * @return array<\Spryker\Zed\PaymentExtension\Dependency\Plugin\PaymentMethodFilterPluginInterface>
 */
protected function getPaymentMethodFilterPlugins(): array
{
    return [
        // ... other plugins
        new YourPspPaymentMethodFilterPlugin(), // Filters available payment methods based on PSP response
    ];
}
```

### Checkout Page Plugins (Yves)

**Location:** `src/Pyz/Yves/CheckoutPage/CheckoutPageDependencyProvider.php`

```php
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use SprykerEco\Shared\YourPsp\YourPspConfig;
use SprykerEco\Yves\YourPsp\Plugin\StepEngine\YourPspCreditCardSubFormPlugin;
use SprykerEco\Yves\YourPsp\Plugin\StepEngine\YourPspCreditCardStepHandlerPlugin;
use SprykerEco\Yves\YourPsp\Plugin\StepEngine\YourPspInvoiceSubFormPlugin;
use SprykerEco\Yves\YourPsp\Plugin\StepEngine\YourPspInvoiceStepHandlerPlugin;

/**
 * @param \Spryker\Yves\Kernel\Container $container
 *
 * @return \Spryker\Yves\Kernel\Container
 */
protected function extendPaymentMethodHandler(Container $container): Container
{
    $container->extend(static::PAYMENT_METHOD_HANDLER, function (StepHandlerPluginCollection $paymentMethodHandler) {
        $paymentMethodHandler->add(
            new YourPspCreditCardStepHandlerPlugin(),
            YourPspConfig::PAYMENT_METHOD_CREDIT_CARD,
        );
        $paymentMethodHandler->add(
            new YourPspInvoiceStepHandlerPlugin(),
            YourPspConfig::PAYMENT_METHOD_INVOICE,
        );

        return $paymentMethodHandler;
    });

    return $container;
}

/**
 * @param \Spryker\Yves\Kernel\Container $container
 *
 * @return \Spryker\Yves\Kernel\Container
 */
protected function extendSubFormPluginCollection(Container $container): Container
{
    $container->extend(static::PAYMENT_SUB_FORMS, function (SubFormPluginCollection $paymentSubFormPluginCollection) {
        $paymentSubFormPluginCollection->add(new YourPspCreditCardSubFormPlugin());
        $paymentSubFormPluginCollection->add(new YourPspInvoiceSubFormPlugin());

        return $paymentSubFormPluginCollection;
    });

    return $container;
}
```

**Important Notes:**
- The second parameter (payment method key) must match the constants defined in `YourPspConfig::PAYMENT_METHOD_*`
- The payment method key must also match the `payment_method_key` from your payment method data import
- These methods extend existing collections, so other payment methods remain registered

### Router Plugin (Yves) - For Redirect/Webhook Endpoints

**Location:** `src/Pyz/Yves/Router/RouterDependencyProvider.php`

```php
use SprykerEco\Yves\YourPsp\Plugin\Router\YourPspRouteProviderPlugin;

/**
 * @return array<\Spryker\Yves\RouterExtension\Dependency\Plugin\RouteProviderPluginInterface>
 */
protected function getRouteProvider(): array
{
    return [
        // ... other route providers
        new YourPspRouteProviderPlugin(),
    ];
}
```

### Refresh Routes

After registering the route provider plugin, you must refresh the route cache for the new routes to become available.

```bash
docker/sdk cli
vendor/bin/yves router:cache:warm-up
```

This generates the route cache and makes your PSP endpoints (redirect, webhook) accessible.

## 5. OMS Configuration

### Configure OMS Process Location

Add the module's OMS directory to your project configuration so Spryker can find the OMS process files.

**Location:** `config/Shared/config_default.php`

```php
use Spryker\Shared\Oms\OmsConstants;
use SprykerEco\Shared\PaymentTemplate\PaymentTemplateConfig;

$config[OmsConstants::PROCESS_LOCATION] = [
    // ... other previously configured locations
    PaymentTemplateConfig::OMS_PROCESS_LOCATION,
];
```

### Map Payment Methods to OMS Processes

Configure which OMS process each payment method should use.

**Location:** `config/Shared/config_default.php`

```php
use Spryker\Shared\Sales\SalesConstants;

$config[SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING] = [
    // ... other payment method mappings
    'yourPspCreditCard' => 'YourPspCreditCard01',
    'yourPspInvoice' => 'YourPspInvoice01',
];
```

**Important Notes:**
- The array keys must match the `payment_method_key` from your payment method import data
- The array values must match the OMS process names (the `<process name="...">` in the XML files)
- If you renamed the module, ensure process names match your renamed OMS files

**Example for Adyen:**
```php
$config[SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING] = [
    'dummyPaymentInvoice' => 'DummyPayment01',
    'dummyPaymentCreditCard' => 'DummyPayment01',
    'adyenCreditCard' => 'AdyenCreditCard01',
    'adyenInvoice' => 'AdyenInvoice01',
];
```

### Register Active OMS Processes

Add the PSP's OMS processes to the list of active processes so Spryker loads and recognizes them.

**Location:** `config/Shared/config_default.php`

```php
use Spryker\Shared\Oms\OmsConstants;

$config[OmsConstants::ACTIVE_PROCESSES] = [
    // ... other active processes
    'YourPspCreditCard01',
    'YourPspInvoice01',
];
```

**Important Notes:**
- Process names must match exactly with the `<process name="...">` attribute in the OMS XML files
- Process names must also match the values in `getPaymentMethodStatemachineMapping()`
- Without this configuration, the OMS processes will not be loaded or available

**Example for Adyen:**
```php
$config[OmsConstants::ACTIVE_PROCESSES] = [
    'DummyPayment01',
    'AdyenCreditCard01',
    'AdyenInvoice01',
];
```

## 6. Generate Transfer Objects

After installation, generate transfer objects:

```bash
docker/sdk cli
vendor/bin/console transfer:generate
```

## 7. Clear Caches

Clear all caches to ensure new configurations are loaded:

```bash
docker/sdk cli
vendor/bin/console cache:clear
```

Or for full rebuild:
```bash
docker/sdk up --build --assets --data
```

## 8. Verification

### Verify Installation

1. **Check Payment Methods in Checkout**
   - Go to your storefront
   - Add items to cart
   - Proceed to checkout
   - On the payment step, verify PSP payment methods appear

2. **Verify OMS States**
   - Place a test order using a PSP payment method
   - Go to Zed Back Office → **Sales → Orders**
   - Find your test order
   - Verify order items are in the correct initial OMS state (usually "new" or "processing")

3. **Verify OMS Commands Work**
   - In the order detail page, try triggering manual OMS commands (if applicable)
   - Example: Trigger "authorize" command
   - Verify state transitions work

4. **Check Logs**
   - **Local development**: Check logs dashboard at `http://spryker.local/logs`
   - **Remote environments**: Check CloudWatch logs in AWS console
   - Verify API calls are being made (if PSP integration is active)

### Test Payment Flow

1. **Sandbox Testing**
   - Ensure you're using sandbox/test API credentials
   - Use test card numbers provided by the PSP
   - Test successful payment flow
   - Test declined payment scenarios
   - Test error handling

2. **Webhook Testing** (if applicable)
   - Configure webhook URL in PSP dashboard: `https://yourdomain.com/your-psp/notification`
   - Trigger test webhook from PSP dashboard
   - Verify webhook is received and processed
   - Check that payment status updates correctly

## 9. Troubleshooting

### Payment Methods Not Appearing in Checkout

**Possible causes:**
- Payment methods not imported or not active
- Payment methods not assigned to current store
- Plugin not registered in `CheckoutPageDependencyProvider`
- Cache not cleared

**Solution:**
```bash
# Verify import
vendor/bin/console data:import payment-method
vendor/bin/console data:import payment-method-store

# Clear cache
vendor/bin/console cache:clear

# Verify in Zed: Administration → Payment → Payment Methods
```

### OMS State Machine Not Working

**Possible causes:**
- OMS process location not configured
- Payment method not assigned to OMS process
- OMS command/condition plugins not registered

**Solution:**
- Check `OmsConstants::PROCESS_LOCATION` in `config/Shared/config_default.php`
- Verify `SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING` includes your payment methods
- Verify `OmsConstants::ACTIVE_PROCESSES` includes your OMS process names

### API Errors / Authorization Failures

**Possible causes:**
- Invalid API credentials
- Using production credentials in sandbox mode (or vice versa)
- API credentials not configured in config_local.php

**Solution:**
- Verify API credentials in `config/Shared/config_local.php`
- Check PSP dashboard for credential validity
- Review API request/response logs:
  - **Local development**: `http://spryker.local/logs`
  - **Remote environments**: CloudWatch logs in AWS console
- Verify environment setting (sandbox vs production)

### Webhook Not Receiving Notifications

**Possible causes:**
- Webhook URL not configured in PSP dashboard
- Webhook signature validation failing
- Firewall blocking PSP requests
- Basic auth blocking PSP requests (staging/dev environments)

**Solution:**
- Verify webhook URL is configured: `https://yourdomain.com/your-psp/notification`
- Check webhook signature validation logic
- Review webhook logs:
  - **Local development**: `http://spryker.local/logs`
  - **Remote environments**: CloudWatch logs in AWS console
- If using basic auth, exclude PSP IP addresses: [Configure basic authentication](https://docs.spryker.com/docs/pbc/all/identity-access-management/latest/configure-basic-htaccess-authentication#exclude-ip-addresses-from-htaccess-authentication)
- Test webhook delivery from PSP dashboard

### Transfer Objects Not Found

**Error:** Class not found errors for transfer objects

**Solution:**
```bash
vendor/bin/console transfer:generate
```

## 10. Going to Production

Before deploying to production:

- [ ] **Update API credentials** to production keys in production config
- [ ] **Update webhook URL** in PSP dashboard to production domain
- [ ] **Test in staging** environment first
- [ ] **Run database migrations** if module adds tables
- [ ] **Import payment methods** in production database
- [ ] **Clear all caches** after deployment
- [ ] **Test payment flow** end-to-end in production
- [ ] **Monitor logs** for errors after deployment
- [ ] **Set up monitoring/alerts** for failed payments

## Support

For module-specific issues:
- Check the module's README
- Review module documentation
- Create a PR with the fix
- Create a Github Issue

## Module Information

Check the module's `composer.json` and README for:
- Supported Spryker versions
- Required dependencies
- Module-specific configuration
- Additional features
- Known limitations
