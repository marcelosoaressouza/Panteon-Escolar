// top.location="/meuperfil"; return true;

function onSelectChange() { $("#id_cidade").load('/lib/ajax.php?uf=' + escape($(this).val())); }
function onSelectChangeFormSubmit() { this.form.submit(); }

jQuery(document).ready(function() {
  $("#caixa_login").fancybox({ 'scrolling' : 'no', 'titleShow' : false });

  $("#editform").bind("submit", function() { parent.$.fancybox.close(); });

  $(".lista_direita_detalhe").fancybox({
   'scrolling' : 'auto',
   'autoScale' : false,
   'autoDimensions' : false,
   'titleShow' : false,
   'width' : 700,
   'height' : 500,
   'transitionIn'		: 'none',
   'transitionOut'		: 'none'

  });

  $(".coletar").fancybox({
   'autoScale' : false,
   'autoDimensions' : false,
   'titleShow' : false,
   'scrolling' : 'no',
   'width' : 700,
   'height' : 300,
   'transitionIn'		: 'none',
   'transitionOut'		: 'none'

  });

  $(".delecao").fancybox({
   'autoScale' : false,
   'autoDimensions' : false,
   'titleShow' : false,
   'scrolling' : 'no',
   'width' : 340,
   'height' : 140,
   'transitionIn'		: 'none',
   'transitionOut'		: 'none'

  });
  
  $(".foto_detalhe").fancybox({
   'autoScale' : false,
   'autoDimensions' : false,
   'titleShow' : false,
   'scrolling' : 'no',
   'width' : 700,
   'height' : 630,
   'transitionIn'		: 'none',
   'transitionOut'		: 'none'

  });

  $(".video_detalhe").fancybox({
   'autoScale' : false,
   'autoDimensions' : false,
   'titleShow' : false,
   'scrolling' : 'no',
   'width' : 650,
   'height' : 450,
   'transitionIn'		: 'none',
   'transitionOut'		: 'none'

  });
  
  $(".youtube").click(function() {
                 $.fancybox({
                  'padding'             : 0,
                  'autoScale'   : false,
                  'transitionIn'        : 'none',
                  'transitionOut'       : 'none',
                  'title'               : this.title,
                  'width'               : 680,
                  'height'              : 495,
                  'href'                : this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'),
                  'type'                : 'swf',    // <--add a comma here
                  'swf'                 : {'allowfullscreen':'true'} // <-- flashvars here
                  });
                 return false;

            }); 

  $("#uf_estado").change(onSelectChange);

/*
  //  $("#id_temas_panteon_filtro").change(onSelectChangeFormSubmit);
  $("#id_minhas_estruturas_sociais_filtro").change(onSelectChangeFormSubmit);
  $("#id_grupo_social_filtro").change(onSelectChangeFormSubmit);
  $("#id_meus_metodos_de_analise_filtro").change(onSelectChangeFormSubmit);
  $("#id_tema_panteon_filtro").change(onSelectChangeFormSubmit);
  $("#id_meu_forum_filtro").change(onSelectChangeFormSubmit);
  $("#id_minhas_mensagens_filtro").change(onSelectChangeFormSubmit);
  $("#id_situacao_problema_filtro").change(onSelectChangeFormSubmit);
  $("#id_tipo_midia_filtro").change(onSelectChangeFormSubmit);
  $("#id_estrutura_social_filtro").change(onSelectChangeFormSubmit);
  $("#id_minhas_midiatecas_filtro").change(onSelectChangeFormSubmit);
  $("#id_coletados_filtro").change(onSelectChangeFormSubmit);
*/

/*
    $("#url_midiateca").attr("disabled", true);
    $("#arquivo_ou_link").click(function(){ $("#url_midiateca").attr("disabled", true); $("#caminho_arquivo_midiateca").attr("disabled", false);});
    $("#link_arquivo").click(function(){$("#caminho_arquivo_midiateca").attr("disabled", true); $("#url_midiateca").attr("disabled", false);});

  $("#arquivo_ou_link").click(function() {
    the_value = jQuery('#arquivo_ou_link input:radio:checked').val();
    alert(the_value);

    if ($('input[name=arquivo_ou_link]:checked').val() == 1) {
      $("#url_midiateca").attr("disabled", true);
      $("#caminho_arquivo_midiateca").attr("disabled", false);
    }
    else
    {
      $("#url_midiateca").attr("disabled", false);
      $("#caminho_arquivo_midiateca").attr("disabled", true);
    }

  });
*/

});

// <![CDATA[
  function openWindow(url,width,height,options,name) {
    width = width ? width : 640;
    height = height ? height : 420;
    name = name ? name : 'openWindow';
    window.open(
      url,
      name,
      'screenX='+(screen.width-width)/2+',screenY='+(screen.height-height)/2+',width='+width+',height='+height+',location=0,resizable=0,status=0,toolbar=0,titlebar=0'
      )
  }
// ]]>
