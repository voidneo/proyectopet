const BB_COLORS = [
    "#dc3545",
    "#fd7e14",
    "#ffc107",
    "#198754",
    "#0dcaf0",
    "#0d6efd",
    "#6610f2",
    "#d63384",
    "#727272"
];

const BB_SIZES = [
    "2.5rem",
    "2rem",
    "1.75rem",
    "1.5rem",
    "1.25rem"
];

const BB_ALIGNMENTS = [ "center", "right" ];

function bb_readTag(text) {
    let tag = "";
    for(let i = 0; i < text.length; i++) {

        tag += text[i];

        if(text[i] == "]")
            return tag;
    }
}

function bb_compile(token) {
    let tag = token;

    // bb opening/closing tags b, i and u
    if(tag.match(/^\[\/?(b|i|u)\]$/gi)) {
        tag = "<" + tag.substr(1, tag.length - 2) + ">";
    }
    // bb opening size tag
    else if(tag.match(/\[size=\d\]/gi)) {
        tag = "<span style='font-size:" + BB_SIZES[tag.substr(6, 1) - 1] + "'>";
    }
    // bb closing size tag
    else if(tag.match(/\[\/size\]/gi)) {
        tag = "</span>";
    }
    // bb opening color tag
    else if(tag.match(/\[color=\d\]/gi)) {
        let color_id = parseInt(tag.substr(7, 1)) - 1;
        tag = "<span style='color: " + BB_COLORS[color_id] + ";'>";
    }
    // bb closing color tag
    else if(tag.match(/\[\/color\]/gi)) {
        tag = "</span>";
    }
    // bb opening align tag
    else if(tag.match(/\[align=\d\]/gi)) {
        let alignment_id = parseInt(tag.substr(7, 1)) - 1;
        tag = "<p style='text-align: " + BB_ALIGNMENTS[alignment_id] + ";'>";
    }
    // bb closing align tag
    else if(tag.match(/\[\/align\]/gi)) {
        tag = "</p>";
    }
    else if(tag.match(/\[br\]/gi)) {
        tag = "<br>";
    }

    return tag;
}

function bb_clean(bb_code) {
    let html = bb_compile(bb_code);
    if(html == "<br>") return "\n";
    return bb_code == html ? bb_code : "";
}

function bb_tokenize(text) {
    let token_list = [];
    let token = "";

    for(let i = 0; i < text.length; i++) {
        
        // If char isn't part of a tag, add it to current token
        if(text[i] != "[") {

            // If char is new-line compile it as <br> and save as a token
            if(text[i].match(/\n/gi)) {
                token_list.push(token);
                token_list.push(bb_compile("[br]"));
                token = "";
                continue;
            }

            token += text[i];
            continue;
        }


        // If we find a tag..
        // Save read chars, if any, as a token
        if(token != "") {
            token_list.push(token);
            token = "";
        }
        
        // Read the whole tag and save as a token
        token = bb_readTag(text.substr(i));
        token_list.push(token);
        
        i += token.length - 1;
        token = "";
    }

    // Save read chars, if any, as a token
    if(token != "") {
        token_list.push(token);
    }

    return token_list;
}

function bb_toHtml(text, clean_mode) {
    
    let compile = clean_mode ? bb_clean : bb_compile;
    let tokens = bb_tokenize(text);
    let sizes = [];
    let html = "";

    for(let i = 0; i < tokens.length; i++) {
        html += compile(tokens[i]);
    }

    return html;
}