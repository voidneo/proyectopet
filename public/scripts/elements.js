function createCard(id, image, title, body, date, page) {
    let a = document.createElement("a");
    let card = document.createElement("div");
    let row = document.createElement("div");
    let img_col = document.createElement("div");
    let img = document.createElement("img");
    let body_col = document.createElement("div");
    let card_body = document.createElement("div");
    let c_title = document.createElement("h5");
    let c_body = document.createElement("p");
    let c_date = document.createElement("p");
    let c_date_inner = document.createElement("small");

    date = new Date(date);
    formattedDate = date.getDay() + "/" + date.getMonth() + "/" + date.getFullYear();
    hour = date.getHours();
    minutes = date.getMinutes();

    a.setAttribute("href", `${BASE_URL}/${page}/` + date.getFullYear() + "/" + date.getMonth() + "/" + date.getDay() + "/" + id);
    card.setAttribute("class", "card mb-3");
    row.setAttribute("class", "row g-0");
    img_col.setAttribute("class", "col-md-4");
    img.setAttribute("src", image);
    img.setAttribute("alt", "");
    img.setAttribute("class", "img-fluid rounded-start");
    body_col.setAttribute("class", "col-md-8");
    card_body.setAttribute("class", "card-body");
    c_title.setAttribute("class", "card-title");
    c_body.setAttribute("class", "card-text");
    c_date.setAttribute("class", "card-text");
    c_date_inner.setAttribute("class", "text-muted");

    c_title.innerText = title;
    c_body.innerText = body; // TODO: limit how much text actually is shown
    c_date_inner.innerText = `Publicado el ${formattedDate} a las ${hour}:${minutes}`;

    c_date.appendChild(c_date_inner);
    card_body.appendChild(c_title);
    card_body.appendChild(c_body);
    card_body.appendChild(c_date);
    body_col.appendChild(card_body);
    img_col.appendChild(img);
    row.appendChild(img_col);
    row.appendChild(body_col);
    card.appendChild(row);
    a.appendChild(card);

    return a;
}

function createPaginationBtn(btn_idx, current_page) {
    let tag = btn_idx == current_page ? "span" : "a";
    let inner_elmt = document.createElement(tag);
    let item = document.createElement("li");
    let classes = "page-item" + (btn_idx == current_page ? " active" : "");

    inner_elmt.setAttribute("class", "page-link");
    inner_elmt.innerText = btn_idx;

    if (tag == "a") {
        inner_elmt.setAttribute("href", "#");
    }

    item.setAttribute("load-page", btn_idx);
    item.setAttribute("title", "Ir a la pagina " + btn_idx);
    item.setAttribute("class", classes);
    item.appendChild(inner_elmt);

    return item;
}

function createHomeCard(id, image, title, body, date, page) {
    let a = document.createElement("a");
    let card = document.createElement("div");
    let row = document.createElement("div");
    let img_col = document.createElement("div");
    let img = document.createElement("img");
    let body_col = document.createElement("div");
    let card_body = document.createElement("div");
    let c_title = document.createElement("h5");
    let c_body = document.createElement("p");
    let c_date = document.createElement("p");
    let c_date_inner = document.createElement("small");
    let read_btn = document.createElement("a");

    date = new Date(date);
    formattedDate = date.getDay() + "/" + date.getMonth() + "/" + date.getFullYear();
    hour = date.getHours();
    minutes = date.getMinutes();

    read_btn.setAttribute("href", `./${page}/` + date.getFullYear() + "/" + date.getMonth() + "/" + date.getDay() + "/" + id);
    read_btn.setAttribute("class", "btn btn-primary");
    read_btn.setAttribute("style", "margin-bottom: 1em;");
    a.setAttribute("class", "col-md-4");
    card.setAttribute("class", "card mb-3");
    row.setAttribute("class", "row g-0");
    img_col.setAttribute("class", "col-md-4");
    img.setAttribute("src", image);
    img.setAttribute("alt", "");
    img.setAttribute("class", "img-fluid rounded-start");
    body_col.setAttribute("class", "col-md-12");
    card_body.setAttribute("class", "card-body");
    c_title.setAttribute("class", "card-title");
    c_body.setAttribute("class", "card-text");
    c_date.setAttribute("class", "card-text");
    c_date_inner.setAttribute("class", "text-muted");
    
    read_btn.innerText = "Leer mas";
    c_title.innerText = title;

    body_substr = body.substr(0, 128);

    c_body.innerText = body_substr + (body == body_substr || body_substr == "" ? "" : "...");
    c_date_inner.innerText = `Publicado el ${formattedDate} a las ${hour}:${minutes}`;

    c_date.appendChild(c_date_inner);
    card_body.appendChild(c_title);
    card_body.appendChild(c_body);
    card_body.appendChild(read_btn);
    card_body.appendChild(c_date);
    body_col.appendChild(card_body);
    //img_col.appendChild(img);
    row.appendChild(img_col);
    row.appendChild(body_col);
    card.appendChild(row);
    a.appendChild(card);

    return a;
}