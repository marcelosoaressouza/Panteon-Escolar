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

ModuleFactory::IncludePhp("panteonescolar", "classes/base/PanteonEscolarBaseModule.class.php");

class LogarUsuario extends UsersDBDataSet
{

  public function UsersDBDataSet($context, $dataBase)
  {
    $this->_context = $context;
    $this->_DB = new DBDataSet($dataBase, $context);
    $this->configTableNames();

  }

  protected function configTableNames()
  {
    $this->_UserTable = new UserTable();
    $this->_UserTable->Table = "usuario";
    $this->_UserTable->Id = "id_usuario";
    $this->_UserTable->Name = "nome_completo_usuario";
    $this->_UserTable->Email= "email_usuario";
    $this->_UserTable->Password = "senha_usuario";
    $this->_UserTable->Created = "data_cadastro_usuario";
    $this->_UserTable->Username = "login_usuario";
    $this->_UserTable->Admin = "admin_usuario";
    $this->_UserTable->Instituicao = "id_instituicao";

    $this->_CustomTable = new CustomTable();
    $this->_CustomTable->Table = "usuario_x_nivel_acesso";
    $this->_CustomTable->Id = "id_usuario_x_nivel_acesso";
    $this->_CustomTable->Name = "nome_usuario_x_nivel_acesso";
    $this->_CustomTable->Value = "valor_usuario_x_nivel_acesso";

    $this->_RolesTable = new RolesTable();
    $this->_RolesTable->Table = "nivel_acesso";
    $this->_RolesTable->Role = "nome_nivel_acesso";
    $this->_RolesTable->Site = "site_nivel_acesso";

  }
  /**
   *
   * Save the current UsersAnyDataSet
   */
  // Modificado por mim dia 20 de abril de 2010. Aqui.
  public function Save()
  {
    foreach($this->_cacheUserOriginal as $key=>$value)
    {
      $srOri = $this->_cacheUserOriginal[$key];
      $srMod = $this->_cacheUserWork[$key];

      $changed = false;
      foreach($srOri->getFieldNames() as $keyfld=>$fieldname)
      {
        if($srOri->getField($fieldname) != $srMod->getField($fieldname))
        {
          $changed = true;
          break;
        }
      }

      if($changed)
      {
        $sql = "UPDATE ".$this->_UserTable->Table;
        $sql .= " SET ".$this->_UserTable->Name." = [[".$this->_UserTable->Name."]] ";
        $sql .= ", ".$this->_UserTable->Email." = [[".$this->_UserTable->Email."]] ";
        $sql .= ", ".$this->_UserTable->Username." = [[".$this->_UserTable->Username."]] ";
        $sql .= ", ".$this->_UserTable->Password." = [[".$this->_UserTable->Password."]] ";
        $sql .= ", ".$this->_UserTable->Created." = [[".$this->_UserTable->Created."]] ";
        $sql .= ", ".$this->_UserTable->Admin." = [[".$this->_UserTable->Admin."]] ";
        $sql .= ", ".$this->_UserTable->Instituicao." = [[".$this->_UserTable->Instituicao."]] ";
        $sql .= " WHERE ".$this->_UserTable->Id." = [[".$this->_UserTable->Id . "]]";

        $param = array();
        $param[$this->_UserTable->Name] = $srMod->getField($this->_UserTable->Name);
        $param[$this->_UserTable->Email] = $srMod->getField($this->_UserTable->Email);
        $param[$this->_UserTable->Username] = $srMod->getField($this->_UserTable->Username);
        $param[$this->_UserTable->Password] = $srMod->getField($this->_UserTable->Password);
        $param[$this->_UserTable->Created] = $srMod->getField($this->_UserTable->Created);
        $param[$this->_UserTable->Admin] = $srMod->getField($this->_UserTable->Admin);
        $param[$this->_UserTable->Instituicao] = $srMod->getField($this->_UserTable->Instituicao);
        $param[$this->_UserTable->Id] = $srMod->getField($this->_UserTable->Id);

        $this->_DB->execSQL($sql, $param);
      }
    }
    $this->_cacheUserOriginal = array();
    $this->_cacheUserWork = array();
  }

  /**
   * Add new user in database
   *
   * @param string $name
   * @param string $userName
   * @param string $email
   * @param string $password
   * @return bool
   */
  public function addUserPanteonEscolar($name, $userName, $email, $password, $id_instituicao)
  {
    if($this->getUserEMail($email) != null)
    {
      return false;
    }

    if($this->getUserName($userName) != null)
    {
      return false;
    }

    $sql = " INSERT INTO ".$this->_UserTable->Table." (".$this->_UserTable->Name.", ".$this->_UserTable->Email.", ".$this->_UserTable->Username .", ".$this->_UserTable->Password .", ".$this->_UserTable->Instituicao .", ".$this->_UserTable->Created ." ) ";
    $sql .=" VALUES ([[".$this->_UserTable->Name."]], [[".$this->_UserTable->Email."]], [[".$this->_UserTable->Username ."]], [[".$this->_UserTable->Password ."]], [[".$this->_UserTable->Instituicao ."]], [[".$this->_UserTable->Created ."]] ) ";

    $param = array();
    $param[$this->_UserTable->Name] = $name;
    $param[$this->_UserTable->Email] = strtolower($email);
    $param[$this->_UserTable->Username] = preg_replace('/(?:([\w])|([\W]))/', '\1', strtolower($userName));
    $param[$this->_UserTable->Password] = $this->getSHAPassword($password);
    $param[$this->_UserTable->Instituicao] = $id_instituicao;
    $param[$this->_UserTable->Created] = date("Y-m-d H:i:s");

    $this->_DB->execSQL($sql, $param);

    $db = new UsuarioXNivelAcessoDB($this->_context);
    $db->cadastrarAnalista(strtolower($email));

    return true;
  }
}

?>