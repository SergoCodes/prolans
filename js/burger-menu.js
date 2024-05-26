class BurgerMenu extends HTMLElement {
  constructor() {
    super();

    const self = this;

    this.state = new Proxy(
      {
        status: 'open',
        enabled: false,
      },
      {
        set(state, key, value) {
          const oldValue = state[key];

          state[key] = value;
          if (oldValue !== value) {
            self.processStateChange();
          }
          return state;
        },
      }
    );
  }

  get maxWidth() {
    return parseInt(this.getAttribute('max-width') || 9999, 10);
  }

  connectedCallback() {
    this.initialMarkup = this.innerHTML;
    this.render();

    window.addEventListener('resize', () => {
      this.checkScreenWidth();
    });
    this.checkScreenWidth();
  }

  render() {
    this.innerHTML = `
      <div class="burger-menu" data-element="burger-root">
        <button class="burger-menu__trigger" data-element="burger-menu-trigger" type="button" aria-label="Open menu">
          <span class="burger-menu__bar" aria-hidden="true"></span>
        </button>
        <div class="burger-menu__panel" data-element="burger-menu-panel">
          ${this.initialMarkup} 
        </div>
      </div>
    `;

    this.postRender();
  }

  postRender() {
    this.trigger = this.querySelector('[data-element="burger-menu-trigger"]');
    this.panel = this.querySelector('[data-element="burger-menu-panel"]');
    this.root = this.querySelector('[data-element="burger-root"]');

    if (this.trigger && this.panel) {
      this.toggle();

      this.trigger.addEventListener('click', (evt) => {
        evt.preventDefault();

        this.toggle();
      });

      return;
    }

    this.innerHTML = this.initialMarkup;
  }

  toggle(forcedStatus) {
    if (forcedStatus) {
      if (this.state.status === forcedStatus) {
        return;
      }

      this.state.status = forcedStatus;
    } else {
      this.state.status = this.state.status === 'closed' ? 'open' : 'closed';
    }
  }

  processStateChange() {
    this.root.setAttribute('status', this.state.status);
    this.root.setAttribute('enabled', this.state.enabled ? 'true' : 'false');

    switch (this.state.status) {
      case 'closed':
        this.trigger.setAttribute('aria-expanded', 'false');
        this.trigger.setAttribute('aria-label', 'Open menu');
        break;
      case 'open':
      case 'initial':
        this.trigger.setAttribute('aria-expanded', 'true');
        this.trigger.setAttribute('aria-label', 'Close menu');
        break;
    }
  }

  checkScreenWidth() {
    this.state.enabled = window.innerWidth <= 768; 
  }

}
if ('customElements' in window) {
  customElements.define('burger-menu', BurgerMenu);
}

export default BurgerMenu;

