<script>
import StatusIcon, { STATUS_ICON_GLYPHS } from '@wexample/symfony-design-system/components/status-icon/status-icon.vue';

export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-vue-partials-demo-status-icons',

  components: {
    StatusIcon
  },

  data() {
    return {
      types: Object.keys(STATUS_ICON_GLYPHS),
      type: 'running',
      progress: 0.3,
      isPlaying: false,
      playTimer: null
    };
  },

  beforeUnmount() {
    this.stop();
  },

  methods: {
    setProgress(value) {
      this.progress = value;
    },

    // A run of its own, so the arc can be watched moving rather than jumping:
    // what the component promises is that a new value is travelled to.
    toggle() {
      if (this.isPlaying) {
        this.stop();

        return;
      }

      this.type = 'running';
      this.progress = 0;
      this.isPlaying = true;
      this.playTimer = setInterval(() => {
        const next = Math.round((this.progress + 0.1) * 10) / 10;

        if (next > 1) {
          this.stop();
          this.type = 'success';

          return;
        }

        this.progress = next;
      }, 600);
    },

    stop() {
      if (this.playTimer) {
        clearInterval(this.playTimer);
        this.playTimer = null;
      }

      this.isPlaying = false;
    }
  }
};
</script>
