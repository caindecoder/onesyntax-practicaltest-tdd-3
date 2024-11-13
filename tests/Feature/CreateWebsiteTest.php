<?php

namespace Tests\Feature;

use App\Mail\PostPublished;
use App\Mail\WebsiteCreated;
use App\Models\Post;
use App\Models\SentEmail;
use App\Models\Subscription;
use App\Models\Website;
use Domain\ValidationExceptions\ValidationException;
use Domain\Websites\Interactors\CreateWebsiteInteractor;
use Domain\Websites\Interactors\Requests\CreateWebsiteRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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
        $request = new CreateWebsiteRequest;

        $request->name = '';
        $request->url = 'https://example.com';

        try {
            $interactor = new CreateWebsiteInteractor;
            $interactor->execute($request);
        } catch (ValidationException $e) {
            $this->assertEquals('The name field is required.', $e->getMessage());
        }
    }

    #[Test]
    public function can_not_create_website_without_a_url(): void
    {
        $request = new CreateWebsiteRequest;

        $request->name = 'Example Website';
        $request->url = '';

        try {
            $interactor = new CreateWebsiteInteractor;
            $interactor->execute($request);
        } catch (ValidationException $e) {
            $this->assertEquals('The url field is required.', $e->getMessage());
        }
    }

    #[Test]
    public function can_not_create_duplicate_website(): void
    {
        Website::create($this->testData);

        try {
            $request = new CreateWebsiteRequest;
            $request->name = 'Example Website';
            $request->url = 'https://example.com';
            $interactor = new CreateWebsiteInteractor;
            $interactor->execute($request);
        } catch (ValidationException $e) {
            $this->assertEquals('A website with the same name or URL already exists.', $e->getMessage());
        }
    }

    #[Test]
    public function can_create_website(): void
    {
        $request = new CreateWebsiteRequest;
        $request->name = 'Example Website';
        $request->url = 'https://example.com';

        $interactor = new CreateWebsiteInteractor;
        $website = $interactor->execute($request);

        $this->assertInstanceOf(Website::class, $website);
        $this->assertEquals($request->name, $website->name);
        $this->assertEquals($request->url, $website->url);
    }

    #[Test]
    public function can_send_email_when_a_website_is_created(): void
    {
        Mail::fake();
        $request = new CreateWebsiteRequest;
        $request->name = 'Example Website';
        $request->url = 'https://example.com';

        $interactor = new CreateWebsiteInteractor;
        $website = $interactor->execute($request);

        Mail::assertSent(WebsiteCreated::class, function ($mail) {
            return $mail->hasTo('admin@example.com');
        });

    }
}
