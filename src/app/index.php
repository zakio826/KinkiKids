<!-- トップページ画面 子供か大人か分ける -->

<!-- ヘッダー -->
<?php
$page_title = "トップページ";
include("./include/header.php");
?>

<?php
    require($absolute_path."lib/index_class.php");
    $index_class = new index_class($db);
    if($index_class->child_adult() > 30){
        header("Location: ./index_child.php");
        exit();
    } else {
        header("Location: ./index_parent.php");
        exit();
    }

?>