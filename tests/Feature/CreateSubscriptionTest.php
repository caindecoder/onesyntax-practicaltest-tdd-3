<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\Website;
use Domain\Subscriptions\Interactors\CreateSubscriptionInteractor;
use Domain\Subscriptions\Interactors\Requests\CreateSubscriptionRequest;
use Domain\ValidationExceptions\ValidationException;
use Domain\Websites\Interactors\CreateWebsiteInteractor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    private array $testData;

    protected function setUp(): void
    {
        parent::setUp();

        $website = Website::create([
            'name' => 'Test Website',
            'url' => 'https://test.com',
        ]);

        $this->testData = [
            'email' => 'user@example.com',
            'website_id' => $website->id,
        ];
    }

    // USER CREATES SUBSCRIPTION TESTS

    #[Test]
    public function can_not_create_subscription_with_invalid_data()
    {
        $request = new CreateSubscriptionRequest();

        $request->email = '';
        $request->website_id = '';

        try {
            $interactor = new CreateSubscriptionInteractor();
            $interactor->create($request);
        } catch (ValidationException $e) {
            $this->assertEquals('The email field is required.', $e->getMessage());
        }
    }

    #[Test]
    public function can_not_create_subscription_with_duplicate_data()
    {
        Subscription::create($this->testData);

        $request = new CreateSubscriptionRequest();

        $request->email = $this->testData['email'];
        $request->website_id = $this->testData['website_id'];

        Subscription::create($this->testData);

        try {
            $request->validate($request);
            $interactor = new CreateSubscriptionInteractor();
            $interactor->create($request);
        } catch (ValidationException $e) {
            $this->assertEquals('Subscription already exists for this email and website.', $e->getMessage());
        }
    }

    #[Test]
    public function can_create_subscription_with_valid_data()
    {
        $request = new CreateSubscriptionRequest();
        $request->email = $this->testData['email'];
        $request->website_id = $this->testData['website_id'];

        $interactor = new CreateSubscriptionInteractor();

        $subscription = $interactor->create($request);

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals($request->email, $subscription->email);
        $this->assertEquals($request->website_id, $subscription->website_id);

        $this->assertDatabaseHas('subscriptions', [
            'email' => $this->testData['email'],
            'website_id' => $this->testData['website_id'],
        ]);
    }


}
