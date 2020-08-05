<?php

namespace Modules\Ticket\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Ticket\Model\Ticket;
use Tests\TestCase;

final class TicketFeatureTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testQueryTickets_ShouldReturnAResponseWithPageableContent(): void
    {
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

        $this->get('/ticket')
            ->assertStatus(200)
            ->assertExactJson($expectedResponseContent);
    }

    public function testQueryTickets_ShouldReturnTheSecondPage_WhenTheUriParameterLeadToTheSecondPage(): void
    {
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

        $this->get('/ticket?page=2')
            ->assertStatus(200)
            ->assertExactJson($expectedResponseContent);
    }

    public function testQueryTickets_ShouldReturnAnEmptyResponse_WhenThereIsNotTicketSaved(): void
    {
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

        $this->get('/ticket')
            ->assertStatus(200)
            ->assertJson($expectedResponseContent);
    }

    public function testCreateNewTicket_ShouldReturnAResponseWithTheContentOfTheNewTicket(): void
    {
        $payload = [
            'title' => $this->faker->title,
            'description' => $this->faker->text,
        ];

        $this->postJson('/ticket', $payload)
            ->assertStatus(201)
            ->assertJson($payload);
    }

    public function testCreateNewTicket_ShouldReturnAnErrorResponse_IfTheNewTicketTitleIsEmpty(): void
    {
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

        $this->postJson('/ticket', $payload)
            ->assertStatus(422)
            ->assertJson($expectedErrorResponse);
    }

    public function testCreateNewTicket_ShouldReturnAnErrorResponse_IfTheNewTicketTitleIsTooLarge(): void
    {
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

        $this->postJson('/ticket', $payload)
            ->assertStatus(422)
            ->assertJson($expectedErrorResponse);
    }

    public function testCreateNewTicket_ShouldReturnAnErrorResponse_IfTheNewTicketDescriptionIsEmpty(): void
    {
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

        $this->postJson('/ticket', $payload)
            ->assertStatus(422)
            ->assertJson($expectedErrorResponse);
    }

    public function testCreateNewTicket_ShouldReturnAnErrorResponse_IfTheNewTicketDescriptionIsTooLarge(): void
    {
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

        $this->postJson('/ticket', $payload)
            ->assertStatus(422)
            ->assertJson($expectedErrorResponse);
    }
}
