# Spryker Payment Module Template

**GitHub template for building payment service provider (PSP) integrations for Spryker Commerce OS**

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

---

## 🎯 What Is This?

A production-ready template for building PSP payment modules for Spryker. Clone it, rename it, implement your PSP-specific logic, and ship a complete payment integration.

---

## 👥 Who Is This For?

This repository serves **three distinct use cases** - choose your workflow below:

### ⚡ Direct Project Integration? (Fastest)

**You are:** A developer building a PSP integration for a single Spryker project

**Your situation:**
- Working directly in project codebase (`src/Pyz/` namespace)
- Don't need to publish as separate composer package
- Want fastest path to implementation

**Quick command:**
```bash
git clone https://github.com/spryker-community/payment-template.git /tmp/payment-template
cd /tmp/payment-template
php rename.php adyen --project-path=/path/to/your-spryker-project
```

**Documentation:** Follow [IMPLEMENTATION.md](IMPLEMENTATION.md) for PSP-specific implementation (assuming you are working in Pyz namespace).

---

### 🔧 Building a Reusable Module?

**You are:** A partner/agency developer creating a reusable payment module for multiple projects

**Your situation:**
- Building package in custom namespace (e.g., `Acme`, `MyCompany`)
- Will publish as composer package (public or private)
- Need proper versioning and distribution

**Quick command:**
```bash
# Use GitHub template or manual clone (see Quick Start below)
php rename.php adyen --namespace=Acme --in-place
```

**Documentation:** Follow [IMPLEMENTATION.md](IMPLEMENTATION.md) for PSP-specific implementation, then publish to Packagist or private repository.

---

### 📦 Installing a Finished Module?

**You are:** A project developer integrating an existing PSP module into your Spryker project

**Your situation:**
- Installing pre-built PSP module via composer
- Need to configure and register plugins

**Quick command:**
```bash
composer require your-org/adyen
```

**Documentation:** Follow [INTEGRATION.md](INTEGRATION.md) for installation, configuration, and plugin registration.

---

## ✨ What's Included

### Complete Module Structure
- Spryker Eco layer architecture (Shared, Client, Yves, Zed)
- Single module pattern (all logic in one place)
- Production-grade infrastructure code

### Payment Flow Support
- Authorize → Capture → Cancel flow
- Synchronous and asynchronous authorization
- Payment method filtering based on PSP availability
- OMS integration with state machines

### Two Payment Method Templates
- Credit Card payment form stub
- Invoice payment form stub
- Easy to customize or add more payment methods

### OMS State Machines
- `PaymentTemplateCreditCard01.xml` - Credit card payment flow
- `PaymentTemplateInvoice01.xml` - Invoice payment flow
- Includes states: new, processing, authorized, captured, failed, canceled

### Webhook Support
- Webhook endpoint infrastructure
- Payload logging to database
- Route provider for webhook URLs

### Data Import
- Pre-configured payment method CSV files
- Glossary translations (English, German)
- Store assignments
- Ready-to-use import configuration

### Developer Experience
- All TODO comments guide implementation
- Automated rename script for quick module setup
- Clear separation: infrastructure vs PSP-specific code

---

## 🚀 Quick Start

Choose your workflow based on your use case:

---

### Workflow 1️⃣: Direct Project Integration (Fastest - No Separate Repo)

For single-project integration in your `Pyz` namespace:

```bash
# Clone template to temporary location
git clone https://github.com/spryker-community/payment-template.git /tmp/payment-template
cd /tmp/payment-template

# Integrate into your project (auto-uses Pyz namespace)
php rename.php adyen --project-path=/path/to/your-spryker-project

# Files are copied to:
# /path/to/project/src/Pyz/Zed/Adyen/
# /path/to/project/src/Pyz/Yves/Adyen/
# /path/to/project/src/Pyz/Client/Adyen/
# /path/to/project/src/Pyz/Shared/Adyen/

# Clean up template directory
rm -rf /tmp/payment-template

# Go to your project and commit
cd /path/to/your-spryker-project
git add src/Pyz
git commit -m "Add Adyen payment integration"
```

**Using custom project namespace?**
```bash
php rename.php adyen --namespace=MyCompany --project-path=/path/to/project
```

**Next:** Follow [IMPLEMENTATION.md](IMPLEMENTATION.md) to implement PSP-specific logic.

---

### Workflow 2️⃣: Reusable Module (For Partners/Agencies)

For building a package to use across multiple projects:

#### Option A: GitHub Template (Recommended)

```bash
# 1. On GitHub: Click "Use this template" → "Create a new repository"
#    Name it after your PSP (e.g., "adyen", "stripe")

# 2. Clone your new repository
git clone https://github.com/your-org/adyen.git
cd adyen

# 3. Rename with your namespace (in-place)
php rename.php adyen --namespace=Acme --in-place

# Module updated:
# - Namespace: Acme
# - Package name: acme/adyen
# - Directory: Already named "adyen"

# 4. Commit and push
git add .
git commit -m "Rename from PaymentTemplate to Adyen"
git push
```

#### Option B: Manual Clone

```bash
# 1. Clone template
git clone https://github.com/spryker-community/payment-template.git
cd payment-template

# 2. Create module with custom namespace (creates new directory)
php rename.php adyen --namespace=Acme

# New directory created: ../acme/adyen/
# - Namespace: Acme
# - Package name: acme/adyen

# 3. Initialize repository
cd ../acme/adyen
git init
git add .
git commit -m "Initial commit: Adyen payment module"
```

**Next:** Follow [IMPLEMENTATION.md](IMPLEMENTATION.md) and publish to Packagist or private repository.

---

### Workflow 3️⃣: Installing Finished Module

```bash
# Install via composer
composer require your-org/adyen

# Or for private packages
composer config repositories.adyen vcs https://github.com/your-org/adyen
composer require your-org/adyen
```

**Next:** Follow [INTEGRATION.md](INTEGRATION.md) for plugin registration and configuration.

---

## 🏗️ Architecture Overview

```
SprykerEco/
├── Shared/
│   └── YourPsp/
│       ├── YourPspConfig.php           # Payment method keys, provider name
│       ├── YourPspConstants.php        # Configuration constants
│       └── Transfer/                   # Transfer object definitions
├── Client/
│   └── YourPsp/
│       └── Api/                        # PSP API communication layer
│           ├── Authorization/          # Authorization endpoint
│           ├── Capture/                # Capture endpoint
│           ├── Cancel/                 # Cancel endpoint
│           └── PaymentMethods/         # Available methods endpoint
├── Yves/
│   └── YourPsp/
│       ├── Form/                       # Payment forms (Credit Card, Invoice)
│       ├── Plugin/                     # Checkout workflow integration plugins
│       └── Controller/                 # Notification controllers
└── Zed/
    └── YourPsp/
        ├── Business/
        │   ├── Payment/                # Payment operations
        │   ├── Oms/                    # OMS command/condition handlers
        │   └── Notification/           # Webhook processing
        ├── Communication/
        │   ├── Plugin/                 # Checkout, Payment, OMS plugins
        │   └── Controller/             # Gateway controller
        └── Persistence/
            ├── Propel/Schema/          # Database schema
            ├── YourPspRepository.php
            └── YourPspEntityManager.php
```

### Key Principles

1. **TODO-Driven Development**: All PSP-specific code has TODO comments with implementation guidance
2. **Status Constant Consistency**: Status constants must match across OMS commands, conditions, and config
3. **Webhook as Source of Truth**: Payment state updates primarily through webhooks
4. **Security First**: Webhook signature validation, credential management, sensitive data masking

---

## 📋 Implementation Overview

**Note:** Implementation steps are the same regardless of workflow. Direct project integration (Pyz namespace) is faster since you skip packaging and versioning concerns.

### What You Need to Implement

1. **Configuration**
   - Define payment status constants
   - Add API credentials configuration

2. **Transfer Objects**
   - Add PSP-specific fields to transfer schemas

3. **Database Schema**
   - Add PSP-specific columns
   - Generate and run migrations

4. **API Client**
   - Implement request/response mappers for each endpoint
   - Add authentication headers
   - Implement error handling

5. **Business Logic**
   - Implement OMS command handlers (authorize, capture, cancel)
   - Implement OMS condition checkers
   - Implement webhook processing
   - Implement payment method filtering

6. **Forms**
   - Customize payment forms for your PSP
   - Add PSP-specific fields and validation

7. **Testing**
   - Write integration tests
   - Test with PSP sandbox

---

## 📚 Documentation

- **[IMPLEMENTATION.md](IMPLEMENTATION.md)** - Implementation guide for module developers
- **[INTEGRATION.md](INTEGRATION.md)** - Installation guide for project developers

---

## 🤝 Contributing

This is a community-maintained template. Contributions are welcome!

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

---

## 📄 License

MIT License - see [LICENSE](LICENSE) file for details

---

## 🆘 Support

### For Template Issues
- Open an issue: [GitHub Issues](https://github.com/spryker-community/payment-template/issues)
- Refer to Spryker documentation: [docs.spryker.com](https://docs.spryker.com)

### For PSP-Specific Issues
- Consult your payment provider's documentation
- Contact your payment provider's support team

---

## 🌟 Success Stories

Built a module using this template? Let us know by opening a PR to add your module to this list!

---

**Ready to start?** Choose your path:
- 🔧 [Building a module? → IMPLEMENTATION.md](IMPLEMENTATION.md)
- 📦 [Installing a module? → INTEGRATION.md](INTEGRATION.md)
