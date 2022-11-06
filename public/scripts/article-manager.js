function getCategories(security_hash) {
	let url = `./api/category/read?&security_hash=${security_hash}`;
	return fetch(url, { method: "GET" });
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
        sort_column: "id",
        sort_order: "asc",
        ...arguments[1]
    };
    // TODO: add parameter to tell API to not include the body of the article
    // TODO: make API return category's name too
    let url = "./api/article/read";
    let params = `?security_hash=${security_hash}`
	+ (args.query       ? `&query=${args.query}`             : "")
	+ (args.year        ? `&year=${args.year}`               : "")
	+ (args.month       ? `&month=${args.month}`             : "")
	+ (args.day         ? `&day=${args.day}`                 : "")
	+ (args.category    ? `&category=${args.category}`       : "")
	+ (args.page        ? `&page=${args.page}`               : "")
	+ (args.page_length ? `&page_length=${args.page_length}` : "")
	+ (args.sort_column ? `&sort_column=${args.sort_column}` : "")
	+ (args.sort_order  ? `&sort_order=${args.sort_order}`   : "");

	return fetch(url + params, { method: "GET" });
}

function am_deleteArticle(security_hash, id) {
    let url = "./api/article/delete";
    let params = new FormData();
    params.append("security_hash", security_hash);
    params.append("id", id);

	return fetch(url, { method: "POST", body: params });
}

function removeChildren(parent) {
	for (i = parent.children.length - 1; parent.children.length > 0; i--) {
		parent.children[i].remove();
	}
}

function fill(table, data, security_hash) {
    let results = data.content.results;

    for(key in results) {
        let tr         = document.createElement("tr");
        let cid        = document.createElement("td");
        let ctitle     = document.createElement("td");
        let ccategory  = document.createElement("td");
        let cdate      = document.createElement("td");
        let cctrls     = document.createElement("td");
        let btngrp     = document.createElement("div");
        let btn_edit   = document.createElement("button");
        let edit_icon  = document.createElement("i");
        let btn_delete = document.createElement("button");
        let delete_icon= document.createElement("i");
        let art_link   = document.createElement("a");
        let date       = results[key].fecha.split("-");
        let year       = date[0];
        let month      = date[1];
        let day        = date[2].split(" ")[0];
        let category   = window.CATEGORIES[results[key].id_categoria].name;
        let ref        = window.CATEGORIES[results[key].id_categoria].href;

        cid.innerText       = results[key].id;
        art_link.innerText  = results[key].titulo;
        ccategory.innerText = category;
        cdate.innerText     = `${day}/${month}/${year}`;

        art_link.href = `./${ref}/${year}/${month}/${day}/${results[key].id}`;

        ctitle.appendChild(art_link);

        btngrp.setAttribute("class", "input-group");

        edit_icon.setAttribute("class", "fa-regular fa-pen-to-square");
		edit_icon.setAttribute("title", "Editar categoria");

        btn_edit.setAttribute("class", "btn btn-success");
		btn_edit.setAttribute("title", "Editar articulo");
        btn_edit.appendChild(edit_icon);

        delete_icon.setAttribute("class", "fa-regular fa-trash-can");
        
        btn_delete.setAttribute("art-id", results[key].id);
        btn_delete.setAttribute("class", "btn btn-danger");
		btn_delete.setAttribute("title", "Eliminar articulo");
        btn_delete.appendChild(delete_icon);

        btngrp.appendChild(btn_edit);
        btngrp.appendChild(btn_delete);

        cctrls.appendChild(btngrp);

        tr.setAttribute("id", "art-" + results[key].id)
        tr.appendChild(cid);
        tr.appendChild(ctitle);
        tr.appendChild(ccategory);
        tr.appendChild(cdate);
        tr.appendChild(cctrls);

        table.appendChild(tr);

        btn_edit.addEventListener("click", evt => {
            window.location.href = "./articulo?id=" + btn_delete.getAttribute("art-id");
        });

        btn_delete.addEventListener("click", evt => {
            let id = btn_delete.getAttribute("art-id");
            am_deleteArticle(security_hash, id)
            .then(res => res.json())
            .then(data => {
                if(data.error) {
                    console.error(data.error);
                    alert("Algo salio mal");
                } else {
                    document.querySelector("#art-" + id).remove();
                }
            })
            .catch(console.error);
        })
    }
}

function generatePagination(container, data) {
    const page           = data.content.page;
    const total_rows     = data.content.total_rows;
    const page_length    = parseInt(document.querySelector("#search-props").getAttribute("page-length"));
    const search_query   = document.querySelector("#art-search-field");
    const year           = document.querySelector("#year");
    const month          = document.querySelector("#month");
    const day            = document.querySelector("#day");
    const category       = document.querySelector("#cat-chooser");
    const cat_valid      = category.getAttribute("valid") == "true" ? true : false;
    const search_props   = document.querySelector("#search-props");

    let extra_page = total_rows % page_length != 0 ? 1 : 0;

    for(i = 1; i <= total_rows / page_length + extra_page; i++) {
        let btn = createPaginationBtn(i, page);
        btn.setAttribute("load-page", i);
        container.appendChild(btn);

        if(i == page) { continue; }

        btn.addEventListener("click", evt => {
            load({
                query: search_query.value,
                year: year.value,
                month: month.value,
                day: day.value,
                category: cat_valid ? category.value : "",
                page: btn.getAttribute("load-page"),
                page_length: search_props.getAttribute("page-length"),
                sort_column: search_props.getAttribute("sort-column"),
                sort_order: search_props.getAttribute("sort-order")
            });
        });
    }
}

(() => {
    security_hash  = document.querySelector("#art-mgr").getAttribute("security-hash");
    table_body     = document.querySelector("#art-table-body");
    pagination_div = document.querySelector("#art-pagination");
    page_length    = document.querySelector("#page-length");
    search_query   = document.querySelector("#art-search-field");
    year           = document.querySelector("#year");
    month          = document.querySelector("#month");
    day            = document.querySelector("#day");
    category       = document.querySelector("#cat-chooser");
    search_props   = document.querySelector("#search-props");
    search_btn     = document.querySelector("#search-btn");

    table_head_id       = document.querySelector("#th-id");
    table_head_title    = document.querySelector("#th-title");
    table_head_category = document.querySelector("#th-category");
    table_head_date     = document.querySelector("#th-date");

    getCategories(security_hash)
    .then(res => res.json())
    .then(data => {
        arr = data.content.results;
        
        if(arr) {
            window.CATEGORIES = {};

            for(i = 0; i < arr.length; i++) {
                let ref = "#";

                if(arr[i].nombre == "ofertas educativas") {
                    ref = "cursos";
                } else
                if(arr[i].nombre == "ofertas laborales") {
                    ref = "llamados";
                } else
                if(arr[i].nombre == "noticias" || arr[i].nombre == "anuncios") {
                    ref = arr[i].nombre;
                }

                window.CATEGORIES[arr[i].id] = {
                    name: arr[i].nombre,
                    href: ref
                }
            }
        }
    })
    .catch(console.error);

    window.load = (params = {
        query: search_query.value,
        year: year.value,
        month: month.value,
        day: day.value,
        page: search_props.getAttribute("page"),
        page_length: search_props.getAttribute("page-length"),
        sort_column: search_props.getAttribute("sort-column"),
        sort_order: search_props.getAttribute("sort-order")
    }) => {
        params.category = category.getAttribute("valid") == "true" ? category.getAttribute("cat-id") : "";
        
        am_getArticles(security_hash, params)
        .then(res => res.json())
        .then(data => {
            removeChildren(table_body);
            fill(table_body, data, security_hash);
            removeChildren(pagination_div);
            generatePagination(pagination_div, data);
        })
        .catch(console.error);
    }

    search_btn.addEventListener("click", load);

    search_timer = null;
    search_query.addEventListener("keyup", evt => {
        clearTimeout(search_timer);

        search_timer = setTimeout(load, 333);
    });


    year.addEventListener("change", evt => {
        clearTimeout(search_timer);

        search_timer = setTimeout(load, 333);
    });


    month.addEventListener("change", evt => {
        clearTimeout(search_timer);

        search_timer = setTimeout(load, 333);
    });


    day.addEventListener("change", evt => {
        clearTimeout(search_timer);

        search_timer = setTimeout(load, 333);
    });

    category.addEventListener("change", evt => {
        clearTimeout(search_timer);

        if(category.getAttribute("valid") == "true") {
            load();
        }
    });

    page_length.addEventListener("change", evt => {
        search_props.setAttribute("page-length", page_length.value);
        load();
    });

    table_head_id.addEventListener("click", evt => {
        if(search_props.getAttribute("sort-column") == "id") {
            if(search_props.getAttribute("sort-order") == "asc") {
                search_props.setAttribute("sort-order", "desc");
            } else {
                search_props.setAttribute("sort-order", "asc");
            }
        } else {
            search_props.setAttribute("sort-column", "id");
        }

        load();
    });

    table_head_title.addEventListener("click", evt => {
        if(search_props.getAttribute("sort-column") == "titulo") {
            if(search_props.getAttribute("sort-order") == "asc") {
                search_props.setAttribute("sort-order", "desc");
            } else {
                search_props.setAttribute("sort-order", "asc");
            }
        } else {
            search_props.setAttribute("sort-column", "titulo");
        }

        load();
    });

    table_head_category.addEventListener("click", evt => {
        if(search_props.getAttribute("sort-column") == "id_categoria") {
            if(search_props.getAttribute("sort-order") == "asc") {
                search_props.setAttribute("sort-order", "desc");
            } else {
                search_props.setAttribute("sort-order", "asc");
            }
        } else {
            search_props.setAttribute("sort-column", "id_categoria");
        }

        load();
    });

    table_head_date.addEventListener("click", evt => {
        if(search_props.getAttribute("sort-column") == "fecha") {
            if(search_props.getAttribute("sort-order") == "asc") {
                search_props.setAttribute("sort-order", "desc");
            } else {
                search_props.setAttribute("sort-order", "asc");
            }
        } else {
            search_props.setAttribute("sort-column", "fecha");
        }

        load();
    });

    load();
})();