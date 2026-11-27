import Page from '@wexample/symfony-loader/js/Class/Page';
import { EVENT_AGENT_STOP } from '@wexample/symfony-loader/js/Services/AgentToolsService';
import { rippleAgentPress } from '@wexample/symfony-design-system/js/Helper/RippleHelper';

// What the scenarios hold the presence under, apart from any real agent.
const HOLD_TURN = 'demo:turn';
const HOLD_MANUAL = 'demo:manual';

// The pace of an agent's turn, between two of its steps.
const STEP_MS = 900;
const KEY_MS = 70;

// A bench for an agent at work: its presence held by hand, or a turn played
// on the targets — pointing, pressing, typing —, stopped by the presence's
// Stop as a real turn is.
export default class extends Page {
  private stopped: boolean = false;

  async pageReady() {
    const agentTools = this.app.services.agentTools;
    const status = this.el.querySelector<HTMLElement>('[data-demo-agent-status]');
    const hold = this.el.querySelector<HTMLElement>('[data-demo-agent-hold]');
    const holdLabel = hold?.querySelector(':scope > span:last-child')?.textContent ?? '';

    if (!agentTools || !status || !hold) {
      return;
    }

    document.addEventListener(EVENT_AGENT_STOP, () => {
      this.stopped = true;
      status.textContent = status.dataset.stopped ?? '';
      this.setLabel(hold, holdLabel);
    });

    hold.addEventListener('click', () => {
      const holding = hold.getAttribute('aria-pressed') === 'true';

      if (holding) {
        agentTools.presence.release(HOLD_MANUAL);
        this.setLabel(hold, holdLabel);
      } else {
        agentTools.resume();
        agentTools.presence.hold(HOLD_MANUAL);
        this.setLabel(hold, hold.dataset.releaseLabel ?? holdLabel, true);
      }
    });

    this.el.querySelector('[data-demo-agent-turn]')?.addEventListener('click', () => void this.playTurn(status));
    this.el.querySelector('[data-demo-agent-wander]')?.addEventListener('click', () => void this.wander());
  }

  // Points at the first button, presses it, types in the field: each step
  // waits as an agent does, and none runs once the user stopped it.
  private async playTurn(status: HTMLElement): Promise<void> {
    const agentTools = this.app.services.agentTools!;
    const target = this.el.querySelector<HTMLElement>('[data-demo-agent-press-target]');
    const field = this.el.querySelector<HTMLInputElement>('[data-demo-agent-field]');
    const labels = this.el.querySelector<HTMLElement>('[data-demo-agent-turn]')?.dataset ?? {};

    this.stopped = false;
    agentTools.resume();
    agentTools.presence.hold(HOLD_TURN);
    status.textContent = status.dataset.playing ?? '';

    const steps: (() => Promise<void> | void)[] = [
      async () => {
        agentTools.pointer.point([target], { caller: 'demo' });
        await agentTools.cursor.moveTo(target, { label: labels.labelPoint });
      },
      () => {
        agentTools.cursor.say(labels.labelPress ?? null);
        return rippleAgentPress(this.app, target);
      },
      async () => {
        agentTools.pointer.point([field], { caller: 'demo' });
        await agentTools.cursor.moveTo(field, { label: labels.labelType });
      },
      async () => {
        if (!field) {
          return;
        }
        field.value = '';
        for (const letter of field.dataset.typed ?? '') {
          if (this.stopped) {
            return;
          }
          field.value += letter;
          await this.wait(KEY_MS);
        }
      },
      () => {
        agentTools.pointer.point([], { caller: 'demo' });
        agentTools.cursor.say(null);
      },
    ];

    for (const step of steps) {
      await this.wait(STEP_MS);
      if (this.stopped) {
        agentTools.pointer.point([], { caller: 'demo' });
        return;
      }
      await step();
    }

    agentTools.presence.release(HOLD_TURN);
    status.textContent = status.dataset.done ?? '';
  }

  // The hand going from one target to the next, near and far, small and
  // large: a bench to judge its curves, its pace and its trail by eye.
  private async wander(): Promise<void> {
    const agentTools = this.app.services.agentTools!;
    const targets = [...this.el.querySelectorAll<HTMLElement>('[data-demo-agent-wander-target]')];

    this.stopped = false;
    agentTools.resume();
    agentTools.presence.hold(HOLD_TURN);

    for (const target of [...targets, ...targets.slice().reverse()]) {
      if (this.stopped) {
        break;
      }
      await agentTools.cursor.moveTo(target);
      await this.wait(260);
    }

    agentTools.presence.release(HOLD_TURN);
  }

  private setLabel(button: HTMLElement, label: string, pressed: boolean = false): void {
    const text = button.querySelector(':scope > span:last-child');
    if (text) {
      text.textContent = label;
    }
    button.setAttribute('aria-pressed', pressed ? 'true' : 'false');
  }

  private wait(ms: number): Promise<void> {
    return new Promise((resolve) => window.setTimeout(resolve, ms));
  }
}
