<xsl:if test="//randernet_duallist_asmselect">
    <script type="text/javascript" src="common/randernet/asmselect/examples/jquery.ui.js"></script>
    <script type="text/javascript" src="common/randernet/asmselect/jquery.asmselect.js"></script>
    <script type="text/javascript">
            <xsl:for-each select="//randernet_duallist_asmselect/leftlist">
            $('#randernet_asmselect_<xsl:value-of select="@name" />').asmSelect({
            addItemTarget: 'bottom',
            animate: true,
            highlight: true,
            sortable: true,
            removeLabel: 'excluir',
            highlightAddedLabel: 'Adicionando: ',
            highlightRemovedLabel: 'Removendo: '

        }).after($('<a href='#' title="Excluir todos">Excluir todos</a>').click(function() {
            $('#randernet_asmselect_<xsl:value-of select="@name" />').children().attr("selected", "").end().change();
            return false;
        })).after($('<a href='#' title="Selecionar todos">Selecionar todos</a><span> | </span>').click(function() {
            $('#randernet_asmselect_<xsl:value-of select="@name" />').children().attr("selected", "selected").end().change();
            return false;
        }));

        $("#randernet_asmselect_<xsl:value-of select="@name" />").change(function(e, data) {
            var $itens_selecionados = $.trim($("#<xsl:value-of select="../@name" />").val());
            var $arr_itens_selecionados = new Array();
            if(data.type == 'add'){
                if($itens_selecionados !=""){ // diferente de vazio
                    var $arr_itens_selecionados = $itens_selecionados.split(",");
                }
                // verifica se o item nao existe no array ou se o array esta vazio
                if( $.inArray(data.value, $arr_itens_selecionados ) == -1 || $itens_selecionados ==""){
                    $arr_itens_selecionados.push(data.value);
                }
                $("#<xsl:value-of select="../@name" />").val($arr_itens_selecionados.join(','));
            }else if(data.type == 'drop'){
                if($itens_selecionados !=""){ // diferente de vazio
                    var $arr_itens_selecionados = $itens_selecionados.split(",");
                }
                $posicao_item = $.inArray(data.value, $arr_itens_selecionados );
                if($posicao_item > -1){
                    $arr_itens_selecionados.splice($posicao_item,1);
                }
                $("#<xsl:value-of select="../@name" />").val($arr_itens_selecionados.join(','));
            }
        });
            </xsl:for-each>
    </script>
    <link rel="stylesheet" type="text/css" href="common/randernet/asmselect/jquery.asmselect.css" />
</xsl:if>




<xsl:if test="//select">
    <xsl:for-each select="//select">
        <xsl:if test="@randernet_ajax_module_name!=''">
            <script type="text/javascript">
                $('#<xsl:value-of select="@name" />').change(function() {
                    var auxUrl = "[param:script_name]";
                    var arrAuxUrl = auxUrl.split("/");
                    var auxUrl = arrAuxUrl[arrAuxUrl.length-1];
                    var selected_value = this.value;
                    var varURL = auxUrl+"?module=<xsl:value-of select="@randernet_ajax_module_name" />&amp;site=[param:site]&amp;lang=[param:lang]&amp;xsl=randernet_ajax&amp;<xsl:value-of select="@name" />="+selected_value+"<xsl:value-of select="@randernet_ajax_parametros" />";
                        
                    $.ajax({
                        type: "GET",
                        url: varURL,
                        success: function(msg){
                            msg = msg.replace("<dados>","").replace("</dados>","");
                            $('#div_randernet_select_ajax_<xsl:value-of select="@randernet_ajax_select_name" />').html(msg);
                        }
                    });

                });
            </script>
        </xsl:if>
    </xsl:for-each>
</xsl:if>



<xsl:if test="//randernet_clone_field_container">
    <script type="text/javascript">
        <xsl:for-each select="//randernet_clone_field_container">
            $("#link_adicionar_<xsl:value-of select="@id" />").cloneField($("#<xsl:value-of select="@id" />"));

            $("#adicionar_<xsl:value-of select="@id" />").click(function () {
                adicionar_<xsl:value-of select="@id" />();
            });

            function adicionar_<xsl:value-of select="@id" />(){
                /* Utilizacao de try catch para evitar erro no ie e Firefox */
                try {$('#link_adicionar_<xsl:value-of select="@id" />').click(); } catch (exception) { }
                var contador = $("#clonefield-counter").val();
                var id_tag = '#<xsl:value-of select="@id" />'+contador.toString();
                var href_lista = '#lista_<xsl:value-of select="@id" />';
                var remover_<xsl:value-of select="@id" /> = '<a href="'+href_lista+'">Remover</a>';
                $(id_tag).find('#remover_<xsl:value-of select="@id" />').html(remover_<xsl:value-of select="@id" />);
                $(id_tag).find('#remover_<xsl:value-of select="@id" />').click(function () {
                    $(id_tag).remove();
                });
            }
        </xsl:for-each>
    </script>
</xsl:if>