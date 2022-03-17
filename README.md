# FAQ plugin

Create Frequently Asked Questions with ease!
These can be translated if you are using [Winter.Translate](https://github.com/wintercms/wn-translate-plugin).
Note:

- The FAQ plugin comes without JavaScript and without CSS. You must style this yourself, so that it will fit your website brand.
- You can [override or write your own translations](https://wintercms.com/docs/plugin/localization#overriding).
- You can [extend any WinterCMS plugin](https://wintercms.com/docs/plugin/extending).
- You can [override the default markup](https://wintercms.com/docs/cms/components#overriding-partials).

## Features

With the FAQ plugin, you can:

- Create and update your FAQ
- Create and update categories for your FAQ
- Assign categories to your FAQ
- Define questions and answers in your FAQ
- Control which FAQs are published
    - 'Published'
    - 'In progress' allows backend users to see them on the frontend
    - 'Hidden'
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
- Automatically hides FAQs that are not translated
    - You can change this on the component itself by unchecking 'Translated FAQs only'.

## Installation

You can install this plugin for free using WinterCMS Marketplace.
You can then go to 'CMS -> Components' to drag and drop FAQ in to your page/layout.
Clicking the FAQ component gives you the options to modify the default behaviour.

## Let me know what you think

All I ask in return is that you [let me know](https://github.com/AIC-BV) that you are using my plugin.
I'm sure you all understand that it is very nice for me to know if my plugin is being used or not (might make more in the future if people actually use my plugins).

You can do so by sending me a simple message on Discord (Makalele#4465) or an e-mail to __info@aic-bv.be__. It doesn't have to be much, a thank you is all I ask for :)

## Special thanks

Special thanks to the WinterCMS maintainer team for making this possible:

- [Ben Thomson](https://github.com/bennothommo)
- [Jack Wilkinson](https://github.com/jaxwilko)
- [Luke Towers](https://github.com/LukeTowers)
- [Marc Jauvin](https://github.com/mjauvin)
