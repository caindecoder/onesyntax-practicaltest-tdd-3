<?php

namespace Tests\Feature;

use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\Website;
use Domain\Posts\Interactors\CreatePostInteractor;
use Domain\Posts\Interactors\Requests\CreatePostRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    private Website $website;

    protected function setUp(): void
    {
        parent::setUp();

        $this->website = Website::query()->create([
            'name' => 'Test Website',
            'url' => 'https://test.com',
        ]);
    }

    // USER CREATES POST TESTS

   #[Test]
    public function can_not_create_post_without_a_title()
    {
        try {
            $createPostRequest = CreatePostRequest::validateAndCreate([
                'title' => '',
                'description' => 'Test Post Description',
                'website_id' => $this->website->getKey(),
                ]);

            $createPostInteractor = new CreatePostInteractor();
            $createPostInteractor->execute($createPostRequest);

        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('title', $exception->errors());
        }
    }

    #[Test]
    public function can_not_create_post_without_a_description()
    {
        try {
            $createPostRequest = CreatePostRequest::validateAndCreate([
                    'title' => 'Test Post Title',
                    'description' => '',
                    'website_id' => $this->website->getKey(),
            ]);

            $createPostInteractor = new CreatePostInteractor();
            $createPostInteractor->execute($createPostRequest);

        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('description', $exception->errors());
        }
    }

    #[Test]
    public function can_not_create_post_with_a_duplicate_title()
    {
        Post::query()->create([
            'title' => 'Duplicate Title',
            'description' => 'First post description',
            'website_id' => $this->website->getKey(),
        ]);

        try {
            $createPostRequest = CreatePostRequest::validateAndCreate([
                'title' => 'Duplicate Title',
                'description' => 'Another post description',
                'website_id' => $this->website->getKey(),
            ]);

            $createPostInteractor = new CreatePostInteractor();
            $createPostInteractor->execute($createPostRequest);

        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('title', $exception->errors());
        }
    }

    #[Test]
    public function can_create_a_post()
    {
        $createPostRequest = CreatePostRequest::validateAndCreate([
            'title' => 'Duplicate Title',
            'description' => 'Another post description',
            'website_id' => $this->website->getKey(),
        ]);

        $createPostInteractor = new CreatePostInteractor();
        $createPostInteractor->execute($createPostRequest);

        $this->assertDatabaseCount('posts', 1);
        $this->assertDatabaseHas('posts', [
            'title' => $createPostRequest->title,
            'description' => $createPostRequest->description,
            'website_id' => $createPostRequest->websiteId,
        ]);
    }

    #[Test]
    public function can_send_email_to_all_subscribers_when_a_post_is_created(): void
    {
        Mail::fake();
        Subscription::factory(5)->create([
            'website_id' => $this->website->getKey(),
            'email' => 'subscriber@example.com',
        ]);

        $createPostRequest = CreatePostRequest::validateAndCreate([
            'title' => 'Unique Title',
            'description' => 'Post description',
            'website_id' => $this->website->getKey(),
        ]);
        $createPostInteractor = new CreatePostInteractor();
        $createPostInteractor->execute($createPostRequest);
        $post = Post::where('title', 'Unique Title')->firstOrFail();

        Mail::assertQueued(PostPublished::class, 5);
        Mail::assertQueued(PostPublished::class, function ($mail) use ($post) {
            return $mail->post->id === $post->id;
        });
    }
}
