<?php
$fileArray = [];
$fileString="";
$fileNotExist = [];
$fileExist = [];
$folder = [];
$arrayKey = [];
function listFolderFiles($dir)
{
    global $fileString;
    global $fileArray;
    global $folder;
    $ffs = scandir($dir);
    foreach ($ffs as $ff) {
        if ($ff != '.' && $ff != '..') {
            if (is_dir($dir . '/' . $ff)) {
                listFolderFiles($dir . '/' . $ff);
            } else {
                array_push($fileArray, $dir . "/" . $ff);
                $fileString.=$dir."/".$ff;
            }
        }
    }
}

listFolderFiles('main');
?>

<?php
if (isset($_POST['files'])) {

    $files = $_POST['files'];
    if(!empty($files)){
        $files = str_replace(" ", "", $files);
        $ex = explode(",", $files);
        foreach ($ex as $e) {
            foreach ($fileArray as $file) {
                if (strpos($file, $e, 0) > 0) {
                    array_push($fileExist, $file);
                }
            }
            if(!(strpos($fileString, $e, 0))){
                array_push($fileNotExist,$e);
            }

        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Download File</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-theme.css">
    <link rel="stylesheet" href="component/dist/css/bootstrap-tokenfield.min.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="component/dist/js/bootstrap-tokenfield.js"></script>
</head>
<style>
    * {
        -webkit-border-radius: 0px !important;;
        -moz-border-radius: 0px !important;
        border-radius: 0px !important;
    }
</style>
<body>
<form action="readdir.php" method="post" enctype="multipart/form-data">
    <div class="container">
        <div class="row">
            <br>
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="glyphicon glyphicon-download-alt"></i> Download System</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <input type="text" name="files" id="tokenfield" class="form-control">

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <button class="btn btn-default" type="submit"> Search</button>
                            </div>
                            <div class="form-group">
                                <?php if (count($fileExist) > 0): ?>
                                    <?php $fileExist=array_unique($fileExist);?>
                                    <?php $i = 1; ?>
                                    <ul class="list-unstyled">
                                        <?php foreach ($fileExist as $file): ?>
                                            <li><a href="<?php echo $file; ?>" download="<?php echo $file; ?>"
                                                   id="download<?php echo $i; ?>"><?php echo $file; ?></a></li>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                    <div class="total" data-total="<?php echo $i; ?>"></div>
                                    <button class="btn btn-default" type="button" id="downloadButton"><i
                                                class="glyphicon glyphicon-download-alt"></i> Download
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <?php if (count($fileNotExist) > 0): ?>
                                    <?php $fileNotExist = array_unique($fileNotExist); ?>
                                    <h3 class="page-header">File Not Found</h3>
                                    <ul class="list-unstyled">
                                        <?php foreach ($fileNotExist as $no): ?>
                                            <li><?php echo $no; ?></li>

                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $('#tokenfield').tokenfield({
        delimiter: [',', ' ', '\n'],
        beautify: true
    });
    $("#downloadButton").click(function () {
        var totalFile = $(".total").attr('data-total');
        for (var index = 1; index < totalFile; index++) {
            document.getElementById("download" + index + "").click();
        }
    });
</script>
</body>
</html>


