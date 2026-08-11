<?php

use App\Models\Complaint;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Complaint\ComplaintService;
beforeEach(function () {
    $this->service = app(ComplaintService::class);
});
it('deletes existing complaint', function () {

    $complaint = Complaint::factory()->create();

    $result = $this->service->delete($complaint->id);

    expect($result)->toBeTrue();

    $this->assertDatabaseMissing('complaints', [
        'id' => $complaint->id,
    ]);
});

it('throws exception when complaint does not exist', function () {

    $this->service->delete(999999);

})->throws(ModelNotFoundException::class);
it('deletes only requested complaint', function () {

    $first = Complaint::factory()->create();

    $second = Complaint::factory()->create();

    $this->service->delete($first->id);

    $this->assertDatabaseMissing('complaints', [
        'id' => $first->id,
    ]);

    $this->assertDatabaseHas('complaints', [
        'id' => $second->id,
    ]);
});
it('returns true after successful deletion', function () {

    $complaint = Complaint::factory()->create();

    expect(
        $this->service->delete($complaint->id)
    )->toBeTrue();

});