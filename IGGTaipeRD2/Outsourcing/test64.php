<?php 
global $Exporttype ;
header("Content-type: text/html; charset=charset=unicode");
$filename="材料2：申请资料.doc";
if($Exporttype="mat4")$filename="材料4：需求描述模板.doc";
//header("Content-Disposition: attachment; filename=" . $filename); 
header("Content-Type:application/ms-word");  
header("Content-Disposition:attachment;filename=".$filename);
header("Pragma:no-cache");
header("Expires:0");
?> 



<?php
$img=create_data_uri('pic0.png', 'png');
echo "<img src=".$img." />";
function create_data_uri($source_file, $mime_type) {
  $encoded_string = base64_encode(file_get_contents($source_file));
 return('data:image/' . $mime_type . ';base64,' . $encoded_string);
}
//<img alt="Logo" src="<?php create_data_uri('logo.png', 'png')  
?>
 cc

