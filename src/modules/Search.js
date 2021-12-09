class Search {
  constructor() {
    this.addSearchHTML();
    // this.resultsDiv = document.querySelector('#search-overlay__results');
    this.openButton = document.querySelector('#js-search-trigger');
    // this.closeButton = document.querySelector('.search-overlay__close');
    // this.searchOverlay = document.querySelector('.search-overlay');
    // this.searchText = document.querySelector('.search-text');
    // this.searchInput = document.querySelector('.search-input');
    // this.searchField = document.querySelector('#search-field');
    // this.body = document.querySelector('body');
    // this.isOverlayOpen = false;
    // this.isSpinnerVisible = false;
    // this.previousValue;
    // this.typingTimer;
    this.events();
  }

  events() {
    this.openButton.addEventListener('click', this.openOverlay.bind(this));
    // this.closeButton.addEventListener('click', this.closeOverlay.bind(this));
    // document.addEventListener('keydown', this.keypressDispatcher.bind(this));
    // this.searchField.addEventListener('keyup', this.typingLogic.bind(this));
  }

  typingLogic() {
    if (this.searchField.value !== this.previousValue) {
      clearTimeout(this.typingTimer);
      if (this.searchField.value) {
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
    this.previousValue = this.searchField.value;
  }

  getResults() {
    fetch(k6.root_url + '/wp-json/k6/v1/search?term=' + this.searchField.value)
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

  openOverlay() {
    alert('open');
    // this.searchOverlay.classList.add('search-overlay--active');
    // this.searchText.classList.add('search-term--hide');
    // this.searchInput.classList.add('search-input--show');
    // this.body.classList.add('body-no-scroll');
    // this.searchField.value = '';
    // setTimeout(() => this.searchField.focus(), 301);
    // this.isOverlayOpen = true;
  }

  // closeOverlay() {
  //   this.searchOverlay.classList.remove('search-overlay--active');
  //   this.searchText.classList.remove('search-term--hide');
  //   this.searchInput.classList.remove('search-input--show');
  //   this.body.classList.remove('body-no-scroll');
  //   this.searchField.value = '';
  //   this.resultsDiv.innerHTML = '';
  //   this.searchField.blur();
  //   this.isOverlayOpen = false;
  // }

  addSearchHTML() {
    let overlay = document.createElement('div');
    // overlay.classList.add('search-overlay');
    // overlay.innerHTML = `
    //   <div class="search-overlay__close dashicons dashicons-no">
    //   </div>
    //   <div class="container">
    //     <div id="search-overlay__results"></div>
    //   </div>`
    document.querySelector('body').append(overlay);
  }
}

export default Search;