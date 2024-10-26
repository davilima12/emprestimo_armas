<?php

declare(strict_types=1);

namespace App\Features\Auth\Enums;

enum Roles: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case GERENTE = 'gerente';
    case VENDEDOR = 'vendedor';
    case CLIENTE = 'cliente';
    case MARKETING = 'marketing';

    public function id(): int
    {
        return match ($this) {
            self::SUPER_ADMIN => 1,
            self::ADMIN => 2,
            self::GERENTE => 3,
            self::VENDEDOR => 4,
            self::CLIENTE => 5,
            self::MARKETING => 6,
        };
    }

    public function displayName(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::GERENTE => 'Gerente',
            self::VENDEDOR => 'Vendedor',
            self::CLIENTE => 'Cliente',
            self::MARKETING => 'Marketing',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Permissão a todos os recursos',
            self::ADMIN => 'Permissão a todos os recursos exceto adicionar um novo admin',
            self::GERENTE => 'Pode cadastrar, pesquisar, filtrar, porem não pode listar ou ver os graficos.',
            self::VENDEDOR => 'Pode cadastrar produtos e registrar vendas',
            self::CLIENTE => 'Tem acesso ao site, inventario, e pode receber emails',
            self::MARKETING=> 'Pode editar somente as imagens dos produtos'
        };
    }

    public static function parseOrFail(string $role): self
    {
        return match ($role) {
            self::SUPER_ADMIN->value => self::SUPER_ADMIN,
            self::ADMIN->value => self::ADMIN,
            self::GERENTE->value => self::GERENTE,
            self::VENDEDOR->value => self::VENDEDOR,
            self::CLIENTE->value => self::CLIENTE,
            self::MARKETING->value => self::MARKETING,
            default => throw new \InvalidArgumentException("Role $role not found"),
        };
    }

    public static function parseById(int $id): self
    {
        return match ($id) {
            self::SUPER_ADMIN->id() => self::SUPER_ADMIN,
            self::ADMIN->id() => self::ADMIN,
            self::GERENTE->id() => self::GERENTE,
            self::VENDEDOR->id() => self::VENDEDOR,
            self::CLIENTE->id() => self::CLIENTE,
            self::MARKETING->id() => self::MARKETING,
            default => throw new \InvalidArgumentException("Role $id not found"),
        };
    }
}
