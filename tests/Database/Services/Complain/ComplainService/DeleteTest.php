<?php

use App\Models\Complain;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Complain\ComplainService;
beforeEach(function () {
    $this->service = app(ComplainService::class);
});
it('deletes existing complain', function () {

    $complain = Complain::factory()->create();

    $result = $this->service->delete($complain->id);

    expect($result)->toBeTrue();

    $this->assertDatabaseMissing('complains', [
        'id' => $complain->id,
    ]);
});

it('throws exception when complain does not exist', function () {

    $this->service->delete(999999);

})->throws(ModelNotFoundException::class);
it('deletes only requested complain', function () {

    $first = Complain::factory()->create();

    $second = Complain::factory()->create();

    $this->service->delete($first->id);

    $this->assertDatabaseMissing('complains', [
        'id' => $first->id,
    ]);

    $this->assertDatabaseHas('complains', [
        'id' => $second->id,
    ]);
});
it('returns true after successful deletion', function () {

    $complain = Complain::factory()->create();

    expect(
        $this->service->delete($complain->id)
    )->toBeTrue();

});