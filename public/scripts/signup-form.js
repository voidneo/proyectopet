document.getElementById("form").addEventListener("submit", (evt) => {
	evt.preventDefault();

	formData = new FormData();
	formData.append("cedula", document.getElementById("cedula").value);
	formData.append("correo", document.getElementById("correo").value);
	formData.append("nombre", document.getElementById("nombre").value);
	formData.append("apellido", document.getElementById("apellido").value);
	formData.append("contrasena", document.getElementById("contrasena").value);
	formData.append("security_hash", document.getElementById("security_hash").value);
    console.log(formData);

	fetch("./api/user/create", {
		method: "POST",
		body: formData,
	})
		.then((res) => res.json())
		.then((data) => {
			console.log(data);
			if (data["error"]) {
				document.getElementById("feedback").innerText = data["error"];
			}
			else if(data = "") {
				console.log("empty string");
			}
			else {
				alert("Se ha registrado con exito. Podras iniciar sesion apenas un adscripto te valide");
				url = new URL(window.location.href);
				redirect = url.searchParams.get("redirect");
				if (redirect) {
					window.location.href = redirect;
				} else {
					window.location = "./";
				}
			}
		})
		.catch(err => {
			// This shouldn't be needed anymore
			if(err.toString().match(/syntax/gi)) {
				console.log("Syntax error in json response. (likely empty string)");
				alert("Se ha registrado con exito. Podras iniciar sesion apenas un adscripto te valide");
				url = new URL(window.location.href);
				redirect = url.searchParams.get("redirect");
				if (redirect) {
					window.location.href = redirect;
				} else {
					window.location = "./";
				}
			} else {
				console.log(err);
			}
		});
});

// FIXME: duplicated user error on signup not being handled
