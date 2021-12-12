class Search {
  // 1. describe and create/initiate our object
  constructor() {
    this.addSearchHTML();
    this.resultsDiv = document.querySelector('#search-overlay__results');
    this.openButton = document.getElementById('search-trigger');
    this.closeButton = document.querySelector('.search-overlay__close');
    this.searchOverlay = document.querySelector('.search-overlay');
    this.searchTriggerText = document.getElementById('search-trigger-text');
    this.searchInput = document.querySelector('.search-input');
    this.searchField = document.querySelector('#search-field');
    this.body = document.querySelector('body');
    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.previousValue;
    this.typingTimer;
    this.events();
  }

  // 2. events
  events() {
    this.openButton.addEventListener('click', this.openOverlay.bind(this));
    this.closeButton.addEventListener('click', this.closeOverlay.bind(this));
    document.addEventListener('keydown', this.keypressDispatcher.bind(this));
    this.searchField.addEventListener('keyup', this.typingLogic.bind(this));
  }

  typingLogic() {
    if (this.searchInput.value !== this.previousValue) {
      clearTimeout(this.typingTimer);
      if (this.searchInput.value) {
        if (!this.isSpinnerVisible) {
          this.resultsDiv.innerHTML = '<div class="spinner-loader"><div class="anim"></div></div>';
          this.isSpinnerVisible = true;
        }
        this.typingTimer = setTimeout(this.getResults.bind(this), 750);
      } else {
        this.resultsDiv.innerHTML = '';
        this.isSpinnerVisible = false;
      }
    }
    this.previousValue = this.searchInput.value;
  }

  getResults() {
    fetch(k6.root_url + '/wp-json/k6/v1/search?term=' + this.searchInput.value)
      .then(results => results.json())
      .then(results => {
        this.resultsDiv.innerHTML = `
        <div class="row">
          <h2>SEARCH RESULTS</h2>
          ${results.recipes.length ?
            `<div class="column">
              <h3>Recipes</h3>
              <ul class="link-list min-list">
              ${results.recipes.map(
                item => `<li><a href="${item.permalink}">${item.title}</a></li>`
              ).join('')}
              </ul>
            </div>`
          : ''}
          ${results.tags.length ?
            `<div class="column">
              <h3>Tags</h3>
              <ul class="link-list min-list">
                ${results.tags.map(
                item => `<li><a href="${item.permalink}">${item.name}</a></li>`
              ).join('')}
              </ul>
            </div>`
          : ''}
          ${results.taggedRecipes.length ?
            `<div class="column">
              <h3>Recipes with Tag: ${results.term}</h3>
              <ul class="link-list min-list">
                ${results.taggedRecipes.map(
                item => `<li><a href="${item.permalink}">${item.title}</a></li>`
              ).join('')}
              </ul>
            </div>`
          : ''}
        </div>
        ${!results.recipes.length && !results.tags.length && !results.taggedRecipes.length ?
            '<p>No matching results.</p>' : ''}
        `;
        this.isSpinnerVisible = false;
      })
      .catch(err => {
        this.resultsDiv.innerHTML = err;
      });
  }

  keypressDispatcher(e) {
    if (e.keyCode === 83 && !this.isOverlayOpen) {
      this.openOverlay();
    }
    if (e.keyCode === 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }

  // 3. methods 
  openOverlay() {
    this.searchOverlay.classList.add('search-overlay--active');
    this.searchTriggerText.classList.add('search-trigger-text--hide');
    this.searchField.classList.add('search-field--show');
    this.body.classList.add('body-no-scroll');
    this.searchInput.value = '';
    setTimeout(() => this.searchInput.focus(), 301);
    this.isOverlayOpen = true;
  }

  closeOverlay() {
    this.searchOverlay.classList.remove('search-overlay--active');
    this.searchTriggerText.classList.remove('search-trigger-text--hide');
    this.searchField.classList.remove('search-field--show');
    this.body.classList.remove('body-no-scroll');
    this.searchInput.value = '';
    this.resultsDiv.innerHTML = '';
    this.searchInput.blur();
    this.isOverlayOpen = false;
  }

  addSearchHTML() {
    let overlay = document.createElement('div');
    overlay.classList.add('search-overlay');
    overlay.innerHTML = `
      <div class="search-overlay__close dashicons dashicons-no">
      </div>
      <div class="container">
        <div id="search-overlay__results"></div>
      </div>`
    document.querySelector('body').append(overlay);
  }
}

export default Search;