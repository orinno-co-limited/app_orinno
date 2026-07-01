document
    .querySelector("#profile-img-file-input")
    .addEventListener("change", function () {
        var o = document.querySelector(".user-profile-image"),
            e = document.querySelector(".profile-img-file-input").files[0],
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