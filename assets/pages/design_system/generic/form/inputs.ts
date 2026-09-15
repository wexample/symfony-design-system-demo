import Page from '@wexample/symfony-loader/js/Class/Page';
import Field from '@wexample/symfony-design-system/js/Class/Field';
import { assistanceWait } from '@wexample/js-api/Helper/Assistance';

// What the demo agent writes, by field name. It knows the values and nothing
// else: how each one appears is the field's business, which is the whole point
// of the exercise.
const VALUES: Record<string, unknown> = {
  text: 'Maya Lindqvist',
  textarea: '14 Storgatan\n411 38 Göteborg\nSweden',
  email: 'maya@example.com',
  url: 'https://example.com',
  password: 'correct-horse-battery',
  number: '34',
  date: '1992-04-17',
  time: '09:30',
  datetime: '2026-10-02T14:00',
  select: 'two',
  radio: 'three',
  switch: true,
  emoji: '🎉',
};

// Long enough for the eye to follow the halo from one field to the next.
const PAUSE_BETWEEN_FIELDS_MS = 320;

export default class extends Page {
  private isRunning: boolean = false;

  pageReady() {
    this.attach('.assisted-play', () => this.run());
    this.attach('.assisted-reset', () => this.reset());
  }

  private attach(selector: string, onClick: () => void | Promise<void>): void {
    const button = this.el?.querySelector(selector) as HTMLElement | null;

    button?.addEventListener('click', () => {
      void Promise.resolve(onClick());
    });
  }

  // Every field of the page that answers to the field contract, in the order
  // they are read.
  private get fields(): Field[] {
    return this.components.filter((component): component is Field => component instanceof Field);
  }

  private async run(): Promise<void> {
    if (this.isRunning) {
      return;
    }

    this.isRunning = true;

    try {
      for (const field of this.fields) {
        const value = VALUES[field.fieldName];

        if (value === undefined) {
          continue;
        }

        await field.setValueAssisted(value);
        await assistanceWait(PAUSE_BETWEEN_FIELDS_MS);
      }
    } finally {
      this.isRunning = false;
    }
  }

  // Empties the gallery so the run can be watched again. Reaching into the
  // controls is a demo liberty: no agent would do this, which is why it lives
  // here and not on the field.
  private reset(): void {
    this.fields.forEach((field) => field.assistanceDeactivate());

    this.el?.querySelectorAll('input, textarea').forEach((element) => {
      if (element instanceof HTMLTextAreaElement) {
        element.value = '';

        return;
      }

      if (!(element instanceof HTMLInputElement)) {
        return;
      }

      if (element.type === 'checkbox' || element.type === 'radio') {
        element.checked = false;
        element.dispatchEvent(new Event('change', { bubbles: true }));

        return;
      }

      if (element.type !== 'hidden' && element.type !== 'submit') {
        element.value = '';
      }
    });
  }
}
