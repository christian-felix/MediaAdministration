<?php


namespace src\Model;

/**
 * Class User
 * @package src\Model
 */
class User extends Entity
{

    protected string $name;
    protected string $password;
    protected string $email;
    protected int $role;
    protected bool $active;




}