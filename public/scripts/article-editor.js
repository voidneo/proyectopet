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

function updateSelection(input) {
    window.hlText = {
        start: input.selectionStart,
        end:   input.selectionEnd,
        len: input.selectionEnd - input.selectionStart,
        string: input.value.substr(input.selectionStart, input.selectionEnd - input.selectionStart)
    };
}

function addTag(input, tag, value) {
    let preText  = input.value.substr(0, hlText.start);
    let postText = input.value.substr(hlText.end, input.value.length);
    let val = value ? `=${value}` : "";

    input.value = preText + `[${tag}${val}]` + hlText.string + `[/${tag}]` + postText;
}

window.addEventListener("load", evt => {
    let component        = document.getElementById("art-editor");
    let articleId        = component.getAttribute("art-id");
    let inputArtTitle    = document.getElementById("art-title");
    let inputArtCategory = document.getElementById("cat-chooser");
    let inputArtBody     = document.getElementById("art-body");
    let btnSubmit        = document.getElementById("art-submit-btn");
    let preview          = document.getElementById("preview");
    let previewTitle     = document.getElementById("preview-title");
    let previewBody      = document.getElementById("preview-body");
    let btnPreview       = document.getElementById("btn-preview");
    let btnStopPreview   = document.getElementById("btn-stop-preview");
    let security_hash    = component.getAttribute("security-hash");
    let bBtn             = document.getElementById("b-btn");
    let iBtn             = document.getElementById("i-btn");
    let uBtn             = document.getElementById("u-btn");

    window.hlText = {
        start: 0,
        end: 0,
        len: 0,
        string: ""
    }

    btnSubmit.addEventListener("click", evt => {
        // TODO: validate data before creating / updating

        if(articleId) {
            ae_updateArticle(
                articleId,
                inputArtTitle.value,
                inputArtBody.value,
                inputArtCategory.getAttribute("cat-id"),
                security_hash
            )
            .then(res => res.json())
            .then(data => {
                if(data.status == 204) {
                    // TODO: feed back success message
                    alert("Articulo actualizado");
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
                            articleId = artData.content.results[0].id;
                            btnSubmit.innerText = "Actualizar";
                        }
                    });
                }
            })
            .catch(console.error);
        }
    });

    inputArtBody.addEventListener("select", evt => {
        updateSelection(inputArtBody);
    });

    inputArtBody.addEventListener("click", evt => {
        updateSelection(inputArtBody);
    });

    inputArtBody.addEventListener("keyup", evt => {
        updateSelection(inputArtBody);
    });

    bBtn.addEventListener("click", evt => {
        addTag(inputArtBody, "b");
    });

    iBtn.addEventListener("click", evt => {
        addTag(inputArtBody, "i");
    });

    uBtn.addEventListener("click", evt => {
        addTag(inputArtBody, "u");
    });

    // Size buttons
    for(let i = 1; i < 6; i++) {
        let btn = document.getElementById("btn-size-" + i);

        btn.addEventListener("click", evt => {
            addTag(inputArtBody, "size", i);
        });
    }

    // Color buttons
    for(let i = 1; i < 10; i++) {
        let btn = document.getElementById("btn-color-" + i);

        btn.addEventListener("click", evt => {
            addTag(inputArtBody, "color", i);
        });
    }

    // Align buttons
    for(let i = 1; i < 3; i++) {
        let btn = document.getElementById("align-btn-" + i);

        btn.addEventListener("click", evt => {
            addTag(inputArtBody, "align", i);
        });
    }

    btnPreview.addEventListener("click", evt => {
        btnStopPreview.classList.remove("hidden");
        inputArtBody.classList.add("hidden");
        btnPreview.classList.add("hidden");
        previewBody.innerHTML = bb_toHtml(inputArtBody.value);
        previewTitle.innerHTML = inputArtTitle.value;
        preview.classList.remove("hidden");
    });

    btnStopPreview.addEventListener("click", evt => {
        btnStopPreview.classList.add("hidden");
        preview.classList.add("hidden");
        inputArtBody.classList.remove("hidden");
        btnPreview.classList.remove("hidden");
    });

    if(articleId) {
        ae_getArticle(articleId, security_hash)
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