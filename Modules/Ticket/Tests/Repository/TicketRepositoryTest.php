<?php

namespace Modules\Ticket\Tests\Repository;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Assert;
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

    public function testRetrieveTicketByIdOrCry_ShouldRetrieveAExpectedTicketFromTheDatabase(): void {
        $payload = factory(Ticket::class, 10)->create();
        $expectedTicket = $payload->first();

        $repository = new TicketRepository();
        $retrievedTicket = $repository->retrieveTicketByIdOrCry($payload->first()->id);

        Assert::assertEquals($expectedTicket->id, $retrievedTicket->id);
        Assert::assertEquals($expectedTicket->title, $retrievedTicket->title);
        Assert::assertEquals($expectedTicket->description, $retrievedTicket->description);
    }

    public function testRetrieveTicketByIdOrCry_ShouldThrowException_IfTheProvidedIdIsNotPresentInTheDatabase(): void {
        $repository = new TicketRepository();

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('No query results for model [Modules\Ticket\Model\Ticket] some unknown id');
        $repository->retrieveTicketByIdOrCry($id = "some unknown id");
    }
}
