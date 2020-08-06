<?php

namespace Modules\Ticket\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Modules\Ticket\Model\Ticket;
use Tests\TestCase;

final class TicketFeatureTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testQueryTickets_ShouldReturnAResponseWithPageableContent(): void
    {
        $this->mockAccessTokenAcceptedResponse();
        $payload = factory(Ticket::class, 15)->create();

        $expectedResponseContent = [
            'current_page' => 1,
            'data' => $payload->toArray(),
            'first_page_url' => url('/ticket?page=1'),
            'from' => 1,
            'next_page_url' => null,
            'path' => url('/ticket'),
            'per_page' => 15,
            'prev_page_url' => null,
            'to' => 15,
        ];

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->get('/ticket');

        $response->assertStatus(200);
        $response->assertExactJson($expectedResponseContent);
    }

    public function testQueryTickets_ShouldReturnTheSecondPage_WhenTheUriParameterLeadToTheSecondPage(): void
    {
        $this->mockAccessTokenAcceptedResponse();
        $payload = factory(Ticket::class, 16)->create();

        $expectedResponseContent = [
            'current_page' => 2,
            'data' => [
                $payload->last()->toArray()
            ],
            'first_page_url' => url('/ticket?page=1'),
            'from' => 16,
            'next_page_url' => null,
            'path' => url('/ticket'),
            'per_page' => 15,
            'prev_page_url' => url('/ticket?page=1'),
            'to' => 16,
        ];

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->get('/ticket?page=2');

        $response->assertStatus(200);
        $response->assertExactJson($expectedResponseContent);
    }

    public function testQueryTickets_ShouldReturnAnEmptyResponse_WhenThereIsNotTicketSaved(): void
    {
        $this->mockAccessTokenAcceptedResponse();
        $expectedResponseContent = [
            'current_page' => 1,
            'data' => [],
            'first_page_url' => url('/ticket?page=1'),
            'from' => null,
            'next_page_url' => null,
            'path' => url('/ticket'),
            'per_page' => 15,
            'prev_page_url' => null,
            'to' => null,
        ];

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->get('/ticket');

        $response->assertStatus(200);
        $response->assertJson($expectedResponseContent);
    }

    public function testQueryTickets_ShouldReturnAnForbiddenResponse_IfTheAuthenticationServiceRejectsTheAccessToken(): void
    {
        $this->mockAccessTokenRejectionResponse();

        $response = $this->get('/ticket');

        $response->assertStatus(403);
        $response->assertExactJson([
            'error' => 'Access denied',
        ]);
    }

    public function testCreateNewTicket_ShouldReturnAResponseWithTheContentOfTheNewTicket(): void
    {
        $this->mockAccessTokenAcceptedResponse();
        $payload = [
            'title' => $this->faker->title,
            'description' => $this->faker->text,
        ];

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->postJson('/ticket', $payload);

        $response->assertStatus(201);
        $response->assertJson($payload);
    }

    public function testCreateNewTicket_ShouldReturnAnErrorResponse_IfTheNewTicketTitleIsEmpty(): void
    {
        $this->mockAccessTokenAcceptedResponse();
        $payload = [
            'title' => '',
            'description' => $this->faker->text,
        ];

        $expectedErrorResponse = [
            'message' => 'The given data was invalid.',
            'errors' => [
                'title' => [
                    'The title field is required for the ticket'
                ],
            ],
        ];

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->postJson('/ticket', $payload);

        $response->assertStatus(422);
        $response->assertJson($expectedErrorResponse);
    }

    public function testCreateNewTicket_ShouldReturnAnErrorResponse_IfTheNewTicketTitleIsTooLarge(): void
    {
        $this->mockAccessTokenAcceptedResponse();
        $payload = [
            'title' => str_repeat("a", 256),
            'description' => $this->faker->text,
        ];

        $expectedErrorResponse = [
            'message' => 'The given data was invalid.',
            'errors' => [
                'title' => [
                    'The title field can only have up to 255 characters'
                ],
            ],
        ];

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->postJson('/ticket', $payload);

        $response->assertStatus(422);
        $response->assertJson($expectedErrorResponse);
    }

    public function testCreateNewTicket_ShouldReturnAnErrorResponse_IfTheNewTicketDescriptionIsEmpty(): void
    {
        $this->mockAccessTokenAcceptedResponse();
        $payload = [
            'title' => $this->faker->title,
            'description' => '',
        ];

        $expectedErrorResponse = [
            'message' => 'The given data was invalid.',
            'errors' => [
                'description' => [
                    'The description field is required for the ticket'
                ],
            ],
        ];

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->postJson('/ticket', $payload);

        $response->assertStatus(422);
        $response->assertJson($expectedErrorResponse);
    }

    public function testCreateNewTicket_ShouldReturnAnErrorResponse_IfTheNewTicketDescriptionIsTooLarge(): void
    {
        $this->mockAccessTokenAcceptedResponse();
        $payload = [
            'title' => $this->faker->title,
            'description' => str_repeat("a", 501),
        ];

        $expectedErrorResponse = [
            'message' => 'The given data was invalid.',
            'errors' => [
                'description' => [
                    'The description field can only have up to 500 characters'
                ],
            ],
        ];

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->postJson('/ticket', $payload);

        $response->assertStatus(422);
        $response->assertJson($expectedErrorResponse);
    }

    public function testCreateNewTicket_ShouldReturnAnForbiddenResponse_IfTheAuthenticationServiceRejectsTheAccessToken(): void
    {
        $this->mockAccessTokenRejectionResponse();

        $payload = [
            'title' => $this->faker->title,
            'description' => $this->faker->text,
        ];

        $response = $this->postJson('/ticket', $payload);

        $response->assertStatus(403);
        $response->assertExactJson([
            'error' => 'Access denied',
        ]);
    }

    public function testUpdateTicket_ShouldReturnASuccessFullResponseWithTheValuesOfTheTicket(): void
    {
        $this->mockAccessTokenAcceptedResponse();
        $existingTicket = factory(Ticket::class)->create();

        $payload = [
            'title' => $this->faker->title,
            'description' => $this->faker->text,
        ];

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->putJson("/ticket/{$existingTicket->id}", $payload);

        $response->assertStatus(200);
        $response->assertJson($payload);
    }

    public function testUpdateTicket_ShouldReturnASuccessFullResponseWithTheValuesOfTheTicket_IfWeSendARequestJustWithTheTitle(): void
    {
        $this->mockAccessTokenAcceptedResponse();
        $existingTicket = factory(Ticket::class)->create();

        $payload = [
            'title' => $this->faker->title,
        ];

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->putJson("/ticket/{$existingTicket->id}", $payload);

        $response->assertStatus(200);
        $response->assertJson($payload);
        $response->assertJson([
            'description' => $existingTicket->description
        ]);
    }

    public function testUpdateTicket_ShouldReturnASuccessFullResponseWithTheValuesOfTheTicket_IfWeSendARequestJustWithTheDescription(): void
    {
        $this->mockAccessTokenAcceptedResponse();
        $existingTicket = factory(Ticket::class)->create();

        $payload = [
            'description' => $this->faker->text,
        ];

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->putJson("/ticket/{$existingTicket->id}", $payload);

        $response->assertStatus(200);
        $response->assertJson($payload);
        $response->assertJson([
            'title' => $existingTicket->title
        ]);
    }

    public function testUpdateTicket_ShouldReturnAnErrorResponse_IfWeSendATitleTooLarge(): void
    {
        $this->mockAccessTokenAcceptedResponse();
        $existingTicket = factory(Ticket::class)->create();

        $payload = [
            'title' => str_repeat('a', 256),
            'description' => $this->faker->text,
        ];

        $expectedErrorResponse = [
            'message' => 'The given data was invalid.',
            'errors' => [
                'title' => [
                    'The title field can only have up to 255 characters'
                ],
            ],
        ];

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->putJson("/ticket/{$existingTicket->id}", $payload);

        $response->assertStatus(422);
        $response->assertJson($expectedErrorResponse);
    }

    public function testUpdateTicket_ShouldReturnAnErrorResponse_IfWeSendADescriptionTooLarge(): void
    {
        $this->mockAccessTokenAcceptedResponse();
        $existingTicket = factory(Ticket::class)->create();

        $payload = [
            'title' => $this->faker->title,
            'description' => str_repeat('a', 501)
        ];

        $expectedErrorResponse = [
            'message' => 'The given data was invalid.',
            'errors' => [
                'description' => [
                    'The description field can only have up to 500 characters',
                ],
            ],
        ];

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->putJson("/ticket/{$existingTicket->id}", $payload);

        $response->assertStatus(422);
        $response->assertJson($expectedErrorResponse);
    }

    public function testUpdateTicket_ShouldReturnAnErrorResponse_IfWeSendAnEmptyPayload(): void
    {
        $this->mockAccessTokenAcceptedResponse();
        $existingTicket = factory(Ticket::class)->create();

        $payload = [];
        $expectedErrorResponse = [
            'message' => 'The given data was invalid.',
            'errors' => [
                'description' => [
                    'You need to provide at least one of the fields',
                ],
            ],
        ];

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->putJson("/ticket/{$existingTicket->id}", $payload);

        $response->assertStatus(422);
        $response->assertJson($expectedErrorResponse);
    }

    public function testUpdateTicket_ShouldReturnAnErrorResponse_IfWeSendAnInvalidTicketId(): void
    {
        $this->mockAccessTokenAcceptedResponse();
        $payload = [
            'title' => $this->faker->title,
            'description' => $this->faker->text,
        ];

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->putJson("/ticket/unkown-id", $payload);

        $response->assertStatus(404);
    }

    public function testUpdateTicket_ShouldReturnAnForbiddenResponse_IfTheAuthenticationServiceRejectsTheAccessToken(): void
    {
        $this->mockAccessTokenRejectionResponse();
        $existingTicket = factory(Ticket::class)->create();

        $payload = [
            'description' => $this->faker->text,
        ];

        $response = $this->putJson("/ticket/{$existingTicket->id}", $payload);

        $response->assertStatus(403);
        $response->assertExactJson([
            'error' => 'Access denied',
        ]);
    }

    public function testCloseTicket_ShouldReturnASuccessfulResponseWithTheExpectedMessage(): void
    {
        $this->mockAccessTokenAcceptedResponse();

        $originalTicket = factory(Ticket::class)->create();

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->putJson("/ticket/{$originalTicket->id}/close");

        $response->assertStatus(200);
        $response->assertExactJson([
            'message' => 'Ticket closed successfully!',
        ]);
    }

    public function testCloseTicket_ShouldReturnANotFoundResponse_IfWePassAnUnknownId(): void
    {
        $this->mockAccessTokenAcceptedResponse();

        $response = $this->withHeader('Authorization', 'some-access-token')
            ->putJson("/ticket/unkown-id/close");

        $response->assertStatus(404);
    }

    public function testCloseTicket_ShouldReturnAnForbiddenResponse_IfTheAuthenticationServiceRejectsTheAccessToken(): void
    {
        $this->mockAccessTokenRejectionResponse();
        $originalTicket = factory(Ticket::class)->create();

        $response = $this->putJson("/ticket/{$originalTicket->id}/close");

        $response->assertStatus(403);
        $response->assertExactJson([
            'error' => 'Access denied',
        ]);
    }

    private function mockAccessTokenAcceptedResponse(): void
    {
        Http::fake([
            '*' => Http::response(['active' => true], 200),
        ]);
    }

    private function mockAccessTokenRejectionResponse(): void
    {
        Http::fake([
            '*' => Http::response(['active' => false], 200),
        ]);
    }
}
