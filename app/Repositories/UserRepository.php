<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    /**
     * Class Constructor
     *
     * @return void
     *
     */
    public function __construct()
    {
        parent::__construct(User::class);
    }
}
