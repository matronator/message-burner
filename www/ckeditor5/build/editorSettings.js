const fields = document.querySelectorAll(".js-wysiwyg")
fields.forEach(field => {
  ClassicEditor.create(field, {
    toolbar: {
      items: [
        "undo",
        "redo",
        "|",
        "heading",
        "|",
        "alignment",
        "bold",
        "italic",
        "underline",
        "link",
        "|",
        "bulletedList",
        "numberedList",
        "indent",
        "outdent",
        "|",
        "imageUpload",
        "blockQuote",
        "insertTable",
        "mediaEmbed",
        "horizontalLine",
        "|",
        "subscript",
        "superscript",
      ]
    },
    language: "cs",
    image: {
      toolbar: ["imageTextAlternative", "imageStyle:full", "imageStyle:side"]
    },
    table: {
      contentToolbar: ["tableColumn", "tableRow", "mergeTableCells"]
    },
    licenseKey: "",
    heading: {
      options: [
        { model: 'paragraph', title: 'Odstavec', class: 'ck-heading_paragraph' },
        { model: 'heading1', view: 'h4', title: 'Podnadpis', class: 'ck-heading_heading1' },
        { model: 'heading2', view: 'h2', title: 'Nadpis', class: 'ck-heading_heading2' },
        {
          model: 'lead',
          view: {
            name: 'p',
            classes: 'lead'
          },
          title: 'Hlavní odstavec',
          class: 'ck-heading_paragraph_lead',

          // It needs to be converted before the standard 'heading2'.
          converterPriority: 'high'
        }
      ]
    }
  }).catch(error => {
    console.error("Oops, something went wrong!")
    console.error(error)
  })
})
