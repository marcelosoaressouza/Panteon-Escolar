<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Modelo base para o Box</title>
<link href="estilos/principal.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="Boxes">
  <div id="BarraControla"><a href="javascript:;" title="FECHAR"><img src="images/Layout/fecharBox.png" alt="Fechar o Box" width="21" height="21" longdesc="images/Layout/fecharBox.png" /></a></div>
  <div id="conteudo">
    <div class="titulos" style="border-bottom: #FFFFFF solid 2px;;">CONFIGURAÇÕES</div>
  <div class="contents" style="width: 400px; margin-left: 50px; margin-top: 10px;">
    <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1" style="font-family:Helvetica; font-size:14px;">
      <div id="ForDireita" style="float: left; width:49%; text-align: right; font-weight: bold; ">
      	<p style=" float:right; width: 100%; height: auto; min-height: 22px; margin-bottom: 4px">nome</p>
        <p style=" float:right; width: 100%; height: auto; min-height: 22px; margin-bottom: 4px">email</p>
        <p style=" float:right; width: 100%; height: auto; min-height: 22px; margin-bottom: 4px">descrição</p>
        <p style=" float:right; width: 100%; height: auto; min-height: 22px; margin-bottom: 4px">&nbsp;</p>
        <p style=" float:right; width: 100%; height: auto; min-height: 22px; margin-bottom: 4px">&nbsp;</p>
        <p style=" float:right; width: 100%; height: auto; min-height: 22px; margin-bottom: 4px">temas do grupo</p>
        <p style=" float:right; width: 100%; height: auto; min-height: 22px; margin-bottom: 4px">&nbsp;</p>  
        <p style=" float:right; width: 100%; height: auto; min-height: 10px; margin-bottom: 4px">&nbsp;</p>
        <p style=" float:right; width: 100%; height: auto; min-height: 2px; margin-bottom: 4px">&nbsp;</p>
        <p style=" float:right; width: 100%; height: auto; min-height: 22px; margin-bottom: 4px">mensagem de boas-vindas</p>
        <p style=" float:right; width: 100%; height: auto; min-height: 22px; margin-bottom: 4px">imagem</p>
        <p>&nbsp;</p>
      </div>
      
      <div id="ForEsquerda" style="float: left; width:48%; text-align: left; margin-left: 8px; ">
      	<p>
      	  <label>
      	  <input type="text" name="textfield" id="textfield" style=" float:left; width: 300px; height: auto; min-height: 22px; margin-bottom: 4px" />
      	  <br />
      	  <input type="text" name="textfield2" id="textfield2" style=" float:left; width: 300px; height: auto; min-height: 22px; margin-bottom: 4px"  />
      	  </label>
      	</p>
      	<p>
      	  <textarea name="textfield3" rows="4" id="textfield3" style=" float:left; width: 300px; height: auto; min-height: 22px; margin-bottom: 4px"></textarea>
      	</p>
      	<p>
      	  <textarea name="textfield4" rows="4" id="textfield4" style=" float:left; width: 300px; height: auto; min-height: 22px; margin-bottom: 4px"></textarea>
      	  <input type="text" name="textfield5" id="textfield5" style=" float:left; width: 300px; height: auto; min-height: 22px; margin-bottom: 4px"  />
      	  <label>
      	  <input type="file" name="fileField" id="fileField" style=" float:left; width: 300px; height: auto; min-height: 22px; margin-bottom: 4px"  />
      	  </label>
      	</p>
      </div>
      
      

<div id="Forbaixo" style="float: left; width:210px; text-align: left; margin-left: 204px; margin-top: 10px; text-align: left; ">
  <label>
  <input type="image" name="imageField" id="imageField" src="images/btnCancelar.png" /> 
  <input type="image" name="imageField2" id="imageField2" src="images/btnGravar.png" />
  </label>
</div>

      
      
      
    </form>
    </div>
  <div class="contents"></div>
</div>
</div>

</body>
</html>
