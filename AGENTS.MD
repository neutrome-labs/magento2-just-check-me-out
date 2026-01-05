# Agent Guidelines for magento2-just-check-me-out

> **⚠️ IMPORTANT**: After making code changes, always review and update this file if your changes affect the documented structure, key files, configuration, architecture, or important notes. Keep this file in sync with the codebase.

## Project Overview

This is a **Magento 2 module** that provides an alternative checkout experience using SSR GraphQL for initial data hydration and AlpineJS for frontend interactivity. The module is designed to replace the default Magento checkout with a lighter, component-based approach.

### Core Principles

1. Main blocks use Magento 2 layout XML
2. Small components (buttons, inputs) use headless components
3. AlpineJS powers frontend interactivity
4. SSR GraphQL provides data on first render and supports client-side refresh
5. No magic — everything is explicitly called from endpoints

## Directory Structure

```
src/JustCheckMeOut/
├── Api/                    # Service contracts (interfaces)
├── Block/                  # Block classes
├── Controller/             # Frontend controllers
│   └── Index/              # Main checkout controller
├── etc/                    # Module configuration
│   ├── adminhtml/          # Admin panel config
│   └── frontend/           # Frontend routes
├── Model/                  # Business logic implementations
│   ├── Config/             # Configuration models
│   └── Resolver/           # GraphQL resolvers
├── Plugin/                 # Interceptor plugins
├── Service/                # Service layer classes
├── view/frontend/          # Frontend assets
│   ├── layout/             # Layout XML files
│   ├── templates/          # PHTML templates
│   │   ├── api/            # API script templates
│   │   ├── component/      # UI component templates
│   │   │   └── headless/   # Headless component templates
│   │   └── page/           # Page-level templates
│   └── web/                # Static assets (CSS, JS)
└── ViewModel/              # View models for templates

src/JustCheckMeOutWithStripe/
├── etc/                    # Stripe integration DI config
└── view/                   # Stripe-specific templates
```

## Key Files

| File | Purpose |
|------|---------|
| `Block/Stateful.php` | Main block exposing ViewModels and ConfigManager to templates |
| `Controller/Index/Index.php` | Checkout page controller with cart validation |
| `Plugin/CheckoutControllerPlugin.php` | Intercepts default checkout to forward to custom checkout |
| `Service/ConfigManager.php` | Admin config accessor implementing `ArgumentInterface` |
| `Service/AdditionalViewRenderer.php` | Renders additional views for payment/shipping methods |
| `Service/PaymentMethodAdditionalViewRegistry.php` | Registry for payment method additional views |
| `Service/ShippingMethodAdditionalViewRegistry.php` | Registry for shipping method additional views |
| `ViewModel/QuoteViewModel.php` | Quote and masked quote ID accessor |
| `ViewModel/HeadlessComponentRenderer.php` | Wrapper for headless component rendering |
| `ViewModel/AddressConfigProvider.php` | Exposes Magento address field configs (street lines, optional postcode, required region, etc.) |
| `view/frontend/templates/api.phtml` | JavaScript API (`window.justCheckMeOutApiV1`) |
| `view/frontend/layout/justcheckmeout_index_index.xml` | Main checkout page layout |

## Coding Standards

- **PHP**: Follow Magento 2 coding standards (PSR-12 based), `declare(strict_types=1)` required
- **Namespace**: `NeutromeLabs\JustCheckMeOut\`
- **Module Name**: `NeutromeLabs_JustCheckMeOut`
- **Config Path**: `neutromelabs_justcheckmeout/`

## Architecture Decisions

### SSR GraphQL Integration
The module depends on `NeutromeLabs_SsrGraphql` to render GraphQL data server-side. This eliminates loading states on initial page load while maintaining the ability to refresh data client-side.

### Headless Components
UI components (inputs, buttons, selects) are rendered via `NeutromeLabs_HeadlessComponents`. Templates live in `view/frontend/templates/component/headless/` and are accessed through `HeadlessComponentRenderer` ViewModel.

### AlpineJS Frontend
All interactivity uses AlpineJS. Component state is managed via:
- `Alpine.store()` for global state
- `x-data` for component-local state
- Event bus (`justCheckMeOutApiV1.bus`) for cross-component communication

### Additional View Pattern
Payment and shipping methods can have additional HTML injected via the `AdditionalViewInterface` system:
- Configure via DI using virtual types
- Supports three kinds: `plain_html`, `block`, `render`
- GraphQL resolvers expose `justcheckmeout_view_html` field

### Plugin-based Checkout Replacement
When `replace_default` config is enabled, `CheckoutControllerPlugin` forwards the default checkout controller to the custom checkout route.

## Configuration

| Path | Type | Description |
|------|------|-------------|
| `neutromelabs_justcheckmeout/general/enable` | bool | Enable the checkout module |
| `neutromelabs_justcheckmeout/general/replace_default` | bool | Replace default Magento checkout |
| `neutromelabs_justcheckmeout/general/ssr` | bool | Enable SSR mode (disables block caching) |
| `neutromelabs_justcheckmeout/general/load_alpine` | bool | Load AlpineJS from CDN |

## JavaScript API

The module exposes `window.justCheckMeOutApiV1` with:

```javascript
justCheckMeOutApiV1.globals.cartId()                    // Get masked cart ID
justCheckMeOutApiV1.globals.registerPlaceOrderGuard()   // Add pre-order validation
justCheckMeOutApiV1.globals.registerPlaceOrderHook()    // Add payment-specific handler
justCheckMeOutApiV1.bus.on(event, callback)             // Subscribe to events
justCheckMeOutApiV1.bus.fire(event, payload, abort)     // Emit events
justCheckMeOutApiV1.component.*                         // Component factories
justCheckMeOutApiV1.fn.*                                // Utility functions
```

## Common Tasks

### Adding a Payment Method Additional View
```xml
<!-- di.xml -->
<virtualType name="myPaymentAdditionalView" type="NeutromeLabs\JustCheckMeOut\Model\AdditionalView">
    <arguments>
        <argument name="kind" xsi:type="string">block</argument>
        <argument name="argument" xsi:type="array">
            <item name="template" xsi:type="string">Vendor_Module::payment/my-view.phtml</item>
        </argument>
    </arguments>
</virtualType>
<type name="NeutromeLabs\JustCheckMeOut\Service\PaymentMethodAdditionalViewRegistry">
    <arguments>
        <argument name="items" xsi:type="array">
            <item name="my_payment_code" xsi:type="object">myPaymentAdditionalView</item>
        </argument>
    </arguments>
</type>
```

### Creating a New Checkout Component
1. Create template in `view/frontend/templates/component/`
2. Add block to layout XML under `checkout.main`
3. Use `$block->headlessComponentRenderer->render()` for sub-components
4. Define AlpineJS component via `x-data` or register in `api.phtml`

### Extending the JavaScript API
Add script blocks to `justcheckmeout.api.after` container in layout XML.

## Dependencies

- `neutromelabs/magento2-ssr-graphql` — SSR GraphQL resolution
- `neutromelabs/magento2-headless-components` — Template rendering
- `magento/framework`
- `magento/module-checkout`

## Sub-modules

- **JustCheckMeOutWithStripe**: Stripe payment integration extending the base checkout

## Important Notes

1. **Block caching** is disabled when SSR mode is enabled (see `Stateful::getCacheLifetime()`)
2. **Masked quote ID** is auto-generated if not present (see `QuoteViewModel::getMaskedQuoteId()`)
3. **Place order flow** uses guards (validation) and hooks (payment-specific handlers)
4. **Event bus** callbacks are awaited sequentially, supporting async operations
5. **Headless component templates** are prefixed with `component/headless/` automatically by `HeadlessComponentRenderer`
6. **Script companion templates** (for AlpineJS) are rendered to `justcheckmeout.api.after` container
7. **Address form fields** respect Magento configuration: street lines count (`customer/address/street_lines`), optional postcode countries (`general/country/optional_zip_countries`), required region countries (`general/region/state_required`), display all regions (`general/region/display_all`)

## Version

Current: `0.0.0` (see composer.json)
