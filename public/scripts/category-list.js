// TODO: implement search by value
// TODO: load same page number when refreshing the table after creating a category

async function getCategories(
	pagination = { page: 1, rows_per_page: 5 },
	order = { column: "id", order: "ASC" }
) {
    let securityHashInput = document.querySelector("#security-hash");
    // TODO: find workaround url object
	url = new URL("http://localhost/proyectopet/public/api/category/read");
	a = JSON.stringify(pagination);
	b = JSON.stringify(order);
    c = securityHashInput.value;
	url.searchParams.append("pagination", a);
	url.searchParams.append("order", b);
	url.searchParams.append("security_hash", c);

	return await fetch(url, { method: "GET" });
}

function createCategory(name) {
    let pageLengthCtrl     = document.querySelector("#rows-per-page");
	let orderColumnCtrl    = document.querySelector("#order-column");
	let orderDirectionCtrl = document.querySelector("#order-direction");
    let securityHashInput  = document.querySelector("#security-hash");

	formData = new FormData();
	formData.append("nombre", name);
    formData.append("security_hash", securityHashInput.value);

	fetch("./api/category/create", {
		method: "POST",
		body: formData,
	})
		.then((res) => res.json())
		.then((data) => {
			if (data["error"]) {
				console.log(data["error"]);
			} else {
                getCategories(
                    { page: 1, rows_per_page: pageLengthCtrl.value },
                    { column: orderColumnCtrl.value, order: orderDirectionCtrl.value }
                )
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

	fetch("./api/category/update", {
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

	fetch("./api/category/delete", {
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
	const rows_per_page      = document.querySelector("#rows-per-page").value;
	const orderColumnCtrl    = document.querySelector("#order-column");
	const orderDirectionCtrl = document.querySelector("#order-direction");

	const currentPage = results["content"]["page"];
	const rows        = results["content"]["rows"];
	const total_rows  = results["content"]["total_rows"];
	const nOfPages    = Math.ceil(total_rows / rows_per_page);

	let pages = document.querySelector("#pages");

	for (let i = 1; i <= nOfPages; i++) {
		let btn = document.createElement("button");
		btn.setAttribute("id", "page-" + i);

		if (currentPage == i) btn.setAttribute("disabled", "");

		btn.innerText = i;
		pages.appendChild(btn);
		pages.innerHTML += " ";
	}

	for (let i = 1; i <= nOfPages; i++) {
		let btn = document.querySelector("#page-" + i);
		document.querySelector("#page-" + i).addEventListener("click", (evt) => {
			let rowsPerPage = document.querySelector("#rows-per-page").value;
			getCategories(
				{ page: i, rows_per_page: rowsPerPage },
				{ column: orderColumnCtrl.value, order: orderDirectionCtrl.value }
			)
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

		let tr    = document.createElement("tr");
		let c1    = document.createElement("td");
		let input = document.createElement("input");
		let c2    = document.createElement("td");
		let edit  = document.createElement("a");
		let del   = document.createElement("a");
		let c3    = document.createElement("td");
        
		tr.setAttribute("id", "row-" + id);

		c1.innerText = id;
		tr.appendChild(c1);

		input.setAttribute("value", name);
		input.setAttribute("id", "id-" + id);
		input.setAttribute("type", "text");
		input.setAttribute("readonly", "true");

		c2.appendChild(input);
		tr.appendChild(c2);

		edit.innerText = "Editar";
		edit.setAttribute("href", "#");

		del.innerText = "Eliminar";
		del.setAttribute("href", "#");

		c3.appendChild(edit);
		c3.innerHTML += " | ";
		c3.appendChild(del);
		tr.appendChild(c3);

		table.appendChild(tr);

		edit.addEventListener("click", (evt) => {
			// FIXME: this event doesn't fire because javascript, why else
			input.removeAttribute("readonly");
			input.focus();
		});

		del.addEventListener("click", (evt) => {
			if (confirm("Seguro que desea eliminar el registro '" + name + "'?")) {
				deleteCategory(id);
			}
		});

		input.addEventListener("dblclick", (evt) => {
			input.setAttribute("previous-value", input.value);
			input.removeAttribute("readonly");
		});

		input.addEventListener("keyup", (evt) => {
			if (evt.code == "Enter") {
                // TODO: validate the data
				input.setAttribute("readonly", "true");
				updateCategory(id, input.value);
			} else if (evt.code == "Escape") {
				input.value = input.getAttribute("previous-value");
				input.setAttribute("readonly", "true");
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

window.addEventListener("load", (evt) => {
	let pageLengthCtrl     = document.querySelector("#rows-per-page");
	let idTableHeader      = document.querySelector("#column-id");
	let nameTableHeader    = document.querySelector("#column-nombre");
	let orderColumnCtrl    = document.querySelector("#order-column");
	let orderDirectionCtrl = document.querySelector("#order-direction");
	let catNameInput       = document.querySelector("#cat-creation-name");
	let catCreationBtn     = document.querySelector("#cat-creation-btn");

	idTableHeader.addEventListener("click", (evt) => {
		if (orderColumnCtrl.value == "id") {
			orderDirectionCtrl.value =
				orderDirectionCtrl.value == "asc" ? "desc" : "asc";
		} else {
			orderColumnCtrl.value = "id";
			orderDirectionCtrl.value = "asc";
		}

		getCategories(
			{ page: 1, rows_per_page: pageLengthCtrl.value },
			{ column: orderColumnCtrl.value, order: orderDirectionCtrl.value }
		)
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

		getCategories(
			{ page: 1, rows_per_page: pageLengthCtrl.value },
			{ column: orderColumnCtrl.value, order: orderDirectionCtrl.value }
		)
			.then((response) => response.json())
			.then(refreshTable)
			.catch(console.log);
	});

	nameTableHeader.addEventListener("click", (evt) => {
		document.querySelector("#order-column").value = "nombre";
	});

	pageLengthCtrl.addEventListener("change", (evt) => {
		getCategories(
			{ page: 1, rows_per_page: pageLengthCtrl.value },
			{ column: orderColumnCtrl.value, order: orderDirectionCtrl.value }
		)
			.then((response) => response.json())
			.then(refreshTable)
			.catch(console.log);
	});

    catCreationBtn.addEventListener("click", evt => {
        if(catNameInput.value != "") {
            createCategory(catNameInput.value);
        }
    })

	getCategories(
		{ page: 1, rows_per_page: pageLengthCtrl.value },
		{ column: orderColumnCtrl.value, order: orderDirectionCtrl.value }
	)
		.then((response) => response.json())
		.then(refreshTable)
		.catch(console.log);
});
