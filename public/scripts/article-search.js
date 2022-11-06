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
    inner_elmt.innerText = i;

    if (tag == "a") {
        inner_elmt.setAttribute("href", "#");
    }

    item.setAttribute("load-page", btn_idx);
    item.setAttribute("class", classes);
    item.appendChild(inner_elmt);

    // If the button number equals current page number return it before attaching the on-click event
    if (btn_idx == current_page) return item;

    // Else attach event to load page on click
    item.addEventListener("click", evt => {
        load({
            page: btn_idx,
            category: document.querySelector("#article-category").value
        });
    });

    return item;
}

function am_getArticles(security_hash) {
    let args = {
        query: "",
        year: "",
        month: "",
        day: "",
        category: "",
        page: 1,
        page_length: 10,
        sort_column: "fecha",
        sort_order: "desc",
        ...arguments[1]
    };
    let url = BASE_URL + "/api/article/read";
    let params = `?security_hash=${security_hash}`
        + (args.query ? `&query=${args.query}` : "")
        + (args.year ? `&year=${args.year}` : "")
        + (args.month ? `&month=${args.month}` : "")
        + (args.day ? `&day=${args.day}` : "")
        + (args.category ? `&category=${args.category}` : "")
        + (args.page ? `&page=${args.page}` : "")
        + (args.page_length ? `&page_length=${args.page_length}` : "")
        + (args.sort_column ? `&sort_column=${args.sort_column}` : "")
        + (args.sort_order ? `&sort_order=${args.sort_order}` : "");

    return fetch(url + params, { method: "GET" });
}

function removeChildren(parent) {
    for (i = parent.children.length - 1; parent.children.length > 0; i--) {
        parent.children[i].remove();
    }
}

function fill(list, data) {
    let results = data.content.results;
    let page = window.location.href.split("/")[5];
    
    for (key in results) {
        let body = bb_toHtml(results[key].cuerpo, true);
        let dots = body.length > 300 ? "..." : "";
        
        list.appendChild(
            createCard(
                results[key].id,
                results[key].image,
                results[key].titulo,
                body.substr(0, 300) + dots,
                results[key].fecha,
                page
            )
        );
    }
}

function generatePagination(container, data) {
    const page = data.content.page;
    const total_rows = data.content.total_rows;
    const page_length = 10;

    let extra_page = total_rows % page_length != 0 ? 1 : 0;

    for (i = 1; i <= total_rows / page_length + extra_page; i++) {
        container.appendChild(createPaginationBtn(i, page));
    }
}

window.addEventListener("load", evt => {
    component = document.querySelector("#article-search");
    security_hash = component.getAttribute("security-hash");
    search_query = document.querySelector("#art-search-field");
    search_btn = document.querySelector("#search-btn");
    article_category = document.querySelector("#article-category").value;
    search_results = document.querySelector("#search-results");
    pagination_div = document.querySelector("#pagination");
    year = component.getAttribute("year");
    month = component.getAttribute("month");
    day = component.getAttribute("day");

    console.log("Load evt");

    window.load = (params = {
        query: search_query.value,
        category: article_category,
        year: year,
        month: month,
        day: day,
        page: component.getAttribute("page")
    }) => {
        console.log("load func exec");
        am_getArticles(security_hash, params)
            .then(res => res.json())
            .then(data => {
                console.log(data);
                removeChildren(search_results);
                fill(search_results, data);
                removeChildren(pagination_div);
                generatePagination(pagination_div, data);
            })
            .catch(console.error);
    }

    search_timer = null;
    search_query.addEventListener("keyup", evt => {
        clearInterval(search_timer);
        search_timer = setTimeout(load, 333);
    });

    search_btn.addEventListener("click", evt => {
        clearInterval(search_timer);
        load();
    });

    load();
});