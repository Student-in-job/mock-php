<?php

interface IQuery
{
    public function ExecuteQuery(string $query): void;

    public function GetData(string $query): array;
}