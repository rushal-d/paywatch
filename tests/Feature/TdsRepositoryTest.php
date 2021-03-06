<?php

namespace Tests\Feature;

use App\Repositories\TdsRepository;
use Tests\TestCase;

class TdsRepositoryTest extends TestCase
{

    private $tdsRepository;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->tdsRepository = $this->app->make(TdsRepository::class);
    }

    public function testGetTdsDeductionAmountBySlabNumber()
    {
        $tdsDeductionAmount = $this->tdsRepository->getTdsDeductionAmountBySlabNumber(1, 1000000);
        dd($tdsDeductionAmount);
        $this->assertEquals(1,1);
    }
}
