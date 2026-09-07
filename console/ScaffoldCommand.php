<?php

namespace Aic\Faq\Console;

use Aic\Faq\Models\Categories;
use Aic\Faq\Models\Faqs;
use Winter\Storm\Console\Command;
use Winter\Storm\Support\Str;

class ScaffoldCommand extends Command
{
    /**
     * @var string The console command name.
     */
    protected static $defaultName = 'scaffold:aic.faq';

    /**
     * @var string The name and signature of this command.
     */
    protected $signature = 'scaffold:aic.faq
        {--fresh : Delete any existing scaffold data before recreating it}';

    /**
     * @var string The console command description.
     */
    protected $description = 'Scaffold Aic.Faq demo data (categories + a spread of varied faqs) for local development/testing.';

    const SLUG_PREFIX = 'scaffold-';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        // Never inject demo content into a production install.
        if ($this->getLaravel()->environment('production')) {
            $this->error('scaffold:aic.faq cannot run in the production environment.');

            return self::FAILURE;
        }

        if ($this->option('fresh')) {
            $this->deleteExisting();
        }

        if (Categories::where('slug', 'like', self::SLUG_PREFIX . '%')->exists()) {
            $this->warn('FAQ scaffold data already exists. Use --fresh to recreate it.');

            return self::SUCCESS;
        }

        $categories = $this->createCategories();
        $this->info('Created ' . count($categories) . ' categories.');

        $faqsCount = $this->createFaqs($categories);
        $this->info("Created {$faqsCount} faqs.");
    }

    /**
     * Remove previously scaffolded faqs and categories.
     */
    protected function deleteExisting(): void
    {
        $faqs = Faqs::whereHas('category', function ($query) {
            $query->where('slug', 'like', self::SLUG_PREFIX . '%');
        })->get();
        foreach ($faqs as $faq) {
            $faq->delete();
        }

        $categories = Categories::where('slug', 'like', self::SLUG_PREFIX . '%')
            ->get();
        foreach ($categories as $category) {
            $category->delete();
        }

        if ($faqs->isNotEmpty() || $categories->isNotEmpty()) {
            $this->info("Removed {$faqs->count()} scaffold faq(s) and {$categories->count()} category(ies).");
        }
    }

    /**
     * Build the categories and return a handle => Categories map used to
     * assign faqs.
     */
    protected function createCategories(): array
    {
        $technology = $this->makeCategory('Technology');
        $web        = $this->makeCategory('Web Development');
        $design     = $this->makeCategory('Design');
        $news       = $this->makeCategory('News');
        $longName   = $this->makeCategory(
            'A deliberately very long category name used to test truncation'
        );

        return compact('technology', 'web', 'design', 'news', 'longName');
    }

    /**
     * Create a new category with the given name.
     */
    protected function makeCategory(string $name): Categories
    {
        $handle = Str::slug(Str::limit($name, 48, ''));

        $category = new Categories();
        $category->name = $name;
        $category->slug = self::SLUG_PREFIX . $handle;
        $category->save();

        return $category;
    }


    protected function createFaqs(array $cats): int
    {
        $count = 0;

        // 1. Very long title.
        $this->makeFaq(
            'This is an intentionally and excessively long blog faq title that exists purely to test how '
            . 'the backend list, breadcrumb, form header and tab labels handle text that simply refuses to '
            . 'end and keeps going well past any reasonable length',
            "A short body — the point of this faq is the **title length**, not the content.",
            [
                'is_published' => 1,
                'is_featured' => 1,
                'category' => $cats['technology']
            ]
        );
        $count++;

        // 2. Draft (unpublished) — exercises the "draft" list style + report widget.
        $this->makeFaq(
            'A work-in-progress draft',
            "This draft is **not published** yet.\n\n- still writing\n- todo: add more test\n- todo: make it featured",
            [
                'is_published' => 0,
                'is_featured' => 0,
                'category' => $cats['web']
            ]
        );
        $count++;

        // 3. Featured.
        $this->makeFaq(
            'A featured FAQ',
            "This FAQ is **featured** and should appear in the featured list.",
            [
                'is_published' => 1,
                'is_featured' => 1,
                'category' => $cats['design']
            ]
        );
        $count++;

        // 4. Unfeatured.
        $this->makeFaq(
            'A regular FAQ',
            "This FAQ is **not featured** and should appear in the regular list.",
            [
                'is_published' => 1,
                'is_featured' => 0,
                'category' => $cats['news']
            ]
        );
        $count++;

        // 5. Filler for pagination (list is 25/page) + list density.
        $catList = array_values($cats);
        for ($i = 1; $i <= 24; $i++) {
            $this->makeFaq(
                "Sample FAQ #{$i}",
                $this->fillerBody($i),
                [
                    'is_published' => 1,
                    'is_featured' => 0,
                    'category' => $catList[$i % count($catList)],
                ]
            );
            $count++;
        }

        return $count;
    }

    protected function makeFaq(string $question, string $answer, array $opts = []): Faqs
    {
        $faq = new Faqs();
        $faq->question = $question;
        $faq->answer = $answer;
        $faq->is_published = $opts['is_published'] ?? 0;
        $faq->is_featured = $opts['is_featured'] ?? 0;

        if (!empty($opts['category'])) {
            $faq->category()->associate($opts['category']);
        }

        $faq->save();

        return $faq;
    }

    protected function fillerBody(int $i): string
    {
        return "## Sample faq {$i}\n\n"
            . "Scaffolded filler content for **faq {$i}** — gives the list something to "
            . "paginate and the form something to render.\n\n"
            . "- point one\n- point two\n- point three\n\n"
            . "> A short blockquote for good measure.\n";
    }
}
