<?php

namespace Tests\Feature;

use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\Website;
use Domain\Posts\Interactors\CreatePostInteractor;
use Domain\Posts\Interactors\PublishPostInteractor;
use Domain\Posts\Interactors\Requests\CreatePostRequest;
use Domain\ValidationExceptions\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    private array $testData;
    private int $websiteId;

    protected function setUp(): void
    {
        parent::setUp();
        $websiteData = Website::query()->create([
            'name' => 'Test Website',
            'url' => 'https://test.com',
        ]);
        $this->websiteId = $websiteData->id;
        $this->postData = [
            'title' => 'Test Post Title',
            'description' => 'Test Post Description',
            'website_id' => $this->websiteId,
        ];
    }

    // USER CREATES POST TESTS

   #[Test]
    public function can_not_create_post_without_a_title()
    {
        $request = new CreatePostRequest;

        $request->title = '';
        $request->description = 'Sample Description';
        $request->website_id = $this->websiteId;

        try {
            $interactor = app(CreatePostInteractor::class);
            $interactor->execute($request);
        } catch (ValidationException $e) {
            $this->assertEquals('The title field is required.', $e->getMessage());
        }
    }

    #[Test]
    public function can_not_create_post_without_a_description()
    {
        $request = new CreatePostRequest;

        $request->title = 'Sample Post Title';
        $request->description = '';
        $request->website_id = $this->websiteId;

        try {
            $interactor = app( CreatePostInteractor::class);
            $interactor->execute($request);
        } catch (ValidationException $e) {
            $this->assertEquals('The description field is required.', $e->getMessage());
        }
    }

    #[Test]
    public function can_not_create_post_with_a_duplicate_title()
    {
        Post::query()->create([
            'title' => 'Duplicate Title',
            'description' => 'First post description',
            'website_id' => $this->websiteId,
        ]);

        try {
            $request = new CreatePostRequest;
            $request->title = 'Duplicate Title';
            $request->description = 'Another post description';
            $request->website_id = $this->websiteId;
            $interactor = new CreatePostInteractor(new PublishPostInteractor);
            $interactor->execute($request);
        } catch (ValidationException $e) {
            $this->assertEquals('A post with the same title already exists.', $e->getMessage());
        }

    }

    #[Test]
    public function can_create_a_post()
    {
        $request = new CreatePostRequest;
        $request->title = 'Unique Post Title';
        $request->description = 'This is a sample post description.';
        $request->website_id = $this->websiteId;
        $interactor = new CreatePostInteractor(new PublishPostInteractor);
        $post = $interactor->execute($request);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($request->title, $post->title);
        $this->assertEquals($request->description, $post->description);
        $this->assertEquals($request->website_id, $post->website_id);
    }

    #[Test]
    public function can_send_email_to_all_subscribers_when_a_post_is_created(): void
    {
        Mail::fake();
        $subscriptions = Subscription::factory(5)
            ->create([
                'website_id' => $this->websiteId
            ]);

        $request = new CreatePostRequest;
        $request->title = $this->postData['title'];
        $request->description = $this->postData['description'];
        $request->website_id = $this->websiteId;

        $interactor = new CreatePostInteractor(new PublishPostInteractor);
        $post = $interactor->execute($request);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertTrue($post->sent);

        Mail::assertSent(PostPublished::class, 5);
        foreach ($subscriptions as $subscription) {
            $this->assertDatabaseHas('sent_emails', [
                'subscription_id' => $subscription->id,
                'post_id' => $post->id,
            ]);
        }
    }
}
