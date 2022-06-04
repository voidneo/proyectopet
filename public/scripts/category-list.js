async function getCategories(pagination = {"page": 1, "rows_per_page": 5}, order = {"column": "id", "order": "ASC"}) {
    url = new URL("http://localhost/proyectopet/public/api/category/read");
    a = JSON.stringify(pagination);
    b = JSON.stringify(order);
    url.searchParams.append("pagination", a);
    url.searchParams.append("order", b);

    return await fetch(url, { method: "GET" });
}

function generatePagination(results) {
    let currentPage  = results["content"]["page"];
    const rows       = results["content"]["rows"];
    const total_rows = results["content"]["total_rows"];
    const nOfPages   = rows == 0 ? total_rows / 5 : total_rows / rows;
    
    let pages        = document.querySelector("#pages");
    pages.innerHTML  = "";

    for(let i = 1; i <= nOfPages; i++) {
        let btn = document.createElement("button");
        btn.setAttribute("id", "page-" + i);
        
        if(currentPage == i)
            btn.setAttribute("disabled", "");

        btn.innerText = i;
        pages.appendChild(btn);
        pages.innerHTML += " ";
    }

    for(let i = 1; i <= nOfPages; i++) {
        let btn = document.querySelector("#page-" + i);
        document.querySelector("#page-" + i).addEventListener("click", evt => {
            getCategories(
                {"page": i, "rows_per_page": 5} // TODO: get rows per page from html controller
            )
            .then(res => res.json())
            .then(data => {
                for(let i = 1; i <= nOfPages; i++) {
                    let btn = document.querySelector("#page-" + i);
                    btn.remove();
                }
                populateTable(data);
                generatePagination(data);
            })
            .catch(console.error);
        });
    }
}

function populateTable(results) {
    let table = document.querySelector("#table-body");
    table.innerHTML = "";

    rows = results["content"]["results"];
    for(i = 0; i < rows.length; i++) {
        let tr = document.createElement("tr");

        let c1 = document.createElement("td");
        c1.innerText = rows[i]["id"];
        tr.appendChild(c1);

        let input = document.createElement("input");
        input.setAttribute("value", rows[i]["nombre"]);
        input.setAttribute("id", "id-" + rows[i]["id"]);
        input.setAttribute("type", "text");
        input.setAttribute("readonly", "true");

        let c2 = document.createElement("td");
        c2.appendChild(input);
        tr.appendChild(c2);

        let edit = document.createElement("a");
        edit.innerText = "Editar";
        edit.setAttribute("href", "#");
        // TODO: add evt listener
        
        let del = document.createElement("a");
        del.innerText = "Eliminar";
        del.setAttribute("href", "#")
        
        let c3 = document.createElement("td");
        c3.appendChild(edit);
        c3.innerHTML += " | ";
        c3.appendChild(del);
        tr.appendChild(c3);

        table.appendChild(tr);

        del.addEventListener("click", evt => {
            // TODO: prompt to confirm
            // TODO: delete through API
            table.removeChild(tr);
        });

        input.addEventListener("dblclick", evt => {
            input.removeAttribute("readonly");
        });

        input.addEventListener("keyup", evt => {
            if(evt.code == "Enter") {
                input.setAttribute("readonly", "true");
                // TODO: update through API
            }
        });
    }
}

window.addEventListener("load", evt => {
    getCategories()
    .then(response => response.json())
    .then(data => {
        populateTable(data);
        generatePagination(data);
    })
    .catch(console.log);
});