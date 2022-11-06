<style>
    #search-results > a {
        text-decoration: none;
    }

    #search-results h5, #search-results p.card-text {
        color: rgb(33, 37, 41) !important;
    }

    .margin {
        margin: 2em auto;
    }

</style>
<div
    id="article-search"
    security-hash="<?php echo $data["security_hash"]; ?>" 
    year="<?php if($data["year"])  echo $data["year"]; ?>"
    month="<?php if($data["month"])  echo $data["month"]; ?>"
    day="<?php if($data["day"])  echo $data["day"]; ?>"
    page="1"
    class="container">
    <div class="row margin">
        <div class="col-xl-3">
            <div class="input-group mb-3">
                <input id="art-search-field" type="text" class="form-control" placeholder="Buscar..." aria-label="Buscar..." aria-describedby="search-btn">
                <input type="hidden" id="article-category" value="<?php echo $data["category_id"]; ?>" />
                <button title="Buscar" class="btn btn-primary" type="button" id="search-btn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>
        <div class="col-xl-9">
            <div id="search-results" class="container"></div>
        </div>
    </div>
    <div class="row">
        <nav aria-label="...">
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>
    </div>
</div>

<?php
$script_path = "./";

if($data["year"]) {
    $script_path = "$script_path../";
}
if($data["month"]) {
    $script_path = "$script_path../";
}
if($data["day"]) {
    $script_path = "$script_path../";
}
?>
<script type="text/javascript" src="<?php echo $script_path; ?>scripts/article-search.js"></script>