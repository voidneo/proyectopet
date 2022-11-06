<?php
$article_url = urlencode("http://www." . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
?>

<style>
    .blog-post {
        margin-top: 4rem;
        margin-bottom: 4rem;
    }


    .blog-post-meta {
        margin-bottom: 1.25rem;
        color: #727272;
    }

    .flex {
        display: flex;
    }

    .social {
        font-size: 1.25em;
        justify-content: flex-start;
        gap: 1em;
        margin-top: 0.5em;
        margin-bottom: 0.75em;
    }

    .social-fb {
        background-color: transparent;
        color: #3b5998;
    }

    .btn:hover {
        color: white;
        background-color: #4e77cc;
        background-color: #3b5998;
        border-color: transparent;
    }

    h1 {
        margin-bottom: 0.5em;
    }

    .cpy-icon {
        font-size: 1.25rem;
        color: #727272;
        vertical-align: middle;
    }

.cpy-icon:hover {
    color: #929292;
}
</style>
<div id="article-view" security-hash="<?php echo $data["security_hash"]; ?>" class="container col-md-12">
    <article class="blog-post">
        <h2 class="blog-post-title mb-1"><?php echo $data["article"]->getTitulo(); ?> <a class="cpy-icon" href="#" title="Copiar url" id="url-cpy"><i class="fa-solid fa-link"></i></a></h2>
        <p class="blog-post-meta">
            <?php
            $date = new DateTime($data["article"]->getFecha());
            $mes = "enero";

            switch ($date->format("m")) {
                case "02":
                    $mes = "febrero";
                    break;
                case "03":
                    $mes = "marzo";
                    break;
                case "04":
                    $mes = "abril";
                    break;
                case "05":
                    $mes = "mayo";
                    break;
                case "06":
                    $mes = "junio";
                    break;
                case "07":
                    $mes = "julio";
                    break;
                case "08":
                    $mes = "agosto";
                    break;
                case "09":
                    $mes = "septiembre";
                    break;
                case "10":
                    $mes = "octubre";
                    break;
                case "11":
                    $mes = "noviembre";
                    break;
                default:
                    $mes = "";
                    break;
            }


            echo $date->format("d") . " de " . $mes . ", " . $date->format("Y");
            ?>
        </p>
        <div class="social">
            <div class="flex social">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $article_url; ?>" class="btn social-btn social-fb" target="_BLANK" title="Compartir en facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
            </div>
        </div>
        <p id="article-body"><?php echo $data["article"]->getCuerpo(); ?></p>
    </article>
    <input type="hidden" id="url" value="<?php echo $article_url; ?>">
</div>
<script type="text/javascript" src="./../../../../scripts/article-view.js"></script>