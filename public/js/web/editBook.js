CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
    toolbar: {
        items: [
            "bold",
            "italic",
            "strikethrough",
            "underline",
            "subscript",
            "superscript",
            "|",
            "fontSize",
            "fontFamily",
            "fontColor",
            "fontBackgroundColor",
            "highlight",
            "|",
            "alignment",
            "blockQuote",
            "removeFormat",
            "|",
            "heading",
            "-",
            "bulletedList",
            "numberedList",
            "todoList",
            "|",
            "outdent",
            "indent",
            "|",
            "link",
            "uploadImage",
            "mediaEmbed",
            "insertTable",
            "|",
            "horizontalLine",
            "pageBreak",
            "specialCharacters",
            "|",
            "code",
            "codeBlock",
            "htmlEmbed",
            "|",
            "undo",
            "redo",
            "|",
            "findAndReplace",
            "selectAll",
            "exportPDF",
        ],
        shouldNotGroupWhenFull: true,
    },
    cloudServices: {
        tokenUrl:
            "https://93732.cke-cs.com/token/dev/csKwY3YDbLKDeypIWsJmiYkYcw9cevcGtL8v?limit=10",
        uploadUrl: "https://93732.cke-cs.com/easyimage/upload/",
    },
    fontColor: {
        colors: [
            {
                color: "#000",
                label: "Black",
            },
            {
                color: "#4D4D4D",
                label: "Dim grey",
            },
            {
                color: "#999",
                label: "Grey",
            },
            {
                color: "#E6E6E6",
                label: "Light grey",
            },
            {
                color: "#FFF",
                label: "White",
            },
            {
                color: "#E64C4C",
                label: "Red",
            },
            {
                color: "#E6994C",
                label: "Orange",
            },
            {
                color: "#E6E64C",
                label: "Yellow",
            },
            {
                color: "#99E64C",
                label: "Light green",
            },
            {
                color: "#4CE64C",
                label: "Green",
            },
            {
                color: "#4CE699",
                label: "Aquamarine",
            },
            {
                color: "#4CE6E6",
                label: "Turquoise",
            },
            {
                color: "#4C99E6",
                label: "Light blue",
            },
            {
                color: "#4C4CE6",
                label: "Blue",
            },
            {
                color: "#994CE6",
                label: "Purple",
            },
        ],
    },
    fontBackgroundColor: {
        colors: [
            {
                color: "#000",
                label: "Black",
            },
            {
                color: "#4D4D4D",
                label: "Dim grey",
            },
            {
                color: "#999",
                label: "Grey",
            },
            {
                color: "#E6E6E6",
                label: "Light grey",
            },
            {
                color: "#FFF",
                label: "White",
            },
            {
                color: "#E64C4C",
                label: "Red",
            },
            {
                color: "#E6994C",
                label: "Orange",
            },
            {
                color: "#E6E64C",
                label: "Yellow",
            },
            {
                color: "#99E64C",
                label: "Light green",
            },
            {
                color: "#4CE64C",
                label: "Green",
            },
            {
                color: "#4CE699",
                label: "Aquamarine",
            },
            {
                color: "#4CE6E6",
                label: "Turquoise",
            },
            {
                color: "#4C99E6",
                label: "Light blue",
            },
            {
                color: "#4C4CE6",
                label: "Blue",
            },
            {
                color: "#994CE6",
                label: "Purple",
            },
        ],
    },
    removePlugins: [
        "CKBox",
        "CKFinder",
        "RealTimeCollaborativeComments",
        "RealTimeCollaborativeTrackChanges",
        "RealTimeCollaborativeRevisionHistory",
        "PresenceList",
        "Comments",
        "TrackChanges",
        "TrackChangesData",
        "RevisionHistory",
        "Pagination",
        "WProofreader",
        "MathType",
    ],
    fontFamily: {
        supportAllValues: true,
    },
    language: {
        ui: "en",
        content: language,
    },
    exportPdf: {
        fileName: `${chapterName}.pdf`,
        stylesheets: [pdfFileURL, "EDITOR_STYLES", pdf2FileURL],
        converterOptions: {
            format: "A4",
            margin_top: "20mm",
            margin_bottom: "30mm",
            margin_right: "20mm",
            margin_left: "20mm",
            footer_html: `
                <div style="text-align: center; font-size: 10px; margin-bottom: 10mm">
                    <p> -<span class="pageNumber"></span>- </p>
                    <p> PenPeers </p>
                </div>`,
        },
    },
})
    .then((editor) => {
        window.editor = editor;

        if (isDisabled) {
            editor.enableReadOnlyMode("editor");
        }

        if (isCompleted && !isDisabled) {
            document.querySelector("#editor-status").innerHTML =
                reeditChapterText;
            document
                .querySelector("#editor-status")
                .classList.add("text-success");
            editor.enableReadOnlyMode("editor");
        }

        // Set a custom container for the toolbar.
        document
            .querySelector(".document-editor__toolbar")
            .appendChild(editor.ui.view.toolbar.element);
        document.querySelector(".ck-toolbar").classList.add("ck-reset_all");

        displayStatus(editor);
    })
    .catch((error) => {
        console.error(error);
    });

const statusIndicator = document.querySelector("#editor-status");

function displayStatus(editor) {
    let timer;

    editor.model.document.on("change:data", () => {
        statusIndicator.innerHTML = writingText;
        statusIndicator.classList.remove("text-success");
        statusIndicator.classList.add("text-danger");

        clearTimeout(timer);
        timer = setTimeout(() => {
            const aTag = document.createElement("a");
            aTag.innerHTML = saveText;
            aTag.classList.add(
                "text-secondary",
                "text-underline",
                "btn",
                "btn-link",
                "p-0"
            );
            aTag.addEventListener("click", () => saveData(editor.getData()));
            aTag.href = "javascript:;";
            statusIndicator.innerHTML = "";
            statusIndicator.appendChild(aTag);
            statusIndicator.classList.remove("text-danger", "text-success");
        }, 1000);
    });
}

const replaceVideo = (content) => {
    const div = document.createElement("div");
    div.innerHTML = content;
    const videos = div.querySelectorAll("figure.media:not(.ck-widget)");

    videos.forEach((figure) => {
        const url = figure.querySelector("oembed").getAttribute("url");
        const pTag = document.createElement("p");
        const anchorTag = document.createElement("a");

        const search = new URLSearchParams(url.split("?")[1]);
        const id = search.get("v");

        anchorTag.href = url;

        if (url.startsWith("https://www.youtube.com/watch")) {
            const figureTag = document.createElement("figure");
            const imgTag = document.createElement("img");
            const figcaptionTag = document.createElement("figcaption");
            const anchorTag2 = document.createElement("a");

            figureTag.classList.add("image");

            imgTag.src = `https://i3.ytimg.com/vi/${id}/hqdefault.jpg`;

            anchorTag2.innerHTML = url;
            anchorTag2.href = url;
            console.log(anchorTag2);

            figcaptionTag.appendChild(anchorTag2);
            anchorTag.appendChild(imgTag);
            figureTag.appendChild(anchorTag);
            figureTag.appendChild(figcaptionTag);
            pTag.appendChild(figureTag);
        } else {
            anchorTag.innerHTML = url;
            pTag.appendChild(anchorTag);
        }

        figure.parentNode.replaceChild(pTag, figure);
    });

    return div.innerHTML;
};

function saveData(data) {
    const content = replaceVideo(data);
    if (content !== data) editor.setData(content);

    let bodyFormData = new FormData();
    bodyFormData.append("_token", "{{csrf_token()}}");
    bodyFormData.append("_method", "PUT");
    bodyFormData.append("content", data);

    return new Promise((resolve) => {
        axios.post(api_url, bodyFormData).then((response) => {
            if (response && response.status === 200) {
                statusIndicator.innerHTML = savedText;
                statusIndicator.classList.add("text-success");
                statusIndicator.classList.remove("text-danger");
                resolve();
            }
        });
    });
}
