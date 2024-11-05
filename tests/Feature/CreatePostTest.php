<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Website;
use Domain\Posts\Interactors\CreatePostInteractor;
use Domain\Posts\Interactors\Requests\CreatePostRequest;

use Domain\ValidationExceptions\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $website = Website::create([
            'name' => 'Test Website',
            'url' => 'https://test.com',
        ]);
        $this->websiteId = $website->id;
        $this->testData = [
            'title' => 'Test Post Title',
            'description' => 'Test Post Description',
            'website_id' => $this->websiteId,
        ];
    }

    // USER CREATES POST TESTS

   #[Test]
    public function can_not_create_post_without_a_title()
    {
        $request = new CreatePostRequest();

        $request->title = '';
        $request->description = 'Sample Description';
        $request->website_id = $this->websiteId;

        try {
            $interactor = new CreatePostInteractor();
            $interactor->create($request);
        } catch (ValidationException $e) {
            $this->assertEquals('Post title is required.', $e->getMessage());
        }

    }

    #[Test]
    public function can_not_create_post_without_a_description()
    {
        $request = new CreatePostRequest();

        $request->title = 'Sample Post Title';
        $request->description = '';
        $request->website_id = $this->websiteId;

        try {
            $interactor = new CreatePostInteractor();
            $interactor->create($request);
        } catch (ValidationException $e) {
            $this->assertEquals('Post description is required.', $e->getMessage());
        }

    }

    #[Test]
    public function can_not_create_post_with_a_duplicate_title()
    {
        Post::create([
            'title' => 'Duplicate Title',
            'description' => 'First post description',
            'website_id' => $this->websiteId,
        ]);

        $request = new CreatePostRequest();

        $request->title = 'Duplicate Title';
        $request->description = 'Another post description';
        $request->website_id = $this->websiteId;

        Post::create($this->testData);

        try {
            $request->validate($request);
            $interactor = new CreatePostInteractor();
            $interactor->create($request);
        } catch (ValidationException $e) {
            $this->assertEquals('A post with the same title already exists.', $e->getMessage());
        }

    }

    #[Test]
    public function can_create_a_post()
    {
        $request = new CreatePostRequest();

        $request->title = 'Unique Post Title';
        $request->description = 'This is a sample post description.';
        $request->website_id = $this->websiteId;

        $interactor = new CreatePostInteractor();

        $request->validate($request);
        $post = $interactor->create($request);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($request->title, $post->title);
        $this->assertEquals($request->description, $post->description);
        $this->assertEquals($request->website_id, $post->website_id);

        $this->assertDatabaseHas('posts', [
            'title' => 'Unique Post Title',
            'description' => 'This is a sample post description.',
        ]);
    }
}
