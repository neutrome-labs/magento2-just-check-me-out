# Принципи

1. основні блоки через м2 лейаут
2. маленькі блоки (кнопка, інпут) - headless компоненти
3. алпайн на фронті
4. SSR GraphQL - один graphql фрагмент віддає дані при першому рендері і при оновленнях на фронті
5. no magic. все явно викликається з кінцевих точок

# Документація

```
src/JustCheckMeOut/
├── Controller
│   └── Onepage
│       └── Index.php
├── etc
│   ├── frontend
│   │   └── routes.xml
│   └── module.xml
├── Model
│   └── SsrGraphqlContextFactory.php # внутрішня реалізація SSR GraphQl / конструктор контексту
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
│           └── onepage # компоненти саме onepage
│               ├── api.phtml # js-частини алпайн-компонентів
│               ├── main.phtml # єдиний шаблон onepage
│               └── script-alpine.phtml # скрипт алпайну для нон-хіва тем
├── ViewModel
│   ├── HeadlessComponentRenderer.php # 1 метод ::render(...) - рендерить headless компоненти
│   ├── QuoteViewModel.php # в'юмодель для корзини
│   └── SsrGraphqlResolver.php # допомагає вставляти SSR GraphQl виклики в js-компоненти
└── registration.php

16 directories, 23 files
```