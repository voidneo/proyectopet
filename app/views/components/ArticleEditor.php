<?php
$art_id = isset($data["article_id"]) ? $data["article_id"] : "";
?>

<style>
    .menu {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.25rem;
    }

    .nowrap {
        flex-wrap: nowrap;
    }

    .ctrl-group {
        display: flex;
        gap: 0.25rem;
    }

    #align-btn-1,
    #align-btn-2 {
        display: flex;
        flex-direction: column;
        align-content: center;
        justify-content: center;
    }

    .blog-post {
        margin-top: 4rem;
        margin-bottom: 4rem;
    }


    .blog-post-meta {
        margin-bottom: 1.25rem;
        color: #727272;
    }
</style>

<div id="art-editor" class="container" art-id="<?php echo $art_id; ?>" security-hash="<?php echo $data["security_hash"] ?>">
    <br>
    <h2><?php echo $art_id ? "Editar" : "Crear"; ?> art&iacute;culo</h2>
    <hr>
    <br>
    <div class="row g-3">
        <input type="text" id="art-title" class="form-control" name="titulo" placeholder="Titulo" />
        <?php (new Controller)->load_view("components/CategoryChooser", $data); ?>
        <div class="menu">
            <div class="ctrl-group">
                <div class="input-group nowrap">
                    <button title="Negrita" id="b-btn" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-bold"></i>
                    </button>
                    <button title="Cursiva" id="i-btn" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-italic"></i>
                    </button>
                    <button title="Subrayado" id="u-btn" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-underline"></i>
                    </button>
                </div>
                <div class="input-group nowrap">
                    <button title="Centrar" id="align-btn-1" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-align-center"></i>
                    </button>
                    <button title="Alinear a la derecha" id="align-btn-2" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-align-right"></i>
                    </button>
                </div>
                <div>
                    <button title="Tamano de fuente" type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Tama&ntilde;o
                    </button>
                    <ul class="dropdown-menu">
                        <li><a id="btn-size-1" class="dropdown-item" href="#">
                                <h1>Titulo 1</h1>
                            </a>
                        </li>
                        <li><a id="btn-size-2" class="dropdown-item" href="#">
                                <h2>Titulo 2</h2>
                            </a>
                        </li>
                        <li><a id="btn-size-3" class="dropdown-item" href="#">
                                <h3>Titulo 3</h3>
                            </a>
                        </li>
                        <li><a id="btn-size-4" class="dropdown-item" href="#">
                                <h4>Titulo 4</h4>
                            </a>
                        </li>
                        <li><a id="btn-size-5" class="dropdown-item" href="#">
                                <h5>Titulo 5</h5>
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <button title="Color de fuente" type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Color
                    </button>
                    <ul class="dropdown-menu">
                        <li><a id="btn-color-1" class="dropdown-item" style="color: var(--bs-red)" href="#">Rojo</a></li>
                        <li><a id="btn-color-2" class="dropdown-item" style="color: var(--bs-orange)" href="#">Naranja</a></li>
                        <li><a id="btn-color-3" class="dropdown-item" style="color: var(--bs-yellow)" href="#">Amarillo</a></li>
                        <li><a id="btn-color-4" class="dropdown-item" style="color: var(--bs-green)" href="#">Verde</a></li>
                        <li><a id="btn-color-5" class="dropdown-item" style="color: var(--bs-cyan)" href="#">Cyan</a></li>
                        <li><a id="btn-color-6" class="dropdown-item" style="color: var(--bs-blue)" href="#">Azul</a></li>
                        <li><a id="btn-color-7" class="dropdown-item" style="color: var(--bs-indigo)" href="#">Violeta</a></li>
                        <li><a id="btn-color-8" class="dropdown-item" style="color: var(--bs-pink)" href="#">Magenta</a></li>
                        <li><a id="btn-color-9" class="dropdown-item" style="color: var(--bs-gray-600)" href="#">Gris</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="input-group">
                    <button title="Vista previa" id="btn-preview" class="btn btn-outline-primary">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                    <button title="Volver a modo edicion" id="btn-stop-preview" class="btn btn-outline-danger hidden">
                        <i class="fa-regular fa-eye-slash"></i>
                    </button>
                    <button id="art-submit-btn" class="btn btn-success">
                        <i class="fa-regular fa-floppy-disk"></i> <?php echo $art_id ? "Guardar" : "Crear"; ?></button>
                </div>
            </div>
        </div>
        <div class="container">
            <article class="blog-post" id="preview">
                <h2 class="blog-post-title mb-1" id="preview-title"></h2>
                <br>
                <br>
                <p id="preview-body"></p>
            </article>
        </div>
        <textarea id="art-body" class="form-control" placeholder="Cuerpo del articulo..."></textarea>

    </div>
</div>
<script type="text/javascript" src="./scripts/article-editor.js"></script>