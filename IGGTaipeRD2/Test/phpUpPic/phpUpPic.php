<script type="text/javascript">
function dragHandler(e)
{
    e.preventDefault() ; //防止瀏覽器執行預設動作
}
function drop_image(e)
{
    e.preventDefault() ; //防止瀏覽器執行預設動作
    var files  = e.dataTransfer.files ; //擷取拖曳的檔案
    /* 把擷取到的檔案用POST送到後端去 */
}
 
function drop_image(e)
{
    e.preventDefault() ;
    var images_container = document.getElementById('images_container') ;
    var objXhr           = new XMLHttpRequest() ;
    var files            = e.dataTransfer.files ;
    var objForm          = new FormData() ;
    
    objXhr.open('POST', API_URL+'upload/uploadFile?name=images') ;
    for (var i=0; i<files.length; i++)
    {
        if (!files[i].type.match('image')) //判斷上傳的檔案是否為圖檔

        {
            var name = files[i].name ;
            alert(name+'無法上傳！請拖曳圖片檔案！') ;
            continue ;
        }
        objForm.append('images[]', files[i]) ;
    }
}
XMLHttpRequest.upload.onprogress = function(e) //上傳中

{
    if (e.lengthComputable)
    {
        var intComplete = (e.loaded / e.total) * 100 | 0 ;
            
        elProgress.innerHTML   = intComplete + '%' ; // 控制進度條的顯示數字，例如65%

        elProgress.style.width = intComplete + '%' ; // 控制進度條的長度

            
        elProgress.setAttribute('aria-valuenow', intComplete) ;
    }
}
objXhr.onload = function(e) //上傳完成時

{
    /*接收後端傳回的Response，本範例的後端程式會傳回每個圖檔是否都上傳成功，以及上傳成功的圖片數量*/
    var arrData = JSON.parse(objXhr.responseText) ;
       
    for (var i=0; i<arrData.length; i++)  // 檢查每個圖檔上傳成功或是失敗，成功上傳者顯示在頁面上

    {
        var img = new Image() ;
        img.src = arrData[i].url ;
        img.className = 'image' ;
        images_container.appendChild(img) ;
    }
}
</script>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>php拖曳圖形上傳</title>
</head>
ww
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
<div class="progress text-center">
    <div id="upload_progress" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100">
    </div>
</div>
<div ondragover="javascript: dragHandler(event);" ondrop="javascript: drop_image(event);" id="drop_image" class="upload-image"></div>

<?php

$arrReqData = array() ;
        
try
{
    if (!isset($_GET['name']))
    {
        throw new Exception("Missing Name") ;
    }
    $strName  = $_GET['name'] ;
    $arrFiles = $_FILES[$strName] ;
    
    foreach ($arrFiles as $field => $row) //調整$_FILES格式為JSON物件的格式
    {
        for ($i=0; $i<count($row); $i++)
        {
            if (!isset($arrReqData[$i]))
            {
                $objReqData         = new stdClass() ;
                $objReqData->$field = $row[$i] ;
                $arrReqData[]       = $objReqData ;
            }
            else
            {
                $arrReqData[$i]->$field = $row[$i] ;
            }
        }
    }
}
catch (Exception $e)
{
    $this->http->halt(400) ;
    exit(0) ;
}

try
{
    $strUploadDir = '/image' ; //設定儲存圖片的路徑(目錄)
    $datetime     = date('YmdHis') ;
    $count        = 0 ;
    $arrData      = array() ;
    
    foreach ($arrReqData as $row) //上傳每張圖片並且為檔案重新命名
    {
        if ($row->error != UPLOAD_ERR_OK)
        {
            continue ;
        }
        $arrFileName  = explode('.', $row->name) ;
        $type         = $arrFileName[1] ;
        $tmp_datetime = date('YmdHis') ;
        if ($tmp_datetime != $datetime)
        {
            $count = 0 ;
            $datetime = $tmp_datetime ;
            $name     = $datetime.'-'.$count ;
        }
        else
        {
            $count++ ;
            $name = $datetime.'-'.$count ;
        }
        
        $rs = move_uploaded_file($row->tmp_name, "$strUploadDir/$name.$type") ; //上傳檔案
        $objData = new stdClass() ;
        if ($rs == true) //上傳成功則回傳成功訊息並且加上圖檔URL
        {
            $objData->message = 'Success' ;
            $objData->url     = "/image/$name.$type" ;
        }
        else  // 上傳失敗則回傳失敗訊息
        {
            $objData->message = 'Fail' ;
        }
        $arrData[] = $objData ;
    }
    echo json_encode($arrData) ;
}
catch(Exception $e)
{
    $this->http->halt(400) ;
    exit(0) ;
}
?>
