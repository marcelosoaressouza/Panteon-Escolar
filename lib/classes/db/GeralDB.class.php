<?php

/*
*
* Panteon Escolar
*
* Yuri Wanderley (yuri.wanderley at gmail.com)
* Tarcisio Araujo (tatauphp at gmail.com)
* Marcelo Soares Souza (marcelo at juntadados.org)
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* http://www.gnu.org/licenses/gpl-2.0.html
*
*/

class GeralDB extends PanteonEscolarBaseDBAccess
{

  public function obter($oque, $onde, $id)
  {
    $sql = "SELECT ".$oque." FROM ".$onde." WHERE ".$onde.".id_".$onde." = ".$id;
    $it = $this->getIterator($sql);

    while($it->hasNext())
    {
      $sr = $it->moveNext();
      $texto = $sr->getField($oque);

    }

    return $texto;
  }

  public function excluir($id, $oque)
  {
    $sql = "DELETE FROM ".$oque." WHERE id_".$oque." = ".$id;

    $resultado = $this->executeSQL($sql);

    return $resultado;

  }


}

?>
