# Принципи

1. основні блоки через м2 лейаут
2. маленькі блоки (кнопка, інпут) - headless компоненти
3. алпайн на фронті
4. SSR GraphQL - один graphql фрагмент віддає дані при першому рендері і при оновленнях на фронті
5. no magic. все явно викликається з кінцевих точок


# Roadmap

- [ ] headless component .script.phtml companion
- [ ] gql via bus, state events
- [ ] loaders
- [ ] spa sign in 


# Getting Started


## Merchant

First thing first - this is a core repository containing base code for derived checkout extensions.
To use this checkout in production you should consider:

1. installing one of ready-to-use checkout extensions from the [Magento 2 Extension Store](https://google.com)
   and any required extension integrations (shipment/payment providers, giftcards, etc.) [there](integrations.md);
2. building your own checkout extension with a developer/team to deeply integrate any required custom features for your storefront.


## Developer

### To integrate ready-to-use checkout extension based on this module

0. Read the docs [here](docs/index.md)
1. Install selected checkout extension as described in its Installing Guide,
2. Review and find integrations for the required additional extensions [here](integrations.md),
3. Review and set desired settings in the Admin (`Stores` - `Configuration` - `Sales` - `Just Check Me Out`),
4. Enjoy!

### To create your own checkout extension based on this module

1. Create new Magento 2 module depending on this one (both via composer and module.xml),
2.
