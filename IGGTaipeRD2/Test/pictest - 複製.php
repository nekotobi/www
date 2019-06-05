<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width" />
    <title>HTML5 拖拉圖檔顯示在網頁上</title>
    <style>
        .drop-zone {
            position: absolute; top: 6px; width: 120px; height: 90px; 
            background-color: green; color: white; text-align: center;
        }
        .drop-zone.hover {
            background-color: blue;
        }
        .img-list {
            position: absolute; height: 90px; background-color: #444;
            top: 6px; left: 135px; right: 6px; overflow-y: hidden;
            overflow-x: auto; white-space: nowrap;
        }
        .thumbnail {
            max-width: 100px; max-width: 75px; vertical-align: top;
            margin: 3px; cursor: pointer;
            border: 1px solid transparent;
        }
        .thumbnail:hover {
            border: 1px solid red;
        }
        .display {
            position: absolute; top: 110px; 
            left: 6px; right: 6px; bottom: 6px;
            padding: 12px;
        }

    </style>

</head>

<body>

    <div class="drop-zone"><span>圖檔拖放區</span></div>
    <div data-bind ="foreach: images" class="img-list">
        <img data-bind="attr: { src: dataUri }, click: $root.currImage" class="thumbnail" />
    </div>
    <fieldset class="display" data-bind="with: currImage">
        <legend>
            <span data-bind="text: name"></span>
            <span data-bind="text: size"></span>
        </legend>
        <div>
            <img data-bind="attr: { src: dataUri }" />
        </div>

    </fieldset>
    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.9.1.js "></script>
    <script src="http://ajax.aspnetcdn.com/ajax/knockout/knockout-3.0.0.js "></script>
    <script src="http://cdn.kendostatic.com/2013.3.1119/js/kendo.core.min.js"></script>

<script>
        $(function () {
            var $drop = $(".drop-zone");
            //抑制瀏覽器原有的拖拉操作效果
            function stopEvent(evt) {
                evt.stopPropagation();
                evt.preventDefault();
            }
            $drop.bind("dragover", function (e) {
                //滑鼠經過上方時加入特效
                stopEvent(e);
                $(e.target).addClass("hover");
            }).bind("dragleave", function (e) {
                //滑鼠移開時移除特效
                stopEvent(e);
                $(e.target).removeClass("hover");
            }).bind("drop", function (e) {
                //拖放操作完成事件
                stopEvent(e);
                $(e.target).removeClass("hover");
                //由dataTransfer.files取得檔案資訊
                var files = e.originalEvent.dataTransfer.files;
                var imageFiles = $.map(files, function (f, i) {
                    //只留下type為image/*者，例如: image/gif, image/jpeg, image/png...
                    return f.type.indexOf("image") == 0 ? f : null;
                });
                //清除ViewModel
                vm.images.removeAll(); vm.currImage(null);
                //逐一讀入各圖檔，取得DataURI，顯示在網頁上
                $.each(imageFiles, function (i, file) {
                    //使用File API讀取圖檔內容轉為DataUri
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        //將檔名、檔案大小、DataURI放入ViewModel
                        vm.images.push({
                            name: file.name,
                            size: kendo.format("{0:n0} bytes", file.size),
                            dataUri: e.target.result
                        })
                    }
                    reader.readAsDataURL(file);
                });
            });

 

            function myViewModel() {

                var self = this;

                self.images = ko.observableArray();

                self.currImage = ko.observable();

            }

            var vm = new myViewModel();

            ko.applyBindings(vm);

        });

    </script>

</body>

</html>

