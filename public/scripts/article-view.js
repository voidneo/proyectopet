window.addEventListener("load", (evt) => {
	let article = document.getElementById("article-body");
	let url_cpy = document.getElementById("url-cpy");

	url_cpy.addEventListener("click", (e) => {
        navigator.clipboard.writeText(window.location.href);
	});

	article.innerHTML = bb_toHtml(article.innerHTML);
});
