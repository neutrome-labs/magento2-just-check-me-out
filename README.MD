# Принципи

1. основні блоки через м2 лейаут
2. маленькі блоки (кнопка, інпут) - headless компоненти
3. алпайн на фронті
4. SSR GraphQL - один graphql фрагмент віддає дані при першому рендері і при оновленнях на фронті
5. no magic. все явно викликається з кінцевих точок

# Документація
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
│   └── schema.graphqls # додає поле з хтмл-кодом додаткових форм методів
├── Model # внутрішня реалізація всяких штук
│   ├── Resolver
│   │   └── PaymentMethodDetails.php
│   ├── AdditionalView.php
│   └── SsrGraphqlContextFactory.php
├── Service
│   ├── AdditionalViewRenderer.php # рендерить додаткові форми шипінг- пеймент-методів
│   └── PaymentMethodAdditionalViewRegistry.php # реєстр додаткових форм для розширення
├── view
│   └── frontend
│       ├── layout
│       │   └── psteamjustcheckmeout_onepage_index.xml
│       └── templates
│           ├── component
│           │   ├── headless # headless компоненти, ака @storyblock. не треба налаштовувати в лейауті, рендеряться через спец. вьюмодель
│           │   │   ├── atom 
│           │   │   │   ├── button.phtml
│           │   │   │   └── input-text.phtml
│           │   │   └── block
│           │   │       ├── address-fieldset.phtml
│           │   │       ├── input-text.phtml
│           │   │       └── radio-list-with-details.phtml
│           │   ├── address.phtml # хмтл алпайн-компоненту блоку адрес
│           │   ├── items.phtml # айтемів
│           │   ├── payment-method-list.phtml # пейментів
│           │   ├── place-button.phtml # ...
│           │   ├── shipping-method-list.phtml
│           │   └── totals.phtml
│           ├── onepage
│           │   └── main.phtml # єдиний шаблон onepage
│           ├── api.phtml # js-частини алпайн-компонентів
│           └── script-alpine.phtml # скрипти алпайну
├── ViewModel
│   ├── HeadlessComponentRenderer.php # 1 метод ::render(...) - рендерить headless компоненти
│   ├── QuoteViewModel.php # в'юмодель для корзини
│   └── SsrGraphqlResolver.php # допомагає вставляти SSR GraphQl виклики в js-компоненти
└── registration.php

19 directories, 29 files
```

```
src/JustCheckMeOutWithStripe/
├── etc
│   ├── di.xml # реєструє додатковий блок для пейменту
│   └── module.xml
├── view
│   └── frontend
│       └── templates
│           └── details.phtml # додатковий блок для пейменту: ініціалізує форму та проводить кастомний плейс
└── registration.php

5 directories, 4 files
```