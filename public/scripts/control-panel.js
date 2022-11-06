function cp_getComponent(name, args = {}, container, security_hash) {
    let url = "./api/component/" + name;
	let params = `?security_hash=${security_hash}`;

    for(key in args) {
        params = params + "&" + key + "=" + args[key];
    }

    fetch(url + params, { method: "GET" })
    .then(res => res.text())
    .then(data => {
        container.innerHTML = data;
        scripts = document.getElementsByTagName("script");
        for(i = 0; i < scripts.length; i++) {
            if(scripts[i].innerHTML != "")
                eval(scripts[i].innerHTML);
        }
    })
    .catch(console.error);
}

window.addEventListener("load", evt => {
    security_hash = document.getElementById("security-hash").value;
    btnUsr        = document.getElementById("btn-users");
    btnCat        = document.getElementById("btn-categories");
    btnArt        = document.getElementById("btn-articles");
    container     = document.getElementById("component");

    btnUsr.addEventListener("click", evt => {
        // TODO: load users component
    });

    btnCat.addEventListener("click", evt => {
        cp_getComponent("category_list", {}, container, security_hash);
    })

    btnArt.addEventListener("click", evt => {
        cp_getComponent("article_manager", {}, container, security_hash);
    });
});