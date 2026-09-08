<?php

declare(strict_types=1);

namespace Aic\Faq\Tests\Console;

use Artisan;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Aic\Faq\Console\ScaffoldCommand;
use Aic\Faq\Models\Categories;
use Aic\Faq\Models\Faqs;
use Aic\Faq\Tests\FaqPluginTestCase;

class ScaffoldCommandTest extends FaqPluginTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // When Winter.Translate is present it makes Faq translatable, so deleting a
        // faq (exercised by --fresh) touches its tables. Migrate it if installed;
        // this is a no-op (throw = false) in a bare Blog CI where it is absent.
        $this->instantiatePlugin('Winter.Translate', false);

        // Plugin console commands are registered via ConsoleApplication::starting, which has
        // already fired by the time the test harness boots the plugin — so the command isn't
        // resolvable through Artisan here. Register it directly with the kernel for the test.
        $this->app->make(ConsoleKernel::class)->registerCommand(new ScaffoldCommand());
    }

    protected function scaffoldCategoryCount(): int
    {
        return Categories::where('slug', 'like', ScaffoldCommand::SLUG_PREFIX . '%')->count();
    }

    protected function scaffoldFaqCount(): int
    {
        return Faqs::whereHas('category', function ($query) {
            $query->where('slug', 'like', ScaffoldCommand::SLUG_PREFIX . '%');
        })->count();
    }

    public function testCreatesDemoCategoriesAndFaqs()
    {
        $this->assertSame(0, $this->scaffoldCategoryCount(), 'No scaffold categories should exist beforehand.');

        $exitCode = Artisan::call('scaffold:aic.faq');

        $this->assertSame(0, $exitCode);
        $this->assertSame(5, $this->scaffoldCategoryCount());
        $this->assertSame(28, $this->scaffoldFaqCount());
    }

    public function testIsIdempotentWithoutFresh()
    {
        Artisan::call('scaffold:aic.faq');

        $exitCode = Artisan::call('scaffold:aic.faq');

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('already exists', Artisan::output());
        $this->assertSame(5, $this->scaffoldCategoryCount(), 'A second run must not duplicate categories.');
        $this->assertSame(28, $this->scaffoldFaqCount(), 'A second run must not duplicate faqs.');
    }

    public function testFreshRecreatesTheData()
    {
        Artisan::call('scaffold:aic.faq');
        $firstIds = Categories::where('code', 'like', ScaffoldCommand::SLUG_PREFIX . '%')->pluck('id')->all();

        $exitCode = Artisan::call('scaffold:aic.faq', ['--fresh' => true]);

        $this->assertSame(0, $exitCode);
        $this->assertSame(5, $this->scaffoldCategoryCount());
        $this->assertSame(28, $this->scaffoldFaqCount());

        $newIds = Categories::where('code', 'like', ScaffoldCommand::SLUG_PREFIX . '%')->pluck('id')->all();
        $this->assertEmpty(array_intersect($firstIds, $newIds), '--fresh should delete and recreate the categories.');
    }

    public function testRefusesToRunInProduction()
    {
        $this->app['env'] = 'production';

        $exitCode = Artisan::call('scaffold:aic.faq');

        $this->assertSame(1, $exitCode);
        $this->assertSame(0, $this->scaffoldCategoryCount(), 'Nothing should be created in production.');

        $this->app['env'] = 'testing';
    }
}
