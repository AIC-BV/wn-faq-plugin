# FAQ plugin

Create Frequently Asked Questions with ease!
These can be translated if you are using [Winter.Translate](https://github.com/wintercms/wn-translate-plugin).

Note that if you are NOT using Winter.Translate, everything will work exactly the same but the 'Translated FAQs only' checkbox will be useless and there will be no translation support.

Note:

- The FAQ plugin comes without JavaScript and without CSS. You must style this yourself, so that it will fit your website brand.
- You can [override the translations](https://wintercms.com/docs/plugin/localization#overriding).
- You can [extend any WinterCMS plugin](https://wintercms.com/docs/plugin/extending).
- You can [override the default markup](https://wintercms.com/docs/cms/components#overriding-partials).


## Images
<p align="center">
    <img src="https://github.com/AIC-BV/wn-faq-plugin/blob/main/assets/faq_list.jpg" alt="FAQ list" width="100%">
</p>

<p align="center">
    <img src="https://github.com/AIC-BV/wn-faq-plugin/blob/main/assets/faq_edit.jpg" alt="FAQ edit" width="100%">
</p>

## Features

With the FAQ plugin, you can:

- Create and update your FAQ
- Create and update categories for your FAQ
- Assign categories to your FAQ
- Define questions and answers in your FAQ
- Control which FAQs are published
    - Published
    - In progress (allows logged in backend users to see them on the frontend)
    - Hidden
- Add a featured status to FAQ
    - Featured
    - Not featured
- Choose the sorting method between predefined choices
    - Category (ascending and descending)
    - Created at (ascending and descending)
- Choose which categories you wish to display
    - Your own created category
    - All
- Choose which FAQs type you wish to display
    - Featured
    - Not featured
    - All
- Enable a search field, allowing users to quickly find FAQs
- Define the minimum amount of records that are required for displaying the search field
- Make sure that only FAQs in the correct language are shown
    - for example: if FAQ #1 is NOT translated in Spanish, it won't show when the user's locale is Spanish
    - for example: if FAQ #2 is translated in Spanish and English, it will show in both Spanish and English, but not in French.
- The default markup makes use of [HTML5 details & summary](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/details)
    - This adds default behaviour for opening and closing FAQs
- Translate FAQs

## Default behaviour:

- Automatically displays all FAQ catagories
- Automatically displays all FAQs regardless of their featured status
- Automatically adds a search box if there are more than 10 FAQs in total
    - You can change this on the component itself by changing 'Search minimum results'.
    - Not shown if 'Search enabled' is unchecked
- Automatically hides FAQs that are not translated in the current website langauge
    - You can change this on the component itself by unchecking 'Translated FAQs only'.

## Installation

You can (soon) install this plugin for free using WinterCMS Marketplace or using composer (composer require aic/wn-faq-plugin and php artisan winter:up).
You can then go to 'CMS -> Components' to drag and drop FAQ in to your page/layout.
Clicking the FAQ component gives you the options to modify the default behaviour.

## FAQ variables

A FAQ exists out of the following variables:

- id
- category_id
- is_published
- is_featured
- question
- answer
- created_at
- updated_at

In the component itself you can use the following variables (note that you should prepend them with [{{ `__SELF__` }}](https://wintercms.com/docs/plugin/components#referencing-self) if you have multiple FAQ components on one page):  

- faqs (array of FAQs)
- isSearch (if true, searchbox is enabled)
- searchLabel (label for the search field)
- searchPlaceholder (placeholder for the search field)
- minSearchResults (the minimum amount of results required for displaying the searchbox)
- searchQuery (the querystring user used in the search box)

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
