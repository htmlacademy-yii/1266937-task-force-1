document.addEventListener("DOMContentLoaded", function () {
  const autoCompleteJS = new autoComplete({
    selector: "#location",
    data: {
      src: async (query) => {
        try {
          const source = await fetch(`/geo/index?address=${window.userCity}, ${query}`);
          return await source.json();
        } catch (error) {
          return error;
        }
      },
      keys: ["text"]
    },
    resultItem: {
      highlight: true
    },
    events: {
      input: {
        selection: (event) => {
          const selection = event.detail.selection.value;
          autoCompleteJS.input.value = selection.text;
          document.querySelector("#latitude").value = selection.lat;
          document.querySelector("#longitude").value = selection.long;
        }
      }
    }
  });
});