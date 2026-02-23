# Payment Service Provider Integration Implementation Guide

This document provides a comprehensive checklist and implementation guide for **module developers** building a PSP integration module using the PaymentTemplate.

> **Are you a project developer looking to install a finished PSP module?** See [INTEGRATION.md](INTEGRATION.md) instead.

## Overview

This module is a **GitHub template** designed to accelerate PSP integration development. The goal is to enable fast integration. All placeholder code and TODO comments must be replaced with your PSP-specific implementation.

### Two Guides, Two Audiences

- **IMPLEMENTATION.md (this file)** - For module developers creating/customizing the PSP integration module
- **[INTEGRATION.md](INTEGRATION.md)** - For project developers installing the finished module into their Spryker project

## Quick Start: Automated Module Renaming

Before starting your implementation, it is **strongly recommended** to rename the module from "PaymentTemplate" to your actual PSP name. This module includes an automated rename script that handles all file renames, namespace updates, and case conversions.

### Workflow 1: Using GitHub Template (Recommended)

This is the recommended workflow when using this repository as a GitHub template.

**Step 1: Create repository from template**
1. Go to https://github.com/spryker-community/payment-template
2. Click "Use this template" → "Create a new repository"
3. Name your repository (e.g., "adyen" or "stripe")
4. Clone your new repository:
   ```bash
   git clone https://github.com/your-org/adyen.git
   cd adyen
   ```

**Step 2: Run rename script in-place**
```bash
# Test first with dry-run to see what will change
php rename.php adyen --in-place --dry-run

# If satisfied with preview, run the actual rename
php rename.php adyen --in-place

# Or run interactively (will prompt for PSP name)
php rename.php --in-place
```

**Step 3: Commit and push**
```bash
git add .
git commit -m "Rename from PaymentTemplate to Adyen"
git push
```

**Step 4: Install in your Spryker project**
```bash
# Add to your project's composer.json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/your-org/adyen.git"
    }
  ],
  "require": {
    "your-org/adyen": "dev-main"
  }
}

# Install via composer
composer update your-org/adyen
```

### Workflow 2: Manual Clone and Copy

If you prefer to keep the original PaymentTemplate and create a separate copy:

**Step 1: Clone to temporary location**
```bash
cd /tmp
git clone https://github.com/spryker-community/payment-template.git
cd payment-template
```

**Step 2: Run rename script (copy mode)**
```bash
# Without --in-place flag, creates a new directory
php rename.php adyen --dry-run  # Preview first
php rename.php adyen            # Creates ../adyen directory
```

**Step 3: Initialize new repository**
```bash
cd ../adyen
git init
git add .
git commit -m "Initial commit: Adyen payment module from PaymentTemplate"
git remote add origin https://github.com/your-org/adyen.git
git push -u origin main
```

### Workflow 3: Direct Project Integration

For single-project implementations where you do not need a separate package:

**Step 1: Clone to temporary location**
```bash
cd /tmp
git clone https://github.com/spryker-community/payment-template.git
cd payment-template
```

**Step 2: Copy to project namespace**
```bash
# Copy files directly to your project's Pyz namespace
php rename.php adyen --project-path=/path/to/your/spryker/project

# Or with custom project namespace
php rename.php adyen --project-path=/path/to/your/spryker/project --namespace=Pyz
```

**Step 3: Commit to your project**
```bash
cd /path/to/your/spryker/project
git add src/Pyz/
git commit -m "Add Adyen payment integration"
git push
```

### What the Rename Script Does

The script automatically handles all renaming for:
- **Files and directories**: `PaymentTemplateFacade.php` → `AdyenFacade.php`
- **PHP namespaces**: `SprykerEco\Zed\PaymentTemplate` → `SprykerEco\Zed\Adyen` (or custom namespace if `--namespace` is used)
- **Class names**: `PaymentTemplateFacade` → `AdyenFacade`
- **Variables**: `$paymentTemplateTransfer` → `$adyenTransfer`
- **Routes**: `payment-template-redirect` → `adyen-redirect`
- **Database tables**: `spy_payment_template` → `spy_adyen`
- **Constants**: `PAYMENT_TEMPLATE` → `ADYEN`
- **composer.json**: Package name and description (includes namespace in vendor name when custom namespace is used)
- **Directory structure**: Copies to project layers when `--project-path` is used

### Script Options

- `--in-place` - Rename files in current directory (for GitHub template workflow)
- `--dry-run` - Preview changes without modifying anything
- `--namespace=<namespace>` - Use custom namespace instead of default SprykerEco (for example, `--namespace=Acme`)
- `--project-path=<path>` - Copy files directly to project namespace for direct project integration (for example, `--project-path=/path/to/spryker/project`)

**Flag combinations:**
- `php rename.php adyen --in-place --dry-run` - Preview in-place rename
- `php rename.php adyen --namespace=Acme --in-place` - Rename with custom namespace
- `php rename.php adyen --project-path=/path/to/project` - Direct project integration
- `php rename.php adyen --project-path=/path/to/project --namespace=Pyz` - Direct project integration with custom namespace

### Important Notes

- The rename script accepts PSP names in **kebab-case** format only (e.g., `adyen`, `pay-pal`, `stripe-connect`)
- All case conversions (PascalCase, camelCase, snake_case, SCREAMING_SNAKE_CASE) are handled automatically
- **Default namespace**: SprykerEco (for reusable modules). Use `--namespace` to specify custom namespace:
  - For partner/agency modules: `--namespace=Acme` or `--namespace=MyCompany`
  - For direct project integration: Defaults to `Pyz` if `--project-path` is used without `--namespace`
- After renaming, update this IMPLEMENTATION.md file to replace "PaymentTemplate" references with your PSP name
- The script requires Symfony Finder component (included in Spryker projects by default)

### Alternative: Manual Renaming

If you prefer not to use the script, you can manually rename classes, namespaces, and files. However, this is error-prone and time-consuming. The script ensures consistency across all files in the module and handles all case conversions automatically.

## Implementation Checklist

Use this checklist to track your implementation progress. All items marked with ⚠️ are **CRITICAL** and must be completed before production deployment.

### 1. Configuration & Setup

- [ ] ⚠️ **Define PSP-specific status constants** in `PaymentTemplateConfig`
  - Location: `src/SprykerEco/Zed/PaymentTemplate/PaymentTemplateConfig.php`
  - Define constants for all payment states your PSP supports
  - Examples: `PAYMENT_STATUS_AUTHORIZED`, `PAYMENT_STATUS_CAPTURED`, `PAYMENT_STATUS_CANCELLED`
  - These constants are referenced throughout the codebase in status update methods

- [ ] ⚠️ **Configure PSP API credentials** in `PaymentTemplateConstants`
  - Location: `src/SprykerEco/Shared/PaymentTemplate/PaymentTemplateConstants.php`
  - Add constants for: API keys, secret keys, webhook secrets, environment URLs
  - Store sensitive values in `config/Shared/config_local.php` (never commit to repository)

- [ ] ⚠️ **Update payment provider name**
  - Location: `src/SprykerEco/Shared/PaymentTemplate/PaymentTemplateConfig.php`
  - Change `PAYMENT_PROVIDER_NAME` constant from 'paymentTemplate' to your PSP name
  - This name is used throughout checkout and OMS processes

- [ ] **Configure API endpoint URLs**
  - Location: `src/SprykerEco/Client/PaymentTemplate/PaymentTemplateConfig.php`
  - Implement `getAuthorizationUrl()`, `getCaptureUrl()`, `getCancelUrl()`, `getPaymentMethodsUrl()`
  - Use environment-based configuration for production vs sandbox URLs

### 2. Transfer Object Schema

- [ ] ⚠️ **Define PSP-specific transfer object fields**
  - Location: `src/SprykerEco/Shared/PaymentTemplate/Transfer/payment_template.transfer.xml`
  - Add fields required by your PSP to `PaymentTemplateTransfer`
  - Common fields: amount, currency, providerReference, paymentMethodToken, status
  - Run `vendor/bin/console transfer:generate` after changes
  - Documentation: [Create, use, and extend the transfer objects](https://docs.spryker.com/docs/dg/dev/backend-development/data-manipulation/data-ingestion/structural-preparations/create-use-and-extend-the-transfer-objects)

- [ ] **Define payment method specific transfers**
  - Update `PaymentTemplateCreditCardTransfer`, `PaymentTemplateInvoiceTransfer` with PSP-required fields
  - Add additional payment method transfers if needed

- [ ] **Define API request/response transfers**
  - Customize `PaymentTemplateAuthorizeRequestTransfer`, `PaymentTemplateAuthorizeResponseTransfer`
  - Customize `PaymentTemplateCaptureRequestTransfer`, `PaymentTemplateCaptureResponseTransfer`
  - Customize `PaymentTemplateCancelRequestTransfer`, `PaymentTemplateCancelResponseTransfer`
  - Add fields for error handling in `PaymentTemplateApiErrorResponseTransfer`

### 3. Database Schema

- [ ] ⚠️ **Update database schema** for PSP-specific fields
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Persistence/Propel/Schema/spy_payment_template.schema.xml`
  - Add columns for: provider reference, status, payment method token, timestamps
  - Ensure foreign key relationships are correct
  - Documentation: [Database schema definition](https://docs.spryker.com/docs/dg/dev/backend-development/zed/persistence-layer/database-schema-definition.html)

- [ ] **Run database migrations**
  - Generate Propel models: `vendor/bin/console propel:model:build`
  - Create migration: `vendor/bin/console propel:migration:generate`
  - Run migration: `vendor/bin/console propel:migration:migrate`

- [ ] ⚠️ **Implement status field usage** in `PaymentTemplateEntityManager`
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Persistence/PaymentTemplateEntityManager.php`
  - Uncomment and implement status update logic in `updatePaymentStatus()` method
  - Map status constants to database field

- [ ] ⚠️ **Implement provider reference filtering** in `PaymentTemplateRepository`
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Persistence/PaymentTemplateRepository.php`
  - Implement `findPaymentTemplateByProviderReference()` method
  - Add proper filter by provider reference field

### 4. API Client Implementation

- [ ] ⚠️ **Implement authorization API request mapper**
  - Location: `src/SprykerEco/Client/PaymentTemplate/Api/Authorization/AuthorizationMapper.php`
  - Implement `mapRequest()`: Convert `PaymentTemplateAuthorizeRequestTransfer` to PSP API format
  - Implement `mapResponse()`: Parse successful PSP response to `PaymentTemplateAuthorizeResponseTransfer`
  - Implement `mapErrorResponse()`: Parse error response to `PaymentTemplateApiErrorResponseTransfer`

- [ ] ⚠️ **Implement capture API request mapper**
  - Location: `src/SprykerEco/Client/PaymentTemplate/Api/Capture/CaptureMapper.php`
  - Implement all three mapper methods (request, response, error)

- [ ] ⚠️ **Implement cancel API request mapper**
  - Location: `src/SprykerEco/Client/PaymentTemplate/Api/Cancel/CancelMapper.php`
  - Implement all three mapper methods (request, response, error)

- [ ] ⚠️ **Implement payment methods API request mapper**
  - Location: `src/SprykerEco/Client/PaymentTemplate/Api/PaymentMethods/PaymentMethodsMapper.php`
  - Implement all three mapper methods (request, response, error)

- [ ] **Add authentication headers** to API requests
  - Location: Various `*ApiRequest.php` files in `src/SprykerEco/Client/PaymentTemplate/Api/*/`
  - Implement PSP-specific authentication (API keys, bearer tokens, signatures)
  - Update `sendRequest()` methods to include authentication headers

- [ ] **Implement API request logging**
  - Location: `src/SprykerEco/Client/PaymentTemplate/Api/ApiLogger.php`
  - Customize logging implementation for debugging and audit trails
  - Ensure sensitive data (card numbers, API keys) is masked

### 5. Business Layer - Payment Operations

#### Payment Saver
- [ ] **Customize payment data persistence**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Payment/PaymentSaver.php`
  - Update `createPaymentTemplateTransfer()` to map additional PSP-specific fields from checkout

#### Payment Authorizer
- [ ] ⚠️ **Implement authorization request builder**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Payment/PaymentAuthorizer.php`
  - Method: `buildAuthorizeRequest()`
  - Map data from `PaymentTemplateTransfer` to `PaymentTemplateAuthorizeRequestTransfer`
  - Add additional method parameters if needed (QuoteTransfer, CheckoutResponseTransfer)

- [ ] ⚠️ **Implement authorization error handler**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Payment/PaymentAuthorizer.php`
  - Method: `handleAuthorizationError()`
  - Extract error details from `PaymentTemplateApiErrorResponseTransfer`
  - Update payment status with appropriate failure constant
  - Optionally extract and save provider reference

- [ ] ⚠️ **Implement post-authorization update**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Payment/PaymentAuthorizer.php`
  - Method: `updatePaymentAfterAuthorization()`
  - Extract success data (provider reference, status) from response
  - Update payment status with authorized constant

#### OMS Command Handler
- [ ] ⚠️ **Implement authorize command request builder**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Oms/Command/OmsCommandHandler.php`
  - Method: `buildAuthorizeRequest()`
  - Map data from `PaymentTemplateTransfer` to authorization request
  - Add additional parameters if PSP requires data not in PaymentTemplateTransfer

- [ ] ⚠️ **Implement authorize command error handler**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Oms/Command/OmsCommandHandler.php`
  - Method: `handleAuthorizeError()`
  - Define and use appropriate status constant
  - Extract provider reference from error response if available

- [ ] ⚠️ **Implement authorize command success handler**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Oms/Command/OmsCommandHandler.php`
  - Method: `updatePaymentAfterAuthorize()`
  - Replace empty string with proper status constant
  - Extract and save provider reference from response

- [ ] ⚠️ **Implement capture command request builder**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Oms/Command/OmsCommandHandler.php`
  - Method: `buildCaptureRequest()`
  - Include provider reference from authorization
  - Add amount and currency if partial capture is supported

- [ ] ⚠️ **Implement capture command error handler**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Oms/Command/OmsCommandHandler.php`
  - Method: `handleCaptureError()`
  - Define and use appropriate status constant

- [ ] ⚠️ **Implement capture command success handler**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Oms/Command/OmsCommandHandler.php`
  - Method: `updatePaymentAfterCapture()`
  - Replace empty string with proper status constant
  - Extract and save provider reference from response

- [ ] ⚠️ **Implement cancel command request builder**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Oms/Command/OmsCommandHandler.php`
  - Method: `buildCancelRequest()`
  - Include provider reference from authorization
  - Add cancellation reason if required by PSP

- [ ] ⚠️ **Implement cancel command error handler**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Oms/Command/OmsCommandHandler.php`
  - Method: `handleCancelError()`
  - Define and use appropriate status constant

- [ ] ⚠️ **Implement cancel command success handler**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Oms/Command/OmsCommandHandler.php`
  - Method: `updatePaymentAfterCancel()`
  - Replace empty string with proper status constant
  - Extract and save provider reference from response

#### OMS Condition Checker
- [ ] ⚠️ **Implement payment authorized condition**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Oms/Condition/OmsConditionChecker.php`
  - Method: `isPaymentAuthorized()`
  - Replace `return true;` with actual status check against `PAYMENT_STATUS_AUTHORIZED`
  - Must align with status set in `OmsCommandHandler::updatePaymentAfterAuthorize()`

- [ ] ⚠️ **Implement payment authorization failed condition**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Oms/Condition/OmsConditionChecker.php`
  - Method: `isPaymentAuthorizationFailed()`
  - Replace `return false;` with actual status check against `PAYMENT_STATUS_AUTHORIZATION_FAILED`
  - Must align with status set in `OmsCommandHandler::handleAuthorizeError()`

- [ ] ⚠️ **Implement payment captured condition**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Oms/Condition/OmsConditionChecker.php`
  - Method: `isPaymentCaptured()`
  - Replace `return true;` with actual status check against `PAYMENT_STATUS_CAPTURED`
  - Must align with status set in `OmsCommandHandler::updatePaymentAfterCapture()`

- [ ] **Add additional conditions** if needed
  - Examples: `isPaymentRefunded()`, `isPaymentExpired()`, `isPaymentCancelled()`
  - Add corresponding methods to `OmsConditionCheckerInterface`

#### Payment Method Filter
- [ ] ⚠️ **Implement payment method availability check**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Payment/PaymentMethodFilter.php`
  - Method: `isPaymentMethodAllowed()`
  - Replace `return true;` with actual check using PSP response
  - Typically: `return in_array($paymentMethodName, $paymentTemplateAvailablePaymentMethods, true);`

- [ ] ⚠️ **Implement payment methods request builder**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Payment/PaymentMethodFilter.php`
  - Method: `buildPaymentMethodsRequest()`
  - Map data from QuoteTransfer (amount, currency, billing address, etc.)

- [ ] ⚠️ **Implement available payment methods extraction**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Payment/PaymentMethodFilter.php`
  - Method: `getPaymentTemplateAvailablePaymentMethods()`
  - Replace `return [];` with actual extraction from PSP response
  - Return array of payment method identifiers that match configured method names

#### Notification Processor
- [ ] ⚠️ **Implement webhook processing logic**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Business/Notification/NotificationProcessor.php`
  - Method: `processWebhook()`
  - Implement webhook signature validation for security
  - Parse webhook payload to identify event type
  - Find related payment using `PaymentReader::findPaymentByProviderReference()`
  - Update payment status based on webhook event
  - Optionally trigger OMS transitions
  - Set success/error response in `PaymentTemplateWebhookProcessResponseTransfer`

### 6. Presentation Layer - Forms

Documentation: [Forms](https://docs.spryker.com/docs/dg/dev/backend-development/forms/forms.html) | [Checkout Process](https://docs.spryker.com/docs/pbc/all/cart-and-checkout/latest/base-shop/extend-and-customize/checkout-process-review-and-implementation)

- [ ] **Customize credit card payment form**
  - Location: `src/SprykerEco/Yves/PaymentTemplate/Form/PaymentTemplateCreditCardSubForm.php`
  - Add PSP-specific fields (e.g., JS library keys)
  - Implement client-side validation
  - Integrate PSP JavaScript SDK if required

- [ ] **Customize invoice payment form**
  - Location: `src/SprykerEco/Yves/PaymentTemplate/Form/PaymentTemplateInvoiceSubForm.php`
  - Add invoice-specific fields required by PSP

- [ ] **Update form data providers**
  - Location: `src/SprykerEco/Yves/PaymentTemplate/Form/DataProvider/`
  - Customize `getData()` and `getOptions()` methods if needed
  - Add PSP-specific form options

- [ ] **Create form templates**
  - Location: `src/SprykerEco/Yves/PaymentTemplate/Theme/default/views/`
  - Create Twig templates for each payment method
  - Integrate PSP hosted payment fields or SDK if applicable

### 7. OMS Process Configuration

The module provides two OMS process template files (one per payment method) that implement the authorize-capture-cancel flow:
- `config/Zed/oms/PaymentTemplateCreditCard01.xml` - Credit card payment flow
- `config/Zed/oms/PaymentTemplateInvoice01.xml` - Invoice payment flow

**Note:** Project integration steps (registering OMS location, assigning processes to payment methods) are covered in INTEGRATION.md.

Documentation: [Order Management System](https://docs.spryker.com/docs/pbc/all/order-management-system/latest/base-shop/order-management-feature-overview/order-management-feature-overview)

- [ ] **Review and understand default OMS process flow**
  - Review the state transitions in the OMS files
  - **Default flow provided**:
    1. `new` → `processing` (automatic transition)
    2. `processing` → `authorization pending` (manual/on-enter authorize command)
    3. `authorization pending` → `authorized` (condition: IsAuthorized)
    4. `authorization pending` → `authorization failed` (condition: IsAuthorizationFailed)
    5. `authorized` → `capture pending` (manual/on-enter capture command)
    6. `capture pending` → `captured` (condition: IsCaptured)
    7. `authorized` → `canceled` (manual cancel command)

- [ ] **Customize OMS process if needed** (only if using Option 2)
  - Add states for PSP-specific statuses (e.g., `expired`, `refunded`, `partially captured`)
  - Add timeout events for authorization expiry
  - Add refund flow if needed
  - Adjust `onEnter` vs `manual` event triggers based on your integration approach
  - Update state display names in `glossary.csv` if you add custom states

- [ ] **Verify OMS command registrations**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/PaymentTemplateDependencyProvider.php`
  - Method: `provideCommunicationLayerDependencies()` → command plugin section
  - **Default commands registered**:
    - `PaymentTemplate/Authorize` → `OmsAuthorizeCommandPlugin`
    - `PaymentTemplate/Capture` → `OmsCaptureCommandPlugin`
    - `PaymentTemplate/Cancel` → `OmsCancelCommandPlugin`
  - Add additional command plugins if you added custom commands to OMS

- [ ] **Verify OMS condition registrations**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/PaymentTemplateDependencyProvider.php`
  - Method: `provideCommunicationLayerDependencies()` → condition plugin section
  - **Default conditions registered**:
    - `PaymentTemplate/IsAuthorized` → `IsAuthorizedConditionPlugin`
    - `PaymentTemplate/IsAuthorizationFailed` → `IsAuthorizationFailedConditionPlugin`
    - `PaymentTemplate/IsCaptured` → `IsCapturedConditionPlugin`
  - Add additional condition plugins if you added custom conditions to OMS

- [ ] **Test OMS state machine transitions**
  - Create a test order using your payment method
  - Verify order items appear in correct initial state
  - Trigger manual events from Zed Back Office (Order Management)
  - Verify state transitions work correctly
  - Verify conditions evaluate correctly based on payment status

### 9. Webhook Endpoint

- [ ] ⚠️ **Implement webhook controller**
  - Location: `src/SprykerEco/Zed/PaymentTemplate/Communication/Controller/WebhookController.php`
  - Implement `indexAction()` to receive webhook POST requests
  - Parse raw request body to `PaymentTemplateWebhookPayloadTransfer`
  - Call `PaymentTemplateFacade::processWebhook()`
  - Return appropriate HTTP response (typically, 200 OK for success)

- [ ] **Configure webhook URL in PSP dashboard**
  - URL format: `https://yourdomain.com/payment-template/webhook`
  - Configure webhook events to subscribe to
  - Configure webhook secret for signature validation

- [ ] **Test webhook delivery**
  - Use PSP test mode to trigger webhook events
  - Verify webhook signature validation
  - Verify payment status updates correctly

### 10. Testing

- [ ] **Write unit tests** for business logic
  - Test mappers with various PSP responses
  - Test status update logic
  - Test condition checkers
  - Framework: Codeception
  - Run: `docker/sdk testing codecept run -c vendor/spryker-community/payment-template`
  - Documentation: [Testing best practices](https://docs.spryker.com/docs/dg/dev/guidelines/testing-guidelines/testing-best-practices/testing-best-practices)

- [ ] **Write integration tests** for API client
  - Test API requests with PSP sandbox environment
  - Test error handling for various error scenarios

- [ ] **Test checkout flow end-to-end**
  - Test successful payment flow
  - Test failed payment scenarios
  - Test declined payment scenarios
  - Verify OMS transitions correctly

- [ ] **Test webhook processing**
  - Test webhook signature validation
  - Test status updates from webhooks
  - Test duplicate webhook handling

- [ ] **Test payment method filtering**
  - Test method availability based on cart conditions
  - Test PSP unavailability fallback

### 11. Security

- [ ] ⚠️ **Validate webhook signatures**
  - Implement HMAC signature validation in `NotificationProcessor`
  - Use webhook secret from configuration
  - Reject requests with invalid signatures

- [ ] ⚠️ **Secure API credentials**
  - Never commit credentials to version control
  - Store in `config/Shared/config_local.php` (gitignored)
  - Use environment variables for production deployment

- [ ] **Mask sensitive data in logs**
  - Never log full card numbers
  - Never log API keys or secrets
  - Mask or tokenize sensitive fields

### 12. Error Handling & Logging

- [ ] **Implement comprehensive error logging**
  - Log all PSP API errors with context
  - Log webhook processing errors
  - Use structured logging for easy filtering

- [ ] **Implement user-friendly error messages**
  - Map PSP error codes to customer-facing messages
  - Display actionable messages in checkout

### 13. Documentation

- [ ] **Document PSP-specific configuration**
  - Document required config values
  - Document API credential setup
  - Document webhook configuration

- [ ] **Document custom transfer fields**
  - Document purpose of each custom field
  - Document field formats and validations

- [ ] **Document deployment steps**
  - Document database migration steps
  - Document configuration deployment
  - Document webhook URL registration

### 14. Deployment Checklist

- [ ] ⚠️ **Configure production API credentials**
  - Switch from sandbox to production API keys
  - Update API endpoint URLs

- [ ] ⚠️ **Run database migrations in production**
  - Test migration in staging first
  - Plan for zero-downtime deployment if needed

- [ ] ⚠️ **Register production webhook URL**
  - Update webhook URL in PSP dashboard
  - Test webhook delivery in production

- [ ] **Enable production logging**
  - Configure appropriate log levels
  - Set up log aggregation and monitoring

- [ ] **Verify OMS process is correct**
  - Test state transitions in production-like environment
  - Verify email notifications work

## Critical Implementation Notes

### Status Constant Consistency

**CRITICAL**: Status constants must be consistent across three locations:

1. **OmsCommandHandler** - Sets status in `updatePaymentAfter*()` and `handle*Error()` methods
2. **OmsConditionChecker** - Checks status in `isPayment*()` methods
3. **PaymentTemplateConfig** - Defines status constants

**Example Flow:**
```php
// OmsCommandHandler sets status
$this->entityManager->updatePaymentStatus(
    $paymentTemplateTransfer->getIdPaymentTemplateOrFail(),
    PaymentTemplateConfig::PAYMENT_STATUS_AUTHORIZED, // Status set here
);

// OmsConditionChecker checks same status
return $paymentTemplateTransfer->getStatus() === $this->config::PAYMENT_STATUS_AUTHORIZED; // Must match
```

### Database Schema Must Match Code

Ensure your database schema has fields for:
- `status` (string) - For payment status tracking
- `provider_reference` (string) - For PSP transaction ID
- Any PSP-specific fields added to transfers

The `updatePaymentStatus()` method in `PaymentTemplateEntityManager` **will not work** until you uncomment and implement the status field persistence (lines 48-53).

### Webhook Security

**NEVER** process webhooks without signature validation in production. Attackers can forge webhook requests to manipulate payment statuses.

### Testing with PSP Sandbox

Most PSPs provide sandbox/test environments. Always:
1. Test with sandbox API before production
2. Use test card numbers provided by PSP
3. Test failure scenarios (declined cards, network errors)
4. Test webhook delivery and replay

## Getting Help

- Check PSP API documentation for endpoint formats and authentication
- Review [Spryker Developer Documentation](https://docs.spryker.com/docs/dg/dev/development-getting-started-guide) for:
  - [Checkout integration](https://docs.spryker.com/docs/pbc/all/cart-and-checkout/latest/base-shop/extend-and-customize/checkout-process-review-and-implementation)
  - [OMS integration](https://docs.spryker.com/docs/pbc/all/order-management-system/latest/base-shop/order-management-feature-overview/order-management-feature-overview)
  - [Module development](https://docs.spryker.com/docs/dg/dev/backend-development/extend-spryker/create-modules)
- Check existing Spryker Eco payment integrations for reference patterns
- Review TODO comments in code - they provide specific implementation guidance

## Success Criteria

Your implementation is complete when:
- ✅ All TODO comments are resolved
- ✅ All placeholder return values are replaced
- ✅ All tests pass
- ✅ Checkout flow works end-to-end in sandbox environment
- ✅ Webhooks process correctly and update payment status
- ✅ OMS state machine transitions correctly based on payment status
- ✅ Error scenarios are handled gracefully with user-friendly messages
- ✅ All sensitive data is properly secured and masked in logs
- ✅ Documentation is complete for deployment and configuration

## Common Pitfalls

1. **Forgetting to generate transfers** after modifying `.transfer.xml` files
2. **Hardcoded return values** in condition checkers blocking OMS flow
3. **Empty status strings** in OmsCommandHandler causing unclear payment states
4. **Missing provider reference** preventing webhook matching to payments
5. **Unvalidated webhooks** allowing security vulnerabilities
6. **Logged sensitive data** (card numbers, API keys) causing PCI compliance issues
7. **Mismatched status constants** between command handlers and condition checkers
8. **Missing database migrations** causing persistence errors
9. **Sandbox credentials in production** causing failed live payments
10. **Untested error scenarios** causing poor user experience

---

This guide assumes familiarity with:
- [Spryker Commerce OS architecture](https://docs.spryker.com/docs/dg/dev/architecture/architecture.html)
- PSP API documentation (from your payment provider)
- [OMS concepts](https://docs.spryker.com/docs/pbc/all/order-management-system/latest/base-shop/order-management-feature-overview/order-management-feature-overview)
