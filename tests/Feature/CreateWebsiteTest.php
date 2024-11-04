<?php

namespace Tests\Feature;

use App\Models\Website;
use Domain\ValidationExceptions\ValidationException;
use Domain\Websites\Interactors\CreateWebsiteInteractor;
use Domain\Websites\Interactors\Requests\CreateWebsiteRequest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateWebsiteTest extends TestCase
{
    use refreshDatabase;

    private array $testData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testData = [
            'name' => 'Example Website',
            'url' => 'https://example.com',
        ];
    }

    #[Test]
    public function can_not_create_website_without_a_name(): void
    {
        $request = new CreateWebsiteRequest;

        $request->name = '';
        $request->url = 'https://example.com';

        try {
            $interactor = new CreateWebsiteInteractor;
            $interactor->create($request);
        } catch (ValidationException $e) {
            $this->assertEquals('Website name is required.', $e->getMessage());
        }
    }

    #[Test]
    public function can_not_create_website_without_a_url(): void
    {
        $request = new CreateWebsiteRequest;

        $request->name = 'Example Website';
        $request->url = '';

        try {
            $interactor = new CreateWebsiteInteractor();
            $interactor->create($request);
        } catch (ValidationException $e) {
            $this->assertEquals('Website URL is required.', $e->getMessage());
        }
    }

    #[Test]
    public function can_not_create_duplicate_website(): void
    {
        $request = new CreateWebsiteRequest();

        $request->name = 'Example Website';
        $request->url = 'https://example.com';

        Website::create($this->testData);

        try {
            $request->validate($request);
            $interactor = new CreateWebsiteInteractor();
            $interactor->create($request);
        } catch (ValidationException $e) {
            $this->assertEquals('A website with the same name or URL already exists.', $e->getMessage());
        }

    }

    #[Test]
    public function can_create_website(): void
    {
        $request = new CreateWebsiteRequest();

        $request->name = 'Example Website';
        $request->url = 'https://example.com';

        $interactor = new CreateWebsiteInteractor();

        $request->validate($request);
        $website = $interactor->create($request);

        $this->assertInstanceOf(Website::class, $website);
        $this->assertEquals($request->name, $website->name);
        $this->assertEquals($request->url, $website->url);
        $this->assertDatabaseHas('websites', [
            'name' => $request->name,
            'url' => $request->url,
        ]);
    }

}
