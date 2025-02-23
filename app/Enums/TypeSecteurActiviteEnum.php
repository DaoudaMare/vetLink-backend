<?php

namespace App\Enums;

enum TypeSecteurActiviteEnum:string{
  case ProductionAgricole = 'production_agricole';
  case Elevage = 'elevage';
  case Transformation = 'transformation';
  case Distribution = 'distribution';
  case Export = 'export';
  case Peche = 'peche';

  public function label(): string{
    return match ($this) {
     self::ProductionAgricole  => 'productionAgricole',
     self::Elevage  => 'elevage',
     self::Transformation  => 'transformation',
     self::Distribution  => 'distribution',
     self::Export  => 'export',
     self::Peche  => 'peche',   
    };
  }
}