<?php

namespace Aic\Faq\Classes;

use Aic\Faq\Models\Categories;
use Aic\Faq\Models\Faqs as Faq;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Lang;
use Winter\Storm\Database\Collection;
use Winter\Translate\Classes\Translator;

abstract class FaqBaseComponent extends ComponentBase
{
    /**
     * A collection of faqs to display
     */
    public ?Collection $faqs = null;

    /**
     * Array of faq grouped by category
     */
    public array $faqsPerCategory = [];

    /**
     * JSON-LD structured data for the FAQs
     */
    public string $jsonLd = '';

    /**
     * The message to show when no FAQs are found
     */
    public string $noFaqsMessage = '';

    /**
     * Resolved category id to use when filtering FAQs.
     * A null value means the requested category filter is invalid.
     */
    protected ?int $resolvedCategoryId = 0;

    /**
     * Returns the properties shared by FAQ listing components.
     *
     * @return array<string, mixed>
     */
    protected function defineBaseProperties(): array
    {
        return [
            'sort' => [
                'title'       => 'aic.faq::lang.components.faqs.properties.sort.title',
                'description' => 'aic.faq::lang.components.faqs.properties.sort.description',
                'type'        => 'dropdown',
                'default'     => 'question asc',
            ],
            'isFeatured' => [
                'title'       => 'aic.faq::lang.components.faqs.properties.featured.title',
                'description' => 'aic.faq::lang.components.faqs.properties.featured.description',
                'type'        => 'dropdown',
                'default'     => null,
                'options'     => 'aic.faq::lang.components.faqs.properties.featured.options',
            ],
            'isTranslated' => [
                'title'       => 'aic.faq::lang.components.faqs.properties.translated.title',
                'description' => 'aic.faq::lang.components.faqs.properties.translated.description',
                'default'     => true,
                'type'        => 'checkbox'
            ],
            'noFaqsMessage' => [
                'title'             => 'aic.faq::lang.components.faqs.properties.no_faqs.title',
                'description'       => 'aic.faq::lang.components.faqs.properties.no_faqs.description',
                'type'              => 'string',
                'default'           => Lang::get('aic.faq::lang.components.faqs.no_results'),
                'showExternalParam' => false
            ],
        ];
    }

    /**
     * Sort options getter
     */
    public function getSortOptions(): array
    {
        $allowedSorting = Faq::$allowedSorting;

        return collect(Lang::get('aic.faq::lang.sorting'))
            ->filter(fn ($label, $key) => in_array(preg_replace('/_(asc|desc)$/', ' $1', $key), $allowedSorting))
            ->mapWithKeys(fn ($label, $key) => [preg_replace('/_(asc|desc)$/', ' $1', $key) => $label])
            ->all();
    }

    /**
     * Prepare variables shared by FAQ list components.
     */
    protected function prepareBaseVars(): void
    {
        $this->noFaqsMessage = $this->page['noFaqsMessage'] = (string) $this->property('noFaqsMessage');
    }

    protected function getFeaturedFilter(): ?int
    {
        $value = $this->property('isFeatured');

        if ($value === null || $value === '') {
            return null;
        }

        // BC: `2` used to mean "all". Kept for themes not yet updated to `null`.
        // TODO: deprecate and remove in a future major version.
        if ((int) $value === 2) {
            return null;
        }

        return (int) $value;
    }

    /**
     * Get the FAQs based on common component properties.
     *
     * @param array<string, mixed> $extraOptions
     */
    protected function getFAQs(array $extraOptions = []): Collection
    {
        if ($this->resolvedCategoryId === null) {
            return new Collection();
        }

        $options = array_merge([
            'sort'         => $this->property('sort'),
            'categoryId'   => $this->resolvedCategoryId,
            'isFeatured'   => $this->getFeaturedFilter(),
            'isSearch'     => false,
            'isTranslated' => (bool) $this->property('isTranslated'),
            'searchQuery'  => '',
        ], $extraOptions);

        return Faq::with('category')->listFrontEnd($options);
    }

    /**
     * Group FAQs by category for frontend rendering.
     *
     * @return array<int|string, array<string, mixed>>
     */
    protected function faqsPerCategory(): array
    {
        if ($this->faqs === null || $this->faqs->isEmpty()) {
            return [];
        }

        $groupedFaqs = $this->faqs->groupBy(fn ($faq) => $faq->category_id ?: 0);

        $sort = (string) $this->property('sort');

        if (!str_starts_with($sort, 'category_id')) {
            $groupedFaqs = $groupedFaqs->sortBy(
                fn ($faqsInCategory) => optional($faqsInCategory->first()->category)->sort_order ?: 0
            );
        }

        $noCategoryLabel = (string) Lang::get('aic.faq::lang.components.faqs.properties.category.no_category_label');
        $result = [];

        foreach ($groupedFaqs as $categoryId => $faqsInCategory) {
            $category = $faqsInCategory->first()->category;

            $result[$categoryId] = [
                'id'       => $categoryId ?: null,
                'name'     => optional($category)->name ?: $noCategoryLabel,
                'category' => $category,
                'faqs'     => $faqsInCategory,
            ];
        }

        return $result;
    }

    /**
     * Generate JSON-LD structured data for the FAQs.
     */
    protected function getJsonLd(): string
    {
        if ($this->faqs === null || $this->faqs->isEmpty()) {
            return '';
        }

        return json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $this->faqs->flatten(1)->map(function ($faq) {
                return [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $this->cleanHtmlWhitespace($faq->answer),
                    ],
                ];
            })->values()->toArray(),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

    /**
     * Highlight case-insensitive matches of a search term in escaped plain text.
     */
    public function highlightPlainText(string $text, string $searchTerm): string
    {
        $escaped = e($text);

        if (trim($searchTerm) === '') {
            return $escaped;
        }

        return preg_replace(
            '/' . preg_quote(e($searchTerm), '/') . '/iu',
            '<mark>$0</mark>',
            $escaped
        ) ?? $escaped;
    }

    /**
     * Highlight case-insensitive matches of a search term inside stored HTML,
     * touching only text nodes so tags and attributes are never altered.
     */
    public function highlightHtml(string $html, string $searchTerm): string
    {
        if ($html === '' || trim($searchTerm) === '') {
            return $html;
        }

        $document = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div>' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $pattern = '/' . preg_quote($searchTerm, '/') . '/iu';
        $xpath = new \DOMXPath($document);

        foreach ($xpath->query('//text()') as $textNode) {
            $content = $textNode->nodeValue;

            if ($content === '' || !preg_match($pattern, $content)) {
                continue;
            }

            $fragment = $document->createDocumentFragment();
            $lastOffset = 0;

            preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);
            foreach ($matches[0] as [$matchedText, $offset]) {
                if ($offset > $lastOffset) {
                    $fragment->appendChild($document->createTextNode(substr($content, $lastOffset, $offset - $lastOffset)));
                }

                $mark = $document->createElement('mark');
                $mark->appendChild($document->createTextNode($matchedText));
                $fragment->appendChild($mark);

                $lastOffset = $offset + strlen($matchedText);
            }

            if ($lastOffset < strlen($content)) {
                $fragment->appendChild($document->createTextNode(substr($content, $lastOffset)));
            }

            $textNode->parentNode->replaceChild($fragment, $textNode);
        }

        $wrapper = $document->getElementsByTagName('div')->item(0);
        $innerHtml = '';
        foreach ($wrapper->childNodes as $child) {
            $innerHtml .= $document->saveHTML($child);
        }

        return $innerHtml;
    }


    /**
     * Collapse line breaks and repeated whitespace from stored HTML.
     */
    protected function cleanHtmlWhitespace(string $html): string
    {
        return trim(preg_replace('/\s+/', ' ', $html));
    }

    /**
     * Find a category by slug, optionally in a specific locale.
     */
    protected function findCategoryBySlug(string $slug, ?string $locale = null): ?Categories
    {
        $slug = trim($slug);
        if ($slug === '') {
            return null;
        }

        $query = Categories::query()->where('is_published', 1);
        $category = new Categories();

        if ($category->isClassExtendedWith('Winter.Translate.Behaviors.TranslatableModel')) {
            $query = $locale ? $query->transWhere('slug', $slug, $locale) : $query->transWhere('slug', $slug);
        } else {
            $query = $query->where('slug', $slug);
        }

        return $query->first();
    }

    /**
     * Resolve category by slug with an optional fallback to the default locale.
     */
    protected function resolveCategoryFromSlug(string $slug): ?Categories
    {
        $category = $this->findCategoryBySlug($slug);
        if ($category) {
            return $category;
        }

        if (!class_exists(Translator::class)) {
            return null;
        }

        $translator = Translator::instance();
        $defaultLocale = $translator->getDefaultLocale();
        $currentLocale = $translator->getLocale();

        if (!$defaultLocale || $defaultLocale === $currentLocale) {
            return null;
        }

        return $this->findCategoryBySlug($slug, $defaultLocale);
    }

    /**
     * Resolve the route parameter name from a property expression like {{ :slug }}.
     */
    protected function getRouteParamNameFromProperty(string $propertyName): ?string
    {
        if (!$this->page || !method_exists($this->page, 'getComponentProperties')) {
            return null;
        }

        $properties = $this->page->getComponentProperties($this->alias);
        if (!is_array($properties) || !isset($properties[$propertyName])) {
            return null;
        }

        if (!preg_match('/^\{\{\s*:([^\}]+)\s*\}\}$/', (string) $properties[$propertyName], $matches)) {
            return null;
        }

        return trim($matches[1]);
    }
}
