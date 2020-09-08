<?php

namespace App\Repositories;

use App\SocialSecurityTaxStatement;

class SocialSecurityTaxStatementRepository
{
    public function get()
    {
        return SocialSecurityTaxStatement::query();
    }

}
