document
    .querySelector("#language-icon-img-file-input")
    .addEventListener("change", function () {
        var o = document.querySelector(".language-icon-image"),
            e = document.querySelector(".language-icon-img-file-input").files[0],
            i = new FileReader();
        i.addEventListener(
            "load",
            function () {
                o.src = i.result;
            },
            !1
        ),
            e && i.readAsDataURL(e);
    });

document
    .querySelector("#language-icon-img-file-input-edit")
    .addEventListener("change", function () {
        var o = document.querySelector(".language-icon-image-edit"),
            e = document.querySelector(".language-icon-img-file-input-edit").files[0],
            i = new FileReader();
        i.addEventListener(
            "load",
            function () {
                o.src = i.result;
            },
            !1
        ),
            e && i.readAsDataURL(e);
    });
