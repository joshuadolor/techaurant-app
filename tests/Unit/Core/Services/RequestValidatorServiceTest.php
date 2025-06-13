<?php

namespace Tests\Unit\Core\Services;

use App\Core\Services\RequestValidatorService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RequestValidatorServiceTest extends TestCase
{
    private RequestValidatorService $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new RequestValidatorService();
    }

    /** @test */
    public function it_validates_valid_data()
    {
        $request = new Request();
        $request->merge([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'age' => 25
        ]);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'age' => 'required|integer|min:18'
        ];

        $result = $this->validator->validate($request, $rules);

        $this->assertEquals([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'age' => 25
        ], $result);
    }

    /** @test */
    public function it_throws_exception_for_invalid_data()
    {
        $request = new Request();
        $request->merge([
            'name' => '',  // Empty name
            'email' => 'invalid-email',  // Invalid email
            'age' => 15  // Underage
        ]);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'age' => 'required|integer|min:18'
        ];

        $this->expectException(ValidationException::class);

        $this->validator->validate($request, $rules);
    }

    /** @test */
    public function it_validates_nested_data()
    {
        $request = new Request();
        $request->merge([
            'user' => [
                'name' => 'John Doe',
                'email' => 'john@example.com'
            ],
            'address' => [
                'street' => '123 Main St',
                'city' => 'New York'
            ]
        ]);

        $rules = [
            'user.name' => 'required|string|max:255',
            'user.email' => 'required|email',
            'address.street' => 'required|string',
            'address.city' => 'required|string'
        ];

        $result = $this->validator->validate($request, $rules);

        $this->assertEquals([
            'user' => [
                'name' => 'John Doe',
                'email' => 'john@example.com'
            ],
            'address' => [
                'street' => '123 Main St',
                'city' => 'New York'
            ]
        ], $result);
    }

    /** @test */
    public function it_validates_array_data()
    {
        $request = new Request();
        $request->merge([
            'tags' => ['php', 'laravel', 'testing'],
            'scores' => [85, 90, 95]
        ]);

        $rules = [
            'tags' => 'required|array',
            'tags.*' => 'required|string',
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:0|max:100'
        ];

        $result = $this->validator->validate($request, $rules);

        $this->assertEquals([
            'tags' => ['php', 'laravel', 'testing'],
            'scores' => [85, 90, 95]
        ], $result);
    }

    /** @test */
    public function it_validates_with_custom_rules()
    {
        $request = new Request();
        $request->merge([
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $rules = [
            'password' => 'required|string|min:8|confirmed'
        ];

        $result = $this->validator->validate($request, $rules);

        $this->assertEquals([
            'password' => 'password123'
        ], $result);
    }

    /** @test */
    public function it_validates_with_conditional_rules()
    {
        $request = new Request();
        $request->merge([
            'type' => 'business',
            'company_name' => 'Acme Corp'
        ]);

        $rules = [
            'type' => 'required|in:personal,business',
            'company_name' => 'required_if:type,business|string',
            'personal_name' => 'required_if:type,personal|string'
        ];

        $result = $this->validator->validate($request, $rules);

        $this->assertEquals([
            'type' => 'business',
            'company_name' => 'Acme Corp'
        ], $result);
    }

    /** @test */
    public function it_validates_with_date_rules()
    {
        $request = new Request();
        $request->merge([
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31'
        ]);

        $rules = [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ];

        $result = $this->validator->validate($request, $rules);

        $this->assertEquals([
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31'
        ], $result);
    }

    /** @test */
    public function it_validates_with_file_rules()
    {
        $request = new Request();
        $request->files->add([
            'document' => new \Illuminate\Http\UploadedFile(
                __FILE__,
                'test.php',
                'text/plain',
                null,
                true
            )
        ]);

        $rules = [
            'document' => 'required|file|max:1024'
        ];

        $result = $this->validator->validate($request, $rules);

        $this->assertArrayHasKey('document', $result);
    }
} 