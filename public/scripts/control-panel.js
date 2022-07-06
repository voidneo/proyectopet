function cp_getComponent(name, args = {}, frame, security_hash) {
    let url = "./api/component/" + name;
	let params = `?security_hash=${security_hash}`;

    for(key in args) {
        params = params + "&" + key + "=" + args[key];
    }

	frame.setAttribute("src", url + params);
}

window.addEventListener("load", evt => {
    security_hash = document.getElementById("security-hash").value;
    btnUsr = document.getElementById("btn-users");
    btnCat = document.getElementById("btn-categories");
    btnArt = document.getElementById("btn-articles");
    frame  = document.getElementById("frame");

    btnUsr.addEventListener("click", evt => {
        // TODO: load users component
    });

    btnCat.addEventListener("click", evt => {
        cp_getComponent("category_list", {}, frame, security_hash);
        /* FIXME: component's script path loading from api/component/ instead of public/
         *  https://localhost/proyectopet/public/api/component/scripts/category-list.js
         *
         * FIXME: component's script fetch calls uses api/component/ as base instead of public/
         *  https://localhost/proyectopet/public/api/component/api/category/read?query=&page=1&page_length=5&sort_column=id&sort_order=asc&security_hash=5f9ad333c73b2918541d1107b003aafa25b4ac80
         */
    })

    btnArt.addEventListener("click", evt => {
        // TODO: load articles component
    });
});