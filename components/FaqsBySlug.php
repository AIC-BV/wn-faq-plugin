<?php

namespace Aic\Faq\Components;

use Aic\Faq\Classes\FaqBaseComponent;
use Aic\Faq\Models\Categories;
use Illuminate\Support\Facades\Event;

class FaqsBySlug extends FaqBaseComponent
{
    /**
     * Currently resolved category from slug.
     */
    public null|Categories $category = null;

    /**
     * Register locale param translation for category slugs.
     */
    public function init(): void
    {
        Event::listen('translate.localePicker.translateParams', function ($page, $params, $oldLocale, $newLocale) {
            $paramName = $this->getRouteParamNameFromProperty('categoryFilter');
            if (!$paramName || !isset($params[$paramName])) {
                return;
            }

            $record = $this->findCategoryBySlug((string) $params[$paramName], (string) $oldLocale);
            if (!$record || !method_exists($record, 'getAttributeTranslated')) {
                return;
            }

            $translatedSlug = $record->getAttributeTranslated('slug', (string) $newLocale);
            if (!$translatedSlug) {
                return;
            }

            $newParams = $params;
            $newParams[$paramName] = $translatedSlug;

            return $newParams;
        });
    }

    /**
     * Gets the details for the component
     */
    public function componentDetails(): array
    {
        return [
            'name'        => 'aic.faq::lang.components.faqs_by_slug.title',
            'description' => 'aic.faq::lang.components.faqs_by_slug.description',
        ];
    }

    /**
     * Returns the properties provided by the component
     */
    public function defineProperties(): array
    {
        return array_merge($this->defineBaseProperties(), [
            'categoryFilter' => [
                'title'       => 'aic.faq::lang.components.faqs_by_slug.properties.category_filter.title',
                'description' => 'aic.faq::lang.components.faqs_by_slug.properties.category_filter.description',
                'type'        => 'string',
                'default'     => '{{ :slug }}',
            ],
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function onRun()
    {
        $this->prepareBaseVars();

        $categorySlug = trim((string) $this->property('categoryFilter'));
        if ($categorySlug === '') {
            $this->resolvedCategoryId = 0;
            $this->category = null;
        } else {
            $this->category = $this->resolveCategoryFromSlug($categorySlug);

            // Unknown or unpublished slug: don't soft-404 an empty page, hand off to the theme's 404.
            if (!$this->category) {
                return $this->controller->run('404');
            }

            $this->resolvedCategoryId = (int) $this->category->id;
        }

        $this->page['faqCategory'] = $this->category;
        $this->faqs = $this->getFAQs();
        $this->faqsPerCategory = $this->faqsPerCategory();
        $this->jsonLd = $this->getJsonLd();
    }
}
