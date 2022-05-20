window.T = async () => {
	formData = new FormData();
	formData.append("user", document.getElementById("user").value);
	formData.append("pass", document.getElementById("pass").value);
	formData.append("hash", document.getElementById("hash").value);

	return await fetch("./api/authenticate", {
		method: "POST",
		body: formData,
	});
};

document.getElementById("form").addEventListener("submit", (evt) => {
	evt.preventDefault();

	formData = new FormData();
	formData.append("user", document.getElementById("user").value);
	formData.append("pass", document.getElementById("pass").value);
	formData.append("hash", document.getElementById("hash").value);

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
