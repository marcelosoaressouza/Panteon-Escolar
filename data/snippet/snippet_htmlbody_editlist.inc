<!-- USED ONLY WITH SNIPPET-HTMLBODY-BASIC -->
<!-- ATENTION: YOU NEED PUT IN YOUR HEADER SNIPPET_HTMLHEADER.INC -->
<!-- EDITLIST -->

<xsl:template match="editlist" >
	<script type='text/javascript'>
	function submit<xsl:value-of select="@name"/>()
	{
		form = document.<xsl:value-of select="@name"/>;
		<xsl:if test="@clientsubmit" >
	 	<xsl:value-of select="@clientsubmit" />(form);
	 	</xsl:if>
		<xsl:if test="not(@clientsubmit)" >
	 	form.submit();
	 	</xsl:if>
	}
	</script>
    
	<form method="post"><xsl:if test="@module"><xsl:attribute name="action"><xsl:value-of select="@module"/></xsl:attribute></xsl:if><xsl:attribute name="name"><xsl:value-of select="@name" /></xsl:attribute>
	<input type="hidden" name="acao" value="" />
	<input type="hidden" name="valueid" value="" />
	<input type="hidden" name="curpage"><xsl:attribute name="value"><xsl:value-of select="@curpage" /></xsl:attribute></input>
	<input type="hidden" name="offset"><xsl:attribute name="value"><xsl:value-of select="@offset" /></xsl:attribute></input>
	<xsl:for-each  select="param">
	<input type="hidden"><xsl:attribute name="name"><xsl:value-of select="@name" /></xsl:attribute><xsl:attribute name="value"><xsl:value-of select="@value" /></xsl:attribute></input>
	</xsl:for-each>
	</form>
	
	<table class="el_table">
	<tr> 
	<td>

		<!-- DATA TABLE - START -->
		<table width="100%" border="0" cellspacing="0" cellpadding="2" class="editlist">
		<form><xsl:attribute name="id"><xsl:value-of select="@name" />_LIST</xsl:attribute><xsl:attribute name="name"><xsl:value-of select="@name" />_LIST</xsl:attribute>
		
			<!-- Show table header -->
			<tr>
			<xsl:call-template name="editlist.header">
				<xsl:with-param name="cols"><xsl:value-of select="@cols"/></xsl:with-param>
				<xsl:with-param name="colsminus"><xsl:value-of select="@cols - 1"/></xsl:with-param>
				<xsl:with-param name="readonly"><xsl:value-of select="@readonly"/></xsl:with-param>
				<xsl:with-param name="title"><xsl:value-of select="@title"/></xsl:with-param>
			</xsl:call-template>
			</tr>

			<!-- Show column headers -->
			<tr> 
				<xsl:for-each select="row[1]/field">

					<xsl:if test="position() = 1 and not(../../@readonly)">


                                            <!-- Teste Se Ha Botao, nao havendo tira coluna de Selecionar -->

				            <xsl:if test="../../@new or ../../@edit or ../../@view or ../../@delete or ../../button" >
                                              <th id="th_coluna_processpagefield_selecione"><xsl:value-of select="@name" />Selecione</th>
                                            </xsl:if>

					</xsl:if>

					<xsl:if test="position() != 1">
						<th id="th_coluna_processpagefield"><xsl:value-of select="@name" /></th>
					</xsl:if>
				</xsl:for-each>
			</tr>
 
			<!-- Show values -->
			<xsl:for-each select="row[not(@total='true')]">
			<tr>
				<xsl:if test="position() mod 2 = 1"><xsl:attribute name="class">row-a</xsl:attribute></xsl:if>
				<xsl:if test="position() mod 2 = 0"><xsl:attribute name="class">row-b</xsl:attribute></xsl:if>
				<xsl:for-each select="field">

					<xsl:if test="position() = 1 and not(../../@readonly)">


                                            <!-- Teste Se Ha Botao, nao havendo tira coluna de Selecionar -->

					    <xsl:if test="../../@new or ../../@edit or ../../@view or ../../@delete or ../../button" >
						<td width="5">
                                                <center>
						<xsl:if test="../../@new or ../../@edit or ../../@view or ../../@delete or ../../button" >
						<xsl:choose>
							<xsl:when test="../../@selecttype='check'"><input type="checkbox" name="inputvalueid"><xsl:attribute name="value"><xsl:value-of select="text()" /></xsl:attribute></input></xsl:when>
							<xsl:otherwise><input type="radio" name="inputvalueid" class="botaoradio"><xsl:attribute name="value"><xsl:value-of select="text()" /></xsl:attribute></input></xsl:otherwise>
						</xsl:choose>
						</xsl:if>
                                                </center>
					        </td>
                                            </xsl:if>
					</xsl:if>

					<xsl:if test="position() != 1">
						<td id="td_coluna_processpagefield"><xsl:apply-templates /></td>
					</xsl:if>

				</xsl:for-each>

                                <!-- <td>Aqui Porra</td> -->

			</tr>
			</xsl:for-each>
			
			<!-- Show Total Row (if exists) -->
			<xsl:for-each select="row[@total='true']">
			<tr>
				<xsl:if test="position() mod 2 = 1"><xsl:attribute name="class">row-a</xsl:attribute></xsl:if>
				<xsl:if test="position() mod 2 = 0"><xsl:attribute name="class">row-b</xsl:attribute></xsl:if>
				<xsl:for-each select="field">
					<xsl:if test="position() = 1 and not(../../@readonly)">
						<th width="5">
						</th>
					</xsl:if>
					<xsl:if test="position() != 1">
						<th><xsl:apply-templates /></th>
					</xsl:if>
				</xsl:for-each>
			</tr>
			</xsl:for-each>
			
		</form>
		</table>
		<!-- DATA TABLE - END -->
	
		<!-- BUTTONS - START -->
		<table width="100%" border="0" cellspacing="0" cellpadding="2" class="el_buttons">
		<tr align="left"> 
			<!-- BUTTONS - ONLY IF NOT READONLY -->
			<xsl:if test="not(@readonly)">
				<td><xsl:attribute name="colspan"><xsl:value-of select="@cols"/></xsl:attribute>
					<!-- Buttons -->
                                        <div id="nav_button">
					<xsl:if test="@new"><a><xsl:attribute name="href">javascript:submitEditList(this.document.<xsl:value-of select="@name" />,'new','',0);</xsl:attribute><img src="common/editlist/ic_novo.gif" alt="Criar Novo"><xsl:attribute name="id"><xsl:value-of select="@name"/>_imgnew</xsl:attribute></img></a></xsl:if>
					<xsl:if test="@view"><a><xsl:attribute name="href">javascript:submitEditList(this.document.<xsl:value-of select="@name" />,'view','',1);</xsl:attribute><img src="common/editlist/ic_detalhes.gif" alt="Ver Detalhes"><xsl:attribute name="id"><xsl:value-of select="@name"/>_imgview</xsl:attribute></img></a></xsl:if>
					<xsl:if test="@edit"><a><xsl:attribute name="href">javascript:submitEditList(this.document.<xsl:value-of select="@name" />,'edit','',1);</xsl:attribute><img src="common/editlist/ic_editar.gif" alt="Editar"><xsl:attribute name="id"><xsl:value-of select="@name"/>_imgedit</xsl:attribute></img></a></xsl:if>
					<xsl:if test="@delete"><a><xsl:attribute name="href">javascript:submitEditList(this.document.<xsl:value-of select="@name" />,'delete','',1);</xsl:attribute><img src="common/editlist/ic_excluir.gif" alt="Excluir"><xsl:attribute name="id"><xsl:value-of select="@name"/>_imgdelete</xsl:attribute></img></a></xsl:if>
                                        
					<xsl:for-each select="button">
						<a><xsl:attribute name="href">javascript:submitEditList(this.document.<xsl:value-of select="parent::*/@name" />,'<xsl:value-of select="@acao" />','<xsl:if test="@url!=''"><xsl:value-of select="parent::*/@name"/>_C<xsl:value-of select="@custom" /></xsl:if>','<xsl:value-of select="@multiple"/>');</xsl:attribute><img ><xsl:attribute name="src"><xsl:value-of select="@img" /></xsl:attribute><xsl:attribute name="alt"><xsl:value-of select="@alt" /></xsl:attribute><xsl:attribute name="title"><xsl:value-of select="@alt" /></xsl:attribute></img></a>
					</xsl:for-each>

                                        <xsl:if test="button">
                                          <div id="nav_desc">Posicione o mouse sobre o ícone para obter detalhes sobre a sua função</div>
                                        </xsl:if>

                                        </div>
					<!-- End Buttons -->


					<!-- Navigation --> <!-- Barra de Navegacao Para Frente e Para Tras -->
					<xsl:if test="@pages &gt; 1">

                                        <div id="nav_editlist">
                                        
					<xsl:if test="@pageback"><a><xsl:attribute name="href">javascript:submitEditList(this.document.<xsl:value-of select="@name" />, 'move', '1');</xsl:attribute><img src="common/editlist/ic_primeiro.gif"><xsl:attribute name="id"><xsl:value-of select="@name"/>_imgfirst</xsl:attribute></img></a></xsl:if>
					<xsl:if test="not(@pageback) and @offset"><img alt="Primeiro Registro" src="common/editlist/ic_primeiro.gif"><xsl:attribute name="id"><xsl:value-of select="@name"/>_imgfirst</xsl:attribute></img></xsl:if>
					
					<xsl:if test="@pageback"><a><xsl:attribute name="href">javascript:submitEditList(this.document.<xsl:value-of select="@name" />, 'move', '<xsl:value-of select="@pageback" />');</xsl:attribute><img src="common/editlist/ic_voltar.gif"><xsl:attribute name="id"><xsl:value-of select="@name"/>_imgprevious</xsl:attribute></img></a></xsl:if>
					<xsl:if test="not(@pageback) and @offset"><img alt="Voltar" src="common/editlist/ic_voltar.gif"><xsl:attribute name="id"><xsl:value-of select="@name"/>_imgprevious</xsl:attribute></img></xsl:if>
					
					<xsl:if test="@offset">
					<img src="common/editlist/blank.gif" width="10" height="1" alt="blank"/>
					<select>
                                          <xsl:attribute name="onChange">submitEditList(document.<xsl:value-of select="@name" />, 'move', this.selectedIndex + 1);</xsl:attribute>
					  <xsl:call-template name="editlist.loop">
					  <xsl:with-param name="i">1</xsl:with-param>
					  <xsl:with-param name="current"><xsl:value-of select="@curpage" /></xsl:with-param>
					  <xsl:with-param name="count"><xsl:value-of select="@pages" /></xsl:with-param>
					  </xsl:call-template>
                                        </select>
					<img src="common/editlist/blank.gif" width="10" height="1" alt="blank"/>
					</xsl:if>
					
					<xsl:if test="@pagefwd"><a><xsl:attribute name="href">javascript:submitEditList(this.document.<xsl:value-of select="@name" />, 'move', '<xsl:value-of select="@pagefwd" />');</xsl:attribute><img src="common/editlist/ic_avancar.gif"><xsl:attribute name="id"><xsl:value-of select="@name"/>_imgnext</xsl:attribute></img></a></xsl:if>
					<xsl:if test="not(@pagefwd) and @offset"><img alt="Adiantar" src="common/editlist/ic_avancar.gif"><xsl:attribute name="id"><xsl:value-of select="@name"/>_imgnext</xsl:attribute></img></xsl:if>
					
					<xsl:if test="@pagefwd"><a><xsl:attribute name="href">javascript:submitEditList(this.document.<xsl:value-of select="@name" />, 'move', '<xsl:value-of select="@pages" />');</xsl:attribute><img src="common/editlist/ic_ultimo.gif"><xsl:attribute name="id"><xsl:value-of select="@name"/>_imglast</xsl:attribute></img></a></xsl:if>
					<xsl:if test="not(@pagefwd) and @offset"><img alt="Ultimo" src="common/editlist/ic_ultimo.gif"><xsl:attribute name="id"><xsl:value-of select="@name"/>_imglast</xsl:attribute></img></xsl:if>
					
					<xsl:for-each select="xmlheader">
                                          <img alt="blank" src="common/editlist/blank.gif" width="30" height="1" />
                                          <xsl:apply-templates />
					</xsl:for-each>
                                        </div>
                                            
                                        </xsl:if>
                                        <!-- End Navigation -->

				</td>

				<xsl:for-each select="button">
				<xsl:if test="@url!=''">
				<form method="post"><xsl:attribute name="name"><xsl:value-of select="parent::*/@name"/>_C<xsl:value-of select="@custom" /></xsl:attribute><xsl:attribute name="action"><xsl:value-of select="@url"/></xsl:attribute>
				<input type="hidden" name="acao"/>
				<input type="hidden" name="valueid"/>
				</form>
				</xsl:if>
				</xsl:for-each>
				
			</xsl:if>
		</tr>
		</table>
		<!-- BUTTONS - END -->

	</td>
	</tr>
	</table>

	<script type='text/javascript'>
		var editlistName = '<xsl:value-of select="@name"/>';
		defineImageCaption(editlistName + "_imgnew", BTN_NEW);
		defineImageCaption(editlistName + "_imgview", BTN_VIEW);
		defineImageCaption(editlistName + "_imgedit", BTN_EDIT);
		defineImageCaption(editlistName + "_imgdelete", BTN_DELETE);
		defineImageCaption(editlistName + "_imgfirst", BTN_FIRST);
		defineImageCaption(editlistName + "_imgprevious", BTN_PREVIOUS);
		defineImageCaption(editlistName + "_imgnext", BTN_NEXT);
		defineImageCaption(editlistName + "_imglast", BTN_LAST);
	</script>
</xsl:template>


<xsl:template name="editlist.loop">
<xsl:param name="i"/>
<xsl:param name="count"/>
<xsl:param name="current"/>

	<xsl:if test="$i &lt;= $count">
		<option><xsl:if test="$i = $current"><xsl:attribute name="selected">true</xsl:attribute></xsl:if><xsl:value-of select="$i" /></option>
	</xsl:if>
	<xsl:if test="$i &lt;= $count">
		<xsl:call-template name="editlist.loop">
			<xsl:with-param name="i">
			<!-- Increment index-->
			<xsl:value-of select="$i + 1"/>
			</xsl:with-param>
			<xsl:with-param name="count">
			<xsl:value-of select="$count"/>
			</xsl:with-param>
			<xsl:with-param name="current">
			<xsl:value-of select="$current"/>
			</xsl:with-param>
		</xsl:call-template>
	</xsl:if>

</xsl:template>

<xsl:template name="editlist.header">
<xsl:param name="cols"/>
<xsl:param name="colsminus"/>
<xsl:param name="readonly" />
<xsl:param name="title" />

	<th class="editlisttitle">
		<xsl:attribute name="colspan">
			<xsl:if test="$readonly=''"><xsl:value-of select="$cols"/></xsl:if>
			<xsl:if test="$readonly='true'"><xsl:value-of select="$colsminus"/></xsl:if>
		</xsl:attribute>
		<strong><xsl:value-of select="$title" /></strong>
	</th>
</xsl:template>
