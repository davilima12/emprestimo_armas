<?php

declare(strict_types=1);

namespace App\Features\Auth\Enums;

enum Permissions: string
{
    case CREATE_USER = 'create_user';
    case LIST_USERS = 'list_users';
    case FILTER_USERS = 'filter_users';
    case CREATE_ADMIN = 'create_admin';
    case VIEW_PROFILE = 'view_profile';
    case UPLOAD_MEDIA = 'upload_media';
    case UPDATE_PRODUCT_IMAGES = 'update_product_images';
    case CREATE_PRODUCT = 'create_product';
    case LIST_PRODUCTS = 'list_products';
    case CREATE_SALES = 'create_sales';
    case CREATE_FINANCIAL_MOVEMENT = 'create_financial_movement';
    case CREATE_PROFIT_MARGIN = 'create_profit_margin';
    case VIEW_DASHBORAD = 'view_dashboard';

    public function id(): int
    {
        return match ($this) {
            self::CREATE_USER => 1,
            self::LIST_USERS => 2,
            self::FILTER_USERS => 3,
            self::CREATE_ADMIN => 4,
            self::VIEW_PROFILE => 5,
            self::UPLOAD_MEDIA => 6,
            self::CREATE_PRODUCT => 7,
            self::LIST_PRODUCTS => 8,
            self::CREATE_SALES => 9,
            self::CREATE_FINANCIAL_MOVEMENT => 10,
            self::CREATE_PROFIT_MARGIN => 11,
            self::VIEW_DASHBORAD => 12,
            self::UPDATE_PRODUCT_IMAGES => 13,
        };
    }

    public static function fromId(int $id): self
    {
        return match ($id) {
            self::CREATE_USER->id() => self::CREATE_USER,
            self::LIST_USERS->id() => self::LIST_USERS,
            self::FILTER_USERS->id() => self::FILTER_USERS,
            self::CREATE_ADMIN->id() => self::CREATE_ADMIN,
            self::VIEW_PROFILE->id() => self::VIEW_PROFILE,
            self::UPLOAD_MEDIA->id() => self::UPLOAD_MEDIA,
            self::CREATE_PRODUCT->id() => self::CREATE_PRODUCT,
            self::LIST_PRODUCTS->id() => self::LIST_PRODUCTS,
            self::CREATE_SALES->id() => self::CREATE_SALES,
            self::CREATE_FINANCIAL_MOVEMENT->id() => self::CREATE_FINANCIAL_MOVEMENT,
            self::CREATE_PROFIT_MARGIN->id() => self::CREATE_PROFIT_MARGIN,
            self::VIEW_DASHBORAD->id() => self::VIEW_DASHBORAD,
            self::UPDATE_PRODUCT_IMAGES->id() => self::UPDATE_PRODUCT_IMAGES,
        };
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->value,
        ];
    }
}
