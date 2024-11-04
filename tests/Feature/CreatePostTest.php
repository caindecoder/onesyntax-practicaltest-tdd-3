<?php

namespace Tests\Feature;

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

        $interactor = new CreatePostInteractor();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Post title is required.');


        $interactor->create($request);

    }

    #[Test]
    public function can_not_create_post_without_a_description()
    {

    }

    #[Test]
    public function can_not_create_post_with_a_duplicate_title()
    {

    }

    #[Test]
    public function can_create_a_post()
    {

    }
}
