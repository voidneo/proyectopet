<style>input[valid=false] { color:red; } input[valid=true] { color:green; }</style>
<input security-hash="<?php echo $data["security_hash"] ?>" list="cat-list" name="category" id="cat-chooser" class="form-control" placeholder="Categoria...">
<datalist id="cat-list"></datalist>
<script type="text/javascript"><?php echo file_get_contents("./scripts/category-chooser.js"); ?></script>