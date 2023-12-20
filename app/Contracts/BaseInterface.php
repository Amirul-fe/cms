<?php

namespace App\Contracts;

interface BaseInterface
{
    public function index();

    public function create();

    public function store($request);

    public function show(string $id);

    public function edit(string $id);

    public function update($request, $id);

    public function delete(string $id);
}
