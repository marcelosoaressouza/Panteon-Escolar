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

class TermoDeUsoDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 4;

  public function generateObject($current) {
    if($this->_context->IsAuthenticated()) $this->_context->redirectUrl("/meuperfil");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "listarDireita") {
      $txt = "Estudo é o tempo que uma pessoa gasta na obtenção do conhecimento. É relativo a análise e avaliação de informações. Pessoas que gostam de estudar, são consideradas pessoas que pensam no seu futuro, que estão preparadas para enfrentar exames, provas, testes complicados. Estudo é treinar determinadas matérias na realização de exercícios. Por exemplo: Ler umas páginas de um livro e depois fazer exercicios sobre esse mesmo para ficar na cabeça, para aprender — estudar. Pode-se estudar de muitas maneiras, por meios de resumos, leitura, questões elaboradas por outra pessoa procurando auxiliar na aprendizagem, ou decorando as partes mais importantes do tema estudado. ";
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node);
      $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, "", $txt, "Wikipedia");

    }

    if($this->_opcao == "processPageField")  {
      $txt = "
             <b>1.</b> Os presentes termos governam todo o acesso e uso do site Panteon Escolar (doravante denominado “Panteon Escolar” ou “site”) por você (doravante denominado “usuário”). O registro de uma conta de usuário no Panteon Escolar implica, imediatamente, a aceitação da integridade deste documento e do documento FAQ, que esclarece algumas das regras destes termos e acrescenta outras. O registro de uma conta pressupõe que o usuário tenha lido atentamente os presentes termos e o documento FAQ, bem como eventuais versões futuras de qualquer um deles.
             <b>2.</b> A ausência de registro por parte de um usuário não o escusa da leitura destes termos e do documento FAQ. Usuários, registrados ou não, devem necessariamente observar o disposto no item 6, infra.
             <b>3.</b> As versões mais recentes destes termos e do documento FAQ são as vinculantes, regulando as relações passadas e presentes entre o Panteon Escolar e o usuário. Todo usuário encontra-se sujeito a eventuais mudanças na redação dos presentes termos e do documento FAQ, devendo manter-se em dia com qualquer atualização, comprometendo-se a ler os documentos sempre que estes forem alterados.
             <b>4.</b> O Panteon Escolar se compromete a apenas revelar dados de registro do usuário mediante ordem judicial, mas não se responsabiliza por eventuais danos causados pela obtenção ilícita dos mesmos dados por terceiros. Ao se registrar no Panteon Escolar, o usuário tem ciência de que, apesar de todos os cuidados tomados com a segurança do site, essa é uma possibilidade. Compromete-se, assim, a não ter o Panteon Escolar como responsável por danos que possam ocorrer pela obtenção ilícita, por terceiros, de seus dados pessoais.
             <b>5.</b> Todo o conteúdo enviado ao Panteon Escolar pelo usuário, seja como tópico ou comentário de tópico, é de integral responsabilidade do usuário, que atesta a) ser detentor de seus direitos autorais; b) estar sob o amparo de uma das limitações do art. 46 da Lei n.º 9.610/98 (Lei de Direitos Autorais); ou c) estar autorizado por licença para a publicação e reprodução do conteúdo. O envio de conteúdo de autoria do usuário implica, além disso, autorização automática para publicação e reprodução pelo Panteon Escolar, nos limites do próprio site.
             <b>6.</b> Os presentes termos são regidos pela legislação brasileira. Para eventuais controvérsias judiciais, tendo-se em mente e mais uma vez frisado o já afirmado no item 4, supra, fica eleito o foro da Capital do Estado da Bahia, com exclusão de qualquer outro.


             ";

      $span1->addXmlnukeObject(new XmlNukeText($txt));

    }

    // Inicio - menu head
    //
    if($this->_opcao == "menuHead") {
      $node = XmlUtil::CreateChild($current, "blockbarramenu", "");
      $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadInicial());

    }
    //
    // Fim - menu head


    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function filtro() {
    $span = new XmlnukeSpanCollection();
    $formPost = "module:panteonescolar.TermoDeUso";
    $form = new XmlFormCollection($this->_context, $formPost, "Bilbioteca Tema Panteon");

    $buttons = new XmlInputButtons();
    $buttons->addSubmit("Filtrar");

    $form->addXmlnukeObject($this->filtroMetodoAnalise());
    $form->addXmlnukeObject($this->filtroEstruturaSocial());
    $form->addXmlnukeObject($buttons);

    $span->addXmlnukeObject($form);

    return $span;

  }

  public function filtroMetodoAnalise() {
    $db = new MetodoAnaliseDB($this->_context);
    $it = $db->obterTodos();
    $listaMetodoAnalise = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_metodo_analise", "nome_metodo_analise");
    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_metodo_analise_filtro", "Metódo Análise", $listaMetodoAnalise);

    return $lista;

  }

  public function filtroEstruturaSocial() {
    $db = new EstruturaSocialDB($this->_context);
    $it = $db->obterTodos();
    $listaEstruturaSocial = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_estrutura_social", "nome_estrutura_social");
    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_estrutura_social_filtro", "Estrutura Social", $listaEstruturaSocial);

    return $lista;

  }

  public function TermoDeUsoDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>