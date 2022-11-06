async function getCategories() {
    let search_query  = document.querySelector("#search-box").value;
    let current_page  = document.querySelector("#loaded-page").value;
    let page_length   = document.querySelector("#rows-per-page").value;
	let sort_col_name = document.querySelector("#order-column").value;
	let sort_order    = document.querySelector("#order-direction").value;
    let security_hash = document.querySelector("#security-hash").value;

	let url = "./api/category/read";
	let params = `?
	query=${search_query}
	&page=${current_page}
	&page_length=${page_length}
	&sort_column=${sort_col_name}
	&sort_order=${sort_order}
	&security_hash=${security_hash}
	`;

	return await fetch(url + params, { method: "GET" });
}

function createCategory(name) {
    let security_hash = document.querySelector("#security-hash").value;

	formData = new FormData();
	formData.append("nombre", name);
    formData.append("security_hash", security_hash);

	fetch(BASE_URL + "/api/category/create", {
		method: "POST",
		body: formData,
	})
		.then((res) => res.json())
		.then((data) => {
			if (data["error"]) {
				console.log(data["error"]);
			} else {
                getCategories()
                    .then((response) => response.json())
                    .then(refreshTable)
                    .catch(console.log);
            }
		})
		.catch(console.log);
}

function updateCategory(id, name) {
	let input              = document.querySelector("#id-" + id);
    let securityHashInput  = document.querySelector("#security-hash");
    
	formData = new FormData();
	formData.append("id", id);
	formData.append("nombre", name);
    formData.append("security_hash", securityHashInput.value);

	fetch(BASE_URL + "/api/category/update", {
		method: "POST",
		body: formData,
	})
		.then((res) => res.json())
		.then((data) => {
			if (data["error"]) {
				console.log(data["error"]);
				input.value = input.getAttribute("previous-value");
			}
		})
		.catch((err) => {
			input.value = input.getAttribute("previous-value");
		});
}

function deleteCategory(id) {
    let securityHashInput  = document.querySelector("#security-hash");

	formData = new FormData();
	formData.append("id", id);
    formData.append("security_hash", securityHashInput.value);

	fetch(BASE_URL + "/api/category/delete", {
		method: "POST",
		body: formData,
	})
		.then((res) => res.json())
		.then((data) => {
			if (data["error"]) {
				console.log(data["error"]);
			} else {
				let table = document.querySelector("#table-body");
				let row = document.querySelector("#row-" + id);
				table.removeChild(row);
			}
		})
		.catch(console.error);
}

function removeAllChildren(id) {
	let parent = document.querySelector("#" + id);
	for (i = parent.children.length - 1; parent.children.length > 0; i--) {
		parent.children[i].remove();
	}
}

function generatePagination(results) {
	const rows_per_page = document.querySelector("#rows-per-page").value;

	const currentPage = results["content"]["page"];
	const total_rows  = results["content"]["total_rows"];
	const nOfPages    = Math.ceil(total_rows / rows_per_page);

	let pages = document.querySelector("#pages");

	for (let i = 1; i <= nOfPages; i++) {
		let btn = createPaginationBtn(i, currentPage);
		btn.setAttribute("id", "page-" + i);

		pages.appendChild(btn);
	}

    let currentPageCtrl = document.querySelector("#loaded-page");

	for (let i = 1; i <= nOfPages; i++) {

		if(i == currentPage) continue;

		document.querySelector("#page-" + i).addEventListener("click", (evt) => {
			currentPageCtrl.value = i;
			getCategories()
				.then((response) => response.json())
				.then(refreshTable)
				.catch(console.log);
		});
	}
}

function populateTable(results) {
	let table = document.querySelector("#table-body");

	rows = results["content"]["results"];
	for (i = 0; i < rows.length; i++) {
		let id    = rows[i]["id"];
		let name  = rows[i]["nombre"];

		let tr        = document.createElement("tr");
		let c1        = document.createElement("td");
		let input     = document.createElement("input");
		let c2        = document.createElement("td");
		let btngrp    = document.createElement("div");
		let edit      = document.createElement("button");
		let edit_icon = document.createElement("i");
		let del       = document.createElement("button");
        let del_icon  = document.createElement("i");
		let c3        = document.createElement("td");
        
		tr.setAttribute("id", "row-" + id);

		c1.innerText = id;
		tr.appendChild(c1);

		input.setAttribute("value", name);
		input.setAttribute("id", "id-" + id);
		input.setAttribute("type", "text");
		input.classList.add("form-control", "disabled");
		input.setAttribute("title", "Doble click para editar");
		input.readOnly = true;
		
		c2.appendChild(input);
		tr.appendChild(c2);
		
		btngrp.setAttribute("class", "input-group")
		
        edit_icon.setAttribute("class", "fa-regular fa-pen-to-square");

		edit.setAttribute("class", "btn btn-success");
		edit.setAttribute("title", "Editar categoria");
		edit.appendChild(edit_icon);
		
        del_icon .setAttribute("class", "fa-regular fa-trash-can");
		
		del.setAttribute("class", "btn btn-danger");
		del.setAttribute("title", "Eliminar categoria");
		del.appendChild(del_icon);
		
		btngrp.appendChild(edit);
		btngrp.appendChild(del);

		c3.appendChild(btngrp);
		tr.appendChild(c3);

		table.appendChild(tr);

		edit.addEventListener("click", (evt) => {
			input.readOnly = false;
			input.focus();
		});

		del.addEventListener("click", (evt) => {
			if (confirm("Seguro que desea eliminar el registro '" + name + "'?")) {
				deleteCategory(id);
			}
		});

		input.addEventListener("dblclick", (evt) => {
			input.setAttribute("previous-value", input.value);
			input.readOnly = false;
		});

		input.addEventListener("keyup", (evt) => {
			if (evt.code == "Enter") {
                // TODO: validate the data
				input.readOnly = true;
				updateCategory(id, input.value);
			} else if (evt.code == "Escape") {
				input.value = input.getAttribute("previous-value");
				input.readOnly = true;
			}
		});
	}
}

function refreshTable(data) {
	removeAllChildren("table-body");
	populateTable(data);
	removeAllChildren("pages");
	generatePagination(data);
}

(()=> {
	let searchBoxCtrl      = document.querySelector("#search-box");
	let searchBtn          = document.querySelector("#search-btn");
	let pageLengthCtrl     = document.querySelector("#rows-per-page");
	let idTableHeader      = document.querySelector("#column-id");
	let nameTableHeader    = document.querySelector("#column-nombre");
	let orderColumnCtrl    = document.querySelector("#order-column");
	let orderDirectionCtrl = document.querySelector("#order-direction");
	let catNameInput       = document.querySelector("#cat-creation-name");
	let catCreationBtn     = document.querySelector("#cat-creation-btn");

	searchBtn.addEventListener("click", (evt) => {
		getCategories()
			.then((response) => response.json())
			.then(refreshTable)
			.catch(console.log);
	});

	searchBoxCtrl.addEventListener("keyup", (evt) => {
		if (evt.code == "Enter") {
			getCategories()
			.then((response) => response.json())
			.then(refreshTable)
			.catch(console.log);
		}
	});

	idTableHeader.addEventListener("click", (evt) => {
		if (orderColumnCtrl.value == "id") {
			orderDirectionCtrl.value =
				orderDirectionCtrl.value == "asc" ? "desc" : "asc";
		} else {
			orderColumnCtrl.value = "id";
			orderDirectionCtrl.value = "asc";
		}

		getCategories()
			.then((response) => response.json())
			.then(refreshTable)
			.catch(console.log);
	});

	nameTableHeader.addEventListener("click", (evt) => {
		if (orderColumnCtrl.value == "nombre") {
			orderDirectionCtrl.value =
				orderDirectionCtrl.value == "asc" ? "desc" : "asc";
		} else {
			orderColumnCtrl.value = "nombre";
			orderDirectionCtrl.value = "asc";
		}

		getCategories()
			.then((response) => response.json())
			.then(refreshTable)
			.catch(console.log);
	});

	nameTableHeader.addEventListener("click", (evt) => {
		document.querySelector("#order-column").value = "nombre";
	});

	pageLengthCtrl.addEventListener("change", (evt) => {
		getCategories()
			.then((response) => response.json())
			.then(refreshTable)
			.catch(console.log);
	});

    catCreationBtn.addEventListener("click", evt => {
        if(catNameInput.value != "") {
			// TODO: validate category name
            createCategory(catNameInput.value);
        }
    })

	getCategories()
		.then((response) => response.json())
		.then(refreshTable)
		.catch(console.log);
	})();
