<?php

namespace Modules\Ticket\Tests\Repository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Modules\Ticket\Model\Ticket;
use Modules\Ticket\Repository\TicketRepository;

final class TicketRepositoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateNewTicket_ShouldSaveTheNewRecordOnDatabase(): void
    {
        $payload = new Ticket();
        $payload->title = $this->faker->sentence;
        $payload->description = $this->faker->text;

        $repository = new TicketRepository();
        $newTicket = $repository->save($payload);

        $this->assertDatabaseHas($newTicket->getTable(), $newTicket->toArray());
    }

    public function testQueryTickets_ShouldReturnACollectionWithAFewTickets(): void
    {
        factory(Ticket::class, 10)->create();

        $repository = new TicketRepository();
        $tickets = $repository->queryTickets(1);

        $this->assertDatabaseCount((new Ticket())->getTable(), 10);
        $this->assertEquals(10, $tickets->count());
    }
}
