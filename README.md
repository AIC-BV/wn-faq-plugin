# FAQ plugin

Create and manage Frequently Asked Questions with ease! Full support for translation via [Winter.Translate](https://github.com/wintercms/wn-translate-plugin) if needed.

## Features

The FAQ plugin provides:

- FAQ and category management, including publication, featured status and backend ordering.
- Optional Winter.Translate support, including locale-aware FAQ and category-slug filtering.
- CMS components for category navigation, full FAQ listings and category-specific FAQ pages.
- Configurable FAQ sorting, filtering and AJAX search.
- Native HTML5 `<details>` / `<summary>` markup, easy to override and style.
- Automatic [FAQPage JSON-LD](https://schema.org/FAQPage) structured data.

## Screenshots

![FAQ list](https://github.com/AIC-BV/wn-faq-plugin/blob/main/.github/assets/faq_list.jpg?raw=true)

![FAQ editing](https://github.com/AIC-BV/wn-faq-plugin/blob/main/.github/assets/faq_edit.jpg?raw=true)

## CMS components

The plugin provides three frontend components:

- `faqCategories` displays published categories, their FAQ count and an **All** link to the current page, without category parameters.
- `FaqsBySlug` displays FAQs from the published category resolved from its configured route parameter.
- `FAQ` displays all FAQs by default, can be limited to a selected category and provides search when enabled.

FAQ listing components hide entries that are not translated into the current locale by default. Disable the `isTranslated` property to include them.

### Example setup

Use a single page at `/faq/:slug?`, where the optional slug filters the displayed category:

```ini
title = "All faqs with multi components"
url = "/faq/:slug?"
layout = "default"
meta_title = "Show all faqs with multiple components"
meta_description = "This page shows all faqs with multiple components. It uses the FAQ component and the faqCategories component to display the faqs and categories."
is_hidden = 0

[faqCategories]
categorySlug = "{{ :slug }}"
sort = "sort_order asc"
categoryPage = "faq/category"

[FaqsBySlug]
categoryFilter = "{{ :slug }}"
sort = "question asc"
==
<?php
    function onEnd() {
        // Optional - set the page title to the category name
        if ($this->faqCategory) {
            $this->page->title = $this->faqCategory->name;
        }
    }
?>
==
<div class="categories">
    {% component 'faqCategories' %}
</div>

<div class="faqs">
    {% component 'FaqsBySlug' %}
</div>
```

```ini
title = "All FAQs"
url = "/faq"
layout = "default"
meta_title = "Show all faqs"
meta_description = "This page shows all faqs. It uses the FAQ component to display the faqs."
is_hidden = 0

[FAQ]
sort = "sort_order asc"
==
<div>
    <h1>All our faqs</h1>
    {% component 'FAQ' %}
</div>
```

Replace `faq/category` with the filename of your category CMS page. The **All** entry produced by `faqCategories` links to the page that contains the component, without category parameters; every other category links to `categoryPage` using its slug. When Winter.Translate is installed, category slug route parameters are translated with the locale picker.

Both `FaqsBySlug` and `FAQ` support `sort`, `isFeatured`, `isTranslated` and `noFaqsMessage`. `FAQ` additionally supports `categoryId`, `isSearch` and `minSearchResults`.

## FAQ variables

Use [{{ `__SELF__` }}](https://wintercms.com/docs/plugin/components#referencing-self) when a page contains more than one FAQ component.

`faqCategories` exposes:
- `categories`: published category models, plus the synthetic **All** category. Each category has a `url` and `faqs_count`.
- `currentCategorySlug`: the configured current route slug, suitable for marking the active link.

`FaqsBySlug` and `FAQ` expose:
- `faqs`: the matching FAQ collection.
- `faqsPerCategory`: matching FAQs grouped by category, as used by the default partial.
- `jsonLd`: the generated FAQPage JSON-LD string.
- `noFaqsMessage`: the configured empty-state message.

`FAQ` additionally exposes `isSearch`, `canShowSearch` and `searchQuery`. The latter contains the `q` query-string value used by the default AJAX search.

## Installation

The plugin will be available on the WinterCMS Marketplace as soon as the WinterCMS team release it.

### Using composer

```bash
composer require aic/wn-faq-plugin
```

### Clone

Clone this repository into your Winter plugins folder:

```bash
cd plugins
mkdir aic && cd aic
git clone https://github.com/AIC-BV/wn-faq-plugin faq
```

In both cases, run `php artisan winter:up` to create the plugin database tables. You can also log out and back in to the backend to ensure the plugin is fully installed.

## Notes

- Winter.Translate is optional. Without it, the plugin works normally, but the `isTranslated` property has no effect.
- The plugin includes no JavaScript or CSS; style the markup to match your website.
- You can override [translations](https://wintercms.com/docs/plugin/localization#overriding), [default markup](https://wintercms.com/docs/cms/components#overriding-partials) and [the plugin itself](https://wintercms.com/docs/plugin/extending).

## Let me know what you think

I spent a lot of time making this plugin public for the community. All I ask in return is that you [let me know](https://github.com/AIC-BV) that you are using my plugin.
I'm sure you all understand that it is very nice for me to know if my plugin is being used or not (might make more in the future if people actually use my plugins).

You can do so by sending me a simple message on Discord (Makalele#4465) or an e-mail to __info@aic-bv.be__. It doesn't have to be much, a thank you is all I ask for :)

## Special thanks

Special thanks to the WinterCMS maintainer team for making this possible:

- [Ben Thomson](https://github.com/bennothommo)
- [Jack Wilkinson](https://github.com/jaxwilko)
- [Luke Towers](https://github.com/LukeTowers)
- [Marc Jauvin](https://github.com/mjauvin)
- [Damien Mathieu](https://github.com/damsfx)

***
Make awesome sites with ❄ [WinterCMS](https://wintercms.com) !
