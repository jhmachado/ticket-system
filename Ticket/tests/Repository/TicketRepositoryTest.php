<?php

namespace Test\Ticket\Repository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Ticket\Model\Ticket;
use Ticket\Repository\TicketRepository;

final class TicketRepositoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateNewTicket_ShouldSaveTheNewRecordOnDatabase(): void
    {
        $repository = new TicketRepository();

        $title = $this->faker->sentence;
        $description = $this->faker->text;
        $newTicket = $repository->createTicket($title, $description);

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
