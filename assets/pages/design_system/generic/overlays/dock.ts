import Page from '@wexample/symfony-loader/js/Class/Page';
import { showInTarget } from '@wexample/symfony-design-system/js/Helper/TargetHelper';

// What a script shows in a modal, a panel or a dock itself, without a page
// from the server: a title, a body, a foot.
export default class extends Page {
  pageReady() {
    const holder = this.el.querySelector<HTMLElement>('.dock-demo');

    this.el.querySelectorAll<HTMLElement>('.dock-demo--show').forEach((button) => {
      button.addEventListener('click', () => {
        const body = document.createElement('p');
        body.textContent = holder?.dataset.body ?? '';

        void showInTarget(this.app, button.dataset.target || 'dock', {
          title: holder?.dataset.title,
          body,
        });
      });
    });
  }
}
