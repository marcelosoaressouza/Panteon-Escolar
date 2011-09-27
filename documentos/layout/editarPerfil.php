<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="estilos/principal.css" rel="stylesheet" type="text/css" />
</head>

<body style="background: #FFFFFF">
<div id="ContUsuario">
  <div id="BoxUsuarioUsuario"><a href="index.html"><img src="images/Layout/Locadores/FotoUsuario.png" width="142" height="189" alt="Nome do Usuário" longdesc="images/Layout/Locadores/FotoUsuario.png" title="Nome do usuário" /><br />
  </a><br />
  <strong style=" font-family:Helvetica; font-size: 12px; text-align:center; font-weight: normal; text-align: center; width: 100%; float: left; color: #FF0000">Dimensões da foto<br />
  142px por 189px</strong>  </div>
  <div id="BoxUsuarioInformacoes">
    <div id="BoxUsuariosInf2" style="text-align: left; height: auto; font-family:Helvetica; font-size: 14px; font-weight: bold;">
      <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1" style="float: left; height: auto; width: 550px;">
        <div style="float: left; width: 100%">
        
       	  <strong style="float: left; width: 76px; text-align: left; height: 22px;">Nome:</strong> 
          <input name="textfield" type="text" id="textfield" style=" height: 22px; float:left; border: solid #006699 1px; display: block;" value="Antonio Galdino" />
        
        <strong style="float: left; width: 54px; text-align: right; height: 22px; margin-left: 10px;">E-mail </strong>  
          <input name="textfield2" type="text" id="textfield2"  style=" height: 22px; float:left; border: solid #006699 1px; width: 210px;" value="contato@panteon.gov.br" />
        
        </div>
        
        
        <div style="float: left; width: 100%; margin-top: 4px;">
        
        <strong style="float: left; width: 76px; text-align:left;">Instituição</strong>
          <input name="textfield3" type="text" id="textfield3" style=" height: 22px; float:left; border: solid #006699 1px; display: block;" value="COLEGIO TEIXEIRA LEAL" />
            
        <strong style="float: left; width: 54px; text-align: right; height: 22px; margin-left: 10px;">Função</strong>

          <select name="select" id="select" style=" height: 22px; float:left; border: solid #006699 1px; display: block;">
            <option value="Administrador">Administrador</option>
            <option value="Gestor">Gestor</option>
            <option value="Coordenador">Coordenador</option>
            <option value="Editor">Editor</option>
            <option value="Analista">Analista</option>
            <option value="Visitante">Visitante</option>
          </select>
        
       
        </div>
         
         
         <div style="float: left; width: 100%; margin-top: 4px;">
         
         <strong style="float: left; width: 76px; text-align:left;">Estado:</strong> 

          <select name="select2" id="select2" style=" height: 22px; float:left; border: solid #006699 1px; display: block;">
            <option value="Acre">Acre</option>
            <option value="Alagoas">Alagoas</option>
            <option value="Amapá">Amapá</option>
            <option value="Amazonas">Amazonas</option>
            <option value="Bahia">Bahia</option>
            <option value="Ceará">Ceará</option>
            <option value="Distrito Federal">Distrito Federal</option>
            <option value="Espírito Santo">Espírito Santo</option>
            <option value="Goiás">Goiás</option>
            <option value="Maranhão">Maranhão</option>
            <option value="Mato Grosso">Mato Grosso</option>
            <option value="Mato Grosso do Sul">Mato Grosso do Sul</option>
            <option value="Minas Gerais">Minas Gerais</option>
            <option value="Pará">Pará</option>
            <option value="Paraíba">Paraíba</option>
            <option value="Paraná">Paraná</option>
            <option value="Pernambuco">Pernambuco</option>
            <option value="Piauí">Piauí</option>
            <option value="Rio de Janeiro">Rio de Janeiro</option>
            <option value="Rio Grande do Norte">Rio Grande do Norte</option>
            <option value="Rio Grande do Sul">Rio Grande do Sul</option>
            <option value="Rondônia">Rondônia</option>
            <option value="Roraima">Roraima</option>
            <option value="Santa Catarina">Santa Catarina</option>
            <option value="São Paulo">São Paulo</option>
            <option value="Sergipe">Sergipe</option>
            <option value="Tocantins">Tocantins</option>
          </select>
        
          <strong style="float: left; width: 54px; text-align: right; height: 22px; margin-left: 18px;">Cidade</strong> 

          <select name="select3" id="select3" style=" height: 22px; float:left; border: solid #006699 1px; display: block;">
            <option value="Salvador">Salvador</option>
            <option value="Aracaju">Aracaju</option>
            <option value="São Paulo">São Paulo</option>
          </select>
         
        </div> 
        
 

         <div style="float: left; width: 100%; margin-top: 4px;">
         
         <strong style="float: left; width: 45px; text-align: left; height: 22px;">Foto:</strong>

          <input type="file" name="fileField" id="fileField" style=" height: 22px; float:left; border: solid #006699 1px; display: block; margin-left: 30px;" />
         
         </div>
         <div style="float: left; width: 100%; margin-top: 4px;">
           <label>
           <input type="image" name="imageField" id="imageField" src="images/btnGravar.png" />
           </label>
           <label>
           <input type="image" name="imageField2" id="imageField2" src="images/btnCancelar.png" onclick="javascript:ajax('conteudoBoxUsuario', 'meuperfil.php');" />
           </label>
        </div>
      </form>
    </div>
    </div>
</div>
</body>
</html>
