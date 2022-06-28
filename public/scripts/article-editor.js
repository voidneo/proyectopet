function ae_createArticle(title, body, id_categoria, security_hash) {
    formData = new FormData();
	formData.append("titulo", title);
	formData.append("cuerpo", body);
	formData.append("id_categoria", id_categoria);
    formData.append("security_hash", security_hash);

	return fetch("./api/article/create", {
		method: "POST",
		body: formData,
	})
}

function ae_getLastArticle(security_hash) {
    let url = "./api/article/read";
	let params = `?
	&page_length=1
	&sort_column=id
	&sort_order=desc
	&security_hash=${security_hash}
	`;

	return fetch(url + params, { method: "GET" });
}

function ae_getArticle(id, security_hash) {
    let url = "./api/article/read";
	let params = `?
	id=${id}
	&security_hash=${security_hash}
	`;

	return fetch(url + params, { method: "GET" });
}

function ae_updateArticle(id, title, body, id_categoria, security_hash) {
    formData = new FormData();
	formData.append("id", id);
	formData.append("titulo", title);
	formData.append("cuerpo", body);
	formData.append("id_categoria", id_categoria);
    formData.append("security_hash", security_hash);

	return fetch("./api/article/update", {
		method: "POST",
		body: formData,
	})
}

window.addEventListener("load", evt => {
    let inputArtId       = document.getElementById("art-id");
    let inputArtTitle    = document.getElementById("art-title");
    let inputArtCategory = document.getElementById("cat-chooser");
    let inputArtBody     = document.getElementById("art-body");
    let btnSubmit        = document.getElementById("art-submit-btn");
    let security_hash    = document.getElementById("security-hash").value;

    btnSubmit.addEventListener("click", evt => {
        // TODO: validate data before creating / updating

        if(inputArtId.value) {
            ae_updateArticle(
                inputArtId.value,
                inputArtTitle.value,
                inputArtBody.value,
                inputArtCategory.getAttribute("cat-id"),
                security_hash
            )
            .then(res => res.json())
            .then(data => {
                if(data.status == 204) {
                    // TODO: feed back success message
                    alert("success");
                }
            })
            .catch(console.error);
        } else {
            ae_createArticle(
                inputArtTitle.value,
                inputArtBody.value,
                inputArtCategory.getAttribute("cat-id"),
                security_hash
            )
            .then(res => res.json())
            .then(data => {
                if(data.status == 201) {
                    ae_getLastArticle(security_hash)
                    .then(res => res.json())
                    .then(artData => {
                        if(artData.status == 200) {
                            inputArtId.value = artData.content.results[0].id;
                            btnSubmit.innerText = "Actualizar";
                        }
                    });
                }
            })
            .catch(console.error);
        }
    });

    if(inputArtId.value) {
        ae_getArticle(inputArtId.value, security_hash)
        .then(res => res.json())
        .then(data => {
            inputArtTitle.value    = data["content"][0]["titulo"];
            inputArtBody.value     = data["content"][0]["cuerpo"];
            inputArtCategory.value = data["content"][0]["categoria"];

			// Sets the category's id as an attribute in the input element
			inputArtCategory.setAttribute("cat-id", data["content"][0]["id_categoria"]);
			// Sets input's 'valid' attribute to true for validation
			inputArtCategory.setAttribute("valid", "true");
        })
        .catch(console.error);
    }

});