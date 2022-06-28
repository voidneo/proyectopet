<?php $art_id = isset($data["article_id"]) ? $data["article_id"] : ""; ?>
<div>
    <input type="hidden" id="art-id" value="<?php echo $art_id; ?>" />
    <input type="text" id="art-title" name="titulo" placeholder="Titulo"/>
    <br>
    <?php (new Controller)->load_view("components/CategoryChooser", $data); ?>
    <br>
    <textarea id="art-body"></textarea>
    <br>
    <button id="art-submit-btn"><?php echo $art_id ? "Actualizar" : "Crear"; ?></button>
</div>
<script type="text/javascript" src="./scripts/article-editor.js"></script>