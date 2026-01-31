/**
 * Browse Accordion - only one section open at a time.
 * Expanded content fills available vertical space and scrolls when overflow.
 */
class Accordion {
  constructor() {
    this.accordions = document.querySelectorAll('.k6-browse-accordion');
    if (this.accordions.length) {
      this.init();
    }
  }

  init() {
    this.accordions.forEach((accordion) => this.setupAccordion(accordion));
  }

  setupAccordion(accordion) {
    const triggers = accordion.querySelectorAll('.k6-accordion-trigger');
    const defaultOpen = accordion.dataset.defaultOpen;

    // Close all sections first (ensures correct initial state)
    accordion.querySelectorAll('.k6-accordion-section').forEach((s) => {
      this.closeSection(s);
    });

    // Open default section if configured
    if (defaultOpen && defaultOpen !== 'none') {
      const section = accordion.querySelector(`[data-section="${defaultOpen}"]`);
      if (section) {
        this.openSection(section);
      }
    }

    triggers.forEach((trigger) => {
      trigger.addEventListener('click', () => {
        const section = trigger.closest('.k6-accordion-section');
        const isExpanded = trigger.getAttribute('aria-expanded') === 'true';

        // Close all sections in this accordion
        accordion.querySelectorAll('.k6-accordion-section').forEach((s) => {
          this.closeSection(s);
        });

        // Toggle: if was open, leave closed; otherwise open
        if (!isExpanded) {
          this.openSection(section);
        }
      });
    });
  }

  openSection(section) {
    const trigger = section.querySelector('.k6-accordion-trigger');
    const content = section.querySelector('.k6-accordion-content');
    if (!trigger || !content) return;

    section.classList.add('is-open');
    trigger.setAttribute('aria-expanded', 'true');
    content.hidden = false;
  }

  closeSection(section) {
    const trigger = section.querySelector('.k6-accordion-trigger');
    const content = section.querySelector('.k6-accordion-content');
    if (!trigger || !content) return;

    section.classList.remove('is-open');
    trigger.setAttribute('aria-expanded', 'false');
    content.hidden = true;
  }
}

export default Accordion;
