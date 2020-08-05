<?php

namespace Modules\Auth\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class AuthFeatureTest extends TestCase
{
    use WithFaker;

    public function testLoginUser_ShouldReturnASuccessfulResponseWithAnAccessToken(): void
    {
        $successfulResponseBody = [
            'access_token' => '123456',
            'expires_in' => 600
        ];

        $this->mockResponse($successfulResponseBody, 200);

        $payload = [
            'username' => $this->faker->userName,
            'password' => $this->faker->password,
        ];

        $expectedResponse = [
            'access_token' => '123456',
            'expires_in' => 600,
        ];

        $response = $this->postJson('/auth/login', $payload);
        $response->assertStatus(201);
        $response->assertExactJson($expectedResponse);
    }

    public function testLoginUser_ShouldReturnAnErrorResponse_IfTheAuthenticationServiceReturnsAnErrorResponse(): void
    {
        $this->mockResponse($responseBody = [], $httpStatus = 400);

        $payload = [
            'username' => $this->faker->userName,
            'password' => $this->faker->password,
        ];

        $expectedResponse = [
            'error' => 'Could not generate an access token, try again later',
        ];

        $response = $this->postJson('/auth/login', $payload);
        $response->assertStatus(400);
        $response->assertExactJson($expectedResponse);
    }

    public static function provideInvalidPayloadsForLogin(): iterable
    {
        yield 'without username' => ['', 'password'];
        yield 'username too large' => [str_repeat('a', 51), 'password'];
        yield 'without password' => ['username', ''];
        yield 'password too large' => ['username', str_repeat('a', 51)];
    }

    /** @dataProvider provideInvalidPayloadsForLogin
     * @param string $userName
     * @param string $password
     */
    public function testLoginUser_ShouldReturnAnErrorResponse_IfAnInvalidPayloadIsProvided(string $userName, string $password): void
    {
        $payload = [
            'username' => $userName,
            'password' => $password,
        ];

        $expectedResponse = [
            'message' => 'The given data was invalid.',
            'errors' => [
                'Either the username or the password field is invalid',
            ],
        ];

        $this->postJson('/auth/login', $payload)
            ->assertStatus(422)
            ->assertExactJson($expectedResponse);
    }

    public function testLogUserOut_ShouldReturnASuccessfulResponse_WhenTheUserWasLoggedIn(): void
    {
        $expectedResponse = [
            'message' => 'User logged out',
        ];

        $this->postJson('/auth/logout', $payload = [])
            ->assertStatus(200)
            ->assertExactJson($expectedResponse);
    }

    private function mockResponse(array $responseBody, int $httpStatus): void {
        Http::fake([
            '*' => Http::response($responseBody, $httpStatus),
        ]);
    }
}
