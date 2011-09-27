<xsl:template match="blockbarramenu">
<xsl:apply-templates />
<div id="Layout">
  <div id="Topo">
    <div id="Head">
       <div id="HeadInfo"><xsl:value-of select="@nome_usuario" /></div>
      <div id="HeadMenu">
          <xsl:if test="@logout">
          <ul><li><a href="xmlnuke.php?logout=true">Sair Panteon</a></li></ul>
          </xsl:if>
      </div>
   </div>

   <div id="AreaTopo">
     <div id="areaMarca">
     <a href="module:panteonescolar.oquee"><img src="static/images/Layout/Marca.png" width="182" height="167" alt="Panteon Escolar"/></a>
     </div>
   </div>

  </div>

<div id="BarraMenu">
    <div id="menu_principal">
    <ul>
    <xsl:for-each select="opcao_menu">
        <li> <a href="{@href}" title="{@titulo}"> <xsl:value-of select="@titulo" /> </a> </li>
    </xsl:for-each>
    </ul>
    </div>

    <div id="menu_principal_aux">
    <ul>
    <xsl:for-each select="opcao_menu_auxiliar">
      <li> <a href="{@href}" title="{@titulo}"> <xsl:value-of select="@titulo" /> </a> </li>
    </xsl:for-each>
    </ul>
    </div>
</div>

</div>

</xsl:template>