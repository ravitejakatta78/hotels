class Loader {

  constructor() {

    this.state = 0;
    this.message = "Loading...";
    this.loader = this.createLoaderEelement();
    this.loader.append(this.createLoadingContent());
  }

  //create loader div
  createLoaderEelement() {
    $("body").append("<div id='overlay'></div>");
    this.loaderDiv = $("#overlay");
    return this.loaderDiv;
  }

  //create loading content
  createLoadingContent() {
    return `<div id="text"><div class="spinner-grow text-warning" role="status"><span class="sr-only"></span></div><p id="status_message">${this.message}</p></div>`;
  }

  //setMessage
  setMessage(message) {
    this.message = message;
    $("#status_message").text(message);
  }

  //setState
  setState(state) {
    this.state = state;

    if(!this.state) this.hide();
    if(this.state) this.show();
  }

  //Hide it
  hide() {
    this.loaderDiv.hide();
  }

  //Show it
  show() {
    this.loaderDiv.show();
  }
}
