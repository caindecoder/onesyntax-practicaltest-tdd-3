<?php

namespace Tests\Feature;

use App\Mail\WebsiteCreated;
use App\Models\Website;
use Domain\Websites\Interactors\CreateWebsiteInteractor;
use Domain\Websites\Interactors\Requests\CreateWebsiteRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
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

    // USER CREATES WEBSITES TESTS

    #[Test]
    public function can_not_create_website_without_a_name(): void
    {
        try {
            $createWebsiteRequest = CreateWebsiteRequest::validateAndCreate([
                'name' => '',
                'url' => 'https://example.com',
            ]);

            $createWebsiteInteractor = new CreateWebsiteInteractor();
            $createWebsiteInteractor->execute($createWebsiteRequest);

        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('name', $exception->errors());
        }
    }

    #[Test]
    public function can_not_create_website_without_a_url(): void
    {
        try {
            $createWebsiteRequest = CreateWebsiteRequest::validateAndCreate([
                'name' => 'Website Test',
                'url' => '',
            ]);

            $createWebsiteInteractor = new CreateWebsiteInteractor();
            $createWebsiteInteractor->execute($createWebsiteRequest);

        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('url', $exception->errors());
        }
    }

    #[Test]
    public function can_not_create_duplicate_website(): void
    {
        Website::create($this->testData);

        try {
            $createWebsiteRequest = CreateWebsiteRequest::validateAndCreate([
                'name' => 'Website Test',
                'url' => 'https://example.com',
            ]);

            $createWebsiteInteractor = new CreateWebsiteInteractor();
            $createWebsiteInteractor->execute($createWebsiteRequest);

        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('url', $exception->errors());
            $this->assertContains('The url has already been taken.', $exception->errors()['url']);
        }
    }

    #[Test]
    public function can_create_website(): void
    {
        $createWebsiteRequest = CreateWebsiteRequest::validateAndCreate([
            'name' => 'Website Test',
            'url' => 'https://example.com',
        ]);
        $createWebsiteInteractor = new CreateWebsiteInteractor();
        $createWebsiteInteractor->execute($createWebsiteRequest);

        $website = Website::where('url', 'https://example.com')->first();
        $this->assertNotNull($website);
        $this->assertEquals('Website Test', $website->name);
        $this->assertEquals('https://example.com', $website->url);
    }

    #[Test]
    public function can_send_email_when_a_website_is_created(): void
    {
        Mail::fake();
        $createWebsiteRequest = new CreateWebsiteRequest;
        $createWebsiteRequest->name = 'Example Website';
        $createWebsiteRequest->url = 'https://example.com';

        $createWebsiteInteractor = new CreateWebsiteInteractor;
        $createWebsiteInteractor->execute($createWebsiteRequest);

        Mail::assertSent(WebsiteCreated::class, function ($mail) {
            return $mail->hasTo('admin@example.com');
        });

    }
}
