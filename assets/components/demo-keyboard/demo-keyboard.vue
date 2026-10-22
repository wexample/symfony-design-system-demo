<script>
import { keyboardIsMac, keyboardShortcutLabel } from '@wexample/js-helpers/Helper/Keyboard';
import { numberParse } from '@wexample/symfony-design-system/js/Helper/NumberHelper';

const T = 'WexampleSymfonyDesignSystemDemoBundle.components.demo-keyboard.demo-keyboard::';

// The keys by where they sit (`event.code`), whatever they write on this
// layout; `width` in key units. A cap shows the US character first, then
// what it wrote here once pressed: an AZERTY keyboard turns Q into A.
const ROWS = [
  ['Escape', 'F1', 'F2', 'F3', 'F4', 'F5', 'F6', 'F7', 'F8', 'F9', 'F10', 'F11', 'F12'],
  ['Backquote', 'Digit1', 'Digit2', 'Digit3', 'Digit4', 'Digit5', 'Digit6', 'Digit7', 'Digit8', 'Digit9', 'Digit0', 'Minus', 'Equal', ['Backspace', 2]],
  [['Tab', 1.5], 'KeyQ', 'KeyW', 'KeyE', 'KeyR', 'KeyT', 'KeyY', 'KeyU', 'KeyI', 'KeyO', 'KeyP', 'BracketLeft', 'BracketRight', ['Backslash', 1.5]],
  [['CapsLock', 1.75], 'KeyA', 'KeyS', 'KeyD', 'KeyF', 'KeyG', 'KeyH', 'KeyJ', 'KeyK', 'KeyL', 'Semicolon', 'Quote', ['Enter', 2.25]],
  [['ShiftLeft', 1.25], 'IntlBackslash', 'KeyZ', 'KeyX', 'KeyC', 'KeyV', 'KeyB', 'KeyN', 'KeyM', 'Comma', 'Period', 'Slash', ['ShiftRight', 2.75]],
];

// The last row, as each platform lays it.
const ROW_BOTTOM_MAC = [['ControlLeft', 1.25], ['AltLeft', 1.25], ['MetaLeft', 1.5], ['Space', 6], ['MetaRight', 1.5], ['AltRight', 1.25], 'ArrowLeft', 'ArrowDown', 'ArrowUp', 'ArrowRight'];
const ROW_BOTTOM = [['ControlLeft', 1.25], ['MetaLeft', 1.25], ['AltLeft', 1.25], ['Space', 6], ['AltRight', 1.25], ['ControlRight', 1.25], 'ArrowLeft', 'ArrowDown', 'ArrowUp', 'ArrowRight'];

// What a key writes on a US layout, the one `event.code` is named after: what
// a cap shows until it is pressed — and learns, then, what it writes here.
const US = {
  Backquote: '`', Minus: '-', Equal: '=', BracketLeft: '[', BracketRight: ']', Backslash: '\\',
  Semicolon: ';', Quote: "'", IntlBackslash: '<', Comma: ',', Period: '.', Slash: '/',
};

// What a key that writes nothing is called on its cap.
const NAMES = {
  Escape: 'esc', Backspace: '⌫', Tab: '⇥', CapsLock: '⇪', Enter: '↩', ShiftLeft: '⇧', ShiftRight: '⇧',
  Space: ' ', ArrowLeft: '←', ArrowRight: '→', ArrowUp: '↑', ArrowDown: '↓',
};
const NAMES_MAC = { ControlLeft: '⌃', AltLeft: '⌥', AltRight: '⌥', MetaLeft: '⌘', MetaRight: '⌘' };
const NAMES_OTHER = { ControlLeft: 'Ctrl', ControlRight: 'Ctrl', AltLeft: 'Alt', AltRight: 'AltGr', MetaLeft: '⊞' };

// What the page listens for through the keyboard service, written once.
// Copying and pasting are only watched: the browser still does them.
const SHORTCUTS = [
  { combo: 'Mod+C', action: 'copy', prevent: false },
  { combo: 'Mod+X', action: 'cut', prevent: false },
  { combo: 'Mod+V', action: 'paste', prevent: false },
  { combo: 'Mod+Z', action: 'undo', prevent: false },
  { combo: 'Mod+Shift+Z', action: 'redo', prevent: false },
  { combo: 'Mod+S', action: 'save', prevent: true },
  { combo: 'Mod+K', action: 'search', prevent: true },
  { combo: 'Alt+ArrowUp', action: 'move_up', prevent: false },
];

const LOG_SIZE = 14;

let uidCounter = 0;

// What the keyboard and the clipboard tell the page, shown as they tell it:
// the key, the modifiers, the shortcuts the design system recognises, what
// is copied and pasted, every event in order. A bench for what differs from
// one platform, one layout, one browser to another.
export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-demo-keyboard-demo-keyboard',

  data() {
    const isMac = keyboardIsMac();

    return {
      uid: `demo-keyboard-${++uidCounter}`,
      isMac,
      last: null,
      lastDead: false,
      fresh: false,
      held: { Shift: false, Control: false, Alt: false, Meta: false, CapsLock: false },
      pressed: {},
      written: {},
      clipboard: null,
      log: [],
      logId: 0,
      shortcuts: SHORTCUTS.map((shortcut) => ({
        ...shortcut,
        keys: this.splitLabel(keyboardShortcutLabel(shortcut.combo, isMac), isMac),
        count: 0,
        fired: false,
      })),
      board: [...ROWS, isMac ? ROW_BOTTOM_MAC : ROW_BOTTOM].map((row) => row.map((cap) => (
        Array.isArray(cap) ? { code: cap[0], width: cap[1] } : { code: cap, width: 1 }
      ))),
    };
  },

  computed: {
    figure() {
      if (!this.last) {
        return '⌨';
      }

      const key = this.last.key;

      return key === ' ' ? '␣' : (key.length === 1 ? key : (NAMES[this.last.code] ?? key));
    },

    lastFlags() {
      if (!this.last) {
        return '';
      }

      return [
        this.last.repeat ? this.t('flag_repeat') : null,
        this.lastDead ? this.t('flag_dead') : null,
        this.last.isComposing ? this.t('flag_composing') : null,
        this.last.location ? this.t(`flag_location_${this.last.location}`) : null,
      ].filter(Boolean).join(' · ');
    },

    modifiers() {
      const mac = this.isMac;

      return [
        { name: 'Shift', symbol: '⇧', label: 'Shift', on: this.held.Shift },
        { name: 'Control', symbol: mac ? '⌃' : 'Ctrl', label: mac ? 'Control' : 'Ctrl', on: this.held.Control },
        { name: 'Alt', symbol: mac ? '⌥' : 'Alt', label: mac ? 'Option' : 'Alt', on: this.held.Alt },
        { name: 'Meta', symbol: mac ? '⌘' : '⊞', label: mac ? 'Command' : 'Meta', on: this.held.Meta },
        { name: 'CapsLock', symbol: '⇪', label: this.t('caps_lock'), on: this.held.CapsLock },
      ];
    },
  },

  mounted() {
    const keyboard = this.app.services.keyboard;

    this.shortcuts.forEach((shortcut) => keyboard.registerShortcut(this, shortcut.combo, () => this.fire(shortcut), {
      preventDefault: shortcut.prevent,
      // Seen here, still handed on to whatever else listens.
      priority: 100,
    }));

    window.addEventListener('keydown', this.onKeyDown, true);
    window.addEventListener('keyup', this.onKeyUp, true);
    window.addEventListener('blur', this.onBlur);
    ['copy', 'cut', 'paste'].forEach((type) => document.addEventListener(type, this.onClipboard, true));
    ['compositionstart', 'compositionupdate', 'compositionend', 'input'].forEach((type) => document.addEventListener(type, this.onText, true));
  },

  beforeUnmount() {
    this.app.services.keyboard.unregisterOwner(this);
    window.removeEventListener('keydown', this.onKeyDown, true);
    window.removeEventListener('keyup', this.onKeyUp, true);
    window.removeEventListener('blur', this.onBlur);
    ['copy', 'cut', 'paste'].forEach((type) => document.removeEventListener(type, this.onClipboard, true));
    ['compositionstart', 'compositionupdate', 'compositionend', 'input'].forEach((type) => document.removeEventListener(type, this.onText, true));
    clearTimeout(this.freshTimer);
  },

  methods: {
    t(key) {
      return this.trans(`${T}${key}`);
    },

    // « ⌘⇧S » into its keys; « Ctrl+Shift+S » the same.
    splitLabel(label, isMac) {
      return isMac ? [...label] : label.split('+');
    },

    capLabel(cap) {
      return (this.isMac ? NAMES_MAC : NAMES_OTHER)[cap.code]
        ?? NAMES[cap.code]
        ?? this.written[cap.code]
        ?? US[cap.code]
        ?? (/^(Key|Digit)./.test(cap.code) ? cap.code.slice(-1) : null)
        ?? (/^F\d+$/.test(cap.code) ? cap.code : '');
    },

    readModifiers(event) {
      this.held = {
        Shift: event.shiftKey,
        Control: event.ctrlKey,
        Alt: event.altKey,
        Meta: event.metaKey,
        CapsLock: event.getModifierState?.('CapsLock') ?? false,
      };
    },

    onKeyDown(event) {
      this.readModifiers(event);
      // Its fields, not the event: a native event made reactive answers none.
      this.last = { key: event.key, code: event.code, repeat: event.repeat, isComposing: event.isComposing, location: event.location };
      this.lastDead = event.key === 'Dead';
      this.pressed = { ...this.pressed, [event.code]: true };

      // The cap learns what it writes, without modifiers: on this layout.
      if (event.key.length === 1 && !event.ctrlKey && !event.metaKey && !event.altKey) {
        this.written = { ...this.written, [event.code]: event.key.toUpperCase() };
      }

      this.fresh = true;
      clearTimeout(this.freshTimer);
      this.freshTimer = setTimeout(() => { this.fresh = false; }, 150);
      this.write(`keydown  key=${JSON.stringify(event.key)} code=${event.code}${this.modifierText(event)}${event.repeat ? ' repeat' : ''}`);
    },

    onKeyUp(event) {
      this.readModifiers(event);
      const pressed = { ...this.pressed };

      delete pressed[event.code];

      // On a Mac, a key let go while ⌘ is held says nothing: once ⌘ is let
      // go, every other key is taken as released.
      if (event.key === 'Meta') {
        Object.keys(pressed).filter((code) => !code.startsWith('Meta')).forEach((code) => delete pressed[code]);
      }

      this.pressed = pressed;
      this.write(`keyup    key=${JSON.stringify(event.key)} code=${event.code}`);
    },

    // The window left with keys held: their release will never come.
    onBlur() {
      this.pressed = {};
      this.held = { Shift: false, Control: false, Alt: false, Meta: false, CapsLock: this.held.CapsLock };
    },

    onClipboard(event) {
      const data = event.clipboardData;
      // What is copied is the selection; what is pasted, the clipboard.
      const text = event.type === 'paste'
        ? (data?.getData('text/plain') ?? '')
        : (window.getSelection()?.toString() || document.activeElement?.value?.substring?.(document.activeElement.selectionStart, document.activeElement.selectionEnd) || '');

      this.clipboard = {
        type: event.type,
        formats: event.type === 'paste' ? [...(data?.types ?? [])].join(', ') : '',
        text: text.length > 120 ? `${text.slice(0, 120)}…` : text,
        number: event.type === 'paste' ? numberParse(text) : null,
      };
      this.write(`${event.type.padEnd(8)} ${JSON.stringify(this.clipboard.text)}`);
    },

    onText(event) {
      const detail = event.type === 'input'
        ? `inputType=${event.inputType} data=${JSON.stringify(event.data)}`
        : `data=${JSON.stringify(event.data)}`;

      this.write(`${event.type.replace('composition', 'comp.').padEnd(8)} ${detail}`);
    },

    fire(shortcut) {
      shortcut.count++;
      shortcut.fired = true;
      setTimeout(() => { shortcut.fired = false; }, 400);
      this.write(`shortcut ${shortcut.combo} → ${shortcut.action}`);

      // Seen, not taken: the browser still copies and pastes.
      return false;
    },

    modifierText(event) {
      const names = [event.ctrlKey && 'ctrl', event.altKey && 'alt', event.shiftKey && 'shift', event.metaKey && 'meta'].filter(Boolean);

      return names.length ? ` [${names.join('+')}]` : '';
    },

    write(text) {
      this.log = [{ id: ++this.logId, text }, ...this.log].slice(0, LOG_SIZE);
    },
  },
};
</script>
