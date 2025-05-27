<?php

namespace App\Enums;

enum TypeUserEnum:string
{
  case Particulier = 'particulier';
  case Association = 'association';
  case Entreprise = 'entreprise';
  case Startup = 'startup';
  case Admin = 'admin';
  case Moderateur = 'moderateur';
   case Consommateur = 'consommateur';

  public function label(): string{
    return match ($this) {
     self::Particulier  => 'particulier',
     self::Association  => 'association',
     self::Entreprise  => 'entreprise',
     self::Startup  => 'startup',
     self::Admin  => 'admin',
     self::Moderateur  => 'moderateur',
     self::Consommateur => 'consommateur',
    };
  }
}
