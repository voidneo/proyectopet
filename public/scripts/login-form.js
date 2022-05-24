document.getElementById("form").addEventListener("submit", (evt) => {
	evt.preventDefault();

	formData = new FormData();
	formData.append("cedula", document.getElementById("cedula").value);
	formData.append("contrasena", document.getElementById("contrasena").value);
	formData.append("security_hash", document.getElementById("security_hash").value);

	fetch("./api/authenticate", {
		method: "POST",
		body: formData,
	})
		.then((res) => res.json())
		.then((data) => {
			console.log(data);
			if (data["error"]) {
				document.getElementById("feedback").innerText = data["error"];
			} else {
				url = new URL(window.location.href);
				redirect = url.searchParams.get("redirect");
				if (redirect) {
					window.location.href = redirect;
				} else {
					window.location = "./";
				}
			}
		})
		.catch(console.error);
});
