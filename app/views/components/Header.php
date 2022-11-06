<?php
$path_fix = "";
if (isset($data["path_fix"])) {
  $path_fix = $data["path_fix"];
}
?>

<nav class="navbar navbar-expand-lg bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="./<?php echo $path_fix; ?>">PETD</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 mx-auto">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="./<?php echo $path_fix; ?>">Inicio</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Institucional
          </a>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="./<?php echo $path_fix; ?>noticias">
                <i class="fa-solid fa-newspaper"></i> Noticias
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="./<?php echo $path_fix; ?>cursos">
                <i class="fa-solid fa-graduation-cap"></i> Oferta educativa</a>
            </li>
            <li>
              <a class="dropdown-item" href="./<?php echo $path_fix; ?>llamados">
                <i class="fa-solid fa-briefcase"></i> Oferta laboral
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="./<?php echo $path_fix; ?>anuncios">
                <i class="fa-solid fa-bullhorn"></i> Anuncios
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="./<?php echo $path_fix; ?>inscripciones">
                <i class="fa-solid fa-file-pen"></i> Inscripciones
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./<?php echo $path_fix; ?>contacto">Contacto</a>
        </li>
        <!-- </ul> -->
        <?php (new Controller)->load_view("components/UserMenu", $data); ?>
    </div>
  </div>
</nav>