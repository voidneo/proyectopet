function getCategories(security_hash) {
    let url = `./api/category/read?&security_hash=${security_hash}`;
    return fetch(url, { method: "GET" });
}

function am_getArticles(security_hash, cat = "") {
    let args = {
        category: cat,
        page_length: 3,
        sort_column: "fecha",
        sort_order: "desc"
    };
    // TODO: add parameter to tell API to not include the body of the article
    // TODO: make API return category's name too
    let url = "./api/article/read";
    let params = `?security_hash=${security_hash}`
        + (args.category ? `&category=${args.category}` : "")
        + (args.page_length ? `&page_length=${args.page_length}` : "")
        + (args.sort_column ? `&sort_column=${args.sort_column}` : "")
        + (args.sort_order ? `&sort_order=${args.sort_order}` : "");

    return fetch(url + params, { method: "GET" });
}

window.load = evt => {
    const security_hash = document.getElementById("security-hash").value;
    const news_container = document.getElementById("news-results");
    const anno_container = document.getElementById("announcements-results");
    const jobs_container = document.getElementById("jobs-results");

    window.CATEGORIES = {};

    getCategories(security_hash)
        .then(res => res.json())
        .then(data => {
            arr = data.content.results;

            if (arr) {
                for (i = 0; i < arr.length; i++) {
                    let ref = "#";

                    if (arr[i].nombre == "ofertas educativas") {
                        ref = "cursos";
                    } else
                        if (arr[i].nombre == "ofertas laborales") {
                            ref = "llamados";
                        } else
                            if (arr[i].nombre == "noticias" || arr[i].nombre == "anuncios") {
                                ref = arr[i].nombre;
                            }

                    window.CATEGORIES[arr[i].id] = {
                        name: arr[i].nombre,
                        href: ref
                    }
                }
            }
        })
        .then(() => {
            // Get news articles
            am_getArticles(security_hash, 1)
                .then(res => res.json())
                .then(data => {
                    let art = data.content.results;

                    if (art) {
                        for (i = 0; i < art.length; i++) {
                            news_container.appendChild(
                                createHomeCard(art[i].id,
                                    "", art[i].titulo,
                                    bb_toHtml(art[i].cuerpo, true),
                                    art[i].fecha,
                                    window.CATEGORIES[art[i].id_categoria].href)
                            );
                        }
                    }
                })
                .catch(console.error);


            // Get announcements articles
            am_getArticles(security_hash, 4)
            .then(res => res.json())
            .then(data => {
                let art = data.content.results;

                if (art) {
                    for (i = 0; i < art.length; i++) {
                        anno_container.appendChild(
                            createHomeCard(art[i].id,
                                "", art[i].titulo,
                                bb_toHtml(art[i].cuerpo, true),
                                art[i].fecha,
                                window.CATEGORIES[art[i].id_categoria].href)
                        );
                    }
                }
            })
            .catch(console.error);


            // Get job articles
            am_getArticles(security_hash, 3)
            .then(res => res.json())
            .then(data => {
                let art = data.content.results;

                if (art) {
                    for (i = 0; i < art.length; i++) {
                        jobs_container.appendChild(
                            createHomeCard(art[i].id,
                                "", art[i].titulo,
                                bb_toHtml(art[i].cuerpo, true),
                                art[i].fecha,
                                window.CATEGORIES[art[i].id_categoria].href)
                        );
                    }
                }
            })
            .catch(console.error);
        })
        .catch(console.error);
};

window.onload = window.load;