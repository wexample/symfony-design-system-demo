<script>
import ProgressBar from '@wexample/symfony-design-system/components/progress-bar/progress-bar.vue';

// What driving a bar looks like from the outside: a ref, and the same four verbs
// the cli progress speaks. Nothing here computes a percentage — that is the
// component's job, and the point of it.
export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-vue-partials-demo-progress',

  components: {
    ProgressBar
  },

  data() {
    return {
      // The case where neither the unit nor the total means anything: a step is
      // a step, and a hundred of them fill the bar.
      stepCount: 0,
      isRunning: false,
      timer: null
    };
  },

  beforeUnmount() {
    this.stop();
  },

  methods: {
    advance(step) {
      this.stepCount += 1;
      this.$refs.driven.advance(step, `Working — step ${this.stepCount}`);
    },

    finish() {
      this.$refs.driven.finish('Done');
      this.stop();
    },

    reset() {
      this.stepCount = 0;
      this.$refs.driven.reset('Idle');
      this.stop();
    },


    // A run of its own, so the bar can be watched travelling rather than jumping.
    toggle() {
      if (this.isRunning) {
        this.stop();

        return;
      }

      this.isRunning = true;
      this.timer = setInterval(() => {
        this.$refs.driven.advance(7, `Working — ${this.$refs.driven.percent + 7}%`);

        if (this.$refs.driven.isComplete) {
          this.$refs.driven.update(this.$refs.driven.currentTotal, 'Done');
          this.stop();
        }
      }, 500);
    },

    stop() {
      if (this.timer) {
        clearInterval(this.timer);
        this.timer = null;
      }

      this.isRunning = false;
    },

    onFinish() {
      this.stop();
    }
  }
};
</script>
