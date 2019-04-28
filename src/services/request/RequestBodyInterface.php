<?php declare(strict_types=1);
namespace parabox\services\request;


interface RequestBodyInterface
{

    public function getBody();
    public function getPost(): array;
    public function getGet(): array;
    public function getHead(): array;
    public function getServer(): array;
}
