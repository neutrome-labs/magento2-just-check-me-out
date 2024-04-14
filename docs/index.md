# Overview

- Overview
- [Concepts](concepts.md)
- [Integrations](integrations.md)
- [Support](support.md)

---

## stdlib::core

```
src/JustCheckMeOut/
├── Api
│   └── AdditionalViewInterface.php # інтерфейс для реєстрації додаткових форм шипінг- пеймент-методів
├── Controller
│   └── Onepage
│       └── Index.php
├── etc
│   ├── frontend
│   │   └── routes.xml
│   ├── module.xml
│   └── schema.graphqls # додає поля з хтмл-кодом додаткових форм методів
├── Model # внутрішня реалізація всяких штук
│   ├── Resolver
│   │   ├── PaymentMethodDetailsHtml.php
│   │   └── ShippingMethodDetailsHtml.php
│   └── AdditionalView.php
├── Service
│   ├── AdditionalViewRenderer.php # рендерить додаткові форми шипінг- пеймент-методів
│   ├── PaymentMethodAdditionalViewRegistry.php # реєстр додаткових форм пеймент методів для розширення
│   └── ShippingMethodAdditionalViewRegistry.php # ...
├── view
│   └── frontend
│       ├── layout
│       │   └── psteamjustcheckmeout_onepage_index.xml
│       └── templates
│           ├── api # складові публічного апі
│           │   ├── component # js-частини алпайн компонентів
│           │   │   ├── customer-email-form.phtml
│           │   │   ├── payment-method-list.phtml
│           │   │   ├── place-order-button.phtml
│           │   │   ├── shipping-address-form.phtml
│           │   │   ├── shipping-method-list.phtml
│           │   │   └── unified-address-form.phtml
│           │   └── fn # глобальні хелпери
│           │       └── graphql.phtml
│           ├── component
│           │   ├── headless # headless компоненти
│           │   │   ├── atom
│           │   │   │   ├── button.phtml
│           │   │   │   └── input-text.phtml
│           │   │   └── block
│           │   │       ├── address-fieldset.phtml
│           │   │       ├── input-text.phtml
│           │   │       └── radio-list-with-details.phtml
│           │   ├── items.phtml # хмтл алпайн-компоненту блоку айтемів
│           │   ├── payment-method-list.phtml # пейментів
│           │   ├── place-button.phtml # кнопки плейс ордеру
│           │   ├── shipping-method-list.phtml # ...
│           │   ├── totals.phtml
│           │   └── unified-address-form.phtml
│           ├── onepage
│           │   └── main.phtml # єдиний шаблон onepage
│           ├── api.phtml # публічне js апі (компоненти, хелпери)
│           └── script-alpine.phtml # алпайн і плагіни
├── ViewModel
│   ├── HeadlessComponentRenderer.php # 1 метод ::render(...) - рендерить headless компоненти
│   ├── QuoteViewModel.php # в'юмодель для корзини
│   └── SsrGraphqlViewModel.php # допомагає вставляти SSR GraphQl виклики в js-компоненти
└── registration.php

22 directories, 37 files
```

## stdlib::with_stripe

```
src/JustCheckMeOutWithStripe/
├── etc
│   ├── di.xml
│   └── module.xml
├── view
│   └── frontend
│       ├── layout
│       │   └── psteamjustcheckmeout_onepage_index.xml
│       └── templates
│           ├── script.phtml # скрипти, що потрібні блоку пейментів. вставлені в низ сторінки через лейаут
│           └── view.phtml # додатковий блок для пейменту: ініціалізує форму та проводить кастомний плейс
└── registration.php

6 directories, 6 files
```