async function cc_getCategories(query) {
	let security_hash = document.querySelector("#security-hash").value;
	let url           = "./api/category/read";
	let params        = `?query=${query}&security_hash=${security_hash}`;

	return await fetch(url + params, { method: "GET" });
}

function cc_refreshSuggestions() {
	let catChooser = document.querySelector("#cat-chooser");
	cc_getCategories(catChooser.value)
		.then((res) => res.json())
		.then((data) => {
			let list = document.querySelector("#cat-list");
			list.innerHTML = "";

			for (key in data["content"]["results"]) {
				let opt = document.createElement("option");
				opt.setAttribute("value", data["content"]["results"][key]["nombre"]);
				opt.setAttribute("id", data["content"]["results"][key]["id"]);
				list.appendChild(opt);
			}

			cc_isInDataset(catChooser, list);
		});
}

// Checks if input's value is on the given list
function cc_isInDataset(input, list) {
	let options = list.childNodes;
	for (let i = 0; i < options.length; i++) {
		// If it is
		if (options[i].value == input.value) {
			// Sets the category's id as an attribute in the input element
			input.setAttribute("cat-id", options[i].getAttribute("id"));
			// Sets input's 'valid' attribute to true for validation
			input.setAttribute("valid", "true");
			return true;
		}
	}
	input.setAttribute("valid", "false");
}

window.addEventListener("load", (evt) => {
	let catChooser = document.querySelector("#cat-chooser");
	let list       = document.querySelector("#cat-list");
	let timer      = null;
	catChooser.addEventListener("input", (evt) => {
		if (cc_isInDataset(catChooser, list)) {
			clearTimeout(timer);
			return;
		}

		clearTimeout(timer);
		timer = setTimeout(cc_refreshSuggestions, 333);
	});
});
