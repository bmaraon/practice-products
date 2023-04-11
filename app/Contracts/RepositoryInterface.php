<?php

namespace App\Contracts;

interface RepositoryInterface
{
    public function pushCriteria($collection);
    public function paginateRecords(int $page, int $perPage);
    public function getRecordbyId(int $id);
    public function getRecordByIdWithTrash(int $id);
    public function createRecord(array $userDetails);
    public function updateRecord(int $id, array $userDetails);
    public function deleteRecord(int $id);
}
