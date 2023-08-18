<?php

namespace ONSBKS_Slots\Includes\Entities;

interface IEntities
{

    function find_all(string $per_page, string $paged);

    function find_one(string $id);

    function delete(string $id): int;
}