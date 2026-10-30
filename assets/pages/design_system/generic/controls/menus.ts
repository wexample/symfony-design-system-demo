import Page from '@wexample/symfony-loader/js/Class/Page';
import { rippleAgentPress } from '@wexample/symfony-design-system/js/Helper/RippleHelper';

// The entry pointed at wears the agent's frame from the start; the button
// under the entries presses the last one as an agent does.
export default class extends Page {
  async pageReady() {
    this.app.services.agentTools?.pointer.frames.show(this.el.querySelector<HTMLElement>('[data-demo-agent-pointed]'));

    this.el.querySelector('[data-demo-agent-press]')?.addEventListener('click', () => {
      void rippleAgentPress(this.app, this.el.querySelector<HTMLElement>('[data-demo-agent-target]'));
    });
  }
}
