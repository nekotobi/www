<?php
$uploads_dir = 'pic';//存放上傳檔案資料夾
foreach ($_FILES["ff"]["error"] as $key => $error) {
    if ($error == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["ff"]["tmp_name"][$key];
        $name = $_FILES["ff"]["name"][$key];
		$upDir=$uploads_dir."/".$name;
        //move_uploaded_file($tmp_name, "$uploads_dir/$name");
		move_uploaded_file($tmp_name, $upDir);
    }
}

?>