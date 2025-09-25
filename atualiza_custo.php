<?php
include_once("php/connect.php");
include_once("funcoes.php");
$valor = json_decode('{
"atelier":'.intval($_POST['atelier']).',
"corte":'.intval($_POST['corte']).',
"impressao":'.intval($_POST['impressao']).',
"serigrafia":'.intval($_POST['serigrafia']).',
"bordado":'.intval($_POST['bordado']).',
"expedicao":'.intval($_POST['expedicao']).',
"estamparia":'.intval($_POST['estamparia']).',
"pcp":'.intval($_POST['pcp']).',
"impostos":'.intval($_POST['impostos']).',
"fabrica":'.intval($_POST['fabrica']).'}');

print($valor->atelier);

atualizaCustos($conn,$valor);
header("Location:custo.php?atualizado=ok");
?>