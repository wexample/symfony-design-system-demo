import Page from '@wexample/symfony-loader/js/Class/Page';

// What the first list said last (`choice-list:change`), written under it.
export default class extends Page {
  async pageReady() {
    const output = this.el.querySelector<HTMLElement>('[data-demo-chosen]');

    output?.parentElement?.addEventListener('choice-list:change', (event: Event) => {
      const { value } = (event as CustomEvent<{ value: string | null }>).detail;

      output.textContent = (output.dataset.label ?? '%value%').replace('%value%', value ?? '');
    });
  }
}
