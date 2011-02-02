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

class TurmaModel extends PanteonEscolarBaseModel
{
  private $_nome_turma;
  private $_id_instituicao;

  public function getNomeTurma()
  {
    return $this->_nome_turma;
  }

  public function setNomeTurma($nome_turma)
  {
    $this->_nome_turma = $nome_turma;
  }

  public function getIDInstituicao()
  {
    return $this->_id_instituicao;
  }

  public function setIDInstituicao($id_instituicao)
  {
    $this->_id_instituicao = $id_instituicao;
  }

}

?>