<?php

namespace Tests\Feature;

use App\Models\Post;
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

    protected function setUp(): void
    {
        parent::setUp();
        $this->testData = [
            'title' => 'Website',
            'description' => 'Sample Description',
        ];
    }

    // USER CREATES POST TESTS

   #[Test]
    public function can_not_create_post_without_a_title()
    {
        $request = new CreatePostRequest();

        $request->title = '';
        $request->description = 'Sample Description';

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
        ]);

        $request = new CreatePostRequest();

        $request->title = 'Duplicate Title';
        $request->description = 'Another post description';

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

        $interactor = new CreatePostInteractor();

        $request->validate($request);
        $post = $interactor->create($request);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($request->title, $post->title);
        $this->assertEquals($request->description, $post->description);

        $this->assertDatabaseHas('posts', [
            'title' => 'Unique Post Title',
            'description' => 'This is a sample post description.',
        ]);
    }
}
