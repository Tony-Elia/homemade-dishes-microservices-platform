// In your test case, use the following example
<?php
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminCustomerControllerTest extends TestCase
{
    public function test_index_displays_customers()
    {
        // Mock the HTTP request to the Java EE backend
        Http::fake([
            'http://localhost:8080/user-management-service/api/users/customers' => Http::response([
                [
                    'id' => 1,
                    'fullName' => 'John Doe',
                    'email' => 'johndoe@example.com',
                    'phoneNumber' => '1234567890',
                ],
                [
                    'id' => 2,
                    'fullName' => 'Jane Doe',
                    'email' => 'janedoe@example.com',
                    'phoneNumber' => '0987654321',
                ],
            ], 200),
        ]);

        // Simulate a request to the customer index page
        $response = $this->get(route('admin.customers'));

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert that the mock data is being passed to the view
        $response->assertViewHas('customers', function ($customers) {
            return $customers->count() == 2; // Ensure we have 2 customers
        });
    }
}
