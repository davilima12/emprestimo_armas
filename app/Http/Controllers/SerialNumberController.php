<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeaponSerialNumber;
use App\Models\HandheldWeaponSerialNumber;
use App\Models\BodyArmorSerialNumber;

class SerialNumberController extends Controller
{
    // Retorna números de série de armas por ID de Weapon
    public function getWeaponSerialNumbers($weaponId)
    {
        $serialNumbers = WeaponSerialNumber::where('weapon_types_id', $weaponId)->get();
        return response()->json($serialNumbers);
    }

    // Retorna números de série de armas portáteis por ID de Handheld Weapon
    public function getHandheldWeaponSerialNumbers($handheldWeaponId)
    {
        $serialNumbers = HandheldWeaponSerialNumber::where('handheld_weapons_id', $handheldWeaponId)->get();
        return response()->json($serialNumbers);
    }

    // Retorna números de série de coletes por ID de Body Armor
    public function getBodyArmorSerialNumbers($bodyArmorId)
    {
        $serialNumbers = BodyArmorSerialNumber::where('body_armor_id', $bodyArmorId)->get();
        return response()->json($serialNumbers);
    }
}
