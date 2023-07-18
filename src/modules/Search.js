class Search {
  constructor() {
    this.addSearchHTML();
    this.resultsDiv = document.getElementById('search-overlay-results');
    this.openButton = document.getElementById('search-trigger');
    this.closeButton = document.getElementById('search-overlay-close');
    this.searchOverlay = document.getElementById('search-overlay');
    this.searchTriggerText = document.getElementById('search-trigger-text');
    this.searchInput = document.getElementById('search-input');
    this.body = document.querySelector('body');
    this.isSearchFieldOpen = false;
    this.isSpinnerVisible = false;
    this.events();
  }

  events() {
    this.openButton.addEventListener('click', this.showSearchField.bind(this));
    this.closeButton.addEventListener('click', this.closeOverlay.bind(this));
    document.addEventListener('keydown', this.keypressDispatcher.bind(this));
    this.searchInput.addEventListener('keyup', this.typingLogic.bind(this));
    this.searchInput.addEventListener('blur', function() {
      if (!this.body.classList.contains('search-results-active')) {
        this.closeOverlay();
      }
    }.bind(this));
  }

  typingLogic() {
    if (this.searchInput.value !== this.previousValue) {
      clearTimeout(this.typingTimer);
      if (this.searchInput.value) {
        this.openOverlay();
        if (!this.isSpinnerVisible) {
          this.resultsDiv.innerHTML = '<div class="spinner-loader"><div class="anim"></div></div>';
          this.isSpinnerVisible = true;
        }
        this.typingTimer = setTimeout(this.getResults.bind(this), 750);
      } else {
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
              <ul class="links-list min-list">
              ${results.recipes.map(
                item => `<li><a href="${item.permalink}">${item.title}</a></li>`
              ).join('')}
              </ul>
            </div>`
          : ''}
          ${results.tags.length ?
            `<div class="column">
              <h3>Tags</h3>
              <ul class="links-list min-list">
                ${results.tags.map(
                item => `<li><a href="${item.permalink}">${item.name}</a></li>`
              ).join('')}
              </ul>
            </div>`
          : ''}
          ${results.taggedRecipes.length ?
            `<div class="column">
              <h3>Recipes with Tag: ${results.term}</h3>
              <ul class="links-list min-list">
                ${results.taggedRecipes.map(
                item => `<li><a href="${item.permalink}">${item.title}</a></li>`
              ).join('')}
              </ul>
            </div>`
          : ''}
          ${results.ingredients.length ?
            `<div class="column">
              <h3>Ingredient Profiles</h3>
              <ul class="links-list min-list">
                ${results.ingredients.map(
                item => `<li><a href="${item.permalink}">${item.title}</a></li>`
              ).join('')}
              </ul>
            </div>`
          : ''}
        </div>
        ${!results.recipes.length && !results.tags.length && !results.taggedRecipes.length && !results.ingredients ?
            '<p>No matching results.</p>' : ''}
        `;
        this.isSpinnerVisible = false;
      })
      .catch(err => {
        this.resultsDiv.innerHTML = err;
      });
  }

  keypressDispatcher(e) {
    if (e.keyCode === 83 && !this.isSearchFieldOpen) {
      this.showSearchField();
    }
    if (e.keyCode === 27 && this.isSearchFieldOpen) {
      this.closeOverlay();
    }
  }

  showSearchField() {
    this.body.classList.add('search-field-active');
    this.searchInput.value = '';
    // setTimeout to wait for overlay fade-in anim to play out.
    // Otherwise, focus doesn't work.
    setTimeout(() => this.searchInput.focus(), 301); 
    this.openButton.removeEventListener('click', this.showSearchField.bind(this));
    this.isSearchFieldOpen = true;
  }

  openOverlay() {
    this.body.classList.add('search-results-active');
  }

  closeOverlay() {
    this.body.classList.remove('search-results-active');
    this.body.classList.remove('search-field-active');
    this.searchInput.value = '';
    this.searchInput.blur();
    this.isSearchFieldOpen = false;
    this.previousValue = '';
  }

  addSearchHTML() {
    let overlay = document.createElement('div');
    overlay.classList.add('search-overlay');
    overlay.setAttribute('id', 'search-overlay');
    overlay.innerHTML = `
      <div id="search-overlay-close" class="search-overlay-close dashicons dashicons-no-alt">
      </div>
      <div class="search-overlay-container">
        <div id="search-overlay-results"></div>
      </div>`
    document.querySelector('.layout').append(overlay);
  }
}

export default Search;