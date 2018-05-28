<?php

?>

<form method="post" action="/upload/save" enctype="multipart/form-data">
    <input type="hidden" name="_csrf-frontend" value="<?=Yii::$app->request->csrfToken?>" />
    课件类型:<input type= "text" name = "res_type" value="">
    文件类型限制:<input type= "text" name = "ext" value="png">
    文件大小限制:<input type= "text" name = "size" value="1000000">
    <input type= "hidden" name = "course_id" value="461">
    <input name="file[]" type="file"/>
    <!-- <input name="file[]" type="file"/> -->
<!--     <input name="file[]" type="file"/> -->
<!--    <input name="file[]" type="file"/>-->
<!--    <input name="file[]" type="file"/>-->
<!--    <input name="file[]" type="file"/>-->
<!--    <input name="file[]" type="file"/>-->
<!--    <input name="file[]" type="file"/>-->
<!---->

    <input type="submit" value="upload">
</form>