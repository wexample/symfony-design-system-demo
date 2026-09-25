<script>
import Console from '@wexample/symfony-coding/components/console/console.vue';

// Lines arriving one by one, the way a run's output comes in from a topic: the
// same `append` a live update calls, pressed by a button instead of a message.
const SCRIPT = [
  { kind: 'command', text: 'wex app::files/check' },
  { kind: 'info', text: 'Checking 135 files…' },
  '\u001b[32m✔\u001b[39m 01-POLICIES/QAL-P-01-quality-policy.md',
  '\u001b[32m✔\u001b[39m 02-PROCEDURES/DEV-P-01-development.md',
  '\u001b[31m✘\u001b[39m 05-INSTRUCTIONS/RES/RES-I-02-it-access-change-v2.md \u001b[2mcase_format: uppercase\u001b[22m',
  '\u001b[32m✔\u001b[39m 05-INSTRUCTIONS/RES/RES-I-03-backup.md',
  { kind: 'error', text: '1 file at fault.' }
];

export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-demo-console-demo-console',

  components: {
    Console
  },

  data() {
    return {
      next: 0,
      timer: null
    };
  },

  beforeUnmount() {
    this.stop();
  },

  methods: {
    play() {
      this.stop();
      this.timer = setInterval(() => {
        if (this.next >= SCRIPT.length) {
          this.stop();
          return;
        }

        this.$refs.console.append(SCRIPT[this.next]);
        this.next += 1;
      }, 400);
    },

    stop() {
      clearInterval(this.timer);
      this.timer = null;
    }
  }
};
</script>
